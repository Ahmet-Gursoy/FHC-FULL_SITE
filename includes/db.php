<?php
require_once __DIR__ . '/loadEnv.php';
loadEnv(__DIR__ . '/.env');
$conn = new mysqli(
    getenv('DB_HOST'),
    getenv('DB_USER'),
    getenv('DB_PASS'),
    getenv('DB_NAME')
);

$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die("Veritabanı bağlantı hatası: " . $conn->connect_error);
}
