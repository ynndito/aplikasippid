<?php
// Koneksi ke database MySQL
$host = "localhost";
$username = "root";
$password = "";
$database = "db_ppid";

$conn = mysqli_connect($host, $username, $password, $database);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Set charset UTF-8
mysqli_set_charset($conn, "utf8");
?>

