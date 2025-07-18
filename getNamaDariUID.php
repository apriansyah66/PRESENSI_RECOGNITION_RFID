<?php
include 'configg.php';

// Ambil UID dari parameter
$uid = $_GET['uid'] ?? '';
$uid = trim($uid);
$uid = mysqli_real_escape_string($koneksi, $uid);

// Cek jika UID kosong
if ($uid === '') {
    echo "UID kosong";
    exit;
}

// Query nama mahasiswa
$sql = "SELECT nama FROM mahasiswa WHERE rfid_uid = '$uid' LIMIT 1";
$q = mysqli_query($koneksi, $sql);

header("Content-Type: text/plain");

if ($r = mysqli_fetch_assoc($q)) {
    echo $r['nama'];
} else {
    echo "Tidak dikenal";
}
?>
