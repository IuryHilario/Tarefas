<?php
require_once __DIR__ . '/../conexao.php';

// Verificar se há filtro de projeto aplicado
$projeto_filtro = isset($_GET['projeto']) ? (int)$_GET['projeto'] : null;

// Inicializar arrays para organização das tarefas
$tarefas_por_status = [
    'pendente' => [],
    'em_andamento' => [],
    'concluida' => []
];

// Construir query base com joins necessários
$query = "SELECT t.*, p.nome as projeto_nome, u.nome as responsavel_nome, uc.nome as criador_nome 
          FROM tarefas t 
          LEFT JOIN projetos p ON t.projeto_id = p.id 
          LEFT JOIN usuarios u ON t.responsavel_id = u.id 
          LEFT JOIN usuarios uc ON t.criado_por = uc.id";

// Adicionar filtro de projeto se especificado
$where_conditions = [];
if ($projeto_filtro) {
    $where_conditions[] = "t.projeto_id = " . $projeto_filtro;
}

// Adicionar condições WHERE se houver
if (!empty($where_conditions)) {
    $query .= " WHERE " . implode(" AND ", $where_conditions);
}

// Adicionar ordenação
$query .= " ORDER BY 
    CASE t.prioridade 
        WHEN 'urgente' THEN 1 
        WHEN 'alta' THEN 2 
        WHEN 'media' THEN 3 
        WHEN 'baixa' THEN 4 
        ELSE 5 
    END, 
    t.data_criacao ASC";

// Executar query e organizar tarefas por status
$result = $mysqli->query($query);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        // Garantir que o status seja válido
        $status = $row['status'];
        if (array_key_exists($status, $tarefas_por_status)) {
            $tarefas_por_status[$status][] = $row;
        }
    }
    $result->free();
} else {
    error_log("Erro na query de tarefas: " . $mysqli->error);
}

// Calcular estatísticas baseadas nas tarefas carregadas
$stats = [
    'pendentes' => count($tarefas_por_status['pendente']),
    'em_andamento' => count($tarefas_por_status['em_andamento']),
    'concluidas' => count($tarefas_por_status['concluida'])
];
$stats['total_tarefas'] = $stats['pendentes'] + $stats['em_andamento'] + $stats['concluidas'];

// Buscar todos os projetos ativos para o filtro
$projetos = [];
$query_projetos = "SELECT p.id, p.nome, COUNT(t.id) as total_tarefas 
                   FROM projetos p 
                   LEFT JOIN tarefas t ON p.id = t.projeto_id 
                   WHERE p.status = 'ativo' 
                   GROUP BY p.id, p.nome 
                   ORDER BY p.nome ASC";

$result = $mysqli->query($query_projetos);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $projetos[] = $row;
    }
    $result->free();
} else {
    error_log("Erro na query de projetos: " . $mysqli->error);
}

// Manter compatibilidade com código legado
$projetos_ativos = $projetos;
?>