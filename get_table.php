<?php
if (!isset($koneksi)) {
    include 'configg.php';
}

if (!isset($query)) {
    // ulangi query jika file dipanggil langsung
    $limit = 10;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    $filter_prodi = $_GET['prodi'] ?? '';
    $filter_matkul = $_GET['matkul'] ?? '';
    $filter_status = $_GET['status'] ?? '';

    $where_conditions = [];
    if ($filter_prodi) $where_conditions[] = "p.id_prodi = '".mysqli_real_escape_string($koneksi, $filter_prodi)."'";
    if ($filter_matkul) $where_conditions[] = "mk.id_matkul = '".mysqli_real_escape_string($koneksi, $filter_matkul)."'";

    $where_clause = $where_conditions ? 'WHERE '.implode(' AND ', $where_conditions) : '';

    $sql = "SELECT a.*, m.nama AS nama_mhs, p.nama_prodi, mk.nama_matkul
    FROM absensi a 
    JOIN mahasiswa m ON a.nim=m.nim 
    JOIN program_studi p ON m.id_prodi=p.id_prodi 
    JOIN mata_kuliah mk ON a.id_matkul=mk.id_matkul 
    $where_clause ORDER BY a.waktu DESC LIMIT $limit OFFSET $offset";

    $query = mysqli_query($koneksi, $sql) or die(mysqli_error($koneksi));
}
?>

<div class="stats">
📊 Menampilkan <?= mysqli_num_rows($query) ?> data
</div>

<?php if (mysqli_num_rows($query) > 0): ?>
<table border="1" cellpadding="5">
<thead>
<tr>
<th>NIM</th><th>Nama</th><th>Prodi</th><th>Matkul</th><th>Waktu</th><th>Status</th>
</tr>
</thead>
<tbody>
<?php
while ($data = mysqli_fetch_assoc($query)):
    $jam_kuliah = '08:00:00';
    $waktu_absen = strtotime($data['waktu']);
    $waktu_kuliah = strtotime(date('Y-m-d', strtotime($data['waktu'])) . ' ' . $jam_kuliah);

    if ($waktu_absen <= $waktu_kuliah + (15*60)) {
        $status = 'Hadir';
    } elseif ($waktu_absen <= $waktu_kuliah + (60*60)) {
        $status = 'Terlambat';
    } else {
        $status = 'Tidak Hadir';
    }
?>
<tr>
<td><?= htmlspecialchars($data['nim']) ?></td>
<td><?= htmlspecialchars($data['nama_mhs']) ?></td>
<td><?= htmlspecialchars($data['nama_prodi']) ?></td>
<td><?= htmlspecialchars($data['nama_matkul']) ?></td>
<td><?= date('d/m/Y H:i', strtotime($data['waktu'])) ?></td>
<td><?= $status ?></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
<?php else: ?>
<p>Tidak ada data.</p>
<?php endif; ?>
