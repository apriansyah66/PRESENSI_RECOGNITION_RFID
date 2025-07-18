<?php
include __DIR__ .'/configg.php';
require_once("./header.php");


// Periksa apakah koneksi berhasil
if (!$koneksi) {
    die("Koneksi database gagal!");
}

// Pastikan kolom status ada (gunakan try-catch untuk menghindari error jika sudah ada)
$check_column = mysqli_query($koneksi, "SHOW COLUMNS FROM mata_kuliah LIKE 'status'");
if (mysqli_num_rows($check_column) == 0) {
    mysqli_query($koneksi, "ALTER TABLE mata_kuliah ADD COLUMN status TINYINT(1) DEFAULT 1");
}

// Tangani aktif/nonaktif
if (isset($_GET['id']) && isset($_GET['action'])) {
    $id = intval($_GET['id']);
    $action = ($_GET['action'] === 'aktif') ? 1 : 0;
    
    // Gunakan prepared statement untuk keamanan
    $stmt = mysqli_prepare($koneksi, "UPDATE mata_kuliah SET status = ? WHERE id_matkul = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $action, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        
        header("Location: matkul.php");
        exit;
    } else {
        die("Error preparing statement: " . mysqli_error($koneksi));
    }
}

// Ambil data mata kuliah
$result = mysqli_query($koneksi, "SELECT * FROM mata_kuliah ORDER BY id_matkul");

// Periksa apakah query berhasil
if (!$result) {
    die("Error mengambil data: " . mysqli_error($koneksi));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Mata Kuliah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .status-badge {
            font-size: 0.8em;
        }
        .table-container {
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
    </style>
    <div id="layoutSidenav_content">
            </div>
</head>
<body>
<div class="container mt-5">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">📚 Manajemen Mata Kuliah</h2>
            
            <!-- Info koneksi database -->
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>✅ Koneksi Database Berhasil!</strong> 
                Total <?= mysqli_num_rows($result) ?> mata kuliah ditemukan.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            
            <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="table-container">
                <table class="table table-bordered table-striped table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th width="10%">ID</th>
                            <th width="40%">Nama Mata Kuliah</th>
                            <th width="15%">ID Prodi</th>
                            <th width="15%">Status</th>
                            <th width="20%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id_matkul']) ?></td>
                            <td><?= htmlspecialchars($row['nama_matkul']) ?></td>
                            <td><?= htmlspecialchars($row['id_prodi']) ?></td>
                            <td>
                                <?php 
                                $status = isset($row['status']) ? $row['status'] : 1; // Default aktif jika kolom tidak ada
                                ?>
                                <?php if ($status == 1): ?>
                                    <span class="badge bg-success status-badge">✅ Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-danger status-badge">❌ Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($status == 1): ?>
                                    <a href="?action=nonaktif&id=<?= $row['id_matkul'] ?>" 
                                       class="btn btn-sm btn-outline-danger"
                                       onclick="return confirm('⚠ Yakin ingin menonaktifkan mata kuliah: <?= htmlspecialchars($row['nama_matkul']) ?>?')">
                                       🔒 Nonaktifkan
                                    </a>
                                <?php else: ?>
                                    <a href="?action=aktif&id=<?= $row['id_matkul'] ?>" 
                                       class="btn btn-sm btn-outline-success"
                                       onclick="return confirm('✅ Yakin ingin mengaktifkan mata kuliah: <?= htmlspecialchars($row['nama_matkul']) ?>?')">
                                       🔓 Aktifkan
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="alert alert-info">
                <h5>📋 Tidak ada data mata kuliah</h5>
                <p>Belum ada mata kuliah yang terdaftar dalam sistem.</p>
                <hr>
                <p class="mb-0">
                    <small>Pastikan tabel 'mata_kuliah' sudah dibuat dan berisi data.</small>
                </p>
            </div>
            <?php endif; ?>
            
            <!-- Tambah tombol untuk kembali atau tambah data -->
            <div class="mt-4">
                <a href="index.php" class="btn btn-secondary">
                    ← Kembali ke Dashboard
                </a>
                <a href="tambah_matkul.php" class="btn btn-primary">
                    ➕ Tambah Mata Kuliah
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
if ($koneksi) {
    mysqli_close($koneksi);
}
?>