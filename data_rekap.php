<?php
$page = "Data Rekap";
require_once("./header.php");
require_once("./configg.php");

// Ambil filter dari GET
$filter_prodi = $_GET['prodi'] ?? '';
$filter_matkul = $_GET['matkul'] ?? '';
$filter_status = $_GET['status'] ?? '';

// Siapkan WHERE
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

// Query data
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
        ORDER BY a.waktu DESC";
$query = mysqli_query($koneksi, $sql);
$total_records = mysqli_num_rows($query);

?>
<style>
 /* Body */
body {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    color: #fff;
}

/* Container */
.container-fluid {
    position: relative;
    z-index: 10;
    padding-top: 30px;
}

/* Page Title */
.page-title {
    color: #fff;
    text-shadow: 2px 2px 6px rgba(0,0,0,0.6);
    font-weight: bold;
    margin-bottom: 25px;
    font-size: 2.5rem;
}

/* Breadcrumb */
.breadcrumb-3d {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(12px);
    border-radius: 12px;
    border: 1px solid rgba(255, 255, 255, 0.25);
    margin-bottom: 35px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    color: #fff;
}

.breadcrumb {
    background: transparent;
    margin: 0;
    padding: 12px 25px;
}

.breadcrumb-item {
    color: #fff;
    font-size: 1rem;
}

.breadcrumb-item.active {
    color: #ffda47;
    font-weight: bold;
    text-shadow: 0 0 8px rgba(255, 218, 71, 0.4);
}

.breadcrumb-item a {
    color: #fff;
}

.breadcrumb-item a:hover {
    color: #ffd700;
}

/* Alert */
.alert {
    background: rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(18px);
    border: 1px solid rgba(255, 255, 255, 0.4);
    border-radius: 18px;
    color: #fff;
    box-shadow: 0 12px 35px rgba(0,0,0,0.35);
    animation: slideInDown 0.6s ease-out;
    padding: 18px 25px;
    font-size: 1.1rem !important;
    text-align: center;
}

.alert-success {
    background: rgba(76, 175, 80, 0.4);
    border-color: rgba(76, 175, 80, 0.6);
}

.alert-danger {
    background: rgba(244, 67, 54, 0.4);
    border-color: rgba(244, 67, 54, 0.6);
}

.alert-warning {
    background: rgba(255, 193, 7, 0.4);
    border-color: rgba(255, 193, 7, 0.6);
}

.alert .close {
    color: #fff;
    opacity: 0.8;
}

.alert .close:hover {
    opacity: 1;
}

/* Card */
.card-3d {
    background: rgba(255, 255, 255, 0.18);
    backdrop-filter: blur(18px);
    border: 1px solid rgba(255, 255, 255, 0.28);
    border-radius: 25px;
    box-shadow: 0 18px 40px rgba(0,0,0,0.4);
    transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
    overflow: hidden;
    animation: float 8s ease-in-out infinite;
    color: #fff;
}

.card-3d:hover {
    transform: translateY(-8px);
    box-shadow: 0 30px 60px rgba(0,0,0,0.5);
}

.card-header-3d {
    background: rgba(255, 255, 255, 0.25);
    color: #fff;
    padding: 22px 30px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.3);
    font-weight: bold;
    font-size: 1.3rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 20px;
}

.card-header-title {
    display: flex;
    align-items: center;
    gap: 12px;
}

.card-header-3d i {
    font-size: 1.5rem;
    filter: drop-shadow(3px 3px 6px rgba(0,0,0,0.4));
    animation: pulse 2.5s ease-in-out infinite;
}

.card-body-3d {
    padding: 35px;
    background: rgba(255, 255, 255, 0.08);
}

/* Filter Form */
.filter-form-3d {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(12px);
    border-radius: 18px;
    padding: 22px;
    border: 1px solid rgba(255, 255, 255, 0.25);
    margin-bottom: 25px;
    box-shadow: inset 0 0 10px rgba(0,0,0,0.1);
    color: #fff;
}

.filter-row {
    display: flex;
    align-items: center;
    gap: 18px;
    flex-wrap: wrap;
}

.form-control-3d {
    background: rgba(255, 255, 255, 0.25);
    border: 1px solid rgba(255, 255, 255, 0.4);
    border-radius: 12px;
    padding: 12px 18px;
    color: #fff;
    backdrop-filter: blur(12px);
    min-width: 200px;
    font-size: 1rem;
}

.form-control-3d::placeholder {
    color: #eee;
}

.form-control-3d:focus {
    background: rgba(255, 255, 255, 0.35);
    border-color: rgba(255, 255, 255, 0.6);
    box-shadow: 0 0 25px rgba(255, 255, 255, 0.4), inset 0 0 8px rgba(0,0,0,0.2);
    transform: translateY(-3px);
}

.form-control-3d option {
    background: #5b3e82;
    color: #fff;
}

/* Buttons */
.btn-3d {
    background: rgba(255, 255, 255, 0.25);
    color: #fff;
    border: 1px solid rgba(255, 255, 255, 0.4);
    padding: 12px 25px;
    border-radius: 30px;
    font-weight: 600;
    font-size: 1rem;
    letter-spacing: 0.5px;
    position: relative;
    overflow: hidden;
    cursor: pointer;
    text-decoration: none;
}

.btn-3d::before {
    content: '';
    position: absolute;
    top: 0;
    left: -120%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    transition: left 0.6s cubic-bezier(0.23, 1, 0.32, 1);
}

.btn-3d:hover::before {
    left: 120%;
}

.btn-3d:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.4);
}

.btn-primary-3d {
    background: linear-gradient(135deg, #4CAF50 0%, #388E3C 100%);
}

.btn-danger-3d {
    background: linear-gradient(135deg, #f44336 0%, #C62828 100%);
}

.btn-edit-3d {
    background: linear-gradient(135deg, #2196F3 0%, #1565C0 100%);
}

/* Table */
.table-responsive-3d {
    border-radius: 12px;
    overflow-x: auto;
    box-shadow: 0 8px 25px rgba(0,0,0,0.3);
    margin: 20px 0;
}

.table-3d {
    width: 100%;
    border-collapse: collapse;
    background: rgba(255, 255, 255, 0.12);
    backdrop-filter: blur(12px);
    border-radius: 12px;
    border: 1px solid rgba(255, 255, 255, 0.25);
    font-size: 0.85rem;
    color: #fff;
}

.table-3d thead th {
    background: rgba(255, 255, 255, 0.25);
    color: #fff;
    padding: 8px 6px;
    font-size: 0.85rem;
}

.table-3d tbody td {
    background: rgba(255, 255, 255, 0.05);
    color: #fff;
    border: 1px solid rgba(255, 255, 255, 0.12);
    padding: 6px 5px;
    font-size: 0.8rem;
    max-width: 150px;
    word-wrap: break-word;
}

.table-3d tbody tr:hover td {
    background: rgba(255, 255, 255, 0.12);
    box-shadow: inset 0 0 8px rgba(0,0,0,0.1);
}

.table-3d tfoot th {
    background: rgba(255, 255, 255, 0.2);
    color: #fff;
    padding: 6px;
    font-size: 0.8rem;
}

/* Animations */
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-12px); }
}

@keyframes pulse {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.15); opacity: 0.8; }
}

@keyframes slideInDown {
    from { transform: translateY(-50px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}


</style>
<div id="layoutSidenav_content">
<main>
<div class="container-fluid">
    <h1 class="page-title mt-4">📊 Data Rekap</h1>

    <div class="breadcrumb-3d">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">📊 Data Rekap</li>
        </ol>
    </div>

    <?php
    if (isset($_GET['msg'])) {
        if ($_GET['msg'] == 1) {
            echo '<div class="alert alert-success">✅ Berhasil Menghapus Data Rekap!</div>';
        } elseif ($_GET['msg'] == 2) {
            echo '<div class="alert alert-danger">❌ Gagal Menghapus Data Rekap!</div>';
        }
    }
    ?>

    <div class="card-3d mb-4">
        <div class="card-header-3d">
            <div class="card-header-title">
                <i class="fas fa-chart-bar"></i> Data Rekap Kehadiran
            </div>

            <div class="filter-form-3d">
                <form action="" method="get">
                    <div class="filter-row">
                        <select class="form-control-3d" name="prodi">
                            <option value="">-- Semua Prodi --</option>
                            <?php
                            $prodi_query = mysqli_query($koneksi, "SELECT DISTINCT id_prodi, nama_prodi FROM program_studi ORDER BY nama_prodi");
                            while ($prodi = mysqli_fetch_assoc($prodi_query)): ?>
                            <option value="<?= htmlspecialchars($prodi['id_prodi']) ?>" <?= $filter_prodi == $prodi['id_prodi'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($prodi['nama_prodi']) ?>
                            </option>
                            <?php endwhile; ?>
                        </select>

                        <select class="form-control-3d" name="matkul">
                            <option value="">-- Semua Mata Kuliah --</option>
                            <?php
                            $matkul_query = mysqli_query($koneksi, "SELECT DISTINCT id_matkul, nama_matkul FROM mata_kuliah ORDER BY nama_matkul");
                            while ($matkul = mysqli_fetch_assoc($matkul_query)): ?>
                            <option value="<?= htmlspecialchars($matkul['id_matkul']) ?>" <?= $filter_matkul == $matkul['id_matkul'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($matkul['nama_matkul']) ?>
                            </option>
                            <?php endwhile; ?>
                        </select>

                        <select class="form-control-3d" name="status">
                            <option value="">-- Semua Status --</option>
                            <option value="Hadir" <?= $filter_status == 'Hadir' ? 'selected' : '' ?>>Hadir</option>
                            <option value="Tidak Hadir" <?= $filter_status == 'Tidak Hadir' ? 'selected' : '' ?>>Tidak Hadir</option>
                            <option value="Terlambat" <?= $filter_status == 'Terlambat' ? 'selected' : '' ?>>Terlambat</option>
                        </select>

                        <button type="submit" class="btn-3d btn-primary-3d">🔍 Filter</button>
                        <a href="?" class="btn-3d btn-danger-3d">🔄 Reset</a>
                        <a href="export_excel.php?prodi=<?= urlencode($filter_prodi) ?>&matkul=<?= urlencode($filter_matkul) ?>&status=<?= urlencode($filter_status) ?>" 
                            class="btn-3d btn-edit-3d"> 📥 Export ke Excel
                        </a>

                    </div>
                </form>
            </div>
        </div>

        <div class="table-container">
            <div class="stats">
                📊 Menampilkan <?= $total_records ?> data absensi
            </div>

            <?php if ($total_records > 0): ?>
            <table class="table-3d">
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
                        <td><?= htmlspecialchars($data['nim']) ?></td>
                        <td><?= htmlspecialchars($data['nama_mhs']) ?></td>
                        <td><?= htmlspecialchars($data['nama_prodi']) ?></td>
                        <td><?= htmlspecialchars($data['nama_matkul']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($data['waktu'])) ?></td>
                        <td>
                            <span class="status-badge status-<?= strtolower(str_replace(' ', '-', $data['status'])) ?>">
                                <?= htmlspecialchars($data['status']) ?>
                            </span>
                        </td>
                        <td>
                            <?php if (!empty($data['foto_bukti']) && file_exists('uploads/' . $data['foto_bukti'])): ?>
                                <img class="photo-3d" src="uploads/<?= htmlspecialchars($data['foto_bukti']) ?>" alt="Foto" width="50" height="50">
                            <?php else: ?>
                                <span style="font-style: italic;">Tidak ada foto</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <button class="btn-3d btn-danger-3d" onclick="confirmDelete('<?= htmlspecialchars($data['nim']) ?>', '<?= htmlspecialchars($data['waktu']) ?>', '<?= htmlspecialchars($data['nama_mhs']) ?>')">
                                🗑️ Hapus
                            </button>
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
    </div>

    <!-- Hidden form delete -->
    <form id="deleteForm" method="POST" action="hapus_rekap.php" style="display: none;">
        <input type="hidden" name="nim" id="deleteNim">
        <input type="hidden" name="waktu" id="deleteWaktu">
    </form>

<script>
function confirmDelete(nim, waktu, nama) {
    if (confirm(`Apakah Anda yakin ingin menghapus data ${nama}?`)) {
        document.getElementById('deleteNim').value = nim;
        document.getElementById('deleteWaktu').value = waktu;
        document.getElementById('deleteForm').submit();
    }
}
</script>

</div>
</main>
</div>
