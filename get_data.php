<?php
include 'configg.php';
date_default_timezone_set('Asia/Jakarta');

$response = [
    'status' => 'unknown',
    'rfid_uid' => '',
    'nim' => '',
    'nama' => '',
    'prodi' => '',
    'waktu' => date('d-m-Y H:i:s'),
    'absensi' => [],
    'foto_wajah' => null
];

// Ambil RFID terakhir
$sql_uid = "SELECT rfid_uid FROM rfid_terakhir ORDER BY waktu DESC LIMIT 1";
$result_uid = mysqli_query($koneksi, $sql_uid);

if ($result_uid && mysqli_num_rows($result_uid) > 0) {
    $row = mysqli_fetch_assoc($result_uid);
    $rfid_uid = $row['rfid_uid'];
    $response['rfid_uid'] = $rfid_uid;

    // Cek apakah mahasiswa terdaftar
    $sql_mhs = "SELECT m.nim, m.nama, p.nama_prodi, m.foto_wajah
                FROM mahasiswa m
                JOIN program_studi p ON m.id_prodi = p.id_prodi
                WHERE m.rfid_uid = '$rfid_uid'";
    $result_mhs = mysqli_query($koneksi, $sql_mhs);

    if ($result_mhs && mysqli_num_rows($result_mhs) > 0) {
        $data = mysqli_fetch_assoc($result_mhs);
        $response['status'] = 'success';
        $response['nim'] = $data['nim'];
        $response['nama'] = $data['nama'];
        $response['prodi'] = $data['nama_prodi'];

        // Tambahkan foto wajah jika ada
        $foto_path = $data['foto_wajah'];
        if ($foto_path && file_exists($foto_path)) {
            // Pastikan path yang dikirim ke browser bisa diakses secara relatif
            $response['foto_wajah'] = $foto_path;
        } else {
            $response['foto_wajah'] = null;
        }

        // Ambil absensi hari ini
        $abs_q = "SELECT mk.nama_matkul, a.status 
                  FROM absensi a
                  JOIN mata_kuliah mk ON a.id_matkul = mk.id_matkul
                  WHERE a.nim = '{$data['nim']}' AND DATE(a.waktu) = CURDATE()";
        $abs_r = mysqli_query($koneksi, $abs_q);
        while ($row = mysqli_fetch_assoc($abs_r)) {
            $response['absensi'][] = "✅ " . $row['nama_matkul'] . ": " . $row['status'];
        }

        if (empty($response['absensi'])) {
            $response['absensi'][] = "ℹ️ Belum ada absensi hari ini.";
        }
    } else {
        $response['status'] = 'unknown';
    }
}

header('Content-Type: application/json');
echo json_encode($response);
