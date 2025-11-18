<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';
require_login();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $roll_no = trim($_POST['roll_no'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $gender = $_POST['gender'] ?? '';
    $class = trim($_POST['class'] ?? '');

    if ($roll_no === '' || $name === '' || $email === '' || $gender === '' || $class === '') {
        $errors[] = 'All fields are required.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format.';
    }

    if (!$errors) {
        $stmt = $pdo->prepare('INSERT INTO students (roll_no, name, email, gender, class, created_at) VALUES (?, ?, ?, ?, ?, NOW())');
        $stmt->execute([$roll_no, $name, $email, $gender, $class]);
        header('Location: students.php');
        exit;
    }
}

include __DIR__ . '/header.php';
?>
<h4 class="mb-3">Add Student</h4>
<?php if ($errors): ?>
<div class="alert alert-danger">
  <?php foreach ($errors as $e) echo '<div>' . htmlspecialchars($e) . '</div>'; ?>
</div>
<?php endif; ?>
<div class="card shadow-sm border-0">
  <div class="card-body">
    <form method="post" novalidate>
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">Roll No</label>
          <input type="text" name="roll_no" class="form-control" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Name</label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Gender</label>
          <select name="gender" class="form-select" required>
            <option value="">Select</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Class</label>
          <input type="text" name="class" class="form-control" required>
        </div>
      </div>
      <div class="mt-4 d-flex justify-content-between">
        <a href="students.php" class="btn btn-outline-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </form>
  </div>
</div>
<?php include __DIR__ . '/footer.php'; ?>
