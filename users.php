<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'configg.php';
$file_uid = __DIR__ . '/uid_terakhir.txt';
$uid_terakhir = trim(file_get_contents($file_uid));

// Ambil data prodi & matkul untuk dropdown
$prodi_result = mysqli_query($koneksi, "SELECT * FROM program_studi ORDER BY nama_prodi ASC");
$matkul_result = mysqli_query($koneksi, "SELECT * FROM mata_kuliah ORDER BY nama_matkul ASC");

// jika disubmit
if (
    !empty($_POST['nim']) &&
    !empty($_POST['nama']) &&
    !empty($_POST['id_prodi']) &&
    !empty($_POST['id_matkul']) &&
    !empty($uid_terakhir)
) {
    $nim  = mysqli_real_escape_string($koneksi, trim($_POST['nim']));
    $nama = mysqli_real_escape_string($koneksi, trim($_POST['nama']));
    $id_prodi = (int) $_POST['id_prodi'];
    $id_matkul = (int) $_POST['id_matkul'];
    $uid  = mysqli_real_escape_string($koneksi, $uid_terakhir);
    
    // Cek apakah NIM sudah terdaftar
    $check_nim = mysqli_query($koneksi, "SELECT nim FROM mahasiswa WHERE nim = '$nim'");
    if (mysqli_num_rows($check_nim) > 0) {
        $error_message = "NIM $nim sudah terdaftar dalam sistem!";
    } else {
        // Cek apakah UID sudah terdaftar
        $check_uid = mysqli_query($koneksi, "SELECT rfid_uid FROM mahasiswa WHERE rfid_uid = '$uid'");
        if (mysqli_num_rows($check_uid) > 0) {
            $error_message = "UID $uid sudah didaftarkan untuk mahasiswa lain!";
        } else {
            $sql = "INSERT INTO mahasiswa (nim, nama, rfid_uid, id_prodi, id_matkul)
                    VALUES ('$nim', '$nama', '$uid', $id_prodi, $id_matkul)";
            if (mysqli_query($koneksi, $sql)) {
                file_put_contents($file_uid, ""); // kosongkan uid terakhir
                $success_message = "Mahasiswa berhasil didaftarkan!";
                // Refresh untuk menghindari resubmit
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
            } else {
                $error_message = "Error: " . mysqli_error($koneksi);
            }
        }
    }
}

// jika ?json
if (isset($_GET['json'])) {
    $result = mysqli_query($koneksi,
        "SELECT m.*, p.nama_prodi, mk.nama_matkul
         FROM mahasiswa m
         LEFT JOIN program_studi p ON m.id_prodi = p.id_prodi
         LEFT JOIN mata_kuliah mk ON m.id_matkul = mk.id_matkul");
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($rows);
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Mahasiswa ke Database - 3D</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            perspective: 1000px;
            overflow-x: hidden;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            transform-style: preserve-3d;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            transform: translateZ(50px);
        }

        .header h1 {
            color: white;
            font-size: 2.5em;
            text-shadow: 2px 2px 10px rgba(0,0,0,0.3);
            margin-bottom: 10px;
            animation: float 3s ease-in-out infinite;
        }

        .uid-display {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 30px;
            transform: translateZ(30px) rotateX(5deg);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            transition: transform 0.3s ease;
        }

        .uid-display:hover {
            transform: translateZ(40px) rotateX(0deg);
        }

        .uid-display h2 {
            color: white;
            font-size: 1.2em;
            text-align: center;
        }

        .form-container {
            background: rgba(255,255,255,0.95);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
            transform: translateZ(20px) rotateY(-2deg);
            transition: all 0.3s ease;
            margin-bottom: 30px;
        }

        .form-container:hover {
            transform: translateZ(30px) rotateY(0deg);
            box-shadow: 0 30px 70px rgba(0,0,0,0.4);
        }

        .form-group {
            margin-bottom: 20px;
            transform: translateZ(10px);
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
            font-size: 1.1em;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 10px;
            font-size: 16px;
            background: white;
            transition: all 0.3s ease;
            box-shadow: inset 0 2px 5px rgba(0,0,0,0.1);
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 15px rgba(102, 126, 234, 0.3);
            transform: translateZ(5px);
        }

        .submit-btn {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 1.1em;
            border-radius: 10px;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
            transform: translateZ(10px);
        }

        .submit-btn:hover {
            transform: translateZ(20px) scale(1.05);
            box-shadow: 0 15px 30px rgba(0,0,0,0.3);
        }

        .table-container {
            background: rgba(255,255,255,0.95);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
            transform: translateZ(15px) rotateY(2deg);
            transition: all 0.3s ease;
            overflow-x: auto;
        }

        .table-container:hover {
            transform: translateZ(25px) rotateY(0deg);
        }

        .table-container h2 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            font-weight: bold;
        }

        tr:hover {
            background: rgba(102, 126, 234, 0.1);
            transform: translateZ(5px);
        }

        .no-rfid {
            text-align: center;
            color: #666;
            font-size: 1.1em;
            padding: 20px;
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
            backdrop-filter: blur(5px);
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 10px;
            font-weight: bold;
            text-align: center;
            transform: translateZ(20px);
            animation: shake 0.5s ease-in-out;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .json-link {
            display: inline-block;
            padding: 10px 20px;
            background: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            transition: all 0.3s ease;
            transform: translateZ(10px);
        }

        .json-link:hover {
            background: #218838;
            transform: translateZ(15px) scale(1.05);
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) translateZ(50px); }
            50% { transform: translateY(-20px) translateZ(50px); }
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .floating-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .shape {
            position: absolute;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            animation: floatShape 10s infinite linear;
        }

        .shape:nth-child(1) { width: 50px; height: 50px; left: 10%; animation-delay: 0s; }
        .shape:nth-child(2) { width: 30px; height: 30px; left: 20%; animation-delay: 2s; }
        .shape:nth-child(3) { width: 40px; height: 40px; left: 30%; animation-delay: 4s; }
        .shape:nth-child(4) { width: 25px; height: 25px; left: 40%; animation-delay: 6s; }
        .shape:nth-child(5) { width: 35px; height: 35px; left: 50%; animation-delay: 8s; }

        @keyframes floatShape {
            0% { transform: translateY(100vh) rotate(0deg); }
            100% { transform: translateY(-100px) rotate(360deg); }
        }

        @media (max-width: 768px) {
            .form-container, .table-container {
                transform: translateZ(10px);
                margin: 10px;
            }
            
            .header h1 {
                font-size: 2em;
            }
            
            table {
                font-size: 14px;
            }
            
            th, td {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="container">
        <div class="header">
            <h1>📚 Sistem Pendaftaran Mahasiswa </h1>
        </div>

        <div class="uid-display">
            <h2>🔖 UID Terakhir: <?= htmlspecialchars($uid_terakhir ?: 'Belum ada') ?></h2>
        </div>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-error">
                ❌ <?= htmlspecialchars($error_message) ?>
            </div>
        <?php endif; ?>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success">
                ✅ <?= htmlspecialchars($success_message) ?>
            </div>
        <?php endif; ?>

        <?php if ($uid_terakhir): ?>
        <div class="form-container">
            <form method="post" id="studentForm">
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

                <button type="submit" class="submit-btn">💾 Simpan Data</button>
            </form>
        </div>
        <?php else: ?>
        <div class="no-rfid">
            <p>⚠ Silakan tap kartu RFID terlebih dahulu.</p>
        </div>
        <?php endif; ?>

        <div class="table-container">
            <h2>📋 Daftar Mahasiswa Terdaftar</h2>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>UID</th>
                        <th>Program Studi</th>
                        <th>Mata Kuliah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $result = mysqli_query($koneksi,
                        "SELECT m.*, p.nama_prodi, mk.nama_matkul
                         FROM mahasiswa m
                         LEFT JOIN program_studi p ON m.id_prodi = p.id_prodi
                         LEFT JOIN mata_kuliah mk ON m.id_matkul = mk.id_matkul
                         ORDER BY m.nama ASC");
                    while ($row = mysqli_fetch_assoc($result)):
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nim']) ?></td>
                        <td><?= htmlspecialchars($row['nama']) ?></td>
                        <td><?= htmlspecialchars($row['rfid_uid']) ?></td>
                        <td><?= htmlspecialchars($row['nama_prodi'] ?: '-') ?></td>
                        <td><?= htmlspecialchars($row['nama_matkul'] ?: '-') ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <a href="?json" class="json-link" target="_blank">📄 Lihat Data JSON</a>
        </div>
    </div>

    <script>
        // Real-time validation untuk NIM yang sudah terdaftar
        document.getElementById('nim').addEventListener('input', function() {
            const nim = this.value.trim();
            
            if (nim.length >= 8) {
                // Ambil data dari server untuk cek NIM
                fetch('?json')
                    .then(response => response.json())
                    .then(data => {
                        const isRegistered = data.some(student => student.nim === nim);
                        
                        if (isRegistered) {
                            showAlert('⚠ NIM ' + nim + ' sudah terdaftar dalam sistem!', 'warning');
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

        function showAlert(message, type = 'success') {
            // Hapus alert yang sudah ada
            const existingAlert = document.querySelector('.alert');
            if (existingAlert) {
                existingAlert.remove();
            }

            const alert = document.createElement('div');
            alert.className = alert alert-${type};
            alert.textContent = message;
            
            // Insert setelah uid-display
            const uidDisplay = document.querySelector('.uid-display');
            uidDisplay.insertAdjacentElement('afterend', alert);
            
            // Auto remove setelah 5 detik
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.remove();
                }
            }, 5000);
        }

   let currentUid = "<?= htmlspecialchars($uid_terakhir) ?>";

    function updateUidDisplay(newUid) {
        const uidElement = document.querySelector('.uid-display h2');
        uidElement.textContent = `🔖 UID Terakhir: ${newUid || 'Belum ada'}`;

        // Tambahkan efek animasi agar terlihat berubah
        uidElement.style.transition = 'all 0.3s ease';
        uidElement.style.backgroundColor = '#d4edda'; // hijau terang
        setTimeout(() => {
            uidElement.style.backgroundColor = 'transparent';
        }, 1000);
    }

    // Cek UID setiap detik
    setInterval(() => {
        fetch('uid_cek.php')
            .then(response => response.json())
            .then(data => {
                if (data.uid && data.uid !== currentUid) {
                    currentUid = data.uid;
                    updateUidDisplay(currentUid);
                    // Bersihkan form input agar siap input ulang
                    document.getElementById('studentForm').reset();
                }
            })
            .catch(error => {
                console.error('Gagal cek UID:', error);
            });
    }, 1000);
    </script>
</body>
</html>