<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';
require_login();

$date = $_GET['date'] ?? date('Y-m-d');

$stmt = $pdo->prepare('SELECT s.roll_no, s.name, s.class, a.status FROM students s LEFT JOIN attendance a ON a.student_id = s.id AND a.attendance_date = ? ORDER BY s.class, s.name');
$stmt->execute([$date]);
$rows = $stmt->fetchAll();

include __DIR__ . '/header.php';
?>
<div class="page-header">
  <h4>Attendance Report</h4>
  <a href="attendance_mark.php" class="btn btn-outline-primary btn-sm">Back to Attendance</a>
</div>
<div class="card shadow-sm border-0 mb-3">
  <div class="card-body">
    <form class="row g-3" method="get">
      <div class="col-md-4">
        <label class="form-label">Date</label>
        <input type="date" name="date" value="<?php echo htmlspecialchars($date); ?>" class="form-control" required>
      </div>
      <div class="col-md-2 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100">Filter</button>
      </div>
    </form>
  </div>
</div>
<div class="card shadow-sm border-0">
  <div class="card-body">
    <?php if (count($rows) === 0): ?>
      <p class="text-muted mb-0">No students / attendance records for this date.</p>
    <?php else: ?>
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Roll No</th>
            <th>Name</th>
            <th>Class</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $index => $r): ?>
          <tr>
            <td><?php echo $index + 1; ?></td>
            <td><?php echo htmlspecialchars($r['roll_no']); ?></td>
            <td><?php echo htmlspecialchars($r['name']); ?></td>
            <td><?php echo htmlspecialchars($r['class']); ?></td>
            <td>
              <?php
              $status = $r['status'] ?? 'Not Marked';
              if ($status === 'Present') {
                  echo '<span class="badge bg-success">Present</span>';
              } elseif ($status === 'Absent') {
                  echo '<span class="badge bg-danger">Absent</span>';
              } elseif ($status === 'Leave') {
                  echo '<span class="badge bg-warning text-dark">Leave</span>';
              } else {
                  echo '<span class="badge bg-secondary">Not Marked</span>';
              }
              ?>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>
</div>
<?php include __DIR__ . '/footer.php'; ?>
