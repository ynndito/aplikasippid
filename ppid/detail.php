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
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($row['judul']); ?> - PPID</title>
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
                        <span class="text-xs text-gray-600">Pejabat Pengelola Informasi dan Dokumentasi</span>
                    </div>
                </div>
                <nav class="hidden md:flex gap-6 items-center">
                    <a href="index.php" class="text-gray-700 hover:text-primary-600 transition">Beranda</a>
                    <a href="login.php" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition">Login</a>
                </nav>
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <div class="bg-white rounded-2xl p-8 soft-shadow">
            <!-- Info Dokumen -->
            <div class="mb-6 pb-6 border-b">
                <h1 class="text-3xl font-bold mb-4"><?php echo htmlspecialchars($row['judul']); ?></h1>
                <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                    <div>
                        <span class="font-semibold">Kategori:</span>
                        <span class="bg-primary-100 text-primary-700 px-3 py-1 rounded-full text-xs font-semibold ml-2">
                            <?php echo htmlspecialchars($row['kategori']); ?>
                        </span>
                    </div>
                    <div>
                        <span class="font-semibold">Tahun:</span> <?php echo htmlspecialchars($row['tahun']); ?>
                    </div>
                    <div>
                        <span class="font-semibold">Tanggal:</span> <?php echo date('d F Y', strtotime($row['tanggal'])); ?>
                    </div>
                </div>
            </div>

            <!-- Preview PDF -->
            <div class="mb-6">
                <h2 class="text-xl font-bold mb-4">Preview Dokumen</h2>
                <?php if (file_exists($file_path)): ?>
                    <div class="border rounded-lg overflow-hidden">
                        <iframe src="<?php echo htmlspecialchars($file_path); ?>" class="w-full h-screen min-h-[600px]" frameborder="0"></iframe>
                    </div>
                <?php else: ?>
                    <div class="border rounded-lg p-8 text-center text-gray-500">
                        <p>File tidak ditemukan.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4">
                <a href="download.php?id=<?php echo $row['id']; ?>" class="bg-primary-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-primary-700 transition">
                    Download Dokumen
                </a>
                <a href="index.php" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-xl font-semibold hover:bg-gray-300 transition">
                    Kembali ke Daftar
                </a>
            </div>
        </div>

    </main>

    <!-- FOOTER -->
    <footer class="bg-primary-600 text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid md:grid-cols-3 gap-8">
                <div>
                    <h3 class="font-bold text-lg mb-4">KPU Kota Probolinggo</h3>
                    <p class="text-white opacity-90">Jalan Pendidikan No. 45<br>Kota Probolinggo, Jawa Timur 67200<br>Indonesia</p>
                </div>
                <div>
                    <h3 class="font-bold text-lg mb-4">Kontak</h3>
                    <p class="text-white opacity-90">Telepon: (0335) 421-234<br>Email: ppid@kpuprobolinggo.go.id<br>Jam Kerja: Senin-Jumat 08:00-16:00</p>
                </div>
                <div>
                    <h3 class="font-bold text-lg mb-4">Layanan Publik</h3>
                    <p class="text-white opacity-90">Sistem Informasi PPID<br>Pusat Informasi dan Dokumentasi<br>KPU Kota Probolinggo</p>
                </div>
            </div>
            <div class="border-t border-white border-opacity-20 mt-8 pt-8 text-center text-white opacity-90">
                <p>&copy; 2026 KPU Kota Probolinggo. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>

</body>
</html>
<?php
mysqli_close($conn);
?>

