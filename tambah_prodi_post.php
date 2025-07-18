<?php
session_start();
require_once("./configg.php");
require_once("./config/function.php");

if (!isset($_SESSION['username'])) {
    die(json_encode([
        'status' => false,
        'message' => 'Session kamu berakhir, Silahkan login ulang.'
    ]));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $namaProdi = trim($_POST['namaProdi'] ?? '');

    // Validasi kosong
    if (empty($namaProdi)) {
        die(json_encode([
            'status' => false,
            'message' => 'Nama Program Studi tidak boleh kosong.'
        ]));
    }

    // Validasi panjang karakter (maks 100)
    if (strlen($namaProdi) > 100) {
        die(json_encode([
            'status' => false,
            'message' => 'Nama Program Studi maksimal 100 karakter.'
        ]));
    }

    // Cek duplikat
    $stmt = $koneksi->prepare("SELECT id_prodi FROM program_studi WHERE nama_prodi = ?");
    $stmt->bind_param("s", $namaProdi);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->close();
        die(json_encode([
            'status' => false,
            'message' => 'Program Studi sudah terdaftar.'
        ]));
    }
    $stmt->close();

    // Insert
    $stmt = $koneksi->prepare("INSERT INTO program_studi (nama_prodi) VALUES (?)");
    $stmt->bind_param("s", $namaProdi);

    if ($stmt->execute()) {
        die(json_encode([
            'status' => true,
            'message' => 'Program Studi berhasil ditambahkan.'
        ]));
    } else {
        die(json_encode([
            'status' => false,
            'message' => 'Gagal menambahkan Program Studi: ' . $stmt->error
        ]));
    }
}
?>
