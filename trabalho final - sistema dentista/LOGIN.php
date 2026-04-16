<?php
session_start();
require_once 'includes/config.php';

// Simula login Google (futuro: OAuth real)
if (isset($_POST['google'])) {
    $_SESSION['user'] = ['email' => 'demo@gmail.com', 'name' => 'Usuário Demo'];
    header('Location: dashboard.php');
    exit;
}

// Login email
if (isset($_POST['email'])) {
    $email = trim($_POST['email']);
    $_SESSION['user'] = ['email' => $email, 'name' => 'Paciente'];
    header('Location: dashboard.php');
    exit;
}
?>
<!-- Fallback para frontend JS -->

