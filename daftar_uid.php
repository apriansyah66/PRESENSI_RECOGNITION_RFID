<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$file = __DIR__ . '/uid_terakhir.txt';

if (!empty($_POST['uid'])) {
    $uid = strtoupper(trim($_POST['uid']));
    if (file_put_contents($file, $uid)) {
        echo "✅ UID disimpan: $uid";
    } else {
        echo "❌ Gagal menyimpan UID.";
    }
} else {
    echo "❌ Tidak ada UID dikirim.";
}
