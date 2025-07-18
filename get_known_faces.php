<?php
$dir = __DIR__ . '/known_faces';
$labels = [];

// daftar ekstensi foto yang diizinkan
$allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];

if (is_dir($dir)) {
    $files = array_diff(scandir($dir), array('.', '..'));

    foreach ($files as $file) {
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed_extensions)) {
            $labels[] = pathinfo($file, PATHINFO_FILENAME);
        }
    }

    sort($labels);
}

header('Content-Type: application/json');
echo json_encode($labels);
