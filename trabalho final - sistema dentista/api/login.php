<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método não permitido']);
    exit;
}

try {
    $input = json_decode(file_get_contents('php://input'), true);
    $email = trim($input['email'] ?? '');

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['error' => 'Email inválido']);
        exit;
    }

    // Busca ou cria usuário na tabela correta 'usuarios'
    $stmt = $pdo->prepare("SELECT id, nome FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        // Cria novo usuário
        $localName = explode('@', $email)[0];
        $nome = ucwords(str_replace(['.', '_', '-'], ' ', $localName));
        $nome = 'Paciente ' . $nome;
        
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email) VALUES (?, ?)");
        $stmt->execute([$nome, $email]);
        $userId = $pdo->lastInsertId();
        $user = ['id' => $userId, 'nome' => $nome];
    } else {
        $user['name'] = $user['nome']; // Compatibilidade frontend
        unset($user['nome']);
    }

    echo json_encode([
        'success' => true,
        'user' => $user
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    error_log("Login error: " . $e->getMessage());
    echo json_encode(['error' => 'Erro interno do servidor']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro de processamento']);
}
?>

