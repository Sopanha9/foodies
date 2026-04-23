<?php
// includes/db.php

$host = '127.0.0.1'; // Use 127.0.0.1 instead of localhost for PDO sometimes to avoid socket issues
$db   = 'foodie';
$user = 'root'; // Default MAMP user
$pass = 'root'; // User specified no password
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset;port=8889";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // In a production environment, don't echo the full error message
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
