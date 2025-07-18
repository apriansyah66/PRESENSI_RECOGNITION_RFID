<?php
include 'configg.php';
require_once('./header.php');

if (!empty($_POST['jam_kuliah'])) {
    $jam_kuliah = $_POST['jam_kuliah'];
    $toleransi_hadir = max(0, (int)$_POST['toleransi_hadir']);
    $toleransi_terlambat = max(0, (int)$_POST['toleransi_terlambat']);

    // Simpan konfigurasi ke file
    $isi = <<<PHP
<?php
\$jam_kuliah = '$jam_kuliah';
\$toleransi_hadir = $toleransi_hadir;
\$toleransi_terlambat = $toleransi_terlambat;
?>
PHP;
    file_put_contents('config_waktu.php', $isi);

    // Jalankan update status
    include 'config_waktu.php';
    $tanggal = date('Y-m-d');
    $res = mysqli_query($koneksi, "SELECT * FROM absensi WHERE DATE(waktu) = '$tanggal'");
    $updated = 0;

    while ($row = mysqli_fetch_assoc($res)) {
        $waktu_absen = strtotime($row['waktu']);
        $waktu_kuliah = strtotime("$tanggal $jam_kuliah");

        $max_hadir = $waktu_kuliah + ($toleransi_hadir * 60);
        $max_terlambat = $waktu_kuliah + (($toleransi_hadir + $toleransi_terlambat) * 60);

        if ($waktu_absen <= $max_hadir) {
            $status = 'Hadir';
        } elseif ($waktu_absen <= $max_terlambat) {
            $status = 'Terlambat';
        } else {
            $status = 'Tidak Hadir';
        }

        $statusNow = $row['status'];
        if ($status !== $statusNow) {
            $nim = $row['nim'];
            $waktu = $row['waktu'];
            mysqli_query($koneksi, "UPDATE absensi SET status='$status' WHERE nim='$nim' AND waktu='$waktu'");
            $updated++;
        }
    }

    $pesan = "✅ Pengaturan berhasil disimpan & status $updated data telah diperbarui.";
}

// Baca konfigurasi
if (file_exists('config_waktu.php')) {
    include 'config_waktu.php';
} else {
    $jam_kuliah = '08:00:00';
    $toleransi_hadir = 15;
    $toleransi_terlambat = 60;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Atur Waktu & Toleransi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f8fa;
        }
        .container {
            max-width: 400px;
            margin: 40px auto;
            background: white;
            padding: 20px 25px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .pesan {
            color: green;
            text-align: center;
            margin-bottom: 10px;
        }
        select, button {
            width: 100%;
            padding: 10px;
            margin: 5px 0 15px;
            font-size: 15px;
        }
        button {
            background: green;
            color: white;
            border: none;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>⏳ Atur Waktu Kuliah & Toleransi</h2>
    <?php if (!empty($pesan)) echo "<p class='pesan'>$pesan</p>"; ?>
    <form method="post">
        <label>Jam Kuliah:</label>
        <select name="jam_kuliah" required>
            <?php
            for ($h=0; $h<=23; $h++) {
                for ($m=0; $m<60; $m+=5) {
                    $val = sprintf('%02d:%02d:00', $h, $m);
                    $sel = ($val == $jam_kuliah) ? 'selected' : '';
                    echo "<option value=\"$val\" $sel>$val</option>";
                }
            }
            ?>
        </select>
        <label>Toleransi Hadir (menit):</label>
        <select name="toleransi_hadir" required>
            <?php for ($i=0; $i<=60; $i++) {
                $sel = ($i == $toleransi_hadir) ? 'selected' : '';
                echo "<option value=\"$i\" $sel>$i menit</option>";
            } ?>
        </select>
        <label>Toleransi Terlambat (menit):</label>
        <select name="toleransi_terlambat" required>
            <?php for ($i=0; $i<=120; $i++) {
                $sel = ($i == $toleransi_terlambat) ? 'selected' : '';
                echo "<option value=\"$i\" $sel>$i menit</option>";
            } ?>
        </select>
        <button type="submit">💾 Simpan & Update Status</button>
    </form>
</div>
</body>
</html>
