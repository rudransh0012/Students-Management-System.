<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: students.php');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM students WHERE id = ?');
$stmt->execute([$id]);
$student = $stmt->fetch();

if (!$student) {
    header('Location: students.php');
    exit;
}

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
        $stmt = $pdo->prepare('UPDATE students SET roll_no = ?, name = ?, email = ?, gender = ?, class = ? WHERE id = ?');
        $stmt->execute([$roll_no, $name, $email, $gender, $class, $id]);
        header('Location: students.php');
        exit;
    }
}

include __DIR__ . '/header.php';
?>
<h4 class="mb-3">Edit Student</h4>
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
          <input type="text" name="roll_no" class="form-control" value="<?php echo htmlspecialchars($student['roll_no']); ?>" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Name</label>
          <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($student['name']); ?>" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($student['email']); ?>" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Gender</label>
          <select name="gender" class="form-select" required>
            <option value="Male" <?php if ($student['gender']==='Male') echo 'selected'; ?>>Male</option>
            <option value="Female" <?php if ($student['gender']==='Female') echo 'selected'; ?>>Female</option>
            <option value="Other" <?php if ($student['gender']==='Other') echo 'selected'; ?>>Other</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Class</label>
          <input type="text" name="class" class="form-control" value="<?php echo htmlspecialchars($student['class']); ?>" required>
        </div>
      </div>
      <div class="mt-4 d-flex justify-content-between">
        <a href="students.php" class="btn btn-outline-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">Update</button>
      </div>
    </form>
  </div>
</div>
<?php include __DIR__ . '/footer.php'; ?>
