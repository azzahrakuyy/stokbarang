<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    // Belum login → arahkan ke login.php
    header("Location: login.php");
    exit();
}
?>
