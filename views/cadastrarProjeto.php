<?php
require_once __DIR__ . '/../controller/permissoes.php';
verificarAcessoTela('cadastrarProjeto.php');
require_once __DIR__ . '/../controller/cadastrarProjeto.php';
?>

<main class="flex-1 p-6">
    <div class="bg-gray-100 p-4">
        <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold text-gray-700 text-center mb-6">Gerenciar Projetos</h2>
            
            <?php if ($mensagem): ?>
            <div class="mb-4 p-3 <?php echo (strpos($mensagem, 'sucesso') !== false) ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?> rounded">
                <?php echo htmlspecialchars($mensagem); ?>
            </div>
            <?php endif; ?>

            <!-- Formulário para adicionar projeto -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Adicionar Novo Projeto</h3>
                <form method="POST" action="" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="hidden" name="projeto" value="cadastrarProjeto.php">
                    
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1" for="nome">Nome do Projeto</label>
                        <input type="text" id="nome" name="nome" class="w-full p-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-400" required autocomplete="off">
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1" for="status">Status</label>
                        <select id="status" name="status" class="w-full p-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-400">
                            <option value="ativo">Ativo</option>
                            <option value="pausado">Pausado</option>
                            <option value="concluido">Concluído</option>
                        </select>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 font-semibold mb-1" for="descricao">Descrição</label>
                        <textarea id="descricao" name="descricao" rows="3" class="w-full p-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-400" placeholder="Descreva o projeto..."></textarea>
                    </div>
                    
                    <div class="md:col-span-2 text-center">
                        <button type="submit" name="adicionar_projeto" value="1" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                            Adicionar Projeto
                        </button>
                    </div>
                </form>
            </div>

            <!-- Lista de projetos -->
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Projetos Existentes</h3>
                
                <?php if (empty($projetos)): ?>
                <div class="text-center py-8 text-gray-500">
                    <p>Nenhum projeto encontrado. Adicione o primeiro projeto acima.</p>
                </div>
                <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php foreach ($projetos as $projeto): ?>
                    <div class="border rounded-lg p-4 bg-white shadow hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="text-lg font-semibold text-gray-800"><?php echo htmlspecialchars($projeto['nome']); ?></h4>
                            <span class="px-2 py-1 text-xs rounded-full 
                                <?php 
                                switch($projeto['status']) {
                                    case 'ativo': echo 'bg-green-100 text-green-800'; break;
                                    case 'pausado': echo 'bg-yellow-100 text-yellow-800'; break;
                                    case 'concluido': echo 'bg-blue-100 text-blue-800'; break;
                                }
                                ?>">
                                <?php echo ucfirst($projeto['status']); ?>
                            </span>
                        </div>
                        
                        <p class="text-gray-600 text-sm mb-3 line-clamp-3">
                            <?php echo htmlspecialchars($projeto['descricao'] ?: 'Sem descrição'); ?>
                        </p>
                        
                        <div class="text-xs text-gray-500 mb-3">
                            <p>Criado por: <?php echo htmlspecialchars($projeto['criador'] ?: 'Desconhecido'); ?></p>
                            <p>Data: <?php echo date('d/m/Y H:i', strtotime($projeto['data_criacao'])); ?></p>
                        </div>
                        
                        <div class="flex gap-2">
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="id" value="<?php echo $projeto['id']; ?>">
                                <input type="hidden" name="projeto" value="cadastrarProjeto.php">
                                <button type="submit" name="carregar_projeto" value="1" class="px-3 py-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600">
                                    Editar
                                </button>
                            </form>
                            
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja excluir este projeto?');">
                                <input type="hidden" name="id" value="<?php echo $projeto['id']; ?>">
                                <input type="hidden" name="projeto" value="cadastrarProjeto.php">
                                <button type="submit" name="excluir_projeto" value="1" class="px-3 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600">
                                    Excluir
                                </button>
                            </form>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<!-- Modal de Edição -->
<?php if ($projeto_editando): ?>
<div id="modalEdicao" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full mx-4">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Editar Projeto</h3>
        
        <form method="POST" action="">
            <input type="hidden" name="id" value="<?php echo $projeto_editando['id']; ?>">
            <input type="hidden" name="projeto" value="cadastrarProjeto.php">
            
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1" for="edit_nome">Nome do Projeto</label>
                <input type="text" id="edit_nome" name="nome" value="<?php echo htmlspecialchars($projeto_editando['nome']); ?>" class="w-full p-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-400" required autocomplete="off">
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1" for="edit_status">Status</label>
                <select id="edit_status" name="status" class="w-full p-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-400">
                    <option value="ativo" <?php echo ($projeto_editando['status'] === 'ativo') ? 'selected' : ''; ?>>Ativo</option>
                    <option value="pausado" <?php echo ($projeto_editando['status'] === 'pausado') ? 'selected' : ''; ?>>Pausado</option>
                    <option value="concluido" <?php echo ($projeto_editando['status'] === 'concluido') ? 'selected' : ''; ?>>Concluído</option>
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1" for="edit_descricao">Descrição</label>
                <textarea id="edit_descricao" name="descricao" rows="3" class="w-full p-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-400"><?php echo htmlspecialchars($projeto_editando['descricao']); ?></textarea>
            </div>
            
            <div class="flex gap-2 justify-end">
                <button type="button" onclick="fecharModal()" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                    Cancelar
                </button>
                <button type="submit" name="editar_projeto" value="1" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    Salvar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function fecharModal() {
    document.getElementById('modalEdicao').style.display = 'none';
}

// Fechar modal ao clicar fora
document.getElementById('modalEdicao').addEventListener('click', function(e) {
    if (e.target === this) {
        fecharModal();
    }
});
</script>
<?php endif; ?>

<script>
// Limpar formulário após sucesso
<?php if (isset($mensagem) && strpos($mensagem, 'sucesso') !== false): ?>
document.getElementById('nome').value = '';
document.getElementById('descricao').value = '';
document.getElementById('status').value = 'ativo';
<?php endif; ?>
</script>