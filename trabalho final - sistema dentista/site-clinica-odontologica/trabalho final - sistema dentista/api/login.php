<?php
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $email = trim($input['email'] ?? '');

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['error' => 'Email inválido']);
        exit;
    }

    // Busca ou cria usuário
    $stmt = $pdo->prepare("SELECT id, name FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        // Cria novo usuário (cadastro)
        $stmt = $pdo->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
        $stmt->execute(['Paciente ' . explode('@', $email)[0], $email]);
        $userId = $pdo->lastInsertId();
        $user = ['id' => $userId, 'name' => 'Paciente ' . explode('@', $email)[0]];
    }

    echo json_encode([
        'success' => true,
        'user' => $user
    ]);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método não permitido']);
}
?>

