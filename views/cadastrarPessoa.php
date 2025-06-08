<?php
require_once __DIR__ . '/../controller/permissoes.php';
verificarAcessoTela('cadastrarPessoa.php');
require_once __DIR__ . '/../controller/cadastrarPessoa.php';
?>
<main class="flex-1 p-6">
    <div class="bg-gray-100 p-4">
        <div class="max-w-4xl mx-auto bg-white p-4 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold text-gray-700 text-center">Gerenciar Usuários</h2>
            <?php if (isset($mensagem)): ?>
            <div class="mb-4 p-3 <?php echo (strpos($mensagem, 'sucesso') !== false) ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?> rounded">
                <?php echo htmlspecialchars($mensagem); ?>
            </div>
            <?php endif; ?>            <form method="POST" action="" class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <input type="hidden" name="id" id="usuarioId">
                <input type="hidden" name="projeto" value="cadastrarPessoa.php">
                <div>
                    <label class="block text-gray-700 font-semibold mb-1" for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" class="w-full mt-1 p-2 border rounded-lg focus:outline-none focus:ring focus:border-green-400" required autocomplete="off">
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-1" for="email">Email</label>
                    <input type="email" id="email" name="email" class="w-full mt-1 p-2 border rounded-lg focus:outline-none focus:ring focus:border-green-400" required autocomplete="off">
                </div>                <div>
                    <label class="block text-gray-700 font-semibold mb-1" for="grupo">Grupo</label>
                    <select id="grupo" name="grupo" class="w-full mt-1 p-2 border rounded-lg focus:outline-none focus:ring focus:border-green-400" required>
                        <option value="" disabled selected>Selecione um grupo</option>
                        <option value="gerente">Gerente</option>
                        <option value="dev">Desenvolvedor</option>
                    </select>
                </div><div class="justify-self-center md:col-span-3">
                    <button type="submit" name="cadastrar" value="1" class="w-64 mt-4 p-2 text-white bg-green-500 rounded-lg hover:bg-green-600">Adicionar</button>
                </div>
            </form>
            <div class="mt-6">
                <hr>
                <h3 class="py-4 justify-self-center text-xl font-semibold text-gray-700">Usuários Cadastrados</h3>
                <table class="w-full mt-2 bg-white border rounded-lg">
                    <thead>
                        <tr class="bg-green-100">
                            <th class="p-2 border text-left">Nome</th>
                            <th class="p-2 border text-left">Email</th>
                            <th class="p-2 border text-left">Grupo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $usuario): ?>
                        <tr class="border-b hover:bg-green-50">
                            <td class="p-2 border text-left"><?php echo htmlspecialchars($usuario['nome']); ?></td>
                            <td class="p-2 border text-left"><?php echo htmlspecialchars($usuario['email']); ?></td>
                            <td class="p-2 border text-left"><?php echo htmlspecialchars($usuario['grupo']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<script>
// Limpar formulário após sucesso
<?php if (isset($mensagem) && strpos($mensagem, 'sucesso') !== false): ?>
document.getElementById('nome').value = '';
document.getElementById('email').value = '';
document.getElementById('grupo').value = '';
<?php endif; ?>
</script>
