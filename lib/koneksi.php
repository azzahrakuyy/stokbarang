<?php
$host = 'localhost';
$dbname = 'dbstokbarang';
$username = 'root'; 
$password = 'albira12345'; 

try {
    $conn = new PDO("mysql:host=localhost;dbname=dbstokbarang", "root", "albira12345");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
?>