<?php
session_start();
include 'koneksi.php';

// Cek apakah sudah login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$error = '';
$success = '';

// Proses tambah dokumen
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $tahun = intval($_POST['tahun']);
    $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);

    // Validasi file upload
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $file = $_FILES['file'];
        $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        // Cek ekstensi file (hanya PDF)
        if ($file_ext == 'pdf') {
            // Generate nama file unik
            $nama_file = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file['name']);
            $upload_path = 'uploads/' . $nama_file;

            // Upload file
            if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                // Insert ke database
                $query = "INSERT INTO dokumen (judul, kategori, tahun, tanggal, nama_file) VALUES ('$judul', '$kategori', $tahun, '$tanggal', '$nama_file')";
                
                if (mysqli_query($conn, $query)) {
                    $success = "Dokumen berhasil ditambahkan!";
                    // Reset form dengan redirect
                    header("Location: tambah.php?success=1");
                    exit;
                } else {
                    $error = "Gagal menyimpan data ke database: " . mysqli_error($conn);
                    // Hapus file yang sudah diupload jika gagal insert
                    unlink($upload_path);
                }
            } else {
                $error = "Gagal mengupload file!";
            }
        } else {
            $error = "Hanya file PDF yang diizinkan!";
        }
    } else {
        $error = "File harus diupload!";
    }
}

// Cek apakah ada pesan success dari redirect
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $success = "Dokumen berhasil ditambahkan!";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Dokumen - PPID</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#fdf2f2',
                            100: '#fbe4e4',
                            500: '#800000',
                            600: '#800000',
                            700: '#660000',
                        },
                    },
                },
            },
        }
    </script>
    <style>
        .soft-shadow {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <!-- HEADER/NAVBAR -->
    <header class="bg-white soft-shadow sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-lg bg-primary-600 flex items-center justify-center text-white font-bold">KPU</div>
                    <div class="flex flex-col">
                        <span class="font-bold text-gray-800 text-sm leading-tight">KPU KOTA PROBOLINGGO</span>
                        <span class="text-xs text-gray-600">Tambah Dokumen</span>
                    </div>
                </div>
                <nav class="hidden md:flex gap-6 items-center">
                    <a href="dashboard.php" class="text-gray-700 hover:text-primary-600 transition">Dashboard</a>
                    <a href="index.php" class="text-gray-700 hover:text-primary-600 transition">Beranda</a>
                    <a href="login.php?logout=1" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">Logout</a>
                </nav>
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-2xl p-8 soft-shadow max-w-2xl mx-auto">
            <h1 class="text-3xl font-bold mb-8">Tambah Dokumen</h1>
            
            <?php if (!empty($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="tambah.php" enctype="multipart/form-data">
                <div class="mb-6">
                    <label class="block font-semibold mb-2">Judul Dokumen <span class="text-red-500">*</span></label>
                    <input type="text" name="judul" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="Masukkan judul dokumen">
                </div>

                <div class="mb-6">
                    <label class="block font-semibold mb-2">Kategori <span class="text-red-500">*</span></label>
                    <select name="kategori" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <option value="">Pilih Kategori</option>
                        <option value="Peraturan">Peraturan</option>
                        <option value="Keputusan">Keputusan</option>
                        <option value="Laporan">Laporan</option>
                        <option value="Rencana & Program">Rencana & Program</option>
                    </select>
                </div>

                <div class="mb-6 grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold mb-2">Tahun <span class="text-red-500">*</span></label>
                        <input type="number" name="tahun" required min="2000" max="2099" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="2024" value="<?php echo date('Y'); ?>">
                    </div>
                    <div>
                        <label class="block font-semibold mb-2">Tanggal <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block font-semibold mb-2">File PDF <span class="text-red-500">*</span></label>
                    <input type="file" name="file" required accept=".pdf" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <p class="text-sm text-gray-500 mt-2">Hanya file PDF yang diizinkan (maks. 10MB)</p>
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="bg-primary-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-primary-700 transition">Simpan Dokumen</button>
                    <a href="dashboard.php" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-xl font-semibold hover:bg-gray-300 transition">Batal</a>
                </div>
            </form>
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="bg-primary-600 text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="border-t border-white border-opacity-20 pt-8 text-center text-white opacity-90">
                <p>&copy; 2026 KPU Kota Probolinggo. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>

</body>
</html>
<?php
mysqli_close($conn);
?>

