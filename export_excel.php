<?php
require_once("./configg.php");

// Ambil filter dari GET
$filter_prodi = $_GET['prodi'] ?? '';
$filter_matkul = $_GET['matkul'] ?? '';
$filter_status = $_GET['status'] ?? '';

// Siapkan WHERE
$where_conditions = [];
if (!empty($filter_prodi)) {
    $where_conditions[] = "p.id_prodi = '" . mysqli_real_escape_string($koneksi, $filter_prodi) . "'";
}
if (!empty($filter_matkul)) {
    $where_conditions[] = "mk.id_matkul = '" . mysqli_real_escape_string($koneksi, $filter_matkul) . "'";
}
if (!empty($filter_status)) {
    $where_conditions[] = "a.status = '" . mysqli_real_escape_string($koneksi, $filter_status) . "'";
}
$where_clause = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';

// Query data
$sql = "SELECT 
            a.*, 
            m.nama AS nama_mhs, 
            p.nama_prodi, 
            mk.nama_matkul 
        FROM absensi a
        JOIN mahasiswa m ON a.nim = m.nim
        JOIN program_studi p ON m.id_prodi = p.id_prodi
        JOIN mata_kuliah mk ON a.id_matkul = mk.id_matkul
        $where_clause
        ORDER BY a.waktu DESC";
$query = mysqli_query($koneksi, $sql);

// Buat nama file berdasarkan filter
$filename_parts = [];
if (!empty($filter_prodi)) {
    $prodi_name_query = mysqli_query($koneksi, "SELECT nama_prodi FROM program_studi WHERE id_prodi = '" . mysqli_real_escape_string($koneksi, $filter_prodi) . "'");
    $prodi_name = mysqli_fetch_assoc($prodi_name_query)['nama_prodi'] ?? 'Unknown';
    $filename_parts[] = preg_replace('/[^a-zA-Z0-9]/', '_', $prodi_name);
}
if (!empty($filter_matkul)) {
    $matkul_name_query = mysqli_query($koneksi, "SELECT nama_matkul FROM mata_kuliah WHERE id_matkul = '" . mysqli_real_escape_string($koneksi, $filter_matkul) . "'");
    $matkul_name = mysqli_fetch_assoc($matkul_name_query)['nama_matkul'] ?? 'Unknown';
    $filename_parts[] = preg_replace('/[^a-zA-Z0-9]/', '_', $matkul_name);
}
if (!empty($filter_status)) {
    $filename_parts[] = preg_replace('/[^a-zA-Z0-9]/', '_', $filter_status);
}

$filename = 'Data_Rekap_Kehadiran';
if (!empty($filename_parts)) {
    $filename .= '_' . implode('_', $filename_parts);
}
$filename .= '_' . date('Y-m-d_H-i-s') . '.xls';

// Set header untuk download Excel
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');

// Mulai output Excel
echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
echo '<head>';
echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
echo '<style>';
echo 'table { border-collapse: collapse; width: 100%; }';
echo 'th, td { border: 1px solid #000; padding: 8px; text-align: left; }';
echo 'th { background-color: #f2f2f2; font-weight: bold; }';
echo '.center { text-align: center; }';
echo '.status-hadir { background-color: #d4edda; color: #155724; }';
echo '.status-tidak-hadir { background-color: #f8d7da; color: #721c24; }';
echo '.status-terlambat { background-color: #fff3cd; color: #856404; }';
echo '</style>';
echo '</head>';
echo '<body>';

// Header laporan
echo '<h2 style="text-align: center; margin-bottom: 20px;">DATA REKAP KEHADIRAN MAHASISWA</h2>';

// Informasi filter
if (!empty($filter_prodi) || !empty($filter_matkul) || !empty($filter_status)) {
    echo '<div style="margin-bottom: 20px; padding: 10px; background-color: #f8f9fa; border: 1px solid #dee2e6;">';
    echo '<strong>Filter yang diterapkan:</strong><br>';
    
    if (!empty($filter_prodi)) {
        $prodi_name_query = mysqli_query($koneksi, "SELECT nama_prodi FROM program_studi WHERE id_prodi = '" . mysqli_real_escape_string($koneksi, $filter_prodi) . "'");
        $prodi_name = mysqli_fetch_assoc($prodi_name_query)['nama_prodi'] ?? 'Unknown';
        echo '• Program Studi: ' . htmlspecialchars($prodi_name) . '<br>';
    }
    
    if (!empty($filter_matkul)) {
        $matkul_name_query = mysqli_query($koneksi, "SELECT nama_matkul FROM mata_kuliah WHERE id_matkul = '" . mysqli_real_escape_string($koneksi, $filter_matkul) . "'");
        $matkul_name = mysqli_fetch_assoc($matkul_name_query)['nama_matkul'] ?? 'Unknown';
        echo '• Mata Kuliah: ' . htmlspecialchars($matkul_name) . '<br>';
    }
    
    if (!empty($filter_status)) {
        echo '• Status: ' . htmlspecialchars($filter_status) . '<br>';
    }
    
    echo '</div>';
}

// Info waktu export
echo '<div style="margin-bottom: 20px; text-align: right; font-size: 12px;">';
echo 'Diekspor pada: ' . date('d/m/Y H:i:s');
echo '</div>';

// Tabel data
echo '<table>';
echo '<thead>';
echo '<tr>';
echo '<th class="center">No</th>';
echo '<th>NIM</th>';
echo '<th>Nama Mahasiswa</th>';
echo '<th>Program Studi</th>';
echo '<th>Mata Kuliah</th>';
echo '<th class="center">Waktu</th>';
echo '<th class="center">Status</th>';
echo '<th class="center">Foto Bukti</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

$no = 1;
$total_records = 0;
$status_count = [
    'Hadir' => 0,
    'Tidak Hadir' => 0,
    'Terlambat' => 0
];

while ($data = mysqli_fetch_assoc($query)) {
    $total_records++;
    $status_count[$data['status']]++;
    
    // Tentukan kelas CSS berdasarkan status
    $status_class = '';
    switch (strtolower($data['status'])) {
        case 'hadir':
            $status_class = 'status-hadir';
            break;
        case 'tidak hadir':
            $status_class = 'status-tidak-hadir';
            break;
        case 'terlambat':
            $status_class = 'status-terlambat';
            break;
    }
    
    echo '<tr>';
    echo '<td class="center">' . $no++ . '</td>';
    echo '<td>' . htmlspecialchars($data['nim']) . '</td>';
    echo '<td>' . htmlspecialchars($data['nama_mhs']) . '</td>';
    echo '<td>' . htmlspecialchars($data['nama_prodi']) . '</td>';
    echo '<td>' . htmlspecialchars($data['nama_matkul']) . '</td>';
    echo '<td class="center">' . date('d/m/Y H:i', strtotime($data['waktu'])) . '</td>';
    echo '<td class="center ' . $status_class . '">' . htmlspecialchars($data['status']) . '</td>';
    echo '<td class="center">' . (!empty($data['foto_bukti']) ? 'Ada' : 'Tidak ada') . '</td>';
    echo '</tr>';
}

echo '</tbody>';
echo '</table>';

// Ringkasan statistik
echo '<div style="margin-top: 30px; padding: 15px; background-color: #f8f9fa; border: 1px solid #dee2e6;">';
echo '<h3>RINGKASAN STATISTIK</h3>';
echo '<table style="width: 50%; margin-top: 10px;">';
echo '<tr>';
echo '<td><strong>Total Records:</strong></td>';
echo '<td>' . $total_records . '</td>';
echo '</tr>';
echo '<tr>';
echo '<td><strong>Hadir:</strong></td>';
echo '<td>' . $status_count['Hadir'] . ' (' . ($total_records > 0 ? round(($status_count['Hadir'] / $total_records) * 100, 1) : 0) . '%)</td>';
echo '</tr>';
echo '<tr>';
echo '<td><strong>Tidak Hadir:</strong></td>';
echo '<td>' . $status_count['Tidak Hadir'] . ' (' . ($total_records > 0 ? round(($status_count['Tidak Hadir'] / $total_records) * 100, 1) : 0) . '%)</td>';
echo '</tr>';
echo '<tr>';
echo '<td><strong>Terlambat:</strong></td>';
echo '<td>' . $status_count['Terlambat'] . ' (' . ($total_records > 0 ? round(($status_count['Terlambat'] / $total_records) * 100, 1) : 0) . '%)</td>';
echo '</tr>';
echo '</table>';
echo '</div>';

echo '</body>';
echo '</html>';

// Tutup koneksi database
mysqli_close($koneksi);
?>