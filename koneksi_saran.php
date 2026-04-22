<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "saran"; // Sesuaikan dengan nama database baru kamu

$conn_saran = mysqli_connect($host, $user, $pass, $db);

if (!$conn_saran) {
    die("Koneksi ke database saran gagal: " . mysqli_connect_error());
}
?>