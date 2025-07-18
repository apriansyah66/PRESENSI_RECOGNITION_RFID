<?php
include 'configg.php';
date_default_timezone_set('Asia/Jakarta');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $img = $data['image'] ?? null;
    $uid = $data['rfid_uid'] ?? null;

    if ($img && $uid) {
        // Bersihkan string base64
        $img = str_replace('data:image/jpeg;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $file_data = base64_decode($img);

        // Buat nama file
        $nama_file = "foto_absensi/{$uid}_" . date('Ymd_His') . ".jpg";

        // Simpan ke file
        if (file_put_contents($nama_file, $file_data)) {
            // Simpan path file ke database
            $nama_file_db = mysqli_real_escape_string($koneksi, $nama_file);

            $uid_safe = mysqli_real_escape_string($koneksi, $uid);
            $update = mysqli_query($koneksi, 
                "UPDATE mahasiswa SET foto_wajah = '$nama_file_db' WHERE rfid_uid = '$uid_safe'"
            );

            if ($update) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Foto disimpan dan database diperbarui.',
                    'filename' => $nama_file
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Foto disimpan, tapi gagal update database.'
                ]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan file.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Metode bukan POST.']);
}
