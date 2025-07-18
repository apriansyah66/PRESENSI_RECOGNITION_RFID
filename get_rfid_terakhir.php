<?php
include 'configg.php';
$q = mysqli_query($koneksi, "SELECT rfid_uid FROM rfid_terakhir ORDER BY id DESC LIMIT 1");
$data = mysqli_fetch_assoc($q);
echo $data['rfid_uid'] ?? '';
