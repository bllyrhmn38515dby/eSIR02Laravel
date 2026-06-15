<?php
$host = '127.0.0.1';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS esir_v2;");
    echo "Database esir_v2 created successfully or already exists.\n";
} catch (PDOException $e) {
    die("DB ERROR: " . $e->getMessage());
}
