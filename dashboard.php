<?php
session_start();
include 'koneksi.php';

// Cek apakah sudah login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Hitung total dokumen
$count_query = "SELECT COUNT(*) as total FROM dokumen";
$count_result = mysqli_query($conn, $count_query);
$count_row = mysqli_fetch_assoc($count_result);
$total_dokumen = $count_row['total'];

// Hitung total permohonan
$count_permohonan = "SELECT COUNT(*) as total FROM permohonan";
$result_permohonan = mysqli_query($conn, $count_permohonan);
$row_permohonan = mysqli_fetch_assoc($result_permohonan);
$total_permohonan = $row_permohonan['total'];

// Hitung permohonan pending
$count_pending = "SELECT COUNT(*) as total FROM permohonan WHERE status = 'Pending'";
$result_pending = mysqli_query($conn, $count_pending);
$row_pending = mysqli_fetch_assoc($result_pending);
$total_pending = $row_pending['total'];

// Hitung permohonan disetujui
$count_disetujui = "SELECT COUNT(*) as total FROM permohonan WHERE status = 'Disetujui'";
$result_disetujui = mysqli_query($conn, $count_disetujui);
$row_disetujui = mysqli_fetch_assoc($result_disetujui);
$total_disetujui = $row_disetujui['total'];

// Hitung permohonan ditolak
$count_ditolak = "SELECT COUNT(*) as total FROM permohonan WHERE status = 'Ditolak'";
$result_ditolak = mysqli_query($conn, $count_ditolak);
$row_ditolak = mysqli_fetch_assoc($result_ditolak);
$total_ditolak = $row_ditolak['total'];

// Query untuk mengambil semua dokumen
$query = "SELECT * FROM dokumen ORDER BY tanggal DESC, id DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - PPID</title>
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
                        <span class="text-xs text-gray-600">Dashboard Admin</span>
                    </div>
                </div>
                <nav class="hidden md:flex gap-6 items-center">
                    <span class="text-gray-600">Selamat datang, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                    <a href="index.php" class="text-gray-700 hover:text-primary-600 transition">Beranda</a>
                    <a href="tambah.php" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition">+ Tambah Dokumen</a>
                    <a href="login.php?logout=1" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">Logout</a>
                </nav>
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <!-- Statistik -->
        <div class="grid md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl p-6 soft-shadow">
                <div class="text-4xl font-bold text-primary-600"><?php echo $total_dokumen; ?></div>
                <div class="text-gray-600 mt-2">Total Dokumen</div>
            </div>
            <div class="bg-white rounded-2xl p-6 soft-shadow">
                <div class="text-4xl font-bold text-blue-600"><?php echo $total_permohonan; ?></div>
                <div class="text-gray-600 mt-2">Total Permohonan</div>
            </div>
            <div class="bg-white rounded-2xl p-6 soft-shadow">
                <div class="text-4xl font-bold text-orange-600"><?php echo $total_pending; ?></div>
                <div class="text-gray-600 mt-2">Permohonan Pending</div>
            </div>
            <div class="bg-white rounded-2xl p-6 soft-shadow">
                <div class="text-4xl font-bold text-green-600"><?php echo $total_disetujui; ?></div>
                <div class="text-gray-600 mt-2">Permohonan Disetujui</div>
            </div>
        </div>

        <!-- Link ke Halaman Permohonan -->
        <div class="mb-8">
            <a href="permohonan.php" class="bg-blue-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-blue-700 transition inline-block">
                Kelola Permohonan
            </a>
        </div>

        <!-- Tabel Dokumen -->
        <div class="bg-white rounded-2xl overflow-hidden soft-shadow">
            <div class="p-6 border-b flex justify-between items-center">
                <h2 class="text-2xl font-bold">Daftar Dokumen</h2>
                <a href="tambah.php" class="bg-primary-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-primary-700 transition">+ Tambah Dokumen</a>
            </div>
            <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg m-6">
                    Dokumen berhasil dihapus!
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg m-6">
                    Terjadi kesalahan saat menghapus dokumen!
                </div>
            <?php endif; ?>

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
                                        <a href="detail.php?id=<?php echo $row['id']; ?>" class="text-primary-600 hover:text-primary-700 font-semibold text-sm">Preview</a>
                                        <a href="edit.php?id=<?php echo $row['id']; ?>" class="text-blue-600 hover:text-blue-700 font-semibold text-sm ml-4">Edit</a>
                                        <a href="hapus.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus dokumen ini?')" class="text-red-600 hover:text-red-700 font-semibold text-sm ml-4">Hapus</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="p-8 text-center text-gray-500">
                    <p>Belum ada dokumen. <a href="tambah.php" class="text-primary-600 hover:text-primary-700">Tambah dokumen pertama</a></p>
                </div>
            <?php endif; ?>
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

