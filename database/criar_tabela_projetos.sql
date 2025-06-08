-- Criação da tabela de projetos
CREATE TABLE IF NOT EXISTS projetos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT,
    status ENUM('ativo', 'pausado', 'concluido') DEFAULT 'ativo',
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    criado_por INT,
    FOREIGN KEY (criado_por) REFERENCES usuarios(id)
);

-- Inserir alguns projetos de exemplo
INSERT INTO projetos (nome, descricao, status, criado_por) VALUES 
('Sistema de Gestão', 'Sistema para gerenciamento de tarefas e projetos da empresa', 'ativo', 1),
('Website Corporativo', 'Desenvolvimento do novo website da empresa com design responsivo', 'ativo', 1),
('App Mobile', 'Aplicativo mobile para acompanhamento de projetos', 'pausado', 1);