<?php
define('DB_NAME', 'jsguard');
define('DB_USER', 'root');
define('DB_PASS', '');

ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
session_start();

function db(): PDO {
    static $pdo;
    if (!$pdo) {
        $socket = '/data/data/com.termux/files/usr/var/run/mysqld.sock';
        $pdo = new PDO(
            'mysql:unix_socket=' . $socket . ';dbname=' . DB_NAME . ';charset=utf8mb4',
            DB_USER, DB_PASS,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
             PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
        );
    }
    return $pdo;
}

function json_out(array $data, int $code = 200): void {
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function auth_required(): array {
    if (empty($_SESSION['user_id']))
        json_out(['error' => 'Não autenticado.'], 401);
    return ['id' => $_SESSION['user_id'], 'email' => $_SESSION['email']];
}
