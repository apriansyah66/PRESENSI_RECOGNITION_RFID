<?php
include 'configg.php';

if (isset($_POST['rfid_uid'])) {
    $uid = $_POST['rfid_uid'];
    $sql = "INSERT INTO rfid_terakhir (rfid_uid) VALUES ('$uid')";
    if (mysqli_query($koneksi, $sql)) {
        echo "Berhasil simpan UID";
    } else {
        echo "Gagal simpan UID: " . mysqli_error($koneksi);
    }
}
?>
