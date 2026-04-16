<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $name = trim($input['name'] ?? '');
    $phone = trim($input['phone'] ?? '');
    $email = trim($input['email'] ?? '');
    $service = trim($input['service'] ?? '');
    $date = $input['date'] ?? '';
    $time = $input['time'] ?? '';
    $notes = $input['notes'] ?? '';

    if (empty($name) || empty($phone) || empty($email) || empty($service) || empty($date) || empty($time)) {
        http_response_code(400);
        echo json_encode(['error' => 'Todos os campos obrigatórios devem ser preenchidos']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['error' => 'Email inválido']);
        exit;
    }

    // Busca user_id por email
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $userId = $user ? $user['id'] : null;

    // Insere agendamento
    $stmt = $pdo->prepare("INSERT INTO appointments (user_id, name, phone, email, service, appointment_date, appointment_time, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $success = $stmt->execute([$userId, $name, $phone, $email, $service, $date, $time, $notes]);

    if ($success) {
        echo json_encode([
            'success' => true,
            'message' => 'Consulta agendada com sucesso!',
            'id' => $pdo->lastInsertId()
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao agendar']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Lista agendamentos do usuário logado (futuro: by session/email)
    $email = $_GET['email'] ?? '';
    $stmt = $pdo->prepare("SELECT * FROM appointments WHERE email = ? ORDER BY appointment_date DESC, appointment_time ASC LIMIT 10");
    $stmt->execute([$email]);
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(['appointments' => $appointments]);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método não permitido']);
}
?>

