<?php
// Configuração do banco de dados MySQL
define('DB_HOST', 'localhost');
define('DB_USER', 'root'); // padrão XAMPP
define('DB_PASS', '');     // padrão XAMPP vazio
define('DB_NAME', 'sistema_dentista');

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode(['error' => 'Erro de conexão com o banco: ' . $e->getMessage()]);
    exit;
}


?>

