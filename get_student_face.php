<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

include 'configg.php';

$response = [
    'success' => false,
    'message' => '',
    'face_data' => null
];

if (isset($_GET['nim']) && !empty($_GET['nim'])) {
    $nim = mysqli_real_escape_string($koneksi, $_GET['nim']);
    
    // Cari mahasiswa berdasarkan NIM
    $query = mysqli_query($koneksi, "
        SELECT m.nim, m.nama, m.rfid_uid, p.nama_prodi
        FROM mahasiswa m
        JOIN program_studi p ON m.id_prodi = p.id_prodi
        WHERE m.nim = '$nim'
    ");
    
    if ($query && mysqli_num_rows($query) > 0) {
        $mahasiswa = mysqli_fetch_assoc($query);
        
        // Cek apakah file foto wajah ada
        $face_file = __DIR__ . '/known_faces/' . $nim . '.jpg';
        
        if (file_exists($face_file)) {
            $response['success'] = true;
            $response['message'] = 'Face data found';
            $response['face_data'] = [
                'nim' => $mahasiswa['nim'],
                'nama' => $mahasiswa['nama'],
                'rfid_uid' => $mahasiswa['rfid_uid'],
                'prodi' => $mahasiswa['nama_prodi'],
                'face_file' => $nim . '.jpg'
            ];
        } else {
            $response['message'] = 'Face image not found for this student';
        }
    } else {
        $response['message'] = 'Student not found';
    }
} else {
    $response['message'] = 'NIM parameter is required';
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
