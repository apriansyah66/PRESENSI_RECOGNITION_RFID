<?php
include 'configg.php';
include 'config_waktu.php';

$nim = mysqli_real_escape_string($koneksi, $_POST['nim']);
$id_matkul = mysqli_real_escape_string($koneksi, $_POST['id_matkul']);

$tanggal = date('Y-m-d');
$waktu_sekarang = date('Y-m-d H:i:s');
$waktu_absen = strtotime($waktu_sekarang);

$waktu_kuliah = strtotime($tanggal . ' ' . $jam_kuliah);
$max_hadir = $waktu_kuliah + ($toleransi_hadir * 60);
$max_terlambat = $waktu_kuliah + (($toleransi_hadir + $toleransi_terlambat) * 60);

if ($waktu_absen <= $max_hadir) {
    $status = 'Hadir';
} elseif ($waktu_absen <= $max_terlambat) {
    $status = 'Terlambat';
} else {
    $status = 'Tidak Hadir';
}

mysqli_query($koneksi, "
    INSERT INTO absensi (nim, id_matkul, waktu, status) 
    VALUES ('$nim', '$id_matkul', '$waktu_sekarang', '$status')
");

echo "✅ Absensi berhasil dengan status: $status";
?>
