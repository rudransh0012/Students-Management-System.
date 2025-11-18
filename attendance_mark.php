<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';
require_login();

$date = $_GET['date'] ?? date('Y-m-d');
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'] ?? date('Y-m-d');
    if (!$date) {
        $errors[] = 'Date is required.';
    }

    $statuses = $_POST['status'] ?? [];
    if (!$errors) {
        $stmt = $pdo->prepare('INSERT INTO attendance (student_id, attendance_date, status) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE status = VALUES(status)');
        foreach ($statuses as $student_id => $status) {
            $student_id = (int)$student_id;
            if ($student_id <= 0) continue;
            if (!in_array($status, ['Present', 'Absent', 'Leave'], true)) continue;
            $stmt->execute([$student_id, $date, $status]);
        }
        $success = 'Attendance saved successfully for ' . htmlspecialchars($date) . '.';
    }
}

$studentsStmt = $pdo->query('SELECT * FROM students ORDER BY class, name');
$students = $studentsStmt->fetchAll();

// Load existing attendance for selected date
$attendanceByStudent = [];
$attStmt = $pdo->prepare('SELECT student_id, status FROM attendance WHERE attendance_date = ?');
$attStmt->execute([$date]);
foreach ($attStmt->fetchAll() as $row) {
    $attendanceByStudent[(int)$row['student_id']] = $row['status'];
}

include __DIR__ . '/header.php';
?>
<div class="page-header">
  <h4>Attendance</h4>
  <a href="attendance_report.php" class="btn btn-outline-primary btn-sm">View Attendance Report</a>
</div>
<?php if ($errors): ?>
<div class="alert alert-danger">
  <?php foreach ($errors as $e) echo '<div>' . htmlspecialchars($e) . '</div>'; ?>
</div>
<?php endif; ?>
<?php if ($success): ?>
<div class="alert alert-success">
  <?php echo $success; ?>
</div>
<?php endif; ?>
<div class="card shadow-sm border-0 mb-3">
  <div class="card-body">
    <form class="row g-3" method="get">
      <div class="col-md-4">
        <label class="form-label">Date</label>
        <input type="date" name="date" value="<?php echo htmlspecialchars($date); ?>" class="form-control" required>
      </div>
      <div class="col-md-2 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100">Load</button>
      </div>
    </form>
  </div>
</div>

<div class="card shadow-sm border-0">
  <div class="card-body">
    <?php if (count($students) === 0): ?>
      <p class="text-muted mb-0">No students available to mark attendance.</p>
    <?php else: ?>
    <form method="post">
      <input type="hidden" name="date" value="<?php echo htmlspecialchars($date); ?>">
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Roll No</th>
              <th>Name</th>
              <th>Class</th>
              <th class="text-center">Present</th>
              <th class="text-center">Absent</th>
              <th class="text-center">Leave</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($students as $index => $s): 
            $sid = (int)$s['id'];
            $current = $attendanceByStudent[$sid] ?? 'Present';
          ?>
            <tr>
              <td><?php echo $index + 1; ?></td>
              <td><?php echo htmlspecialchars($s['roll_no']); ?></td>
              <td><?php echo htmlspecialchars($s['name']); ?></td>
              <td><?php echo htmlspecialchars($s['class']); ?></td>
              <td class="text-center">
                <input type="radio" name="status[<?php echo $sid; ?>]" value="Present" <?php if ($current === 'Present') echo 'checked'; ?>>
              </td>
              <td class="text-center">
                <input type="radio" name="status[<?php echo $sid; ?>]" value="Absent" <?php if ($current === 'Absent') echo 'checked'; ?>>
              </td>
              <td class="text-center">
                <input type="radio" name="status[<?php echo $sid; ?>]" value="Leave" <?php if ($current === 'Leave') echo 'checked'; ?>>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="mt-3 d-flex justify-content-end">
        <button type="submit" class="btn btn-primary">Save Attendance</button>
      </div>
    </form>
    <?php endif; ?>
  </div>
</div>
<?php include __DIR__ . '/footer.php'; ?>
