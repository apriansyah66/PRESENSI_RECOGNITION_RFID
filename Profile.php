<?php
 include('sidebarM.HTML'); 
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "absensi_mahasiswa");

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil RFID terakhir
$result = $conn->query("SELECT rfid_uid FROM rfid_terakhir ORDER BY id DESC LIMIT 1");

if ($result && $row = $result->fetch_assoc()) {
    $rfid_uid = $row['rfid_uid'];

    // Ambil data mahasiswa berdasarkan RFID
    $sql = "
        SELECT m.nim, m.nama, m.rfid_uid, m.foto_wajah, p.nama_prodi, mk.nama_matkul 
        FROM mahasiswa m
        LEFT JOIN program_studi p ON m.id_prodi = p.id_prodi
        LEFT JOIN mata_kuliah mk ON m.id_matkul = mk.id_matkul
        WHERE m.rfid_uid = '$rfid_uid'
    ";
    $mahasiswa = $conn->query($sql)->fetch_assoc();
} else {
    $mahasiswa = null;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Mahasiswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #667eea, #764ba2);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .profile-card {
            background: rgba(255, 255, 255, 0.1);
            padding: 30px;
            border-radius: 15px;
            backdrop-filter: blur(10px);
            width: 400px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }

        .profile-card h2 {
            margin-bottom: 20px;
            border-bottom: 1px solid #fff;
            padding-bottom: 10px;
        }

        .profile-item {
            margin-bottom: 10px;
        }

        .profile-item strong {
            display: inline-block;
            width: 120px;
        }

        .foto-wajah {
            display: block;
            margin: 0 auto 20px;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #fff;
        }
    </style>
</head>
<body>

<div class="profile-card">
    <h2>👤 Profil Mahasiswa</h2>
    <?php if ($mahasiswa): ?>
        <?php if (!empty($mahasiswa['foto_wajah'])): ?>
            <img src="uploads/<?= htmlspecialchars($mahasiswa['foto_wajah']) ?>" alt="Foto Wajah" class="foto-wajah">
        <?php endif; ?>
        <div class="profile-item"><strong>Nama:</strong> <?= htmlspecialchars($mahasiswa['nama']) ?></div>
        <div class="profile-item"><strong>NIM:</strong> <?= htmlspecialchars($mahasiswa['nim']) ?></div>
        <div class="profile-item"><strong>Prodi:</strong> <?= htmlspecialchars($mahasiswa['nama_prodi']) ?></div>
        <div class="profile-item"><strong>RFID UID:</strong> <?= htmlspecialchars($mahasiswa['rfid_uid']) ?></div>
        <div class="profile-item"><strong>Mata Kuliah:</strong> <?= htmlspecialchars($mahasiswa['nama_matkul']) ?></div>
    <?php else: ?>
        <p>❌ Data tidak ditemukan. Pastikan kartu sudah ditempel.</p>
    <?php endif; ?>
</div>

</body>
</html>
