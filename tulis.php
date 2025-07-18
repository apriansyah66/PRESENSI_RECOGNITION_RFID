<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "absensi_mahasiswa";

$koneksi = mysqli_connect($host, $user, $pass, $db);

// Cek koneksi
if (mysqli_connect_errno()) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>
