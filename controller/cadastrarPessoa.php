<?php
require_once __DIR__ . '/../conexao.php';

// Inserção de novo usuário
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cadastrar'])) {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $grupo = trim($_POST['grupo'] ?? '');
    $senha = password_hash('user123', PASSWORD_DEFAULT);

    if ($nome && $email && $grupo) {
        // Verificar se o email já está cadastrado
        $verificaEmail = $mysqli->prepare('SELECT id FROM usuarios WHERE email = ?');
        $verificaEmail->bind_param('s', $email);
        $verificaEmail->execute();
        $verificaEmail->store_result();
        
        if ($verificaEmail->num_rows > 0) {
            $mensagem = "Este email já está cadastrado.";
        } else {
            $stmt = $mysqli->prepare('INSERT INTO usuarios (nome, email, grupo, senha) VALUES (?, ?, ?, ?)');
            $stmt->bind_param('ssss', $nome, $email, $grupo, $senha);
              if ($stmt->execute()) {
                $mensagem = "Usuário cadastrado com sucesso! Senha padrão: user123";
                // Manter na mesma página com o projeto atual
                $_POST['projeto'] = 'cadastrarPessoa.php';
            } else {
                $mensagem = "Erro ao cadastrar usuário: " . $mysqli->error;
            }
            $stmt->close();
        }
        $verificaEmail->close();
    } else {
        $mensagem = "Por favor, preencha todos os campos obrigatórios.";
    }
}

// Buscar usuários cadastrados
$usuarios = [];
$result = $mysqli->query('SELECT id, nome, email, grupo FROM usuarios ORDER BY nome');
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }
    $result->free();
}
