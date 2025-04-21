<?php
session_start();
include 'lib/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Kolom yang benar adalah 'username' (bukan 'usernameIndeks')
    $sql = "SELECT * FROM tb_user WHERE username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $password === $user['password']) {
        $_SESSION['user_id'] = $user['userIDUtama'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['level'];

        if ($user['level'] === 'admin') {
            header("Location: admin.php");
        } elseif ($user['level'] === 'petugas') {
            header("Location: petugas.php");
        } else {
            $error = "Role tidak dikenali.";
        }
        exit;
    } else {
        $error = "Username atau password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow-lg p-4" style="width: 100%; max-width: 400px; border-radius: 10px;">
        <h2 class="text-center mb-4">Login</h2>

        <?php if (isset($_GET['register']) && $_GET['register'] == 'success'): ?>
            <div class="alert alert-success text-center">Pendaftaran berhasil! Silakan login.</div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger text-center"><?php echo $error; ?></div>
            <?php endif; ?>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
        </form>
        <p class="text-center mt-3">
            Belum punya akun? <a href="register.php">Daftar di sini</a>
        </p>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


</body>
</html>
