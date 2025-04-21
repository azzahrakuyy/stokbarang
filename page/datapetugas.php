<?php
session_start();
include 'lib/koneksi.php';

// Menampilkan semua error untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password']; // Password asli langsung disimpan

    // Cek apakah username sudah dipakai
    $stmt = $conn->prepare("SELECT * FROM tb_user WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $error = "Username sudah digunakan!";
    } else {
        // Insert user baru dengan password asli
        $insert = $conn->prepare("INSERT INTO tb_user (username, password, level) VALUES (:username, :password, 'petugas')");
        $insert->bindParam(':username', $username);
        $insert->bindParam(':password', $password); // Simpan password asli
        $insert->execute();

        // Login otomatis setelah register
        $userID = $conn->lastInsertId();
        $_SESSION['user_id'] = $userID;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = 'petugas';

        // Redirect ke halaman datapetugas
        header("Location: ?page=datapetugas");
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Petugas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow" style="max-width: 400px; width: 100%; border-radius: 10px;">
        <h3 class="text-center mb-3">Daftar Petugas</h3>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-success">Daftar</button>
            </div>
        </form>
        <p class="text-center mt-3">
            Sudah punya akun? <a href="login.php">Login di sini</a>
        </p>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
