<?php
    session_start();
    require_once 'controller/verificaAutenticacao.php';
    require_once 'controller/permissoes.php';
    require_once 'controller/carregaTela.php';
    
    // Verificar se há erro de acesso
    $erro_acesso = isset($_GET['erro']) && $_GET['erro'] === 'acesso_negado';
    
    // Obter telas permitidas para o usuário
    $telas_permitidas = getTelasPermitidas();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador de Tarefas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-screen flex flex-row bg-gray-100">
    <aside class="w-72 h-full flex flex-col bg-gradient-to-b from-green-400 to-green-600 text-white shadow-lg p-6 rounded-r-3xl">
        <h1 class="text-3xl font-extrabold mb-8 text-center drop-shadow">Gerenciador de Tarefas</h1>
        
        <!-- Informações do usuário -->
        <div class="bg-white bg-opacity-20 rounded-lg p-4 mb-6">
            <div class="text-center">
                <h3 class="font-semibold text-lg"><?php echo htmlspecialchars($_SESSION['usuario_nome'] ?? 'Usuário'); ?></h3>
                <p class="text-sm text-green-100 capitalize"><?php echo htmlspecialchars($_SESSION['usuario_grupo'] ?? 'N/A'); ?></p>            </div>
        </div>
        
        <?php if ($erro_acesso): ?>
        <div class="bg-red-500 bg-opacity-80 rounded-lg p-3 mb-4">
            <p class="text-sm text-center">❌ Acesso negado! Você não tem permissão para acessar esta página.</p>
        </div>
        <?php endif; ?>
        
        <nav class="flex-1 flex flex-col justify-center">
            <form method="POST" class="flex flex-col space-y-4">
                <?php foreach ($telas_permitidas as $arquivo => $nome): ?>
                <button name="tela" value="<?php echo htmlspecialchars($arquivo); ?>" type="submit" class="py-3 px-4 rounded-lg bg-white bg-opacity-20 hover:bg-opacity-40 transition font-semibold text-lg shadow">
                    <?php echo htmlspecialchars($nome); ?>
                </button>
                <?php endforeach; ?>
            </form>
        </nav>        <!-- Informações do usuário e logout -->
        <div class="mt-auto border-t border-white border-opacity-20 pt-4">
            <a href="controller/logout.php" class="block w-full py-2 px-4 bg-red-500 bg-opacity-80 hover:bg-opacity-100 text-center rounded-lg text-white font-semibold transition">
                Sair
            </a>
        </div>
    </aside>
    <!-- Conteúdo Principal -->
    <main class="flex-1 p-6 overflow-auto">
        <?php
        // Verificar se uma tela foi solicitada via POST
        $tela_solicitada = isset($_POST['tela']) ? $_POST['tela'] : null;
        
        if ($tela_solicitada && verificarPermissao($tela_solicitada)) {
            include 'views/' . $tela_solicitada;
        } elseif ($tela_em_exibicao != null && verificarPermissao($tela_em_exibicao)) {
            include 'views/' . $tela_em_exibicao;
        } else {
            include 'views/inicio.php';
        }
        ?>
    </main>




</body>
</html>


