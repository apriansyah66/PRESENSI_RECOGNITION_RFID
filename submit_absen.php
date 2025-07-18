<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

include 'configg.php';
date_default_timezone_set('Asia/Jakarta');

$input = json_decode(file_get_contents('php://input'), true);
$response = [
    'success' => false,
    'message' => ''
];

if (!isset($input['nama']) || empty($input['nama'])) {
    $response['message'] = 'Nama tidak diberikan';
    echo json_encode($response);
    exit;
}

$nama = mysqli_real_escape_string($koneksi, $input['nama']);

// cari mahasiswa
$mhs_query = mysqli_query($koneksi, "
    SELECT nim FROM mahasiswa WHERE nama = '$nama' LIMIT 1
");

if ($mhs_query && mysqli_num_rows($mhs_query) > 0) {
    $mhs = mysqli_fetch_assoc($mhs_query);

    $nim = $mhs['nim'];

    $status_messages = [];
    $matkul_q = mysqli_query($koneksi, "SELECT * FROM mata_kuliah WHERE status = 1");

    if ($matkul_q && mysqli_num_rows($matkul_q) > 0) {
        while ($mk = mysqli_fetch_assoc($matkul_q)) {
            $id_matkul = $mk['id_matkul'];
            $nama_matkul = $mk['nama_matkul'];

            $cek = mysqli_query($koneksi, "
                SELECT * FROM absensi 
                WHERE nim = '$nim' 
                AND id_matkul = '$id_matkul' 
                AND DATE(waktu) = CURDATE()
            ");

            if ($cek && mysqli_num_rows($cek) == 0) {
                $insert = mysqli_query($koneksi, "
                    INSERT INTO absensi (nim, id_matkul, status, waktu) 
                    VALUES ('$nim', '$id_matkul', 'Hadir', NOW())
                ");

                $status_messages[] = $insert
                    ? "✅ $nama_matkul: Berhasil absen"
                    : "❌ $nama_matkul: Gagal menyimpan";
            } else {
                $status_messages[] = "ℹ $nama_matkul: Sudah absen hari ini";
            }
        }

        $response['success'] = true;
        $response['message'] = implode(' | ', $status_messages);
    } else {
        $response['message'] = "⚠ Tidak ada mata kuliah aktif";
    }
} else {
    $response['message'] = "❌ Mahasiswa dengan nama '$nama' tidak ditemukan.";
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
