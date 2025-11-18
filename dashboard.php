<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';
require_login();

$totalStudents = $pdo->query('SELECT COUNT(*) AS cnt FROM students')->fetch()['cnt'] ?? 0;
$maleStudents = $pdo->query("SELECT COUNT(*) AS cnt FROM students WHERE gender = 'Male'")->fetch()['cnt'] ?? 0;
$femaleStudents = $pdo->query("SELECT COUNT(*) AS cnt FROM students WHERE gender = 'Female'")->fetch()['cnt'] ?? 0;

include __DIR__ . '/header.php';
?>
<div class="row">
  <div class="col-md-4 mb-3">
    <div class="card stat-card shadow-sm border-0">
      <div class="card-body">
        <h6 class="text-muted">Total Students</h6>
        <h2 class="fw-bold"><?php echo (int)$totalStudents; ?></h2>
      </div>
    </div>
  </div>
  <div class="col-md-4 mb-3">
    <div class="card stat-card shadow-sm border-0">
      <div class="card-body">
        <h6 class="text-muted">Male Students</h6>
        <h2 class="fw-bold text-primary"><?php echo (int)$maleStudents; ?></h2>
      </div>
    </div>
  </div>
  <div class="col-md-4 mb-3">
    <div class="card stat-card shadow-sm border-0">
      <div class="card-body">
        <h6 class="text-muted">Female Students</h6>
        <h2 class="fw-bold text-danger"><?php echo (int)$femaleStudents; ?></h2>
      </div>
    </div>
  </div>
</div>
<div class="card shadow-sm mt-3 border-0">
  <div class="card-body">
    <h5 class="card-title mb-3">Quick Actions</h5>
    <a href="students.php" class="btn btn-primary me-2">View Students</a>
    <a href="student_add.php" class="btn btn-outline-primary">Add New Student</a>
  </div>
</div>
<?php include __DIR__ . '/footer.php'; ?>
