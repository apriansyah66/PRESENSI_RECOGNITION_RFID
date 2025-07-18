<?php
// uid_cek.php
$file = __DIR__ . '/uid_terakhir.txt';
$uid = trim(@file_get_contents($file));
header('Content-Type: application/json');
echo json_encode(['uid' => $uid]);
