<?php
header('Content-Type: application/json');

// Ganti config.php sesuai nama file koneksi kamu
require_once __DIR__ . '/configg.php';

if (!$koneksi) {
    echo json_encode([
        'success' => false,
        'message' => 'Koneksi database gagal'
    ]);
    exit;
}

$query = "SELECT id_matkul, nama_matkul FROM mata_kuliah WHERE status = 1 ORDER BY id_matkul";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    echo json_encode([
        'success' => false,
        'message' => 'Query gagal: ' . mysqli_error($koneksi)
    ]);
    exit;
}

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = [
        'id_matkul' => $row['id_matkul'],
        'nama_matkul' => $row['nama_matkul']
    ];
}

echo json_encode([
    'success' => true,
    'data' => $data
]);

mysqli_free_result($result);
mysqli_close($koneksi);
exit;
?>
