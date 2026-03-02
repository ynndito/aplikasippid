<?php
session_start();
include 'koneksi.php';

// Cek apakah sudah login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Ambil filter status
$status_filter = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : '';

// Query untuk mengambil permohonan
$query = "SELECT * FROM permohonan WHERE 1=1";

if (!empty($status_filter)) {
    $query .= " AND status = '$status_filter'";
}

$query .= " ORDER BY created_at DESC";

$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Permohonan - PPID</title>
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
                        <span class="text-xs text-gray-600">Kelola Permohonan</span>
                    </div>
                </div>
                <nav class="hidden md:flex gap-6 items-center">
                    <span class="text-gray-600">Selamat datang, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                    <a href="dashboard.php" class="text-gray-700 hover:text-primary-600 transition">Dashboard</a>
                    <a href="../index.php" class="text-gray-700 hover:text-primary-600 transition">Beranda</a>
                    <a href="login.php?logout=1" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">Logout</a>
                </nav>
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <div class="mb-8">
            <h1 class="text-3xl font-bold mb-4">Kelola Permohonan Informasi</h1>
            
            <!-- Filter Status -->
            <div class="bg-white rounded-2xl p-4 soft-shadow inline-block">
                <form method="GET" action="permohonan.php" class="flex gap-4 items-center">
                    <label class="font-semibold">Filter Status:</label>
                    <select name="status" onchange="this.form.submit()" class="px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <option value="">Semua Status</option>
                        <option value="Pending" <?php echo ($status_filter == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                        <option value="Disetujui" <?php echo ($status_filter == 'Disetujui') ? 'selected' : ''; ?>>Disetujui</option>
                        <option value="Ditolak" <?php echo ($status_filter == 'Ditolak') ? 'selected' : ''; ?>>Ditolak</option>
                    </select>
                    <?php if (!empty($status_filter)): ?>
                        <a href="permohonan.php" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-xl font-semibold hover:bg-gray-300 transition">Reset</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <!-- Tabel Permohonan -->
        <div class="bg-white rounded-2xl overflow-hidden soft-shadow">
            <div class="p-6 border-b">
                <h2 class="text-2xl font-bold">Daftar Permohonan</h2>
            </div>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-primary-600 text-white">
                            <tr>
                                <th class="px-6 py-4 text-left">Nama</th>
                                <th class="px-6 py-4 text-left">Email</th>
                                <th class="px-6 py-4 text-left">Telepon</th>
                                <th class="px-6 py-4 text-left">Informasi</th>
                                <th class="px-6 py-4 text-left">Cara Penerimaan</th>
                                <th class="px-6 py-4 text-left">Status</th>
                                <th class="px-6 py-4 text-left">Tanggal</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($row['nama']); ?></td>
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($row['telepon']); ?></td>
                                    <td class="px-6 py-4">
                                        <div class="max-w-xs truncate" title="<?php echo htmlspecialchars($row['informasi']); ?>">
                                            <?php echo htmlspecialchars($row['informasi']); ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($row['cara_penerimaan']); ?></td>
                                    <td class="px-6 py-4">
                                        <?php
                                        $status_colors = [
                                            'Pending' => 'bg-yellow-100 text-yellow-700',
                                            'Disetujui' => 'bg-green-100 text-green-700',
                                            'Ditolak' => 'bg-red-100 text-red-700'
                                        ];
                                        $color = $status_colors[$row['status']] ?? 'bg-gray-100 text-gray-700';
                                        ?>
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold <?php echo $color; ?>">
                                            <?php echo htmlspecialchars($row['status']); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4"><?php echo date('d F Y', strtotime($row['created_at'])); ?></td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="detail_permohonan.php?id=<?php echo $row['id']; ?>" class="text-primary-600 hover:text-primary-700 font-semibold text-sm">Detail</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="p-8 text-center text-gray-500">
                    <p>Tidak ada permohonan ditemukan.</p>
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

