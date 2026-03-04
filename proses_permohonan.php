<?php
include 'koneksi.php';

// Proses form permohonan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $telepon = mysqli_real_escape_string($conn, $_POST['telepon']);
    $informasi = mysqli_real_escape_string($conn, $_POST['informasi']);
    $cara_penerimaan = mysqli_real_escape_string($conn, $_POST['cara_penerimaan']);

    // Insert ke database
    $query = "INSERT INTO permohonan (nama, email, telepon, informasi, cara_penerimaan, status) 
              VALUES ('$nama', '$email', '$telepon', '$informasi', '$cara_penerimaan', 'Pending')";
    
    if (mysqli_query($conn, $query)) {
        header("Location: ../index.php?permohonan=success#permohonan");
        exit;
    } else {
        header("Location: ../index.php?permohonan=error#permohonan");
        exit;
    }
} else {
    header("Location: ../index.php");
    exit;
}
?>


