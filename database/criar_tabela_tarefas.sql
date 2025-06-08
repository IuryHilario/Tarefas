-- Criação da tabela de tarefas
CREATE TABLE IF NOT EXISTS tarefas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT,
    projeto_id INT NOT NULL,
    responsavel_id INT NOT NULL,
    criado_por INT NOT NULL,
    status ENUM('pendente', 'em_andamento', 'concluida') DEFAULT 'pendente',
    prioridade ENUM('baixa', 'media', 'alta', 'urgente') DEFAULT 'media',
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    data_prazo DATE NULL,
    FOREIGN KEY (projeto_id) REFERENCES projetos(id) ON DELETE CASCADE,
    FOREIGN KEY (responsavel_id) REFERENCES usuarios(id),
    FOREIGN KEY (criado_por) REFERENCES usuarios(id)
);

-- Inserir algumas tarefas de exemplo (assumindo que existem projetos e usuários)
INSERT INTO tarefas (nome, descricao, projeto_id, responsavel_id, criado_por, status, prioridade, data_prazo) VALUES 
('Análise de Requisitos', 'Levantar e documentar todos os requisitos funcionais e não funcionais do sistema', 1, 1, 1, 'pendente', 'alta', '2024-02-15'),
('Design da Interface', 'Criar wireframes e protótipos das telas principais', 1, 1, 1, 'em_andamento', 'media', '2024-02-20'),
('Configuração do Ambiente', 'Configurar servidor de desenvolvimento e banco de dados', 2, 1, 1, 'concluida', 'alta', '2024-01-30');