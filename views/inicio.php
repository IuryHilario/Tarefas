<?php
require_once __DIR__ . '/../controller/inicio.php';
?>

<main class="flex-1 p-6">
    <div class="bg-gray-100 min-h-screen">        <!-- Header de Boas-vindas -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white p-6 rounded-lg mb-6 shadow-lg">
            <h1 class="text-3xl font-bold mb-2">
                Bem-vindo, <?php echo htmlspecialchars($_SESSION['usuario_nome'] ?? 'Usuário'); ?>! 👋
            </h1>
            <p class="text-blue-100">
                <?php if($grupo_usuario === 'dev'): ?>
                    Aqui estão suas tarefas e o progresso dos seus projetos.
                <?php else: ?>
                    Aqui está um resumo do seu trabalho e do progresso da equipe.
                <?php endif; ?>
            </p>
            <div class="mt-2 inline-block bg-white bg-opacity-20 rounded-full px-3 py-1">
                <span class="text-sm font-medium capitalize"><?php echo htmlspecialchars($_SESSION['usuario_grupo'] ?? 'N/A'); ?></span>
            </div>
        </div>        <!-- Cards de Estatísticas Principais -->
        <?php if($grupo_usuario === 'gerente'): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Total de Projetos</p>
                        <p class="text-2xl font-bold text-blue-600"><?php echo $stats_gerais['total_projetos']; ?></p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2"><?php echo $stats_gerais['projetos_ativos']; ?> ativos</p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Total de Tarefas</p>
                        <p class="text-2xl font-bold text-green-600"><?php echo $stats_gerais['total_tarefas']; ?></p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2"><?php echo $progresso_geral; ?>% concluídas</p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Tarefas Prioritárias</p>
                        <p class="text-2xl font-bold text-orange-600"><?php echo $tarefas_prioritarias; ?></p>
                    </div>
                    <div class="bg-orange-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 18.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">Alta/Urgente</p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Equipe</p>
                        <p class="text-2xl font-bold text-purple-600"><?php echo $stats_gerais['total_usuarios']; ?></p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">Membros ativos</p>
            </div>
        </div>
        <?php else: ?>
        <!-- Cards simplificados para desenvolvedores -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Minhas Tarefas</p>
                        <p class="text-2xl font-bold text-blue-600"><?php echo count($minhas_tarefas['pendente']) + count($minhas_tarefas['em_andamento']); ?></p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">Ativas para trabalhar</p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Em Andamento</p>
                        <p class="text-2xl font-bold text-orange-600"><?php echo count($minhas_tarefas['em_andamento']); ?></p>
                    </div>
                    <div class="bg-orange-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">Trabalhando agora</p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Concluídas</p>
                        <p class="text-2xl font-bold text-green-600"><?php echo count($minhas_tarefas['concluida']); ?></p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">Finalizadas</p>
            </div>
        </div>
        <?php endif; ?>

        <!-- Alertas e Notificações -->
        <?php if($tarefas_em_atraso > 0 || $tarefas_proximas_prazo > 0): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <?php if($tarefas_em_atraso > 0): ?>
            <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Tarefas em Atraso</h3>
                        <p class="text-sm text-red-700">
                            <?php echo $tarefas_em_atraso; ?> tarefa(s) passaram do prazo e precisam de atenção.
                        </p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if($tarefas_proximas_prazo > 0): ?>
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Prazos Próximos</h3>
                        <p class="text-sm text-yellow-700">
                            <?php echo $tarefas_proximas_prazo; ?> tarefa(s) vencem nos próximos 7 dias.
                        </p>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Layout em duas colunas -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Minhas Tarefas -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">📋 Minhas Tarefas</h3>
                
                <div class="space-y-4">
                    <!-- Pendentes -->
                    <div class="border-l-4 border-yellow-400 pl-4">
                        <h4 class="font-medium text-yellow-800 mb-2">Pendentes (<?php echo count($minhas_tarefas['pendente']); ?>)</h4>
                        <?php if(empty($minhas_tarefas['pendente'])): ?>
                            <p class="text-sm text-gray-500">Nenhuma tarefa pendente</p>
                        <?php else: ?>
                            <?php foreach(array_slice($minhas_tarefas['pendente'], 0, 3) as $tarefa): ?>
                            <div class="bg-yellow-50 p-2 rounded mb-2">
                                <p class="text-sm font-medium"><?php echo htmlspecialchars($tarefa['nome']); ?></p>
                                <p class="text-xs text-gray-600"><?php echo htmlspecialchars($tarefa['projeto_nome']); ?></p>
                            </div>
                            <?php endforeach; ?>
                            <?php if(count($minhas_tarefas['pendente']) > 3): ?>
                            <p class="text-xs text-gray-500">E mais <?php echo count($minhas_tarefas['pendente']) - 3; ?>...</p>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>

                    <!-- Em Andamento -->
                    <div class="border-l-4 border-blue-400 pl-4">
                        <h4 class="font-medium text-blue-800 mb-2">Em Andamento (<?php echo count($minhas_tarefas['em_andamento']); ?>)</h4>
                        <?php if(empty($minhas_tarefas['em_andamento'])): ?>
                            <p class="text-sm text-gray-500">Nenhuma tarefa em andamento</p>
                        <?php else: ?>
                            <?php foreach(array_slice($minhas_tarefas['em_andamento'], 0, 3) as $tarefa): ?>
                            <div class="bg-blue-50 p-2 rounded mb-2">
                                <p class="text-sm font-medium"><?php echo htmlspecialchars($tarefa['nome']); ?></p>
                                <p class="text-xs text-gray-600"><?php echo htmlspecialchars($tarefa['projeto_nome']); ?></p>
                            </div>
                            <?php endforeach; ?>
                            <?php if(count($minhas_tarefas['em_andamento']) > 3): ?>
                            <p class="text-xs text-gray-500">E mais <?php echo count($minhas_tarefas['em_andamento']) - 3; ?>...</p>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>

                    <!-- Concluídas -->
                    <div class="border-l-4 border-green-400 pl-4">
                        <h4 class="font-medium text-green-800 mb-2">Recém Concluídas (<?php echo count($minhas_tarefas['concluida']); ?>)</h4>
                        <?php if(empty($minhas_tarefas['concluida'])): ?>
                            <p class="text-sm text-gray-500">Nenhuma tarefa concluída</p>
                        <?php else: ?>
                            <?php foreach(array_slice($minhas_tarefas['concluida'], 0, 2) as $tarefa): ?>
                            <div class="bg-green-50 p-2 rounded mb-2">
                                <p class="text-sm font-medium"><?php echo htmlspecialchars($tarefa['nome']); ?></p>
                                <p class="text-xs text-gray-600"><?php echo htmlspecialchars($tarefa['projeto_nome']); ?></p>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Progresso Geral -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">📊 Progresso Geral</h3>
                
                <!-- Barra de Progresso -->
                <div class="mb-4">
                    <div class="flex justify-between text-sm mb-1">
                        <span>Progresso das Tarefas</span>
                        <span><?php echo $progresso_geral; ?>%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: <?php echo $progresso_geral; ?>%"></div>
                    </div>
                </div>

                <!-- Distribuição por Status -->
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-yellow-400 rounded mr-2"></div>
                            <span class="text-sm">Pendentes</span>
                        </div>
                        <span class="text-sm font-medium"><?php echo $tarefas_por_status['pendente']; ?></span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-blue-500 rounded mr-2"></div>
                            <span class="text-sm">Em Andamento</span>
                        </div>
                        <span class="text-sm font-medium"><?php echo $tarefas_por_status['em_andamento']; ?></span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-500 rounded mr-2"></div>
                            <span class="text-sm">Concluídas</span>
                        </div>
                        <span class="text-sm font-medium"><?php echo $tarefas_por_status['concluida']; ?></span>
                    </div>
                </div>
            </div>
        </div>        <!-- Seção de Insights -->
        <?php if($grupo_usuario === 'gerente'): ?>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Tarefas Recentes -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">🕒 Atividade Recente</h3>
                
                <?php if(empty($tarefas_recentes)): ?>
                    <p class="text-sm text-gray-500">Nenhuma atividade recente</p>
                <?php else: ?>
                    <div class="space-y-3">
                        <?php foreach($tarefas_recentes as $tarefa): ?>
                        <div class="border-l-2 border-gray-300 pl-3">
                            <p class="text-sm font-medium"><?php echo htmlspecialchars($tarefa['nome']); ?></p>
                            <p class="text-xs text-gray-500">
                                <?php echo htmlspecialchars($tarefa['projeto_nome']); ?> • 
                                <?php echo htmlspecialchars($tarefa['responsavel_nome']); ?>
                            </p>
                            <p class="text-xs text-gray-400">
                                <?php echo date('d/m/Y H:i', strtotime($tarefa['data_criacao'])); ?>
                            </p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Projetos em Destaque -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">🏆 Projetos em Destaque</h3>
                
                <?php if(empty($projetos_destaque)): ?>
                    <p class="text-sm text-gray-500">Nenhum projeto ativo</p>
                <?php else: ?>
                    <div class="space-y-3">
                        <?php foreach($projetos_destaque as $projeto): ?>
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm font-medium"><?php echo htmlspecialchars($projeto['nome']); ?></p>
                                <p class="text-xs text-gray-500"><?php echo $projeto['total_tarefas']; ?> tarefas ativas</p>
                            </div>
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-xs font-bold text-blue-600"><?php echo $projeto['total_tarefas']; ?></span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Top Performers -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">⭐ Top Performers</h3>
                
                <?php if(empty($usuarios_produtivos)): ?>
                    <p class="text-sm text-gray-500">Dados insuficientes</p>
                <?php else: ?>
                    <div class="space-y-3">
                        <?php foreach($usuarios_produtivos as $index => $usuario): ?>
                        <div class="flex justify-between items-center">
                            <div class="flex items-center">
                                <div class="w-6 h-6 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-xs font-bold text-white"><?php echo $index + 1; ?></span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium"><?php echo htmlspecialchars($usuario['nome']); ?></p>
                                    <p class="text-xs text-gray-500"><?php echo $usuario['tarefas_concluidas']; ?> concluídas</p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</main>