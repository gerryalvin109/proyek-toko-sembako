<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "uts_pbd";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Sesuaikan dengan nama folder proyek di Laragon (C:/laragon/www/project_toko_sembako)
define('BASE_URL', '/project_toko_sembako');
?>
