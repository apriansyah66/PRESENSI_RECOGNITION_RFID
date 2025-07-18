<?php
include 'configg.php';

if (!isset($_POST['uid']) || !isset($_FILES['image'])) {
  die('Data tidak lengkap');
}

$uid = preg_replace('/[^a-zA-Z0-9]/', '', $_POST['uid']);
$targetDir = __DIR__ . '/Recognition/known_faces/';
if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);

$tmpName = $_FILES['image']['tmp_name'];
$filename = $uid . '.jpg';
$targetFile = $targetDir . $filename;

if (move_uploaded_file($tmpName, $targetFile)) {
  // simpan ke DB
  $stmt = $koneksi->prepare("INSERT INTO wajah (uid, file) VALUES (?, ?)");
  $stmt->bind_param("ss", $uid, $filename);
  if ($stmt->execute()) {
    echo "✅ Wajah berhasil disimpan & dicatat sebagai {$uid}.jpg";
  } else {
    echo "❌ Gagal menyimpan ke database: " . $stmt->error;
  }
  $stmt->close();
} else {
  echo "❌ Gagal menyimpan file.";
}
?>
