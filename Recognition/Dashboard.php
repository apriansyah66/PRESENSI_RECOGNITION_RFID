<?php
// Cek apakah file config.php ada
if (file_exists('configg.php')) {
    include '../RFID/configg.php';
    include 'config_waktu.php'; // ambil jam kuliah & toleransi
} else {
    die('Error: File configg.php tidak ditemukan. Pastikan file config.php ada di direktori yang sama.');
}

// Cek koneksi database
if (!isset($koneksi) || !$koneksi) {
    die('Error: Koneksi database tidak tersedia. Periksa file config.php Anda.');
}

// Handle delete request
if (isset($_POST['delete_nim']) && isset($_POST['delete_waktu']) && !empty($_POST['delete_nim'])) {
    $delete_nim = mysqli_real_escape_string($koneksi, $_POST['delete_nim']);
    $delete_waktu = mysqli_real_escape_string($koneksi, $_POST['delete_waktu']);
    
    // Get foto_bukti path before deleting
    $foto_query = mysqli_query($koneksi, "SELECT foto_bukti FROM absensi WHERE nim = '$delete_nim' AND waktu = '$delete_waktu'");
    $foto_data = mysqli_fetch_assoc($foto_query);
    
    // Delete from database
    $delete_sql = "DELETE FROM absensi WHERE nim = '$delete_nim' AND waktu = '$delete_waktu'";
    $delete_result = mysqli_query($koneksi, $delete_sql);
    
    if ($delete_result) {
        // Delete foto file if exists
        if (!empty($foto_data['foto_bukti']) && file_exists('uploads/' . $foto_data['foto_bukti'])) {
            unlink('uploads/' . $foto_data['foto_bukti']);
        }
        
        echo "<script>
            alert('Data absensi berhasil dihapus!');
            window.location.href = '" . $_SERVER['PHP_SELF'] . "';
        </script>";
    } else {
        echo "<script>
            alert('Error: Gagal menghapus data absensi!');
        </script>";
    }
}

// Pagination setup
$limit = 10; // Jumlah data per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Filter setup
$filter_prodi = isset($_GET['prodi']) ? $_GET['prodi'] : '';
$filter_matkul = isset($_GET['matkul']) ? $_GET['matkul'] : '';
$filter_status = isset($_GET['status']) ? $_GET['status'] : '';

// Build WHERE clause
$where_conditions = [];
if (!empty($filter_prodi)) {
    $where_conditions[] = "p.id_prodi = '" . mysqli_real_escape_string($koneksi, $filter_prodi) . "'";
}
if (!empty($filter_matkul)) {
    $where_conditions[] = "mk.id_matkul = '" . mysqli_real_escape_string($koneksi, $filter_matkul) . "'";
}
if (!empty($filter_status)) {
    $where_conditions[] = "a.status = '" . mysqli_real_escape_string($koneksi, $filter_status) . "'";
}

$where_clause = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';

// Count total records for pagination
$count_sql = "SELECT COUNT(*) as total 
              FROM absensi a
              JOIN mahasiswa m ON a.nim = m.nim
              JOIN program_studi p ON m.id_prodi = p.id_prodi
              JOIN mata_kuliah mk ON a.id_matkul = mk.id_matkul
              $where_clause";

$count_result = mysqli_query($koneksi, $count_sql);
if (!$count_result) {
    die('Error dalam query count: ' . mysqli_error($koneksi));
}

$total_records = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_records / $limit);

// Main query with pagination
$sql = "SELECT 
            a.*, 
            m.nama AS nama_mhs, 
            p.nama_prodi, 
            mk.nama_matkul 
        FROM absensi a
        JOIN mahasiswa m ON a.nim = m.nim
        JOIN program_studi p ON m.id_prodi = p.id_prodi
        JOIN mata_kuliah mk ON a.id_matkul = mk.id_matkul
        $where_clause
        ORDER BY a.waktu DESC
        LIMIT $limit OFFSET $offset";

$query = mysqli_query($koneksi, $sql);
if (!$query) {
    die('Error dalam query utama: ' . mysqli_error($koneksi));
}

// 👇 Tambahkan di sini
$wajah_query = mysqli_query($koneksi, "SELECT * FROM wajah ORDER BY waktu DESC");
if (!$wajah_query) {
    die('Error query wajah: ' . mysqli_error($koneksi));
}

// Get data for filter options
$prodi_sql = "SELECT DISTINCT id_prodi, nama_prodi FROM program_studi 
              UNION 
              SELECT 'SIKC' as id_prodi, 'SIKC' as nama_prodi
              UNION 
              SELECT 'Axioo' as id_prodi, 'Axioo' as nama_prodi
              ORDER BY nama_prodi";
$prodi_query = mysqli_query($koneksi, $prodi_sql);
if (!$prodi_query) {
    // Fallback jika tabel program_studi belum ada
    $prodi_options = [
        ['id_prodi' => '1', 'nama_prodi' => 'Teknik Informatika'],
        ['id_prodi' => '2', 'nama_prodi' => 'Sistem Informasi'],
        ['id_prodi' => '3', 'nama_prodi' => 'Teknik Elektro'],
        ['id_prodi' => '4', 'nama_prodi' => 'Manajemen'],
        ['id_prodi' => '5', 'nama_prodi' => 'Akuntansi'],
        ['id_prodi' => 'SIKC', 'nama_prodi' => 'SIKC'],
        ['id_prodi' => 'Axioo', 'nama_prodi' => 'Axioo']
    ];
}

$matkul_sql = "SELECT DISTINCT id_matkul, nama_matkul FROM mata_kuliah 
               UNION 
               SELECT 'MK001', 'Algoritma dan Pemrograman'
               UNION 
               SELECT 'MK002', 'Struktur Data'
               UNION 
               SELECT 'MK003', 'Basis Data'
               UNION 
               SELECT 'MK004', 'Pemrograman Web'
               UNION 
               SELECT 'MK005', 'Jaringan Komputer'
               UNION 
               SELECT 'MK006', 'Sistem Operasi'
               UNION 
               SELECT 'MK007', 'Kecerdasan Buatan'
               UNION 
               SELECT 'MK008', 'Pemrograman Mobile'
               ORDER BY nama_matkul";

$matkul_query = mysqli_query($koneksi, $matkul_sql);
if (!$matkul_query || mysqli_num_rows($matkul_query) === 0) {
    // fallback jika query gagal atau tidak ada data
    $fallback_matkuls = [
        ['id_matkul' => 'MK001', 'nama_matkul' => 'Algoritma dan Pemrograman'],
        ['id_matkul' => 'MK002', 'nama_matkul' => 'Struktur Data'],
        ['id_matkul' => 'MK003', 'nama_matkul' => 'Basis Data'],
        ['id_matkul' => 'MK004', 'nama_matkul' => 'Pemrograman Web'],
        ['id_matkul' => 'MK005', 'nama_matkul' => 'Jaringan Komputer'],
        ['id_matkul' => 'MK006', 'nama_matkul' => 'Sistem Operasi'],
        ['id_matkul' => 'MK007', 'nama_matkul' => 'Kecerdasan Buatan'],
        ['id_matkul' => 'MK008', 'nama_matkul' => 'Pemrograman Mobile']
    ];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Absensi</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #007BFF, #0056b3);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h2 {
            font-size: 2em;
            margin-bottom: 10px;
        }
        
        .header p {
            opacity: 0.9;
            font-size: 1.1em;
        }
        
        .filters {
            padding: 20px;
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }
        
        .filter-row {
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        
        .filter-group label {
            font-weight: 600;
            color: #495057;
            font-size: 0.9em;
        }
        
        .filter-group select {
            padding: 8px 12px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            background: white;
            min-width: 150px;
        }
        
        .btn {
            padding: 8px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            font-size: 0.9em;
        }
        
        .btn-primary {
            background: #007BFF;
            color: white;
        }
        
        .btn-primary:hover {
            background: #0056b3;
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #545b62;
        }
        
        .btn-danger {
            background: #dc3545;
            color: white;
            padding: 5px 10px;
            font-size: 0.8em;
        }
        
        .btn-danger:hover {
            background: #c82333;
        }
        
        .btn-small {
            padding: 4px 8px;
            font-size: 0.75em;
        }
        
        .table-container {
            padding: 20px;
            overflow-x: auto;
        }
        
        .stats {
            margin-bottom: 20px;
            padding: 15px;
            background: #e3f2fd;
            border-radius: 5px;
            color: #1565c0;
            font-weight: 600;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        th {
            background: linear-gradient(135deg, #007BFF, #0056b3);
            color: white;
            padding: 15px 12px;
            text-align: left;
            font-weight: 600;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }
        
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        tr:hover {
            background-color: #e3f2fd;
            transform: translateY(-1px);
            transition: all 0.2s ease;
        }
        
        .foto {
            max-height: 50px;
            max-width: 50px;
            border-radius: 5px;
            cursor: pointer;
            transition: transform 0.2s ease;
        }
        
        .foto:hover {
            transform: scale(1.1);
        }
        
        .status {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.85em;
            font-weight: 600;
            text-align: center;
        }
        
        .status.hadir {
            background: #d4edda;
            color: #155724;
        }
        
        .status.tidak-hadir {
            background: #f8d7da;
            color: #721c24;
        }
        
        .status.terlambat {
            background: #fff3cd;
            color: #856404;
        }
        
        .pagination {
            padding: 20px;
            text-align: center;
            background: #f8f9fa;
        }
        
        .pagination a, .pagination span {
            display: inline-block;
            padding: 8px 12px;
            margin: 0 3px;
            text-decoration: none;
            border: 1px solid #dee2e6;
            color: #007BFF;
            border-radius: 3px;
        }
        
        .pagination a:hover {
            background: #e9ecef;
        }
        
        .pagination .current {
            background: #007BFF;
            color: white;
            border-color: #007BFF;
        }
        
        .no-data {
            text-align: center;
            padding: 50px;
            color: #6c757d;
            font-style: italic;
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.8);
        }
        
        .modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            max-width: 90%;
            max-height: 90%;
        }
        
        .modal img {
            max-width: 100%;
            max-height: 100%;
            border-radius: 10px;
        }
        
        .close {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
        }
        
        .close:hover {
            color: #ccc;
        }
        
        .action-buttons {
            display: flex;
            gap: 5px;
            justify-content: center;
        }
        
        .confirm-modal {
            display: none;
            position: fixed;
            z-index: 1001;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        
        .confirm-modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            max-width: 400px;
            width: 90%;
        }
        
        .confirm-modal h3 {
            margin-bottom: 15px;
            color: #dc3545;
        }
        
        .confirm-modal p {
            margin-bottom: 20px;
            color: #6c757d;
        }
        
        .confirm-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        
        @media (max-width: 768px) {
            .filter-row {
                flex-direction: column;
                align-items: stretch;
            }
            
            .filter-group {
                width: 100%;
            }
            
            .filter-group select {
                min-width: 100%;
            }
            
            table {
                font-size: 0.9em;
            }
            
            th, td {
                padding: 8px 6px;
            }
            
            .header h2 {
                font-size: 1.5em;
            }
            
            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>📋 Dashboard Absensi Mahasiswa</h2>
            <p>Sistem Monitoring Kehadiran Mahasiswa</p>
        </div>
        
        <!-- Filter Section -->
        <div class="filters">
            <form method="GET" action="">
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="prodi">Program Studi:</label>
                        <select name="prodi" id="prodi">
                            <option value="">-- Semua Prodi --</option>
                            <?php 
                            if ($prodi_query) {
                                while ($prodi = mysqli_fetch_assoc($prodi_query)): 
                            ?>
                                <option value="<?= htmlspecialchars($prodi['id_prodi']) ?>" 
                                        <?= $filter_prodi == $prodi['id_prodi'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($prodi['nama_prodi']) ?>
                                </option>
                            <?php 
                                endwhile; 
                            } else {
                                // Fallback options jika query gagal
                                foreach ($prodi_options as $prodi):
                            ?>
                                <option value="<?= htmlspecialchars($prodi['id_prodi']) ?>" 
                                        <?= $filter_prodi == $prodi['id_prodi'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($prodi['nama_prodi']) ?>
                                </option>
                            <?php endforeach; } ?>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="matkul">Mata Kuliah:</label>
                        <select name="matkul" id="matkul">
                            <option value="">-- Semua Mata Kuliah --</option>
                            <?php 
                            if ($matkul_query && mysqli_num_rows($matkul_query) > 0):
                                while ($matkul = mysqli_fetch_assoc($matkul_query)): ?>
                                    <option value="<?= htmlspecialchars($matkul['id_matkul']) ?>" 
                                        <?= $filter_matkul == $matkul['id_matkul'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($matkul['nama_matkul']) ?>
                                    </option>
                            <?php endwhile;
                            elseif (!empty($fallback_matkuls)):
                                foreach ($fallback_matkuls as $matkul): ?>
                                    <option value="<?= htmlspecialchars($matkul['id_matkul']) ?>" 
                                        <?= $filter_matkul == $matkul['id_matkul'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($matkul['nama_matkul']) ?>
                                    </option>
                            <?php endforeach; 
                            endif; ?>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="status">Status:</label>
                        <select name="status" id="status">
                            <option value="">-- Semua Status --</option>
                            <option value="Hadir" <?= $filter_status == 'Hadir' ? 'selected' : '' ?>>Hadir</option>
                            <option value="Tidak Hadir" <?= $filter_status == 'Tidak Hadir' ? 'selected' : '' ?>>Tidak Hadir</option>
                            <option value="Terlambat" <?= $filter_status == 'Terlambat' ? 'selected' : '' ?>>Terlambat</option>
                        </select>
                    </div>
                    
                    <div class="filter-group" style="flex-direction: row; gap: 10px; align-items: end;">
                        <button type="submit" class="btn btn-primary">🔍 Filter</button>
                        <a href="?" class="btn btn-secondary">🔄 Reset</a>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Table Section -->
 <div class="table-container">
    <div class="stats">
        🧑‍💻 Daftar Wajah Terdaftar
    </div>

<?php if (isset($wajah_query) && $wajah_query && mysqli_num_rows($wajah_query) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>UID</th>
                <th>Foto Wajah</th>
                <th>Waktu Daftar</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            while ($wajah = mysqli_fetch_assoc($wajah_query)): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($wajah['uid']) ?></td>
                <td>
                    <?php 
                    $fotoPath = 'Recognition/known_faces/' . htmlspecialchars($wajah['file']);
                    if (!empty($wajah['file']) && file_exists($fotoPath)): ?>
                        <img class="foto" 
                             src="<?= $fotoPath ?>" 
                             alt="Wajah" 
                             onclick="openModal('<?= $fotoPath ?>')">
                    <?php else: ?>
                        <span style="color:#888;font-style:italic;">Tidak ada foto</span>
                    <?php endif; ?>
                </td>
                <td><?= date('d/m/Y H:i', strtotime($wajah['waktu'])) ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <div class="no-data">
        <h3>📭 Belum ada wajah terdaftar</h3>
        <p>Silakan daftar wajah dulu melalui halaman enroll.</p>
    </div>
<?php endif; ?>
</div>
            <div class="stats">
                📊 Menampilkan <?= mysqli_num_rows($query) ?> dari <?= $total_records ?> total data absensi
            </div>
            
            <?php if (mysqli_num_rows($query) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>NIM</th>
                            <th>Nama Mahasiswa</th>
                            <th>Program Studi</th>
                            <th>Mata Kuliah</th>
                            <th>Waktu</th>
                            <th>Status</th>
                            <th>Foto Bukti</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($data = mysqli_fetch_assoc($query)): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($data['nim']) ?></strong></td>
                                <td><?= htmlspecialchars($data['nama_mhs']) ?></td>
                                <td><?= htmlspecialchars($data['nama_prodi']) ?></td>
                                <td><?= htmlspecialchars($data['nama_matkul']) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($data['waktu'])) ?></td>
                                <td>
                                    <span class="status <?= strtolower(str_replace(' ', '-', $data['status'])) ?>">
                                        <?= htmlspecialchars($data['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if (!empty($data['foto_bukti']) && file_exists('uploads/' . $data['foto_bukti'])): ?>
                                        <img class="foto" 
                                             src="uploads/<?= htmlspecialchars($data['foto_bukti']) ?>" 
                                             alt="Foto Bukti" 
                                             onclick="openModal('uploads/<?= htmlspecialchars($data['foto_bukti']) ?>')">
                                    <?php else: ?>
                                        <span style="color: #6c757d; font-style: italic;">Tidak ada foto</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-danger btn-small" 
                                                onclick="confirmDelete('<?= htmlspecialchars($data['nim']) ?>', '<?= htmlspecialchars($data['waktu']) ?>', '<?= htmlspecialchars($data['nama_mhs']) ?>')">
                                            🗑️ Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-data">
                    <h3>📭 Tidak ada data absensi</h3>
                    <p>Belum ada data absensi yang sesuai dengan filter yang dipilih.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=1<?= !empty($_GET) ? '&' . http_build_query(array_diff_key($_GET, ['page' => ''])) : '' ?>">« First</a>
                    <a href="?page=<?= $page - 1 ?><?= !empty($_GET) ? '&' . http_build_query(array_diff_key($_GET, ['page' => ''])) : '' ?>">‹ Prev</a>
                <?php endif; ?>
                
                <?php
                $start_page = max(1, $page - 2);
                $end_page = min($total_pages, $page + 2);
                
                for ($i = $start_page; $i <= $end_page; $i++):
                ?>
                    <?php if ($i == $page): ?>
                        <span class="current"><?= $i ?></span>
                    <?php else: ?>
                        <a href="?page=<?= $i ?><?= !empty($_GET) ? '&' . http_build_query(array_diff_key($_GET, ['page' => ''])) : '' ?>"><?= $i ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
                
                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?= $page + 1 ?><?= !empty($_GET) ? '&' . http_build_query(array_diff_key($_GET, ['page' => ''])) : '' ?>">Next ›</a>
                    <a href="?page=<?= $total_pages ?><?= !empty($_GET) ? '&' . http_build_query(array_diff_key($_GET, ['page' => ''])) : '' ?>">Last »</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Modal for Image Preview -->
    <div id="imageModal" class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <div class="modal-content">
            <img id="modalImage" src="" alt="Preview">
        </div>
    </div>
    
    <!-- Confirm Delete Modal -->
    <div id="confirmModal" class="confirm-modal">
        <div class="confirm-modal-content">
            <h3>⚠️ Konfirmasi Hapus</h3>
            <p>Apakah Anda yakin ingin menghapus data absensi untuk mahasiswa <strong id="studentName"></strong>?</p>
            <p style="color: #dc3545; font-size: 0.9em;">Data yang dihapus tidak dapat dikembalikan!</p>
            <div class="confirm-buttons">
                <button class="btn btn-secondary" onclick="closeConfirmModal()">Batal</button>
                <button class="btn btn-danger" onclick="executeDelete()">Ya, Hapus</button>
            </div>
        </div>
    </div>
    
    <!-- Hidden form for delete -->
    <form id="deleteForm" method="POST" style="display: none;">
        <input type="hidden" name="delete_nim" id="deleteNim">
        <input type="hidden" name="delete_waktu" id="deleteWaktu">
    </form>
    
    <script>
        let deleteDataToConfirm = null;
        
        function openModal(imageSrc) {
            const modal = document.getElementById('imageModal');
            const modalImg = document.getElementById('modalImage');
            modal.style.display = 'block';
            modalImg.src = imageSrc;
        }
        
        function closeModal() {
            document.getElementById('imageModal').style.display = 'none';
        }
        
        function confirmDelete(nim, waktu, studentName) {
            deleteDataToConfirm = {nim: nim, waktu: waktu};
            document.getElementById('studentName').textContent = studentName;
            document.getElementById('confirmModal').style.display = 'block';
        }
        
        function closeConfirmModal() {
            document.getElementById('confirmModal').style.display = 'none';
            deleteDataToConfirm = null;
        }
        
        function executeDelete() {
            if (deleteDataToConfirm) {
                document.getElementById('deleteNim').value = deleteDataToConfirm.nim;
                document.getElementById('deleteWaktu').value = deleteDataToConfirm.waktu;
                document.getElementById('deleteForm').submit();
            }
        }
        
        // Close modal when clicking outside of image
        window.onclick = function(event) {
            const modal = document.getElementById('imageModal');
            const confirmModal = document.getElementById('confirmModal');
            
            if (event.target === modal) {
                closeModal();
            }
            
            if (event.target === confirmModal) {
                closeConfirmModal();
            }
        }
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
                closeConfirmModal();
            }
        });
    </script>
</body>
</html>