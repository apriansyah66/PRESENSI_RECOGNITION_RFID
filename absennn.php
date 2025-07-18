<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

include 'configg.php';
date_default_timezone_set('Asia/Jakarta');

$response = [
    'rfid_uid' => '',
    'nama' => '',
    'nim' => '',
    'prodi' => '',
    'waktu' => date('d-m-Y H:i:s'),
    'status_pesan' => '',
    'tampilkan_card' => false,
    'rfid_tidak_dikenal' => false
];

// ambil UID RFID terbaru
$rfid_query = mysqli_query($koneksi, "SELECT * FROM rfid_terakhir ORDER BY id DESC LIMIT 1");

if ($rfid_query && mysqli_num_rows($rfid_query) > 0) {
    $rfid_data = mysqli_fetch_assoc($rfid_query);
    $rfid_uid = mysqli_real_escape_string($koneksi, $rfid_data['rfid_uid']);

    // kalau UID kosong, jangan proses
    if (empty($rfid_uid)) {
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }

    $response['rfid_uid'] = $rfid_uid;

    // cari mahasiswa
    $mhs_query = mysqli_query($koneksi, "
        SELECT m.nim, m.nama, p.nama_prodi
        FROM mahasiswa m
        JOIN program_studi p ON m.id_prodi = p.id_prodi
        WHERE m.rfid_uid = '$rfid_uid'
    ");

    if ($mhs_query && mysqli_num_rows($mhs_query) > 0) {
        $mhs = mysqli_fetch_assoc($mhs_query);
        $response['nim'] = $mhs['nim'];
        $response['nama'] = $mhs['nama'];
        $response['prodi'] = $mhs['nama_prodi'];
        $response['tampilkan_card'] = true;

        $status_messages = [];
        $matkul_q = mysqli_query($koneksi, "SELECT * FROM mata_kuliah WHERE status = 1");

        if ($matkul_q && mysqli_num_rows($matkul_q) > 0) {
            while ($mk = mysqli_fetch_assoc($matkul_q)) {
                $id_matkul = $mk['id_matkul'];
                $nama_matkul = $mk['nama_matkul'];

                $cek = mysqli_query($koneksi, "
                    SELECT * FROM absensi 
                    WHERE nim = '{$mhs['nim']}' 
                    AND id_matkul = '$id_matkul' 
                    AND DATE(waktu) = CURDATE()
                ");

                if ($cek && mysqli_num_rows($cek) == 0) {
                    $insert = mysqli_query($koneksi, "
                        INSERT INTO absensi (nim, id_matkul, status, waktu) 
                        VALUES ('{$mhs['nim']}', '$id_matkul', 'Hadir', NOW())
                    ");

                    $status_messages[] = $insert
                        ? "✅ $nama_matkul: Berhasil absen"
                        : "❌ $nama_matkul: Gagal menyimpan";
                } else {
                    $status_messages[] = "ℹ $nama_matkul: Sudah absen hari ini";
                }
            }
        } else {
            $status_messages[] = "⚠ Tidak ada mata kuliah aktif";
        }

        $response['status_pesan'] = implode(' | ', $status_messages);
    } else {
        $response['rfid_tidak_dikenal'] = true;
    }

    // setelah selesai, reset UID
    mysqli_query($koneksi, "UPDATE rfid_terakhir SET rfid_uid='' WHERE id='{$rfid_data['id']}'");

} else {
    // kalau tidak ada baris, kosong juga
    $response['rfid_uid'] = '';
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
