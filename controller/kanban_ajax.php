<?php
session_start();
require_once __DIR__ . '/../conexao.php';
require_once __DIR__ . '/permissoes.php';

// Verificar se usuário está autenticado
if (!isset($_SESSION['usuario_id'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
    exit;
}

// Verificar permissão para Kanban
if (!verificarPermissao('quadroKanban.php')) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Sem permissão para acessar o Kanban']);
    exit;
}

// Processar apenas requisições POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

// Função para validar entrada
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Função para validar status
function isValidStatus($status) {
    $valid_statuses = ['pendente', 'em_andamento', 'concluida'];
    return in_array($status, $valid_statuses);
}

// Função para calcular estatísticas
function calculateStats($mysqli, $projeto_id = null) {
    $where_clause = $projeto_id ? "WHERE projeto_id = " . (int)$projeto_id : "";
    $query = "SELECT 
                status,
                COUNT(*) as count
              FROM tarefas 
              $where_clause
              GROUP BY status";
    $result = $mysqli->query($query);
    $stats = ['pendentes' => 0, 'em_andamento' => 0, 'concluidas' => 0];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            if ($row['status'] === 'pendente') $stats['pendentes'] = (int)$row['count'];
            if ($row['status'] === 'em_andamento') $stats['em_andamento'] = (int)$row['count'];
            if ($row['status'] === 'concluida') $stats['concluidas'] = (int)$row['count'];
        }
        $result->free();
    }
    $stats['total_tarefas'] = $stats['pendentes'] + $stats['em_andamento'] + $stats['concluidas'];
    return $stats;
}

try {
    // Atualizar status da tarefa
    if (isset($_POST['atualizar_status'])) {
        $tarefa_id = (int)$_POST['tarefa_id'];
        $novo_status = sanitizeInput($_POST['novo_status']);
        
        // Validações
        if ($tarefa_id <= 0) {
            throw new Exception('ID da tarefa inválido');
        }
        
        if (!isValidStatus($novo_status)) {
            throw new Exception('Status inválido');
        }
        
        // Preparar e executar query
        $stmt = $mysqli->prepare("UPDATE tarefas SET status = ?, data_atualizacao = NOW() WHERE id = ?");
        if (!$stmt) {
            throw new Exception('Erro na preparação da query: ' . $mysqli->error);
        }
        
        $stmt->bind_param("si", $novo_status, $tarefa_id);
        
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                // Buscar informações da tarefa para calcular estatísticas
                $task_query = $mysqli->prepare("SELECT projeto_id FROM tarefas WHERE id = ?");
                $task_query->bind_param("i", $tarefa_id);
                $task_query->execute();
                $task_result = $task_query->get_result();
                $task_data = $task_result->fetch_assoc();
                $task_query->close();
                
                // Calcular estatísticas atualizadas (sem filtro de projeto para o total global)
                $stats = calculateStats($mysqli, null);
                
                $response = [
                    'success' => true,
                    'message' => 'Status atualizado com sucesso',
                    'tarefa_id' => $tarefa_id,
                    'novo_status' => $novo_status,
                    'stats' => $stats
                ];
                
                echo json_encode($response);
            } else {
                throw new Exception('Nenhuma tarefa foi atualizada. Verifique se o ID existe.');
            }
        } else {
            throw new Exception('Erro ao executar atualização: ' . $stmt->error);
        }
        
        $stmt->close();
    }
    
    // Buscar tarefas filtradas por projeto
    else if (isset($_POST['filtrar_projeto'])) {
        $projeto_id = isset($_POST['projeto_id']) ? (int)$_POST['projeto_id'] : null;
        
        $where_clause = "";
        $params = [];
        $types = "";
        
        if ($projeto_id && $projeto_id > 0) {
            $where_clause = "WHERE t.projeto_id = ?";
            $params[] = $projeto_id;
            $types = "i";
        }
        
        $query = "SELECT t.*, p.nome as projeto_nome, u.nome as responsavel_nome 
                  FROM tarefas t 
                  LEFT JOIN projetos p ON t.projeto_id = p.id 
                  LEFT JOIN usuarios u ON t.responsavel_id = u.id 
                  $where_clause
                  ORDER BY 
                    CASE t.prioridade 
                        WHEN 'urgente' THEN 1 
                        WHEN 'alta' THEN 2 
                        WHEN 'media' THEN 3 
                        WHEN 'baixa' THEN 4 
                        ELSE 5 
                    END, 
                    t.data_criacao ASC";
        
        $stmt = $mysqli->prepare($query);
        if ($params) {
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $tarefas = [];
        while ($row = $result->fetch_assoc()) {
            $tarefas[$row['status']][] = $row;
        }
        
        $stats = calculateStats($mysqli, $projeto_id);
        
        echo json_encode([
            'success' => true,
            'tarefas' => $tarefas,
            'stats' => $stats
        ]);
        
        $stmt->close();
    }
    
    else {
        throw new Exception('Ação não reconhecida');
    }
    
} catch (Exception $e) {
    error_log("Erro no kanban_ajax.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} catch (Error $e) {
    error_log("Erro fatal no kanban_ajax.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Erro interno do servidor'
    ]);
}

$mysqli->close();
?>