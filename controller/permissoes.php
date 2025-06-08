<?php
// Sistema de controle de acesso por grupo de usuário

/**
 * Verifica se o usuário tem permissão para acessar uma determinada tela
 */
function verificarPermissao($tela_requisitada) {
    // Verificar se usuário está logado
    if (!isset($_SESSION['usuario_grupo']) || !isset($_SESSION['usuario_nome'])) {
        return false;
    }
    
    $grupo = $_SESSION['usuario_grupo'];
    
    // Definir permissões por grupo
    $permissoes = [
        'gerente' => [
            'inicio.php',
            'cadastrarProjeto.php',
            'cadastrarTarefa.php',
            'cadastrarPessoa.php',
            'quadroKanban.php'
        ],
        'dev' => [
            'inicio.php',
            'quadroKanban.php'
        ]
    ];
    
    // Verificar se o grupo existe e tem permissão para a tela
    if (isset($permissoes[$grupo]) && in_array($tela_requisitada, $permissoes[$grupo])) {
        return true;
    }
    
    return false;
}

/**
 * Redireciona para página de acesso negado se não tiver permissão
 */
function verificarAcessoTela($tela) {
    if (!verificarPermissao($tela)) {
        header('location: home.php?erro=acesso_negado');
        exit;
    }
}

/**
 * Retorna as telas que o usuário tem acesso
 */
function getTelasPermitidas() {
    // Verificar se usuário está logado
    if (!isset($_SESSION['usuario_grupo']) || !isset($_SESSION['usuario_nome'])) {
        return [];
    }
    
    $grupo = $_SESSION['usuario_grupo'];
    
    $permissoes = [
        'gerente' => [
            'inicio.php' => 'Início',
            'cadastrarProjeto.php' => 'Projetos',
            'cadastrarTarefa.php' => 'Tarefas',
            'cadastrarPessoa.php' => 'Usuários',
            'quadroKanban.php' => 'Quadro Kanban'
        ],
        'dev' => [
            'inicio.php' => 'Início',
            'quadroKanban.php' => 'Quadro Kanban'
        ]
    ];
    
    return isset($permissoes[$grupo]) ? $permissoes[$grupo] : [];
}
?>