<?php
$page = "Data Mahasiswa";
require_once("./header.php");
include 'configg.php';

$file_uid = __DIR__ . '/uid_terakhir.txt';
$uid_terakhir = trim(file_get_contents($file_uid));

$prodi_result = mysqli_query($koneksi, "SELECT * FROM program_studi ORDER BY nama_prodi ASC");
$matkul_result = mysqli_query($koneksi, "SELECT * FROM mata_kuliah ORDER BY nama_matkul ASC");

if (
    !empty($_POST['nim']) &&
    !empty($_POST['nama']) &&
    !empty($_POST['id_prodi']) &&
    !empty($_POST['id_matkul']) &&
    !empty($_POST['password']) &&
    !empty($uid_terakhir)
) {
    $nim  = mysqli_real_escape_string($koneksi, trim($_POST['nim']));
    $nama = mysqli_real_escape_string($koneksi, trim($_POST['nama']));
    $id_prodi = (int) $_POST['id_prodi'];
    $id_matkul = (int) $_POST['id_matkul'];
    $password = mysqli_real_escape_string($koneksi, trim($_POST['password']));
    $uid  = mysqli_real_escape_string($koneksi, $uid_terakhir);

    $check_nim = mysqli_query($koneksi, "SELECT nim FROM mahasiswa WHERE nim='$nim'");
    if (mysqli_num_rows($check_nim) > 0) {
        $error_message = "NIM $nim sudah terdaftar!";
    } else {
        $check_uid = mysqli_query($koneksi, "SELECT rfid_uid FROM mahasiswa WHERE rfid_uid='$uid'");
        if (mysqli_num_rows($check_uid) > 0) {
            $error_message = "UID $uid sudah terdaftar!";
        } else {
            $foto_wajah = null;

            if (
                isset($_FILES['foto']) &&
                $_FILES['foto']['error'] === 0 &&
                !empty($_FILES['foto']['name'])
            ) {
                $target_dir = __DIR__ . "/known_faces/";

                $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png'];

                if (!in_array($ext, $allowed)) {
                    $error_message = "Format foto tidak valid. Gunakan JPG/PNG.";
                } elseif ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
                    $error_message = "Ukuran foto maksimal 2MB.";
                } else {
                    $nama_sanitized = preg_replace('/[^a-z0-9_]/', '', 
                        preg_replace('/\s+/', '_', strtolower($nama))
                    );
                    $nama_file = $nama_sanitized . '.' . $ext;
                    $target_file = $target_dir . $nama_file;

                    foreach (['jpg', 'jpeg', 'png'] as $e) {
                        $old_file = $target_dir . $nama_sanitized . '.' . $e;
                        if (file_exists($old_file)) {
                            unlink($old_file);
                        }
                    }

                    if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
                        $foto_wajah = "known_faces/" . $nama_file;
                    } else {
                        $error_message = "Gagal menyimpan file ke server.";
                    }
                }
            } else {
                $error_message = "Foto wajah wajib diunggah atau ada error saat upload.";
            }

            if (!isset($error_message)) {
                $sql = "INSERT INTO mahasiswa (nim, nama, rfid_uid, id_prodi, id_matkul, foto_wajah, password) 
                        VALUES ('$nim', '$nama', '$uid', $id_prodi, $id_matkul, '$foto_wajah', '$password')";
                if (mysqli_query($koneksi, $sql)) {
                    file_put_contents($file_uid, ""); 
                    $success_message = "✅ Mahasiswa berhasil didaftarkan!";
                    $show_success_popup = true;
                } else {
                    $error_message = "❌ Error database: " . mysqli_error($koneksi);
                }
            }
        }
    }
}
?>
<head>
<title>📚 Sistem Pendaftaran Mahasiswa</title>
<link rel="stylesheet" href="css/form_mahasiswa.css">
<style>
.modal {
  display: none;
  position: fixed;
  z-index: 1000;
  left: 0; top: 0;
  width: 100%; height: 100%;
  overflow: auto;
  background-color: rgba(0,0,0,0.5);
}
.modal-content {
  background-color: #fff;
  margin: 15% auto;
  padding: 20px;
  border-radius: 8px;
  width: 300px;
  text-align: center;
}
.modal-content h3 {
  margin-top: 0;
  color: #e74c3c;
}
.modal-content button {
  margin-top: 20px;
  padding: 8px 16px;
  background: #3498db;
  color: #fff;
  border: none;
  cursor: pointer;
  border-radius: 5px;
}

.success-modal .modal-content {
  background-color: #d4edda;
  border: 1px solid #c3e6cb;
}

.success-modal .modal-content h3 {
  color: #155724;
}

.success-modal .modal-content button {
  background: #28a745;
}

.no-rfid {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  padding: 40px;
  border-radius: 15px;
  text-align: center;
  margin-top: 20px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.no-rfid h2 {
  margin-bottom: 20px;
  font-size: 1.8rem;
  font-weight: 300;
}

.no-rfid p {
  font-size: 1.1rem;
  margin-bottom: 0;
  opacity: 0.9;
}
</style>
</head>

<div id="layoutSidenav_content">
<main>
<div class="container-fluid">
<div class="form-wrapper">
<h1 class="page-title mt-4">👥 Data Mahasiswa</h1>

<div class="breadcrumb-3d">
<ol class="breadcrumb">
<li class="breadcrumb-item active">Data Mahasiswa</li>
</ol>
</div>

<div class="uid-display">
<h2>🔖 UID Terakhir: <?= htmlspecialchars($uid_terakhir ?: 'Belum ada') ?></h2>
</div>

<?php if (isset($error_message)): ?>
<div class="alert alert-error">❌ <?= htmlspecialchars($error_message) ?></div>
<?php endif; ?>

<?php if (isset($success_message)): ?>
<div class="alert alert-success">✅ <?= htmlspecialchars($success_message) ?></div>
<?php endif; ?>

<?php if ($uid_terakhir): ?>
<div class="form-container">
<form method="post" enctype="multipart/form-data" id="studentForm">
    <div class="form-group">
        <label for="nim">📋 NIM:</label>
        <input type="text" id="nim" name="nim" required>
    </div>

    <div class="form-group">
        <label for="nama">👤 Nama:</label>
        <input type="text" id="nama" name="nama" required>
    </div>

    <div class="form-group">
        <label for="id_prodi">🎓 Program Studi:</label>
        <select id="id_prodi" name="id_prodi" required>
            <option value="">-- Pilih Program Studi --</option>
            <?php while ($p = mysqli_fetch_assoc($prodi_result)): ?>
                <option value="<?= $p['id_prodi'] ?>"><?= htmlspecialchars($p['nama_prodi']) ?></option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="id_matkul">📖 Mata Kuliah:</label>
        <select id="id_matkul" name="id_matkul" required>
            <option value="">-- Pilih Mata Kuliah --</option>
            <?php while ($m = mysqli_fetch_assoc($matkul_result)): ?>
                <option value="<?= $m['id_matkul'] ?>"><?= htmlspecialchars($m['nama_matkul']) ?></option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="password">🔒 Password:</label>
        <input type="password" id="password" name="password" required>
    </div>

    <div class="form-group">
        <label for="foto">📷 Foto Wajah:</label>
        <input type="file" name="foto" id="foto" accept="image/*" required>
    </div>

    <button type="submit" class="submit-btn">💾 Simpan Data</button>
</form>
</div>
<?php else: ?>
<div class="no-rfid">
<h2>🔖 UID Terakhir: Belum ada</h2>
<p>⚠ Silakan tap kartu RFID terlebih dahulu.</p>
</div>
<?php endif; ?>
</div>
</div>
</main>
</div>

<!-- Modal Error -->
<div id="errorModal" class="modal">
  <div class="modal-content">
    <h3>⚠ NIM sudah terdaftar!</h3>
    <p>Silakan gunakan NIM yang lain.</p>
    <button onclick="closeModal()">Tutup</button>
  </div>
</div>

<!-- Modal Success -->
<div id="successModal" class="modal success-modal">
  <div class="modal-content">
    <h3>✅ Berhasil!</h3>
    <p>Mahasiswa berhasil didaftarkan!</p>
    <button onclick="closeSuccessModal()">Tutup</button>
  </div>
</div>

<script>
const nimInput = document.getElementById('nim');
const errorModal = document.getElementById('errorModal');
const successModal = document.getElementById('successModal');

nimInput.addEventListener('input', function () {
    const nim = this.value.trim();
    if (nim.length >= 8) {
        fetch('?json')
            .then(response => response.json())
            .then(data => {
                const isRegistered = data.some(student => student.nim === nim);
                if (isRegistered) {
                    showModal();
                    this.style.borderColor = '#dc3545';
                } else {
                    this.style.borderColor = '#28a745';
                }
            })
            .catch(error => {
                console.error('Error checking NIM:', error);
            });
    } else {
        this.style.borderColor = '#ddd';
    }
});

function showModal() {
    errorModal.style.display = "block";
}
function closeModal() {
    errorModal.style.display = "none";
}

function showSuccessModal() {
    successModal.style.display = "block";
}
function closeSuccessModal() {
    successModal.style.display = "none";
    // Reset form dan reload halaman
    document.getElementById('studentForm').reset();
    location.reload();
}

// Show success modal if registration successful
<?php if (isset($show_success_popup) && $show_success_popup): ?>
    showSuccessModal();
<?php endif; ?>

let currentUid = "<?= htmlspecialchars($uid_terakhir) ?>";

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
                document.getElementById('studentForm').reset();
            }
        })
        .catch(error => {
            console.error('Gagal cek UID:', error);
        });
}, 1000);
</script>