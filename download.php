<?php
include 'koneksi.php';

// Ambil ID dari parameter GET
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    header("Location: index.php");
    exit;
}

// Query untuk mengambil data dokumen
$query = "SELECT * FROM dokumen WHERE id = $id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header("Location: index.php");
    exit;
}

$row = mysqli_fetch_assoc($result);
$file_path = 'uploads/' . $row['nama_file'];

// Cek apakah file ada
if (!file_exists($file_path)) {
    header("Location: index.php");
    exit;
}

// Set header untuk download
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . basename($row['nama_file']) . '"');
header('Content-Length: ' . filesize($file_path));
header('Cache-Control: must-revalidate');
header('Pragma: public');

// Output file
readfile($file_path);
exit;
?>


