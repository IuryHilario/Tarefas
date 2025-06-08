<?php
// Definir a tela padrão
$tela_em_exibicao = 'inicio.php';

// Verificar se foi enviado um formulário com projeto ou quadroKanban
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tela_requisitada = null;
    
    if (isset($_POST['projeto'])) {
        $tela_requisitada = $_POST['projeto'];
    } elseif (isset($_POST['quadroKanban'])) {
        $tela_requisitada = $_POST['quadroKanban'];
    } elseif (isset($_POST['inicio'])) {
        $tela_requisitada = $_POST['inicio'];
    }
    
    // Verificar permissão se uma tela foi requisitada
    if ($tela_requisitada) {
        if (verificarPermissao($tela_requisitada)) {
            $tela_em_exibicao = $tela_requisitada;
        } else {
            // Redirecionar para página de erro
            header('location: home.php?erro=acesso_negado');
            exit;
        }
    }
}

// Verificar se a tela existe
$caminho_arquivo = __DIR__ . '/../views/' . $tela_em_exibicao;
if (!file_exists($caminho_arquivo)) {
    $tela_em_exibicao = 'inicio.php';
}
?>