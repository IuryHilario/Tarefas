<?php
require_once __DIR__ . '/../controller/quadroKanban.php';
?>

<link rel="stylesheet" href="assets/css/kanban.css">

<main class="flex-1 p-6">
    <div class="bg-gray-100 min-h-screen">        <!-- Header com estatísticas -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <div class="flex flex-col lg:flex-row justify-between items-center mb-4">
                <h2 class="text-2xl font-bold text-gray-700 mb-4 lg:mb-0">Quadro Kanban</h2>
                  <!-- Filtro por Projeto -->
                <div class="flex items-center space-x-4">
                    <label for="projeto-filter" class="text-sm font-medium text-gray-700">Filtrar por Projeto:</label>
                    <select id="projeto-filter" class="border border-gray-300 rounded-lg px-3 py-2 bg-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Todos os Projetos</option>
                        <?php foreach ($projetos as $projeto): ?>
                            <option value="<?php echo $projeto['id']; ?>" 
                                    data-total="<?php echo $projeto['total_tarefas']; ?>">
                                <?php echo htmlspecialchars($projeto['nome']); ?> 
                                (<?php echo $projeto['total_tarefas']; ?> tarefas)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button id="clear-filter" class="bg-gray-500 text-white px-3 py-2 rounded-lg text-sm hover:bg-gray-600 transition-colors">
                        Limpar
                    </button>
                    <button id="refresh-kanban" class="bg-blue-500 text-white px-3 py-2 rounded-lg text-sm hover:bg-blue-600 transition-colors">
                        🔄 Atualizar
                    </button>
                </div>
            </div>
            
            <!-- Estatísticas -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-blue-50 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-blue-600"><?php echo $stats['total_tarefas']; ?></div>
                    <div class="text-sm text-blue-800">Total de Tarefas</div>
                </div>
                <div class="bg-yellow-50 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-yellow-600"><?php echo $stats['pendentes']; ?></div>
                    <div class="text-sm text-yellow-800">Pendentes</div>
                </div>
                <div class="bg-blue-50 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-blue-600"><?php echo $stats['em_andamento']; ?></div>
                    <div class="text-sm text-blue-800">Em Andamento</div>
                </div>
                <div class="bg-green-50 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-green-600"><?php echo $stats['concluidas']; ?></div>
                    <div class="text-sm text-green-800">Concluídas</div>
                </div>
            </div>
        </div>

        <!-- Quadro Kanban -->
        <div class="kanban-board">
            <!-- Coluna Pendente -->
            <div class="kanban-column" data-status="pendente">
                <h2 class="kanban-header">
                    <span class="status-icon">📋</span>
                    A Fazer
                    <span class="task-count">(<?php echo count($tarefas_por_status['pendente']); ?>)</span>
                </h2>
                <div class="kanban-tasks" id="pendente">
                    <?php if (!empty($tarefas_por_status['pendente'])): ?>                        <?php foreach ($tarefas_por_status['pendente'] as $tarefa): ?>
                            <div class="kanban-task" 
                                 draggable="true" 
                                 data-id="<?php echo $tarefa['id']; ?>" 
                                 data-projeto="<?php echo $tarefa['projeto_id']; ?>"
                                 data-prioridade="<?php echo $tarefa['prioridade']; ?>">
                                <div class="task-header">
                                    <h4 class="task-title"><?php echo htmlspecialchars($tarefa['nome']); ?></h4>
                                    <span class="priority-badge priority-<?php echo $tarefa['prioridade']; ?>">
                                        <?php echo ucfirst($tarefa['prioridade']); ?>
                                    </span>
                                </div>
                                <?php if ($tarefa['descricao']): ?>
                                <p class="task-description"><?php echo htmlspecialchars(substr($tarefa['descricao'], 0, 100)) . (strlen($tarefa['descricao']) > 100 ? '...' : ''); ?></p>
                                <?php endif; ?>
                                <div class="task-meta">
                                    <div class="task-project-info">
                                        <span class="task-project-label">📂</span>
                                        <span class="task-project"><?php echo htmlspecialchars($tarefa['projeto_nome']); ?></span>
                                    </div>
                                    <div class="task-responsible-info">
                                        <span class="task-responsible-label">👤</span>
                                        <span class="task-responsible"><?php echo htmlspecialchars($tarefa['responsavel_nome']); ?></span>
                                    </div>
                                </div>
                                <?php if ($tarefa['data_prazo']): ?>
                                <div class="task-deadline">
                                    📅 <?php echo date('d/m/Y', strtotime($tarefa['data_prazo'])); ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-column">Nenhuma tarefa pendente</div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Coluna Em Andamento -->
            <div class="kanban-column" data-status="em_andamento">
                <h2 class="kanban-header">
                    <span class="status-icon">⚡</span>
                    Em Progresso
                    <span class="task-count">(<?php echo count($tarefas_por_status['em_andamento']); ?>)</span>
                </h2>
                <div class="kanban-tasks" id="em_andamento">
                    <?php if (!empty($tarefas_por_status['em_andamento'])): ?>                        <?php foreach ($tarefas_por_status['em_andamento'] as $tarefa): ?>
                            <div class="kanban-task" 
                                 draggable="true" 
                                 data-id="<?php echo $tarefa['id']; ?>" 
                                 data-projeto="<?php echo $tarefa['projeto_id']; ?>"
                                 data-prioridade="<?php echo $tarefa['prioridade']; ?>">
                                <div class="task-header">
                                    <h4 class="task-title"><?php echo htmlspecialchars($tarefa['nome']); ?></h4>
                                    <span class="priority-badge priority-<?php echo $tarefa['prioridade']; ?>">
                                        <?php echo ucfirst($tarefa['prioridade']); ?>
                                    </span>
                                </div>
                                <?php if ($tarefa['descricao']): ?>
                                <p class="task-description"><?php echo htmlspecialchars(substr($tarefa['descricao'], 0, 100)) . (strlen($tarefa['descricao']) > 100 ? '...' : ''); ?></p>
                                <?php endif; ?>
                                <div class="task-meta">
                                    <div class="task-project-info">
                                        <span class="task-project-label">📂</span>
                                        <span class="task-project"><?php echo htmlspecialchars($tarefa['projeto_nome']); ?></span>
                                    </div>
                                    <div class="task-responsible-info">
                                        <span class="task-responsible-label">👤</span>
                                        <span class="task-responsible"><?php echo htmlspecialchars($tarefa['responsavel_nome']); ?></span>
                                    </div>
                                </div>
                                <?php if ($tarefa['data_prazo']): ?>
                                <div class="task-deadline">
                                    📅 <?php echo date('d/m/Y', strtotime($tarefa['data_prazo'])); ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-column">Nenhuma tarefa em andamento</div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Coluna Concluída -->
            <div class="kanban-column" data-status="concluida">
                <h2 class="kanban-header">
                    <span class="status-icon">✅</span>
                    Concluído
                    <span class="task-count">(<?php echo count($tarefas_por_status['concluida']); ?>)</span>
                </h2>
                <div class="kanban-tasks" id="concluida">
                    <?php if (!empty($tarefas_por_status['concluida'])): ?>                        <?php foreach ($tarefas_por_status['concluida'] as $tarefa): ?>
                            <div class="kanban-task" 
                                 draggable="true" 
                                 data-id="<?php echo $tarefa['id']; ?>" 
                                 data-projeto="<?php echo $tarefa['projeto_id']; ?>"
                                 data-prioridade="<?php echo $tarefa['prioridade']; ?>">
                                <div class="task-header">
                                    <h4 class="task-title"><?php echo htmlspecialchars($tarefa['nome']); ?></h4>
                                    <span class="priority-badge priority-<?php echo $tarefa['prioridade']; ?>">
                                        <?php echo ucfirst($tarefa['prioridade']); ?>
                                    </span>
                                </div>
                                <?php if ($tarefa['descricao']): ?>
                                <p class="task-description"><?php echo htmlspecialchars(substr($tarefa['descricao'], 0, 100)) . (strlen($tarefa['descricao']) > 100 ? '...' : ''); ?></p>
                                <?php endif; ?>
                                <div class="task-meta">
                                    <div class="task-project-info">
                                        <span class="task-project-label">📂</span>
                                        <span class="task-project"><?php echo htmlspecialchars($tarefa['projeto_nome']); ?></span>
                                    </div>
                                    <div class="task-responsible-info">
                                        <span class="task-responsible-label">👤</span>
                                        <span class="task-responsible"><?php echo htmlspecialchars($tarefa['responsavel_nome']); ?></span>
                                    </div>
                                </div>
                                <?php if ($tarefa['data_prazo']): ?>
                                <div class="task-deadline">
                                    📅 <?php echo date('d/m/Y', strtotime($tarefa['data_prazo'])); ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-column">Nenhuma tarefa concluída</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
// JavaScript para drag & drop
document.addEventListener('DOMContentLoaded', function() {
    const tasks = document.querySelectorAll('.kanban-task');
    const columns = document.querySelectorAll('.kanban-tasks');
    
    let draggedTask = null;
    
    // Configurar eventos de drag para as tarefas
    tasks.forEach(task => {
        task.addEventListener('dragstart', function(e) {
            draggedTask = this;
            this.style.opacity = '0.5';
        });
        
        task.addEventListener('dragend', function(e) {
            this.style.opacity = '1';
            draggedTask = null;
        });
    });
    
    // Configurar eventos de drop para as colunas
    columns.forEach(column => {
        column.addEventListener('dragover', function(e) {
            e.preventDefault();
        });
        
        column.addEventListener('drop', function(e) {
            e.preventDefault();
            
            if (draggedTask) {
                const taskId = draggedTask.getAttribute('data-id');
                const newStatus = this.id;
                
                // Mover visualmente a tarefa
                this.appendChild(draggedTask);
                
                // Atualizar no banco de dados
                updateTaskStatus(taskId, newStatus);
            }
        });
    });
      function updateTaskStatus(taskId, newStatus) {
        console.log(`Atualizando tarefa ${taskId} para status ${newStatus}`);
        
        const formData = new FormData();
        formData.append('atualizar_status', '1');
        formData.append('tarefa_id', taskId);
        formData.append('novo_status', newStatus);
        
        fetch('controller/kanban_ajax.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Resposta do servidor:', data);
            
            if (data.success) {
                console.log('Status atualizado com sucesso!');
                
                // Atualizar contadores e estatísticas
                setTimeout(() => {
                    updateCounters();
                    updateStats(data.stats);
                }, 100);
                
                // Mostrar feedback visual
                showNotification('Tarefa movida com sucesso!', 'success');
            } else {
                console.error('Erro do servidor:', data.message);
                showNotification('Erro: ' + data.message, 'error');
                // Reverter movimento da tarefa
                location.reload();
            }
        })
        .catch(error => {
            console.error('Erro na requisição:', error);
            showNotification('Erro de conexão. Recarregando...', 'error');
            setTimeout(() => location.reload(), 2000);
        });
    }
    
    function updateCounters() {
        // Atualizar contadores das colunas
        const pendentesCount = document.querySelectorAll('#pendente .kanban-task').length;
        const emAndamentoCount = document.querySelectorAll('#em_andamento .kanban-task').length;
        const concluidasCount = document.querySelectorAll('#concluida .kanban-task').length;
        
        console.log(`Contadores: Pendentes=${pendentesCount}, Em Andamento=${emAndamentoCount}, Concluídas=${concluidasCount}`);
        
        // Atualizar contadores nos cabeçalhos das colunas
        const pendentesHeader = document.querySelector('[data-status="pendente"] .task-count');
        const emAndamentoHeader = document.querySelector('[data-status="em_andamento"] .task-count');
        const concluidasHeader = document.querySelector('[data-status="concluida"] .task-count');
        
        if (pendentesHeader) pendentesHeader.textContent = `(${pendentesCount})`;
        if (emAndamentoHeader) emAndamentoHeader.textContent = `(${emAndamentoCount})`;
        if (concluidasHeader) concluidasHeader.textContent = `(${concluidasCount})`;
        
        // Mostrar/ocultar mensagens de coluna vazia
        updateEmptyStates();
    }
    
    function updateStats(stats = null) {
        if (stats) {
            // Usar dados do servidor se disponíveis
            document.querySelector('.bg-blue-50:first-child .text-2xl').textContent = stats.total;
            document.querySelector('.bg-yellow-50 .text-2xl').textContent = stats.pendentes;
            document.querySelector('.bg-blue-50:nth-child(3) .text-2xl').textContent = stats.em_andamento;
            document.querySelector('.bg-green-50 .text-2xl').textContent = stats.concluidas;
        } else {
            // Calcular localmente
            const pendentesCount = document.querySelectorAll('#pendente .kanban-task').length;
            const emAndamentoCount = document.querySelectorAll('#em_andamento .kanban-task').length;
            const concluidasCount = document.querySelectorAll('#concluida .kanban-task').length;
            const totalCount = pendentesCount + emAndamentoCount + concluidasCount;
            
            // Atualizar os cards de estatística
            document.querySelector('.bg-blue-50:first-child .text-2xl').textContent = totalCount;
            document.querySelector('.bg-yellow-50 .text-2xl').textContent = pendentesCount;
            document.querySelector('.bg-blue-50:nth-child(3) .text-2xl').textContent = emAndamentoCount;
            document.querySelector('.bg-green-50 .text-2xl').textContent = concluidasCount;
        }
    }
    
    function updateEmptyStates() {
        const columns = ['pendente', 'em_andamento', 'concluida'];
        const messages = {
            'pendente': 'Nenhuma tarefa pendente',
            'em_andamento': 'Nenhuma tarefa em andamento', 
            'concluida': 'Nenhuma tarefa concluída'
        };
        
        columns.forEach(columnId => {
            const column = document.getElementById(columnId);
            if (!column) return;
            
            const tasks = column.querySelectorAll('.kanban-task');
            const emptyMessage = column.querySelector('.empty-column');
            
            if (tasks.length === 0) {
                if (!emptyMessage) {
                    const emptyDiv = document.createElement('div');
                    emptyDiv.className = 'empty-column';
                    emptyDiv.textContent = messages[columnId];
                    column.appendChild(emptyDiv);
                }
            } else {
                if (emptyMessage) {
                    emptyMessage.remove();
                }
            }
        });
    }
      function showNotification(message, type = 'info') {
        // Remover notificação anterior se existir
        const existingNotification = document.querySelector('.kanban-notification');
        if (existingNotification) {
            existingNotification.remove();
        }
        
        // Criar nova notificação
        const notification = document.createElement('div');
        notification.className = `kanban-notification ${type}`;
        notification.textContent = message;
        
        // Adicionar ao DOM
        document.body.appendChild(notification);
        
        // Remover após 3 segundos
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 3000);
    }

    // Filtro por projeto
    const projectFilter = document.getElementById('projeto-filter');
    const clearFilterBtn = document.getElementById('clear-filter');
    
    function filterTasksByProject(projectId = '') {
        const tasks = document.querySelectorAll('.kanban-task');
        let visibleTasks = { pendente: 0, em_andamento: 0, concluida: 0, total: 0 };
        
        tasks.forEach(task => {
            const taskProjectId = task.getAttribute('data-projeto');
            
            if (projectId === '' || taskProjectId === projectId) {
                task.style.display = 'block';
                
                // Contar tarefas visíveis por status
                const parentColumn = task.closest('.kanban-tasks');
                if (parentColumn) {
                    const status = parentColumn.id;
                    if (visibleTasks.hasOwnProperty(status)) {
                        visibleTasks[status]++;
                    }
                    visibleTasks.total++;
                }
            } else {
                task.style.display = 'none';
            }
        });
        
        // Atualizar contadores das colunas
        updateCountersWithFilter(visibleTasks);
        
        // Atualizar estatísticas
        updateStatsWithFilter(visibleTasks);
        
        // Atualizar estados vazios
        updateEmptyStatesWithFilter();
        
        // Feedback visual
        const selectedProject = projectFilter.options[projectFilter.selectedIndex].text;
        if (projectId !== '') {
            showNotification(`Filtrando por: ${selectedProject}`, 'info');
        }
    }
    
    function updateCountersWithFilter(counts) {
        const pendentesHeader = document.querySelector('[data-status="pendente"] .task-count');
        const emAndamentoHeader = document.querySelector('[data-status="em_andamento"] .task-count');
        const concluidasHeader = document.querySelector('[data-status="concluida"] .task-count');
        
        if (pendentesHeader) pendentesHeader.textContent = `(${counts.pendente})`;
        if (emAndamentoHeader) emAndamentoHeader.textContent = `(${counts.em_andamento})`;
        if (concluidasHeader) concluidasHeader.textContent = `(${counts.concluida})`;
    }
    
    function updateStatsWithFilter(counts) {
        // Atualizar os cards de estatística com contadores filtrados
        document.querySelector('.bg-blue-50:first-child .text-2xl').textContent = counts.total;
        document.querySelector('.bg-yellow-50 .text-2xl').textContent = counts.pendente;
        document.querySelector('.bg-blue-50:nth-child(3) .text-2xl').textContent = counts.em_andamento;
        document.querySelector('.bg-green-50 .text-2xl').textContent = counts.concluida;
    }
    
    function updateEmptyStatesWithFilter() {
        const columns = ['pendente', 'em_andamento', 'concluida'];
        const messages = {
            'pendente': 'Nenhuma tarefa pendente',
            'em_andamento': 'Nenhuma tarefa em andamento', 
            'concluida': 'Nenhuma tarefa concluída'
        };
        
        columns.forEach(columnId => {
            const column = document.getElementById(columnId);
            if (!column) return;
            
            const visibleTasks = Array.from(column.querySelectorAll('.kanban-task')).filter(task => 
                task.style.display !== 'none'
            );
            const emptyMessage = column.querySelector('.empty-column');
            
            if (visibleTasks.length === 0) {
                if (!emptyMessage) {
                    const emptyDiv = document.createElement('div');
                    emptyDiv.className = 'empty-column';
                    emptyDiv.textContent = messages[columnId];
                    column.appendChild(emptyDiv);
                }
                if (emptyMessage) emptyMessage.style.display = 'block';
            } else {
                if (emptyMessage) {
                    emptyMessage.style.display = 'none';
                }
            }
        });
    }
      // Event listeners para o filtro
    if (projectFilter) {
        projectFilter.addEventListener('change', function() {
            const selectedProjectId = this.value;
            filterTasksByProject(selectedProjectId);
        });
    }
    
    if (clearFilterBtn) {
        clearFilterBtn.addEventListener('click', function() {
            projectFilter.value = '';
            filterTasksByProject('');
            showNotification('Filtro removido', 'success');
        });
    }
    
    // Botão de atualizar/refresh
    const refreshBtn = document.getElementById('refresh-kanban');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function() {
            showNotification('Atualizando quadro...', 'info');
            location.reload();
        });
    }
    
    // Verificar se há parâmetro de projeto na URL
    const urlParams = new URLSearchParams(window.location.search);
    const projetoParam = urlParams.get('projeto');
    if (projetoParam && projectFilter) {
        projectFilter.value = projetoParam;
        filterTasksByProject(projetoParam);
    }
});
</script>

<style>
/* Estilos do Quadro Kanban */
.kanban-board {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-top: 20px;
}

.kanban-column {
    background: #f8fafc;
    border-radius: 12px;
    padding: 16px;
    min-height: 500px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.kanban-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 1.1rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 16px;
    padding-bottom: 8px;
    border-bottom: 2px solid #e5e7eb;
}

.kanban-task {
    background: white;
    border-radius: 8px;
    padding: 12px;
    margin-bottom: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    cursor: move;
    transition: all 0.2s ease;
    border-left: 4px solid #ddd;
}

.kanban-task:hover {
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    transform: translateY(-1px);
}

.task-title {
    font-weight: 600;
    color: #374151;
    font-size: 0.95rem;
    margin-bottom: 8px;
}

.priority-badge {
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
}

.priority-baixa { background: #f3f4f6; color: #6b7280; }
.priority-media { background: #dbeafe; color: #2563eb; }
.priority-alta { background: #fed7aa; color: #ea580c; }
.priority-urgente { background: #fecaca; color: #dc2626; }

.task-description {
    font-size: 0.85rem;
    color: #6b7280;
    margin-bottom: 8px;
}

.task-meta {
    font-size: 0.8rem;
    color: #059669;
    margin-bottom: 8px;
}

.task-deadline {
    font-size: 0.8rem;
    color: #dc2626;
    font-weight: 500;
}

.empty-column {
    text-align: center;
    color: #9ca3af;
    padding: 40px 20px;
    font-style: italic;
}

/* Notificações */
.kanban-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 12px 24px;
    border-radius: 8px;
    color: white;
    font-weight: 500;
    z-index: 1000;
    animation: slideIn 0.3s ease-out;
    max-width: 300px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.kanban-notification.success {
    background-color: #10b981;
}

.kanban-notification.error {
    background-color: #ef4444;
}

.kanban-notification.info {
    background-color: #3b82f6;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* Melhorar visual durante drag */
.kanban-task.dragging {
    transform: rotate(5deg);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.kanban-tasks.drag-over {
    background-color: #e0f2fe;
    border: 2px dashed #0284c7;
}

/* Responsividade aprimorada */
@media (max-width: 768px) {
    .kanban-board {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .kanban-column {
        min-height: 300px;
    }
    
    .kanban-notification {
        right: 10px;
        left: 10px;
        max-width: none;
    }
}
</style>
