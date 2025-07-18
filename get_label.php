<?php
$dir = __DIR__ . '/Recognition/known_faces/';
$labels = [];

foreach (glob($dir . '*.jpg') as $file) {
    $labels[] = basename($file, '.jpg'); // ambil nama file tanpa .jpg
}

header('Content-Type: application/json');
echo json_encode($labels);
