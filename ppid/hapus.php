<?php
session_start();
include 'koneksi.php';

// Cek apakah sudah login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Ambil ID dari parameter GET
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    header("Location: dashboard.php");
    exit;
}

// Query untuk mengambil data dokumen (untuk mendapatkan nama file)
$query = "SELECT * FROM dokumen WHERE id = $id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $nama_file = $row['nama_file'];
    $file_path = 'uploads/' . $nama_file;

    // Hapus dari database
    $delete_query = "DELETE FROM dokumen WHERE id = $id";
    
    if (mysqli_query($conn, $delete_query)) {
        // Hapus file fisik jika ada
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        header("Location: dashboard.php?deleted=1");
        exit;
    } else {
        header("Location: dashboard.php?error=1");
        exit;
    }
} else {
    header("Location: dashboard.php");
    exit;
}
?>

