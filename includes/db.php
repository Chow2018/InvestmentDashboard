<?php
/**
 * PDO MySQL connection for Investment Dashboard.
 */
$db_config = require __DIR__ . '/../config.php';
if (empty($db_config['db_host']) || empty($db_config['db_name']) || $db_config['db_pass'] === '') {
    die('Please copy config.example.php to config.php and set your database credentials.');
}

$dsn = sprintf(
    'mysql:host=%s;dbname=%s;charset=utf8mb4',
    $db_config['db_host'],
    $db_config['db_name']
);

try {
    $pdo = new PDO($dsn, $db_config['db_user'], $db_config['db_pass'], [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die('Database connection failed: ' . htmlspecialchars($e->getMessage()));
}
