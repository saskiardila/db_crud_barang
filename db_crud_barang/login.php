
<?php
require_once 'functions.php';

if (!empty($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    periksaCSRF();

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare(
        'SELECT id, nama, password
         FROM users WHERE email = ? LIMIT 1'
    );
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_nama'] = $user['nama'];

        header('Location: dashboard.php');
        exit;
    }

    $error = 'Email atau password salah.';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5" style="max-width:450px">
<div class="card p-4 shadow-sm">
<h3>Login</h3>

<?php if ($error !== ''): ?>
<div class="alert alert-danger">
<?= e($error) ?>
</div>
<?php endif; ?>

<form method="post">
<input type="hidden" name="csrf_token"
value="<?= e(tokenCSRF()) ?>">

<label class="form-label">Email</label>
<input class="form-control mb-3"
type="email" name="email" required>

<label class="form-label">Password</label>
<input class="form-control mb-3"
type="password" name="password" required>

<button class="btn btn-primary w-100">
Login
</button>
</form>

<p class="mt-3 mb-0">
Belum punya akun? <a href="register.php">Daftar</a>
</p>
</div>
</div>
</body>
</html>
