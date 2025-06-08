<?php
require_once __DIR__ . '/../conexao.php';

// Variáveis para controle
$mensagem = '';
$tarefa_editando = null;

// Adicionar nova tarefa
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adicionar_tarefa'])) {
    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $projeto_id = intval($_POST['projeto_id'] ?? 0);
    $responsavel_id = intval($_POST['responsavel_id'] ?? 0);
    $status = trim($_POST['status'] ?? 'pendente');
    $prioridade = trim($_POST['prioridade'] ?? 'media');
    $data_prazo = !empty($_POST['data_prazo']) ? $_POST['data_prazo'] : null;
    
    if ($nome && $projeto_id > 0 && $responsavel_id > 0) {
        $criado_por = $_SESSION['usuario_id'] ?? 1; // ID do usuário logado
        
        $stmt = $mysqli->prepare('INSERT INTO tarefas (nome, descricao, projeto_id, responsavel_id, criado_por, status, prioridade, data_prazo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('ssiissss', $nome, $descricao, $projeto_id, $responsavel_id, $criado_por, $status, $prioridade, $data_prazo);
        
        if ($stmt->execute()) {
            $mensagem = "Tarefa criada com sucesso!";
            $_POST['projeto'] = 'cadastrarTarefa.php';
        } else {
            $mensagem = "Erro ao criar tarefa: " . $mysqli->error;
        }
        $stmt->close();
    } else {
        $mensagem = "Por favor, preencha todos os campos obrigatórios.";
    }
}

// Editar tarefa
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_tarefa'])) {
    $id = intval($_POST['id']);
    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $projeto_id = intval($_POST['projeto_id'] ?? 0);
    $responsavel_id = intval($_POST['responsavel_id'] ?? 0);
    $status = trim($_POST['status'] ?? 'pendente');
    $prioridade = trim($_POST['prioridade'] ?? 'media');
    $data_prazo = !empty($_POST['data_prazo']) ? $_POST['data_prazo'] : null;
    
    if ($nome && $projeto_id > 0 && $responsavel_id > 0 && $id > 0) {
        $stmt = $mysqli->prepare('UPDATE tarefas SET nome = ?, descricao = ?, projeto_id = ?, responsavel_id = ?, status = ?, prioridade = ?, data_prazo = ? WHERE id = ?');
        $stmt->bind_param('ssiisssi', $nome, $descricao, $projeto_id, $responsavel_id, $status, $prioridade, $data_prazo, $id);
        
        if ($stmt->execute()) {
            $mensagem = "Tarefa atualizada com sucesso!";
            $_POST['projeto'] = 'cadastrarTarefa.php';
        } else {
            $mensagem = "Erro ao atualizar tarefa: " . $mysqli->error;
        }
        $stmt->close();
    } else {
        $mensagem = "Dados inválidos para edição.";
    }
}

// Excluir tarefa
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir_tarefa'])) {
    $id = intval($_POST['id']);
    
    if ($id > 0) {
        $stmt = $mysqli->prepare('DELETE FROM tarefas WHERE id = ?');
        $stmt->bind_param('i', $id);
        
        if ($stmt->execute()) {
            $mensagem = "Tarefa excluída com sucesso!";
            $_POST['projeto'] = 'cadastrarTarefa.php';
        } else {
            $mensagem = "Erro ao excluir tarefa: " . $mysqli->error;
        }
        $stmt->close();
    }
}

// Carregar tarefa para edição
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['carregar_tarefa'])) {
    $id = intval($_POST['id']);
    
    if ($id > 0) {
        $stmt = $mysqli->prepare('SELECT * FROM tarefas WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $tarefa_editando = $result->fetch_assoc();
        $stmt->close();
    }
}

// Buscar projetos para o select
$projetos = [];
$result = $mysqli->query('SELECT id, nome FROM projetos WHERE status = "ativo" ORDER BY nome');
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $projetos[] = $row;
    }
    $result->free();
}

// Buscar usuários para o select
$usuarios = [];
$result = $mysqli->query('SELECT id, nome FROM usuarios ORDER BY nome');
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }
    $result->free();
}

// Buscar todas as tarefas com informações relacionadas
$tarefas = [];
$query = "SELECT t.*, p.nome as projeto_nome, u.nome as responsavel_nome, uc.nome as criador_nome 
          FROM tarefas t 
          LEFT JOIN projetos p ON t.projeto_id = p.id 
          LEFT JOIN usuarios u ON t.responsavel_id = u.id 
          LEFT JOIN usuarios uc ON t.criado_por = uc.id 
          ORDER BY t.data_criacao DESC";

$result = $mysqli->query($query);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $tarefas[] = $row;
    }
    $result->free();
}
?>