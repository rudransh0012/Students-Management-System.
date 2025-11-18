<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';
require_login();

$search = trim($_GET['search'] ?? '');
$query = 'SELECT * FROM students';
$params = [];

if ($search !== '') {
    $query .= ' WHERE name LIKE ? OR email LIKE ? OR roll_no LIKE ?';
    $like = '%' . $search . '%';
    $params = [$like, $like, $like];
}

$query .= ' ORDER BY created_at DESC';
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$students = $stmt->fetchAll();

include __DIR__ . '/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">Students</h4>
  <a href="student_add.php" class="btn btn-primary">Add Student</a>
</div>
<form class="row g-2 mb-3" method="get">
  <div class="col-md-4">
    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" class="form-control" placeholder="Search by name, email or roll no">
  </div>
  <div class="col-md-2">
    <button type="submit" class="btn btn-outline-primary w-100">Search</button>
  </div>
</form>
<div class="card shadow-sm border-0">
  <div class="table-responsive">
    <table class="table table-hover mb-0 align-middle">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>Roll No</th>
          <th>Name</th>
          <th>Email</th>
          <th>Gender</th>
          <th>Class</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php if (count($students) === 0): ?>
        <tr><td colspan="7" class="text-center text-muted">No students found.</td></tr>
      <?php else: ?>
        <?php foreach ($students as $index => $s): ?>
        <tr>
          <td><?php echo $index + 1; ?></td>
          <td><?php echo htmlspecialchars($s['roll_no']); ?></td>
          <td><?php echo htmlspecialchars($s['name']); ?></td>
          <td><?php echo htmlspecialchars($s['email']); ?></td>
          <td><?php echo htmlspecialchars($s['gender']); ?></td>
          <td><?php echo htmlspecialchars($s['class']); ?></td>
          <td>
            <a href="student_edit.php?id=<?php echo $s['id']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
            <a href="student_delete.php?id=<?php echo $s['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this student?');">Delete</a>
          </td>
        </tr>
        <?php endforeach; ?>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php include __DIR__ . '/footer.php'; ?>
