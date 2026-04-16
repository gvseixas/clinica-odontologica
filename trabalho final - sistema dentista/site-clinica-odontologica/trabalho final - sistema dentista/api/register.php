<?php
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    // Verifica se já existe
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        http_response_code(409);
        echo json_encode(['error' => 'Email já cadastrado']);
        exit;
    }

    // Insere novo usuário
    $stmt = $pdo->prepare("INSERT INTO users (name, email, telefone) VALUES (?, ?, ?)");
    $success = $stmt->execute([$nome, $email, $telefone]);
    
    if ($success) {
        $userId = $pdo->lastInsertId();
        echo json_encode([
            'success' => true,
            'user' => ['id' => $userId, 'name' => $nome, 'email' => $email]
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao cadastrar']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método não permitido']);
}
?>
