<?php
include __DIR__ . '/koneksi.php';

// Ambil parameter search dan filter
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$kategori = isset($_GET['kategori']) ? mysqli_real_escape_string($conn, $_GET['kategori']) : '';
$tahun = isset($_GET['tahun']) ? intval($_GET['tahun']) : 0;

// Query untuk mengambil data dokumen (untuk Popular Documents - 3 terbaru)
$query_popular = "SELECT * FROM dokumen ORDER BY tanggal DESC, id DESC LIMIT 3";
$result_popular = mysqli_query($conn, $query_popular);

// Query untuk mengambil data dokumen (untuk Latest Documents - 5 terbaru)
$query_latest = "SELECT * FROM dokumen ORDER BY tanggal DESC, id DESC LIMIT 5";
$result_latest = mysqli_query($conn, $query_latest);

// Query untuk mengambil semua dokumen dengan filter
$query_all = "SELECT * FROM dokumen WHERE 1=1";

if (!empty($search)) {
    $query_all .= " AND judul LIKE '%$search%'";
}

if (!empty($kategori)) {
    $query_all .= " AND kategori = '$kategori'";
}

if ($tahun > 0) {
    $query_all .= " AND tahun = $tahun";
}

$query_all .= " ORDER BY tanggal DESC, id DESC";
$result_all = mysqli_query($conn, $query_all);

// Ambil semua kategori untuk filter
$kategori_query = "SELECT DISTINCT kategori FROM dokumen ORDER BY kategori";
$kategori_result = mysqli_query($conn, $kategori_query);

// Ambil semua tahun untuk filter
$tahun_query = "SELECT DISTINCT tahun FROM dokumen ORDER BY tahun DESC";
$tahun_result = mysqli_query($conn, $tahun_query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi PPID - KPU Kota Probolinggo</title>
    <meta name="description" content="Portal Sistem Informasi PPID KPU Kota Probolinggo - Pejabat Pengelola Informasi dan Dokumentasi. Akses informasi publik dengan mudah dan transparan.">
    <meta name="theme-color" content="#800000">
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
        * {
            scrollbar-width: thin;
        }
        .soft-shadow {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <!-- HEADER/NAVBAR -->
    <header class="bg-white soft-shadow sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-3">
                    <img 
        src="assets/img/logo-kpu.png" 
        alt="Logo KPU"
        class="w-9 h-9 object-contain"
    />
                    <div class="flex flex-col">
                        <span class="font-bold text-gray-800 text-sm leading-tight">KPU KOTA PROBOLINGGO</span>
                        <span class="text-xs text-gray-600">Pejabat Pengelola Informasi dan Dokumentasi</span>
                    </div>
                </div>
                <nav class="hidden md:flex gap-6 items-center">
                    <button onclick="showSection('landing')" class="text-gray-700 hover:text-primary-600 transition">Beranda</button>
                    <button onclick="showSection('about')" class="text-gray-700 hover:text-primary-600 transition">Tentang PPID</button>
                    <button onclick="showSection('daftar')" class="text-gray-700 hover:text-primary-600 transition">Daftar Informasi</button>
                    <button onclick="showSection('dokumen')" class="text-gray-700 hover:text-primary-600 transition">Dokumen Publik</button>
                    <button onclick="showSection('permohonan')" class="text-gray-700 hover:text-primary-600 transition">Ajukan Permohonan</button>
                    <a href="login.php" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition">Login</a>
                </nav>
                <button onclick="toggleMobileMenu()" class="md:hidden bg-gray-100 p-2 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
            <!-- Mobile Menu -->
            <div id="mobileMenu" class="hidden md:hidden pb-4 border-t">
                <button onclick="showSection('landing')" class="block w-full text-left py-2 px-4 hover:bg-gray-100">Beranda</button>
                <button onclick="showSection('about')" class="block w-full text-left py-2 px-4 hover:bg-gray-100">Tentang PPID</button>
                <button onclick="showSection('daftar')" class="block w-full text-left py-2 px-4 hover:bg-gray-100">Daftar Informasi</button>
                <button onclick="showSection('dokumen')" class="block w-full text-left py-2 px-4 hover:bg-gray-100">Dokumen Publik</button>
                <button onclick="showSection('permohonan')" class="block w-full text-left py-2 px-4 hover:bg-gray-100">Ajukan Permohonan</button>
                <a href="login.php" class="block w-full text-left py-2 px-4 bg-primary-600 text-white rounded-lg mt-2">Login</a>
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <!-- LANDING PAGE -->
        <section id="landing" class="fade-in">
            <!-- Hero Section -->
            <div class="relative rounded-2xl p-8 md:p-16 mb-16 soft-shadow overflow-hidden bg-gradient-to-r from-primary-600 to-primary-700">
                <div class="relative z-10">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4 text-white">Sistem Informasi PPID</h1>
                    <p class="text-lg mb-8 text-white opacity-90">Pusat Informasi Publik dan Dokumentasi. Akses informasi publik dengan mudah dan transparan.</p>
                    <button onclick="showSection('permohonan')" class="bg-white text-primary-600 px-8 py-3 rounded-xl font-semibold hover:bg-gray-100 transition">
                        Ajukan Permohonan
                    </button>
                </div>
            </div>

            <!-- Search & Filter Section -->
            <div class="bg-white rounded-2xl p-8 mb-16 soft-shadow">
                <h2 class="text-2xl font-bold mb-6">Cari Dokumen</h2>
                <form method="GET" action="index.php" class="flex flex-col md:flex-row gap-4">
                    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Cari dokumen..." class="flex-1 px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <select name="kategori" class="px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <option value="">Semua Kategori</option>
                        <?php
                        mysqli_data_seek($kategori_result, 0);
                        while ($row = mysqli_fetch_assoc($kategori_result)) {
                            $selected = ($kategori == $row['kategori']) ? 'selected' : '';
                            echo "<option value=\"{$row['kategori']}\" $selected>{$row['kategori']}</option>";
                        }
                        ?>
                    </select>
                    <select name="tahun" class="px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <option value="">Semua Tahun</option>
                        <?php
                        while ($row = mysqli_fetch_assoc($tahun_result)) {
                            $selected = ($tahun == $row['tahun']) ? 'selected' : '';
                            echo "<option value=\"{$row['tahun']}\" $selected>{$row['tahun']}</option>";
                        }
                        ?>
                    </select>
                    <button type="submit" class="bg-primary-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-primary-700 transition">Cari</button>
                    <?php if (!empty($search) || !empty($kategori) || $tahun > 0): ?>
                        <a href="index.php" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-xl font-semibold hover:bg-gray-300 transition">Reset</a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Popular Documents -->
            <div class="mb-16">
                <h2 class="text-2xl font-bold mb-6">Informasi Publik Populer</h2>
                <div class="grid md:grid-cols-3 gap-6">
                    <?php
                    if (mysqli_num_rows($result_popular) > 0) {
                        $color_classes = [
                            ['bg' => 'bg-primary-100', 'text' => 'text-primary-700', 'icon' => 'text-primary-600'],
                            ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'icon' => 'text-blue-600'],
                            ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'icon' => 'text-green-600']
                        ];
                        $i = 0;
                        while ($row = mysqli_fetch_assoc($result_popular)) {
                            $color = $color_classes[$i % 3];
                            ?>
                            <div class="bg-white rounded-2xl p-6 soft-shadow hover:shadow-lg transition cursor-pointer" onclick="window.location.href='detail.php?id=<?php echo $row['id']; ?>'">
                                <div class="<?php echo $color['bg']; ?> w-12 h-12 rounded-xl flex items-center justify-center mb-4">
                                    <svg class="w-6 h-6 <?php echo $color['icon']; ?>" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 5.414l4 4v8.586a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"></path></svg>
                                </div>
                                <h3 class="font-bold text-lg mb-2"><?php echo htmlspecialchars($row['judul']); ?></h3>
                                <p class="text-gray-600 text-sm mb-4"><?php echo date('d F Y', strtotime($row['tanggal'])); ?></p>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="<?php echo $color['bg']; ?> <?php echo $color['text']; ?> px-3 py-1 rounded-full text-xs font-semibold"><?php echo htmlspecialchars($row['kategori']); ?></span>
                                    <span class="text-gray-500"><?php echo htmlspecialchars($row['tahun']); ?></span>
                                </div>
                            </div>
                            <?php
                            $i++;
                        }
                    } else {
                        echo '<div class="col-span-3 text-center text-gray-500 py-8">Belum ada dokumen populer.</div>';
                    }
                    ?>
                </div>
            </div>

            <!-- Latest Documents -->
            <div>
                <h2 class="text-2xl font-bold mb-6">Informasi Terbaru</h2>
                <div class="space-y-4">
                    <?php
                    if (mysqli_num_rows($result_latest) > 0) {
                        while ($row = mysqli_fetch_assoc($result_latest)) {
                            ?>
                            <div class="bg-white rounded-2xl p-6 soft-shadow hover:shadow-lg transition flex items-center justify-between cursor-pointer" onclick="window.location.href='detail.php?id=<?php echo $row['id']; ?>'">
                                <div>
                                    <h3 class="font-bold text-lg"><?php echo htmlspecialchars($row['judul']); ?></h3>
                                    <p class="text-gray-600 text-sm">Kategori: <?php echo htmlspecialchars($row['kategori']); ?> | Tanggal: <?php echo date('d F Y', strtotime($row['tanggal'])); ?></p>
                                </div>
                                <a href="detail.php?id=<?php echo $row['id']; ?>" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700">Detail</a>
                            </div>
                            <?php
                        }
                    } else {
                        echo '<div class="text-center text-gray-500 py-8">Belum ada dokumen terbaru.</div>';
                    }
                    ?>
                </div>
            </div>
        </section>

        <!-- ABOUT SECTION -->
        <section id="about" class="fade-in hidden">
            <div class="bg-white rounded-2xl p-8 soft-shadow">
                <h1 class="text-3xl font-bold mb-6">Tentang PPID</h1>
                <div class="prose max-w-none space-y-4 text-gray-700">
                    <p>Pusat Informasi Publik dan Dokumentasi (PPID) adalah lembaga yang bertanggung jawab atas pengelolaan dan penyediaan informasi publik kepada masyarakat.</p>
                    <p>Kami berkomitmen untuk meningkatkan transparansi dan akuntabilitas pemerintah melalui akses informasi yang mudah, cepat, dan akurat.</p>
                    <h2 class="text-2xl font-bold mt-6 mb-4">Visi & Misi</h2>
                    <p><strong>Visi:</strong> Menjadi pusat informasi publik yang terpercaya dan transparan.</p>
                    <p><strong>Misi:</strong> Menyediakan akses informasi publik yang mudah, cepat, dan berkualitas untuk semua kalangan.</p>
                </div>
            </div>
        </section>

        <!-- DAFTAR INFORMASI SECTION -->
        <section id="daftar" class="fade-in hidden">
            <h1 class="text-3xl font-bold mb-8">Daftar Informasi Publik</h1>
            <div class="bg-white rounded-2xl overflow-hidden soft-shadow">
                <?php if (mysqli_num_rows($result_all) > 0): ?>
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
                                <?php
                                mysqli_data_seek($result_all, 0);
                                while ($row = mysqli_fetch_assoc($result_all)):
                                ?>
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
        </section>

        <!-- DOKUMEN PUBLIK SECTION -->
        <section id="dokumen" class="fade-in hidden">
            <h1 class="text-3xl font-bold mb-8">Dokumen Publik</h1>
            <div class="grid md:grid-cols-2 gap-6">
                <?php
                mysqli_data_seek($result_all, 0);
                if (mysqli_num_rows($result_all) > 0) {
                    while ($row = mysqli_fetch_assoc($result_all)) {
                        ?>
                        <div class="bg-white rounded-2xl p-6 soft-shadow hover:shadow-lg transition cursor-pointer" onclick="window.location.href='detail.php?id=<?php echo $row['id']; ?>'">
                            <div class="bg-primary-100 w-12 h-12 rounded-xl flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-primary-600" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 5.414l4 4v8.586a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"></path></svg>
                            </div>
                            <h3 class="font-bold text-lg mb-2"><?php echo htmlspecialchars($row['judul']); ?></h3>
                            <p class="text-gray-600 text-sm mb-4"><?php echo date('d F Y', strtotime($row['tanggal'])); ?></p>
                            <div class="flex items-center justify-between">
                                <span class="bg-primary-100 text-primary-700 px-3 py-1 rounded-full text-xs font-semibold"><?php echo htmlspecialchars($row['kategori']); ?></span>
                                <span class="text-gray-500"><?php echo htmlspecialchars($row['tahun']); ?></span>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo '<div class="col-span-2 text-center text-gray-500 py-8">Tidak ada dokumen ditemukan.</div>';
                }
                ?>
            </div>
        </section>

        <!-- FORM PERMOHONAN SECTION -->
        <section id="permohonan" class="fade-in hidden">
            <h1 class="text-3xl font-bold mb-8">Ajukan Permohonan Informasi</h1>
            <div class="bg-white rounded-2xl p-8 soft-shadow max-w-2xl mx-auto">
                <?php if (isset($_GET['permohonan']) && $_GET['permohonan'] == 'success'): ?>
                    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                        Permohonan Anda telah berhasil dikirim! Anda akan menerima email balasan dalam 3 hari kerja.
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['permohonan']) && $_GET['permohonan'] == 'error'): ?>
                    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                        Terjadi kesalahan saat mengirim permohonan. Silakan coba lagi.
                    </div>
                <?php endif; ?>

                <form method="POST" action="proses_permohonan.php">
                    <div class="mb-6">
                        <label class="block font-semibold mb-2">Nama Lengkap</label>
                        <input type="text" name="nama" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="Masukkan nama lengkap">
                    </div>
                    <div class="mb-6">
                        <label class="block font-semibold mb-2">Email</label>
                        <input type="email" name="email" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="Masukkan email">
                    </div>
                    <div class="mb-6">
                        <label class="block font-semibold mb-2">Nomor Telepon</label>
                        <input type="tel" name="telepon" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="Masukkan nomor telepon">
                    </div>
                    <div class="mb-6">
                        <label class="block font-semibold mb-2">Informasi yang Diminta</label>
                        <textarea name="informasi" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 h-32" placeholder="Jelaskan informasi apa yang Anda minta..."></textarea>
                    </div>
                    <div class="mb-6">
                        <label class="block font-semibold mb-2">Cara Penerimaan</label>
                        <select name="cara_penerimaan" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500">
                            <option value="">Pilih cara penerimaan</option>
                            <option value="Email">Email</option>
                            <option value="Ditempat">Di Tempat</option>
                            <option value="Pos">Pos</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full bg-primary-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-primary-700 transition">Kirim Permohonan</button>
                </form>
            </div>
        </section>

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

    <script>
        // Mobile Menu Toggle
        function toggleMobileMenu() {
            document.getElementById('mobileMenu').classList.toggle('hidden');
        }

        // Show/Hide Sections
        function showSection(sectionId) {
            document.querySelectorAll('main > section').forEach(s => s.classList.add('hidden'));
            const target = document.getElementById(sectionId);
            if (target) {
                target.classList.remove('hidden');
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
            document.getElementById('mobileMenu').classList.add('hidden');
        }

        // Smooth scroll untuk anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const sectionId = this.getAttribute('href').substring(1);
                showSection(sectionId);
            });
        });

        // Show landing section by default
        window.addEventListener('load', function() {
            showSection('landing');
        });
    </script>

</body>
</html>
<?php
mysqli_close($conn);
?>

