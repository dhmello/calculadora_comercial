<?php
session_start();

$directory = __DIR__ . '/user-data/';
$filename = $directory . session_id() . '.json';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!is_dir($directory)) {
        mkdir($directory, 0755, true);
    }

    file_put_contents($filename, json_encode($data['data']));
    echo json_encode(['status' => 'success']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (file_exists($filename)) {
        echo file_get_contents($filename);
    } else {
        echo json_encode(['status' => 'no_data']);
    }
    exit;
}
?>
