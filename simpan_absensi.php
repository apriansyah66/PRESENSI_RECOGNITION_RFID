<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json");

include '../RFID/configg.php';
date_default_timezone_set('Asia/Jakarta');

// Ambil data POST
$rfid_uid   = $_POST['rfid_uid'] ?? null;
$foto_bukti = $_POST['foto_bukti'] ?? null;

$response = [
    'success' => false,
    'message' => '',
    'absen_berhasil' => [],
    'absen_gagal' => [],
];

if (!$rfid_uid || !$foto_bukti) {
    $response['message'] = "Data tidak lengkap.";
    echo json_encode($response);
    exit();
}

$rfid_uid = mysqli_real_escape_string($koneksi, $rfid_uid);

// Ambil NIM mahasiswa
$q = mysqli_query($koneksi, "SELECT nim FROM mahasiswa WHERE rfid_uid = '$rfid_uid'");
$data = mysqli_fetch_assoc($q);

if (!$data) {
    $response['message'] = "RFID tidak terdaftar.";
    echo json_encode($response);
    exit();
}

$nim = $data['nim'];

// Ambil semua mata kuliah aktif
$matkul_q = mysqli_query($koneksi, "SELECT id_matkul FROM mata_kuliah WHERE status = 1");
if (mysqli_num_rows($matkul_q) === 0) {
    $response['message'] = "Tidak ada mata kuliah aktif.";
    echo json_encode($response);
    exit();
}

while ($row = mysqli_fetch_assoc($matkul_q)) {
    $id_matkul = $row['id_matkul'];

    $cek = mysqli_query($koneksi, "
        SELECT * FROM absensi 
        WHERE nim = '$nim' 
        AND id_matkul = '$id_matkul' 
        AND DATE(waktu) = CURDATE()
    ");

    if (mysqli_num_rows($cek) > 0) {
        $response['absen_gagal'][] = "Matkul ID $id_matkul: sudah absen.";
        continue;
    }

    // Simpan data absensi
    $stmt = mysqli_prepare($koneksi, "INSERT INTO absensi (nim, id_matkul, foto_bukti, status, waktu) VALUES (?, ?, ?, 'Hadir', NOW())");
    mysqli_stmt_bind_param($stmt, "sss", $nim, $id_matkul, $foto_bukti);
    if (mysqli_stmt_execute($stmt)) {
        $response['absen_berhasil'][] = "Matkul ID $id_matkul: absen berhasil.";
    } else {
        $response['absen_gagal'][] = "Matkul ID $id_matkul: gagal simpan.";
    }
}

$response['success'] = true;
$response['message'] = "Proses absensi selesai.";
echo json_encode($response);
