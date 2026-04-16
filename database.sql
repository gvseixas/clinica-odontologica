-- Banco de dados: sistema_dentista
-- Execute no phpMyAdmin ou MySQL CLI

CREATE DATABASE IF NOT EXISTS sistema_dentista CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sistema_dentista;

-- Tabela de usuários (pacientes)
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    telefone VARCHAR(20),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabela de agendamentos
CREATE TABLE agendamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    email VARCHAR(150) NOT NULL,
    servico VARCHAR(50) NOT NULL,
    data_agendamento DATE NOT NULL,
    hora_agendamento TIME NOT NULL,
    observacoes TEXT,
    status ENUM('pendente', 'confirmado', 'cancelado') DEFAULT 'pendente',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
);

-- Tabela de contatos
CREATE TABLE contatos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    mensagem TEXT NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Dados de teste
INSERT INTO usuarios (nome, email, telefone) VALUES 
('João Silva', 'joao@example.com', '(47) 99999-9999'),
('Maria Santos', 'maria@example.com', '(47) 88888-8888');

INSERT INTO agendamentos (usuario_id, nome, telefone, email, servico, data_agendamento, hora_agendamento, observacoes) VALUES 
(1, 'João Silva', '(47) 99999-9999', 'joao@example.com', 'clareamento', '2024-12-15', '14:00:00', 'Dentes superiores'),
(2, 'Maria Santos', '(47) 88888-8888', 'maria@example.com', 'limpeza', '2024-12-16', '10:00:00', '');

INSERT INTO contatos (nome, email, mensagem) VALUES 
('Pedro', 'pedro@test.com', 'Interessado em implantes.');

-- Indexes para performance
CREATE INDEX idx_usuario_email ON usuarios(email);
CREATE INDEX idx_agendamento_data ON agendamentos(data_agendamento);
CREATE INDEX idx_agendamento_usuario ON agendamentos(usuario_id);