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
    header("Location: permohonan.php");
    exit;
}

// Query untuk mengambil data permohonan
$query = "SELECT * FROM permohonan WHERE id = $id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header("Location: permohonan.php");
    exit;
}

$row = mysqli_fetch_assoc($result);

$error = '';
$success = '';

// Proses update status
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $catatan = mysqli_real_escape_string($conn, $_POST['catatan']);

    $update_query = "UPDATE permohonan SET status = '$status', catatan_admin = '$catatan' WHERE id = $id";
    
    if (mysqli_query($conn, $update_query)) {
        $success = "Status permohonan berhasil diupdate!";
        // Refresh data
        $query = "SELECT * FROM permohonan WHERE id = $id";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
    } else {
        $error = "Gagal mengupdate status: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Permohonan - PPID</title>
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
                        <span class="text-xs text-gray-600">Detail Permohonan</span>
                    </div>
                </div>
                <nav class="hidden md:flex gap-6 items-center">
                    <a href="permohonan.php" class="text-gray-700 hover:text-primary-600 transition">Kembali</a>
                    <a href="dashboard.php" class="text-gray-700 hover:text-primary-600 transition">Dashboard</a>
                    <a href="login.php?logout=1" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">Logout</a>
                </nav>
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <div class="bg-white rounded-2xl p-8 soft-shadow max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold mb-8">Detail Permohonan</h1>
            
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

            <!-- Info Permohonan -->
            <div class="mb-8 space-y-4">
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold mb-2 text-gray-600">Nama Lengkap</label>
                        <div class="px-4 py-3 bg-gray-50 rounded-xl"><?php echo htmlspecialchars($row['nama']); ?></div>
                    </div>
                    <div>
                        <label class="block font-semibold mb-2 text-gray-600">Email</label>
                        <div class="px-4 py-3 bg-gray-50 rounded-xl"><?php echo htmlspecialchars($row['email']); ?></div>
                    </div>
                    <div>
                        <label class="block font-semibold mb-2 text-gray-600">Nomor Telepon</label>
                        <div class="px-4 py-3 bg-gray-50 rounded-xl"><?php echo htmlspecialchars($row['telepon']); ?></div>
                    </div>
                    <div>
                        <label class="block font-semibold mb-2 text-gray-600">Cara Penerimaan</label>
                        <div class="px-4 py-3 bg-gray-50 rounded-xl"><?php echo htmlspecialchars($row['cara_penerimaan']); ?></div>
                    </div>
                </div>
                <div>
                    <label class="block font-semibold mb-2 text-gray-600">Informasi yang Diminta</label>
                    <div class="px-4 py-3 bg-gray-50 rounded-xl min-h-[100px]"><?php echo nl2br(htmlspecialchars($row['informasi'])); ?></div>
                </div>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold mb-2 text-gray-600">Status</label>
                        <div class="px-4 py-3 bg-gray-50 rounded-xl">
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
                        </div>
                    </div>
                    <div>
                        <label class="block font-semibold mb-2 text-gray-600">Tanggal Permohonan</label>
                        <div class="px-4 py-3 bg-gray-50 rounded-xl"><?php echo date('d F Y H:i', strtotime($row['created_at'])); ?></div>
                    </div>
                </div>
                <?php if (!empty($row['catatan_admin'])): ?>
                    <div>
                        <label class="block font-semibold mb-2 text-gray-600">Catatan Admin</label>
                        <div class="px-4 py-3 bg-gray-50 rounded-xl"><?php echo nl2br(htmlspecialchars($row['catatan_admin'])); ?></div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Form Update Status -->
            <div class="border-t pt-8">
                <h2 class="text-2xl font-bold mb-6">Proses Permohonan</h2>
                <form method="POST" action="detail_permohonan.php?id=<?php echo $id; ?>">
                    <div class="mb-6">
                        <label class="block font-semibold mb-2">Status <span class="text-red-500">*</span></label>
                        <select name="status" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500">
                            <option value="Pending" <?php echo ($row['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                            <option value="Disetujui" <?php echo ($row['status'] == 'Disetujui') ? 'selected' : ''; ?>>Disetujui</option>
                            <option value="Ditolak" <?php echo ($row['status'] == 'Ditolak') ? 'selected' : ''; ?>>Ditolak</option>
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="block font-semibold mb-2">Catatan Admin</label>
                        <textarea name="catatan" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 h-32" placeholder="Masukkan catatan untuk pemohon (opsional)"><?php echo htmlspecialchars($row['catatan_admin']); ?></textarea>
                    </div>
                    <div class="flex gap-4">
                        <button type="submit" class="bg-primary-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-primary-700 transition">Update Status</button>
                        <a href="permohonan.php" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-xl font-semibold hover:bg-gray-300 transition">Kembali</a>
                    </div>
                </form>
            </div>
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

