<?php
$page = "Data Mahasiswa";
require_once("./header.php");
include 'configg.php';
?>

<style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
    }
    
    .container-fluid {
        position: relative;
        z-index: 10;
    }
    
    .page-title {
        color: white;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        font-weight: bold;
        margin-bottom: 20px;
    }
    
    .breadcrumb-3d {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        margin-bottom: 30px;
    }
    
    .breadcrumb {
        background: transparent;
        margin: 0;
        padding: 10px 20px;
    }
    
    .breadcrumb-item {
        color: rgba(255, 255, 255, 0.8);
    }
    
    .breadcrumb-item.active {
        color: #ffd700;
        font-weight: bold;
    }
    
    /* Alert Messages */
    .alert {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 15px;
        color: white;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }
    
    .alert-success {
        background: rgba(76, 175, 80, 0.3);
        border-color: rgba(76, 175, 80, 0.5);
    }
    
    .alert-danger {
        background: rgba(244, 67, 54, 0.3);
        border-color: rgba(244, 67, 54, 0.5);
    }
    
    /* Main Card Container */
    .card-3d {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.3);
        transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        overflow: hidden;
    }
    
    .card-3d:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 50px rgba(0,0,0,0.4);
    }
    
    .card-header-3d {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        font-weight: bold;
        font-size: 1.2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .card-header-3d i {
        margin-right: 10px;
        font-size: 1.3rem;
        filter: drop-shadow(2px 2px 4px rgba(0,0,0,0.3));
    }
    
    /* Button Styles */
    .btn-3d {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
        padding: 10px 20px;
        border-radius: 25px;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        backdrop-filter: blur(10px);
        font-weight: 500;
        position: relative;
        overflow: hidden;
    }
    
    .btn-3d::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }
    
    .btn-3d:hover::before {
        left: 100%;
    }
    
    .btn-3d:hover {
        background: rgba(255, 255, 255, 0.3);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        text-decoration: none;
    }
    
    .btn-primary-3d {
        background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
        border-color: rgba(76, 175, 80, 0.5);
    }
    
    .btn-primary-3d:hover {
        background: linear-gradient(135deg, #45a049 0%, #4CAF50 100%);
        color: white;
    }
    
    .btn-danger-3d {
        background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);
        border-color: rgba(244, 67, 54, 0.5);
    }
    
    .btn-danger-3d:hover {
        background: linear-gradient(135deg, #d32f2f 0%, #f44336 100%);
        color: white;
    }
    
    .btn-edit-3d {
        background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
        border-color: rgba(33, 150, 243, 0.5);
    }
    
    .btn-edit-3d:hover {
        background: linear-gradient(135deg, #1976D2 0%, #2196F3 100%);
        color: white;
    }
    
    /* DataTable Controls */
    .datatable-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        background: rgba(255, 255, 255, 0.1);
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .datatable-left {
        display: flex;
        align-items: center;
        gap: 10px;
        color: white;
    }
    
    .datatable-right {
        display: flex;
        align-items: center;
        gap: 10px;
        color: white;
    }
    
    .entries-select, .search-input {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 8px;
        padding: 8px 12px;
        color: white;
        backdrop-filter: blur(10px);
    }
    
    .entries-select option {
        background: #444;
        color: white;
    }
    
    .search-input::placeholder {
        color: rgba(255, 255, 255, 0.7);
    }
    
    .search-input:focus, .entries-select:focus {
        outline: none;
        background: rgba(255, 255, 255, 0.3);
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
    }
    
    /* Table Styles */
    .table-responsive {
        padding: 20px;
    }
    
    .table {
        background: transparent;
        color: white;
        margin: 0;
    }
    
    .table th {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
        font-weight: bold;
        text-align: center;
        padding: 15px;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
    }
    
    .table td {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 12px;
        vertical-align: middle;
    }
    
    .table tbody tr {
        transition: all 0.3s ease;
    }
    
    .table tbody tr:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: scale(1.01);
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }
    
    /* Photo Style */
    .employee-photo {
        border-radius: 50%;
        border: 3px solid rgba(255, 255, 255, 0.5);
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        transition: all 0.3s ease;
    }
    
    .employee-photo:hover {
        transform: scale(1.1);
        border-color: #ffd700;
        box-shadow: 0 8px 25px rgba(0,0,0,0.5);
    }
    
    /* Pagination */
    .datatable-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        background: rgba(255, 255, 255, 0.1);
        border-top: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .pagination-info {
        font-size: 0.9rem;
        opacity: 0.9;
    }
    
    .pagination {
        display: flex;
        gap: 5px;
        margin: 0;
        list-style: none;
        padding: 0;
    }
    
    .pagination .page-item {
        display: inline-block;
    }
    
    .pagination .page-link {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
        padding: 8px 12px;
        border-radius: 8px;
        transition: all 0.3s ease;
        text-decoration: none;
        backdrop-filter: blur(10px);
        cursor: pointer;
    }
    
    .pagination .page-link:hover {
        background: rgba(255, 255, 255, 0.3);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        text-decoration: none;
    }
    
    .pagination .page-item.active .page-link {
        background: rgba(255, 255, 255, 0.4);
        color: #ffd700;
        font-weight: bold;
    }
    
    .pagination .page-item.disabled .page-link {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .empty-data {
        text-align: center;
        padding: 40px;
        color: rgba(255, 255, 255, 0.7);
        font-size: 1.1rem;
    }
    
    /* Action buttons in table */
    .table .btn-3d {
        padding: 5px 10px;
        font-size: 0.85rem;
        margin: 2px;
        border-radius: 15px;
    }
    
    /* Floating Animation */
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    
    .card-3d {
        animation: float 8s ease-in-out infinite;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .card-header-3d {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
        
        .datatable-controls, .datatable-footer {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .datatable-right {
            width: 100%;
            justify-content: flex-end;
        }
        
        .table .btn-3d {
            padding: 3px 8px;
            font-size: 0.8rem;
            margin: 1px;
        }
    }
</style>

<div id="layoutSidenav_content">
<main>
<div class="container-fluid">
<h1 class="page-title mt-4">👥 Data Mahasiswa</h1>

<div class="breadcrumb-3d">
<ol class="breadcrumb">
<li class="breadcrumb-item active">Data Mahasiswa</li>
</ol>
</div>

<?php
if (isset($_GET['msg']) && isset($_SERVER['HTTP_REFERER'])) {
    if ($_GET['msg'] == 1 && $_SERVER['HTTP_REFERER']) {
?>
<div class="alert alert-success alert-dismissible fade show text-center h4" role="alert">
    <strong>✅ Berhasil Menghapus Data Mahasiswa!</strong>
</div>
<?php
    } else if ($_GET['msg'] == 2 && $_SERVER['HTTP_REFERER']) {
?>
<div class="alert alert-danger alert-dismissible fade show text-center h4" role="alert">
    <strong>❌ Gagal Menghapus Data Mahasiswa!</strong>
</div>
<?php
    }
}
?>

<div class="card-3d mb-4">
<div class="card-header-3d">
    <div>
        <i class="fas fa-users"></i>
        Data Mahasiswa
    </div>
    <div>
        <a href="./tambah_mahasiswa.php" class="btn-3d btn-primary-3d">
            <i class="fas fa-plus mr-2"></i>Tambah Data Mahasiswa
        </a>
    </div>
</div>

<!-- Table -->
<div class="table-responsive">
    <table class="table table-bordered" id="employeeTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
                <th>NIM</th>
                <th>Nama</th>
                <th>UID</th>
                <th>Program Studi</th>
                <th>Foto Wajah</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $result = mysqli_query($koneksi,
                "SELECT m.*, p.nama_prodi
                 FROM mahasiswa m
                 LEFT JOIN program_studi p ON m.id_prodi = p.id_prodi
                 ORDER BY m.nama ASC");
            while ($row = mysqli_fetch_assoc($result)):
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['nim']) ?></td>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= htmlspecialchars($row['rfid_uid']) ?></td>
                <td><?= htmlspecialchars($row['nama_prodi'] ?: '-') ?></td>
                <td>
                    <?php if (!empty($row['foto_wajah'])): ?>
                        <img src="<?= htmlspecialchars($row['foto_wajah']) ?>" alt="Foto" width="50" class="employee-photo">
                    <?php else: ?>
                        <span>-</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</div>
</div>
</main>
</div>

<script>
// JS realtime UID display
let currentUid = "<?= htmlspecialchars(trim(file_get_contents(__DIR__.'/uid_terakhir.txt'))) ?>";

function updateUidDisplay(newUid) {
    const uidElement = document.querySelector('.uid-display h2');
    uidElement.textContent = `🔖 UID Terakhir: ${newUid || 'Belum ada'}`;
    uidElement.style.transition = 'all 0.3s ease';
    uidElement.style.backgroundColor = '#d4edda';
    setTimeout(() => uidElement.style.backgroundColor = 'transparent', 1000);
}

setInterval(() => {
    fetch('uid_cek.php')
        .then(response => response.json())
        .then(data => {
            if (data.uid && data.uid !== currentUid) {
                currentUid = data.uid;
                updateUidDisplay(currentUid);
                if (document.getElementById('studentForm')) {
                    document.getElementById('studentForm').reset();
                }
            }
        })
        .catch(error => {
            console.error('Gagal cek UID:', error);
        });
}, 1000);
</script>


