<?php
require_once __DIR__ . '/../conexao.php';

// Buscar estatísticas gerais
$stats_gerais = [];

// Total de projetos
$result = $mysqli->query("SELECT COUNT(*) as total FROM projetos");
$stats_gerais['total_projetos'] = $result->fetch_assoc()['total'];

// Projetos ativos
$result = $mysqli->query("SELECT COUNT(*) as total FROM projetos WHERE status = 'ativo'");
$stats_gerais['projetos_ativos'] = $result->fetch_assoc()['total'];

// Total de usuários
$result = $mysqli->query("SELECT COUNT(*) as total FROM usuarios");
$stats_gerais['total_usuarios'] = $result->fetch_assoc()['total'];

// Total de tarefas
$result = $mysqli->query("SELECT COUNT(*) as total FROM tarefas");
$stats_gerais['total_tarefas'] = $result->fetch_assoc()['total'];

// Tarefas por status
$result = $mysqli->query("SELECT status, COUNT(*) as total FROM tarefas GROUP BY status");
$tarefas_por_status = ['pendente' => 0, 'em_andamento' => 0, 'concluida' => 0];
while($row = $result->fetch_assoc()) {
    $tarefas_por_status[$row['status']] = $row['total'];
}

// Tarefas urgentes/alta prioridade
$result = $mysqli->query("SELECT COUNT(*) as total FROM tarefas WHERE prioridade IN ('urgente', 'alta') AND status != 'concluida'");
$tarefas_prioritarias = $result->fetch_assoc()['total'];

// Tarefas próximas do prazo (próximos 7 dias)
$data_limite = date('Y-m-d', strtotime('+7 days'));
$result = $mysqli->query("SELECT COUNT(*) as total FROM tarefas WHERE data_prazo <= '$data_limite' AND data_prazo >= CURDATE() AND status != 'concluida'");
$tarefas_proximas_prazo = $result->fetch_assoc()['total'];

// Tarefas em atraso
$result = $mysqli->query("SELECT COUNT(*) as total FROM tarefas WHERE data_prazo < CURDATE() AND status != 'concluida'");
$tarefas_em_atraso = $result->fetch_assoc()['total'];

// Minhas tarefas (se usuário estiver logado)
$minhas_tarefas = ['pendente' => [], 'em_andamento' => [], 'concluida' => []];
$grupo_usuario = $_SESSION['usuario_grupo'] ?? '';

if(isset($_SESSION['usuario_id'])) {
    $usuario_id = $_SESSION['usuario_id'];
    
    // Para desenvolvedores, mostrar apenas suas tarefas
    // Para gerentes, mostrar estatísticas mais amplas
    if ($grupo_usuario === 'dev') {
        // Desenvolvedores veem apenas suas próprias tarefas
        $query = "SELECT t.*, p.nome as projeto_nome 
                  FROM tarefas t 
                  LEFT JOIN projetos p ON t.projeto_id = p.id 
                  WHERE t.responsavel_id = ? 
                  ORDER BY t.prioridade DESC, t.data_criacao DESC";
        
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('i', $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while($row = $result->fetch_assoc()) {
            $minhas_tarefas[$row['status']][] = $row;
        }
        $stmt->close();
    } else {
        // Gerentes veem suas tarefas + podem ver todas as tarefas
        $query = "SELECT t.*, p.nome as projeto_nome 
                  FROM tarefas t 
                  LEFT JOIN projetos p ON t.projeto_id = p.id 
                  WHERE t.responsavel_id = ? 
                  ORDER BY t.prioridade DESC, t.data_criacao DESC";
        
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('i', $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while($row = $result->fetch_assoc()) {
            $minhas_tarefas[$row['status']][] = $row;
        }
        $stmt->close();
    }
}

// Tarefas recentes (últimas 5)
$query = "SELECT t.*, p.nome as projeto_nome, u.nome as responsavel_nome 
          FROM tarefas t 
          LEFT JOIN projetos p ON t.projeto_id = p.id 
          LEFT JOIN usuarios u ON t.responsavel_id = u.id 
          ORDER BY t.data_criacao DESC 
          LIMIT 5";
$result = $mysqli->query($query);
$tarefas_recentes = [];
while($row = $result->fetch_assoc()) {
    $tarefas_recentes[] = $row;
}

// Projetos em destaque (com mais tarefas ativas)
$query = "SELECT p.nome, p.status, COUNT(t.id) as total_tarefas 
          FROM projetos p 
          LEFT JOIN tarefas t ON p.id = t.projeto_id AND t.status != 'concluida'
          WHERE p.status = 'ativo' 
          GROUP BY p.id, p.nome, p.status 
          ORDER BY total_tarefas DESC 
          LIMIT 5";
$result = $mysqli->query($query);
$projetos_destaque = [];
while($row = $result->fetch_assoc()) {
    $projetos_destaque[] = $row;
}

// Usuários mais produtivos (com mais tarefas concluídas)
$query = "SELECT u.nome, COUNT(t.id) as tarefas_concluidas 
          FROM usuarios u 
          LEFT JOIN tarefas t ON u.id = t.responsavel_id AND t.status = 'concluida'
          GROUP BY u.id, u.nome 
          ORDER BY tarefas_concluidas DESC 
          LIMIT 5";
$result = $mysqli->query($query);
$usuarios_produtivos = [];
while($row = $result->fetch_assoc()) {
    $usuarios_produtivos[] = $row;
}

// Calcular progresso geral
$total_tarefas = array_sum($tarefas_por_status);
$progresso_geral = $total_tarefas > 0 ? round(($tarefas_por_status['concluida'] / $total_tarefas) * 100, 1) : 0;
?>