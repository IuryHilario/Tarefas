<?php
require_once __DIR__ . '/../controller/cadastrarTarefa.php';
require_once __DIR__ . '/../controller/permissoes.php';
verificarAcessoTela('cadastrarTarefa.php');
?>

<main class="flex-1 p-6">
    <div class="bg-gray-100 p-4">
        <div class="max-w-7xl mx-auto bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold text-gray-700 text-center mb-6">Gerenciar Tarefas</h2>
            
            <?php if ($mensagem): ?>
            <div class="mb-4 p-3 <?php echo (strpos($mensagem, 'sucesso') !== false) ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?> rounded">
                <?php echo htmlspecialchars($mensagem); ?>
            </div>
            <?php endif; ?>

            <!-- Formulário para adicionar tarefa -->
            <div class="bg-gray-50 p-6 rounded-lg mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Criar Nova Tarefa</h3>
                <form method="POST" action="" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <input type="hidden" name="projeto" value="cadastrarTarefa.php">
                    
                    <div class="lg:col-span-2">
                        <label class="block text-gray-700 font-semibold mb-1" for="nome">Nome da Tarefa*</label>
                        <input type="text" id="nome" name="nome" class="w-full p-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-400" required autocomplete="off">
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1" for="projeto_id">Projeto*</label>
                        <select id="projeto_id" name="projeto_id" class="w-full p-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-400" required>
                            <option value="">Selecione um projeto</option>
                            <?php foreach ($projetos as $projeto): ?>
                            <option value="<?php echo $projeto['id']; ?>"><?php echo htmlspecialchars($projeto['nome']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1" for="responsavel_id">Responsável*</label>
                        <select id="responsavel_id" name="responsavel_id" class="w-full p-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-400" required>
                            <option value="">Selecione um responsável</option>
                            <?php foreach ($usuarios as $usuario): ?>
                            <option value="<?php echo $usuario['id']; ?>"><?php echo htmlspecialchars($usuario['nome']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1" for="status">Status</label>
                        <select id="status" name="status" class="w-full p-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-400">
                            <option value="pendente">Pendente</option>
                            <option value="em_andamento">Em Andamento</option>
                            <option value="concluida">Concluída</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1" for="prioridade">Prioridade</label>
                        <select id="prioridade" name="prioridade" class="w-full p-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-400">
                            <option value="baixa">Baixa</option>
                            <option value="media" selected>Média</option>
                            <option value="alta">Alta</option>
                            <option value="urgente">Urgente</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1" for="data_prazo">Data Prazo</label>
                        <input type="date" id="data_prazo" name="data_prazo" class="w-full p-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-400">
                    </div>
                    
                    <div class="lg:col-span-3">
                        <label class="block text-gray-700 font-semibold mb-1" for="descricao">Descrição</label>
                        <textarea id="descricao" name="descricao" rows="3" class="w-full p-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-400" placeholder="Descreva a tarefa..."></textarea>
                    </div>
                    
                    <div class="lg:col-span-3 text-center">
                        <button type="submit" name="adicionar_tarefa" value="1" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                            Criar Tarefa
                        </button>
                    </div>
                </form>
            </div>

            <!-- Lista de tarefas -->
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Tarefas Cadastradas</h3>
                
                <?php if (empty($tarefas)): ?>
                <div class="text-center py-8 text-gray-500">
                    <p>Nenhuma tarefa encontrada. Crie a primeira tarefa acima.</p>
                </div>
                <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full bg-white border rounded-lg">
                        <thead class="bg-blue-50">
                            <tr>
                                <th class="p-3 text-left text-sm font-semibold text-gray-700">Tarefa</th>
                                <th class="p-3 text-left text-sm font-semibold text-gray-700">Projeto</th>
                                <th class="p-3 text-left text-sm font-semibold text-gray-700">Responsável</th>
                                <th class="p-3 text-left text-sm font-semibold text-gray-700">Status</th>
                                <th class="p-3 text-left text-sm font-semibold text-gray-700">Prioridade</th>
                                <th class="p-3 text-left text-sm font-semibold text-gray-700">Prazo</th>
                                <th class="p-3 text-left text-sm font-semibold text-gray-700">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tarefas as $tarefa): ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3">
                                    <div>
                                        <p class="font-medium text-gray-900"><?php echo htmlspecialchars($tarefa['nome']); ?></p>
                                        <?php if ($tarefa['descricao']): ?>
                                        <p class="text-sm text-gray-600 mt-1"><?php echo htmlspecialchars(substr($tarefa['descricao'], 0, 100)) . (strlen($tarefa['descricao']) > 100 ? '...' : ''); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="p-3 text-sm text-gray-700"><?php echo htmlspecialchars($tarefa['projeto_nome'] ?: 'N/A'); ?></td>
                                <td class="p-3 text-sm text-gray-700"><?php echo htmlspecialchars($tarefa['responsavel_nome'] ?: 'N/A'); ?></td>                                <td class="p-3">
                                    <span class="px-2 py-1 text-xs rounded-full whitespace-nowrap
                                        <?php 
                                        switch($tarefa['status']) {
                                            case 'pendente': echo 'bg-yellow-100 text-yellow-800'; break;
                                            case 'em_andamento': echo 'bg-blue-100 text-blue-600'; break;
                                            case 'concluida': echo 'bg-green-100 text-green-800'; break;
                                        }
                                        ?>">
                                        <?php 
                                        switch($tarefa['status']) {
                                            case 'pendente': echo 'Pendente'; break;
                                            case 'em_andamento': echo 'Em Andamento'; break;
                                            case 'concluida': echo 'Concluída'; break;
                                        }
                                        ?>
                                    </span>
                                </td>
                                <td class="p-3">
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        <?php 
                                        switch($tarefa['prioridade']) {
                                            case 'baixa': echo 'bg-gray-100 text-gray-800'; break;
                                            case 'media': echo 'bg-blue-100 text-blue-800'; break;
                                            case 'alta': echo 'bg-orange-100 text-orange-800'; break;
                                            case 'urgente': echo 'bg-red-100 text-red-800'; break;
                                        }
                                        ?>">
                                        <?php echo ucfirst($tarefa['prioridade']); ?>
                                    </span>
                                </td>
                                <td class="p-3 text-sm text-gray-700">
                                    <?php echo $tarefa['data_prazo'] ? date('d/m/Y', strtotime($tarefa['data_prazo'])) : 'Sem prazo'; ?>
                                </td>
                                <td class="p-3">
                                    <div class="flex gap-1">
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="id" value="<?php echo $tarefa['id']; ?>">
                                            <input type="hidden" name="projeto" value="cadastrarTarefa.php">
                                            <button type="submit" name="carregar_tarefa" value="1" class="px-2 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">
                                                Editar
                                            </button>
                                        </form>
                                        
                                        <form method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja excluir esta tarefa?');">
                                            <input type="hidden" name="id" value="<?php echo $tarefa['id']; ?>">
                                            <input type="hidden" name="projeto" value="cadastrarTarefa.php">
                                            <button type="submit" name="excluir_tarefa" value="1" class="px-2 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">
                                                Excluir
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<!-- Modal de Edição -->
<?php if ($tarefa_editando): ?>
<div id="modalEdicao" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-2xl w-full mx-4 max-h-screen overflow-y-auto">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Editar Tarefa</h3>
        
        <form method="POST" action="" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input type="hidden" name="id" value="<?php echo $tarefa_editando['id']; ?>">
            <input type="hidden" name="projeto" value="cadastrarTarefa.php">
            
            <div class="md:col-span-2">
                <label class="block text-gray-700 font-semibold mb-1" for="edit_nome">Nome da Tarefa*</label>
                <input type="text" id="edit_nome" name="nome" value="<?php echo htmlspecialchars($tarefa_editando['nome']); ?>" class="w-full p-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-400" required autocomplete="off">
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-1" for="edit_projeto_id">Projeto*</label>
                <select id="edit_projeto_id" name="projeto_id" class="w-full p-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-400" required>
                    <option value="">Selecione um projeto</option>
                    <?php foreach ($projetos as $projeto): ?>
                    <option value="<?php echo $projeto['id']; ?>" <?php echo ($tarefa_editando['projeto_id'] == $projeto['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($projeto['nome']); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-1" for="edit_responsavel_id">Responsável*</label>
                <select id="edit_responsavel_id" name="responsavel_id" class="w-full p-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-400" required>
                    <option value="">Selecione um responsável</option>
                    <?php foreach ($usuarios as $usuario): ?>
                    <option value="<?php echo $usuario['id']; ?>" <?php echo ($tarefa_editando['responsavel_id'] == $usuario['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($usuario['nome']); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-1" for="edit_status">Status</label>
                <select id="edit_status" name="status" class="w-full p-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-400">
                    <option value="pendente" <?php echo ($tarefa_editando['status'] === 'pendente') ? 'selected' : ''; ?>>Pendente</option>
                    <option value="em_andamento" <?php echo ($tarefa_editando['status'] === 'em_andamento') ? 'selected' : ''; ?>>Em Andamento</option>
                    <option value="concluida" <?php echo ($tarefa_editando['status'] === 'concluida') ? 'selected' : ''; ?>>Concluída</option>
                </select>
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-1" for="edit_prioridade">Prioridade</label>
                <select id="edit_prioridade" name="prioridade" class="w-full p-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-400">
                    <option value="baixa" <?php echo ($tarefa_editando['prioridade'] === 'baixa') ? 'selected' : ''; ?>>Baixa</option>
                    <option value="media" <?php echo ($tarefa_editando['prioridade'] === 'media') ? 'selected' : ''; ?>>Média</option>
                    <option value="alta" <?php echo ($tarefa_editando['prioridade'] === 'alta') ? 'selected' : ''; ?>>Alta</option>
                    <option value="urgente" <?php echo ($tarefa_editando['prioridade'] === 'urgente') ? 'selected' : ''; ?>>Urgente</option>
                </select>
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-1" for="edit_data_prazo">Data Prazo</label>
                <input type="date" id="edit_data_prazo" name="data_prazo" value="<?php echo $tarefa_editando['data_prazo']; ?>" class="w-full p-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-400">
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-gray-700 font-semibold mb-1" for="edit_descricao">Descrição</label>
                <textarea id="edit_descricao" name="descricao" rows="3" class="w-full p-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-400"><?php echo htmlspecialchars($tarefa_editando['descricao']); ?></textarea>
            </div>
            
            <div class="md:col-span-2 flex gap-2 justify-end">
                <button type="button" onclick="fecharModal()" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                    Cancelar
                </button>
                <button type="submit" name="editar_tarefa" value="1" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    Salvar Alterações
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
document.getElementById('projeto_id').value = '';
document.getElementById('responsavel_id').value = '';
document.getElementById('status').value = 'pendente';
document.getElementById('prioridade').value = 'media';
document.getElementById('data_prazo').value = '';
<?php endif; ?>
</script>