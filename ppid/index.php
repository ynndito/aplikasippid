<?php
include 'koneksi.php';

// Ambil parameter search dan filter
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$kategori = isset($_GET['kategori']) ? mysqli_real_escape_string($conn, $_GET['kategori']) : '';

// Query untuk mengambil data dokumen
$query = "SELECT * FROM dokumen WHERE 1=1";

if (!empty($search)) {
    $query .= " AND judul LIKE '%$search%'";
}

if (!empty($kategori)) {
    $query .= " AND kategori = '$kategori'";
}

$query .= " ORDER BY tanggal DESC, id DESC";

$result = mysqli_query($conn, $query);

// Ambil semua kategori untuk filter
$kategori_query = "SELECT DISTINCT kategori FROM dokumen ORDER BY kategori";
$kategori_result = mysqli_query($conn, $kategori_query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Dokumen PPID - KPU Kota Probolinggo</title>
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

        <!-- Hero Section -->
        <div class="relative rounded-2xl p-8 md:p-16 mb-16 soft-shadow overflow-hidden bg-gradient-to-r from-primary-600 to-primary-700">
            <div class="relative z-10">
                <h1 class="text-4xl md:text-5xl font-bold mb-4 text-white">Sistem Informasi PPID</h1>
                <p class="text-lg mb-8 text-white opacity-90">Pusat Informasi Publik dan Dokumentasi. Akses informasi publik dengan mudah dan transparan.</p>
            </div>
        </div>

        <!-- Search & Filter Section -->
        <div class="bg-white rounded-2xl p-8 mb-8 soft-shadow">
            <h2 class="text-2xl font-bold mb-6">Cari Dokumen</h2>
            <form method="GET" action="index.php" class="flex flex-col md:flex-row gap-4">
                <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Cari dokumen..." class="flex-1 px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500">
                <select name="kategori" class="px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">Semua Kategori</option>
                    <?php
                    while ($row = mysqli_fetch_assoc($kategori_result)) {
                        $selected = ($kategori == $row['kategori']) ? 'selected' : '';
                        echo "<option value=\"{$row['kategori']}\" $selected>{$row['kategori']}</option>";
                    }
                    ?>
                </select>
                <button type="submit" class="bg-primary-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-primary-700 transition">Cari</button>
                <?php if (!empty($search) || !empty($kategori)): ?>
                    <a href="index.php" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-xl font-semibold hover:bg-gray-300 transition">Reset</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Daftar Dokumen -->
        <div class="bg-white rounded-2xl overflow-hidden soft-shadow">
            <div class="p-6 border-b">
                <h2 class="text-2xl font-bold">Daftar Dokumen</h2>
            </div>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-primary-600 text-white">
                            <tr>
                                <th class="px-6 py-4 text-left">Judul</th>
                                <th class="px-6 py-4 text-left">Kategori</th>
                                <th class="px-6 py-4 text-left">Tahun</th>
                                <th class="px-6 py-4 text-left">Tanggal</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($row['judul']); ?></td>
                                    <td class="px-6 py-4">
                                        <span class="bg-primary-100 text-primary-700 px-3 py-1 rounded-full text-xs font-semibold">
                                            <?php echo htmlspecialchars($row['kategori']); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($row['tahun']); ?></td>
                                    <td class="px-6 py-4"><?php echo date('d F Y', strtotime($row['tanggal'])); ?></td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="detail.php?id=<?php echo $row['id']; ?>" class="text-primary-600 hover:text-primary-700 font-semibold">Preview</a>
                                        <a href="download.php?id=<?php echo $row['id']; ?>" class="text-gray-600 hover:text-gray-700 font-semibold ml-4">Download</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="p-8 text-center text-gray-500">
                    <p>Tidak ada dokumen ditemukan.</p>
                </div>
            <?php endif; ?>
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

