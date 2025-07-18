<?php
// Tampilkan error (sangat membantu saat debugging)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include koneksi database
require_once 'configg.php';

// Header JSON
header('Content-Type: application/json');

// Pastikan koneksi berhasil
if (!$koneksi) {
    echo json_encode([
        'success' => false,
        'message' => 'Koneksi database gagal: ' . mysqli_connect_error()
    ]);
    exit;
}

// Query untuk mengambil semua mahasiswa + nama prodi dan matkul
$sql = "
    SELECT 
        m.id_mahasiswa,
        m.nim,
        m.nama,
        m.rfid_uid,
        m.id_prodi,
        p.nama_prodi,
        m.id_matkul,
        mk.nama_matkul
    FROM mahasiswa m
    LEFT JOIN program_studi p ON m.id_prodi = p.id_prodi
    LEFT JOIN mata_kuliah mk ON m.id_matkul = mk.id_matkul
    ORDER BY m.nama ASC
";

$result = mysqli_query($koneksi, $sql);

// Jika query gagal
if (!$result) {
    echo json_encode([
        'success' => false,
        'message' => 'Query gagal: ' . mysqli_error($koneksi)
    ]);
    exit;
}

// Ambil hasil ke array
$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

// Kirim hasil JSON
echo json_encode([
    'success' => true,
    'count' => count($data),
    'data' => $data
]);

// Tutup koneksi
mysqli_free_result($result);
mysqli_close($koneksi);
