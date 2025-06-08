<?php
require_once __DIR__ . '/../conexao.php';

// Variáveis para o modal de edição
$projeto_editando = null;
$mensagem = '';

// Adicionar novo projeto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adicionar_projeto'])) {
    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $status = trim($_POST['status'] ?? 'ativo');
    
    if ($nome) {
        $stmt = $mysqli->prepare('INSERT INTO projetos (nome, descricao, status, criado_por) VALUES (?, ?, ?, ?)');
        $criado_por = $_SESSION['usuario_id'] ?? 1; // Usar ID do usuário logado
        $stmt->bind_param('sssi', $nome, $descricao, $status, $criado_por);
        
        if ($stmt->execute()) {
            $mensagem = "Projeto adicionado com sucesso!";
            $_POST['projeto'] = 'cadastrarProjeto.php';
        } else {
            $mensagem = "Erro ao adicionar projeto: " . $mysqli->error;
        }
        $stmt->close();
    } else {
        $mensagem = "Por favor, preencha o nome do projeto.";
    }
}

// Editar projeto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_projeto'])) {
    $id = intval($_POST['id']);
    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $status = trim($_POST['status'] ?? 'ativo');
    
    if ($nome && $id > 0) {
        $stmt = $mysqli->prepare('UPDATE projetos SET nome = ?, descricao = ?, status = ? WHERE id = ?');
        $stmt->bind_param('sssi', $nome, $descricao, $status, $id);
        
        if ($stmt->execute()) {
            $mensagem = "Projeto atualizado com sucesso!";
            $_POST['projeto'] = 'cadastrarProjeto.php';
        } else {
            $mensagem = "Erro ao atualizar projeto: " . $mysqli->error;
        }
        $stmt->close();
    } else {
        $mensagem = "Dados inválidos para edição.";
    }
}

// Excluir projeto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir_projeto'])) {
    $id = intval($_POST['id']);
    
    if ($id > 0) {
        $stmt = $mysqli->prepare('DELETE FROM projetos WHERE id = ?');
        $stmt->bind_param('i', $id);
        
        if ($stmt->execute()) {
            $mensagem = "Projeto excluído com sucesso!";
            $_POST['projeto'] = 'cadastrarProjeto.php';
        } else {
            $mensagem = "Erro ao excluir projeto: " . $mysqli->error;
        }
        $stmt->close();
    }
}

// Carregar projeto para edição
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['carregar_projeto'])) {
    $id = intval($_POST['id']);
    
    if ($id > 0) {
        $stmt = $mysqli->prepare('SELECT * FROM projetos WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $projeto_editando = $result->fetch_assoc();
        $stmt->close();
    }
}

// Buscar todos os projetos
$projetos = [];
$result = $mysqli->query('SELECT p.*, u.nome as criador FROM projetos p LEFT JOIN usuarios u ON p.criado_por = u.id ORDER BY p.data_criacao DESC');
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $projetos[] = $row;
    }
    $result->free();
}
?>