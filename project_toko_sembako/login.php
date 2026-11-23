<?php
session_start();
require_once 'config/database.php';

if (isset($_SESSION['iduser'])) {
    header("Location: index.php");
    exit;
}

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT u.iduser, u.username, r.nama_role 
            FROM `user` u 
            JOIN `role` r ON u.idrole = r.idrole 
            WHERE u.username = '$username' AND u.password = SHA2('$password', 256) 
            LIMIT 1";
    $res = mysqli_query($conn, $sql);

    if ($res && mysqli_num_rows($res) === 1) {
        $row = mysqli_fetch_assoc($res);
        $_SESSION['iduser']    = $row['iduser'];
        $_SESSION['username']  = $row['username'];
        $_SESSION['nama_role'] = $row['nama_role'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Username atau password salah.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Toko Sembako</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="card shadow-sm p-4" style="width: 100%; max-width: 400px;">
        <h4 class="fw-bold text-center mb-1">Toko Sembako</h4>
        <p class="text-muted text-center small mb-4">Silakan login untuk melanjutkan</p>
        <?php if ($error): ?>
            <div class="alert alert-danger py-2 small"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required autofocus>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 fw-semibold">Masuk</button>
        </form>
    </div>
</body>
</html>
