<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

include 'configg.php';
date_default_timezone_set('Asia/Jakarta');

$response = [
    'success' => false,
    'message' => '',
    'attendance_results' => []
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (isset($input['nim']) && isset($input['rfid_uid']) && isset($input['face_verified'])) {
        $nim = mysqli_real_escape_string($koneksi, $input['nim']);
        $rfid_uid = mysqli_real_escape_string($koneksi, $input['rfid_uid']);
        $face_verified = $input['face_verified'];
        
        if (!$face_verified) {
            $response['message'] = 'Face verification failed';
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit;
        }
        
        // Verifikasi bahwa NIM dan RFID cocok
        $verify_query = mysqli_query($koneksi, "
            SELECT m.nim, m.nama, p.nama_prodi
            FROM mahasiswa m
            JOIN program_studi p ON m.id_prodi = p.id_prodi
            WHERE m.nim = '$nim' AND m.rfid_uid = '$rfid_uid'
        ");
        
        if ($verify_query && mysqli_num_rows($verify_query) > 0) {
            $mahasiswa = mysqli_fetch_assoc($verify_query);
            
            // Proses absensi untuk semua mata kuliah aktif
            $attendance_results = [];
            $matkul_query = mysqli_query($koneksi, "SELECT * FROM mata_kuliah WHERE status = 1");
            
            if ($matkul_query && mysqli_num_rows($matkul_query) > 0) {
                while ($mk = mysqli_fetch_assoc($matkul_query)) {
                    $id_matkul = $mk['id_matkul'];
                    $nama_matkul = $mk['nama_matkul'];
                    
                    // Cek apakah sudah absen hari ini
                    $cek_absen = mysqli_query($koneksi, "
                        SELECT * FROM absensi 
                        WHERE nim = '$nim' 
                        AND id_matkul = '$id_matkul' 
                        AND DATE(waktu) = CURDATE()
                    ");
                    
                    if ($cek_absen && mysqli_num_rows($cek_absen) == 0) {
                        // Belum absen, lakukan insert
                        $insert_absen = mysqli_query($koneksi, "
                            INSERT INTO absensi (nim, id_matkul, status, waktu) 
                            VALUES ('$nim', '$id_matkul', 'Hadir', NOW())
                        ");
                        
                        if ($insert_absen) {
                            $attendance_results[] = [
                                'matkul' => $nama_matkul,
                                'status' => 'success',
                                'message' => 'Berhasil absen'
                            ];
                        } else {
                            $attendance_results[] = [
                                'matkul' => $nama_matkul,
                                'status' => 'error',
                                'message' => 'Gagal menyimpan absensi'
                            ];
                        }
                    } else {
                        $attendance_results[] = [
                            'matkul' => $nama_matkul,
                            'status' => 'already',
                            'message' => 'Sudah absen hari ini'
                        ];
                    }
                }
            }
            
            $response['success'] = true;
            $response['message'] = 'Attendance processed successfully';
            $response['student_data'] = $mahasiswa;
            $response['attendance_results'] = $attendance_results;
            
        } else {
            $response['message'] = 'NIM and RFID mismatch';
        }
        
    } else {
        $response['message'] = 'Missing required parameters';
    }
} else {
    $response['message'] = 'Invalid request method';
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
