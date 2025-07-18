<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../RFID/configg.php'; // pastikan configg.php di folder yang sama

$rfid_uid   = $_REQUEST['rfid_uid'] ?? null;
$id_matkul  = $_REQUEST['id_matkul'] ?? null;
$foto_bukti = $_REQUEST['foto_bukti'] ?? null;

if (!$rfid_uid || !$id_matkul) {
    echo "Data POST tidak lengkap.";
    exit();
}

// Bersihkan input
$rfid_uid   = mysqli_real_escape_string($koneksi, $rfid_uid);
$id_matkul  = intval($id_matkul);
$foto_bukti = $foto_bukti ? mysqli_real_escape_string($koneksi, $foto_bukti) : null;

// Cek apakah ID mata kuliah valid
$cek_matkul = mysqli_query($koneksi, "SELECT id_matkul FROM mata_kuliah WHERE id_matkul = $id_matkul");
if (!$cek_matkul || mysqli_num_rows($cek_matkul) == 0) {
    echo "ID mata kuliah tidak valid.";
    exit();
}

// Cari NIM berdasarkan UID RFID
$q = mysqli_query($koneksi, "SELECT nim FROM mahasiswa WHERE rfid_uid = '$rfid_uid'");
$data = mysqli_fetch_assoc($q);

if ($data) {
    $nim = $data['nim'];

    // Cek apakah sudah absen hari ini
    $cek = mysqli_query($koneksi, "
        SELECT * FROM absensi 
        WHERE nim = '$nim' 
        AND id_matkul = $id_matkul 
        AND DATE(waktu) = CURDATE()
    ");

    if (mysqli_num_rows($cek) > 0) {
        echo "Anda sudah absen hari ini untuk mata kuliah ini.";
    } else {
        $sql = "INSERT INTO absensi (nim, id_matkul, foto_bukti, status) 
                VALUES ('$nim', $id_matkul, " . 
                ($foto_bukti ? "'$foto_bukti'" : "NULL") . ", 'Hadir')";

        if (mysqli_query($koneksi, $sql)) {
            echo "Absensi berhasil dicatat.";
        } else {
            echo "Gagal menyimpan absensi: " . mysqli_error($koneksi);
        }
    }
} else {
    echo "RFID tidak terdaftar.";
}
?>
