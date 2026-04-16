<?php
require_once '../includes/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { exit(0); }

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'Método não permitido']);
        exit;
    }

    $input = json_decode(file_get_contents('php://input'), true);
    $nome = trim($input['nome'] ?? '');
    $email = trim($input['email'] ?? '');
    $telefone = trim($input['telefone'] ?? '');

    if (empty($nome) || empty($email) || empty($telefone)) {
        http_response_code(400);
        echo json_encode(['error' => 'Todos os campos são obrigatórios']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['error' => 'Email inválido']);
        exit;
    }

    // Verifica se já existe (tabela usuarios)
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        http_response_code(409);
        echo json_encode(['error' => 'Email já cadastrado']);
        exit;
    }

    // Insere novo usuário na tabela usuarios
    $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, telefone) VALUES (?, ?, ?)");
    $stmt->execute([$nome, $email, $telefone]);
    
    $userId = $pdo->lastInsertId();
    echo json_encode([
        'success' => true,
        'user' => ['id' => $userId, 'name' => $nome, 'email' => $email]
    ]);

} catch (Exception $e) {
    http_response_code(500);
    error_log("Register error: " . $e->getMessage());
    echo json_encode(['error' => 'Erro interno do servidor']);
}
?>

