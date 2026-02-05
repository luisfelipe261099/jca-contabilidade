-- ============================================
-- JCA ERP - SCHEMA DO BANCO DE DADOS
-- Sistema ERP para JCA Soluções Contábeis
-- ============================================

CREATE DATABASE IF NOT EXISTS jca_erp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE jca_erp;

-- ============================================
-- TABELA: usuarios
-- Gerencia todos os usuários do sistema
-- ============================================
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    tipo_usuario ENUM('admin', 'funcionario', 'cliente') NOT NULL DEFAULT 'cliente',
    setor_id INT NULL,
    foto_perfil VARCHAR(255) NULL,
    telefone VARCHAR(20) NULL,
    ativo BOOLEAN DEFAULT TRUE,
    ultimo_acesso DATETIME NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_tipo (tipo_usuario),
    INDEX idx_ativo (ativo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABELA: setores
-- Departamentos da empresa
-- ============================================
CREATE TABLE setores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT NULL,
    cor VARCHAR(7) DEFAULT '#0A2463',
    icone VARCHAR(50) DEFAULT 'fa-building',
    ativo BOOLEAN DEFAULT TRUE,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABELA: clientes
-- Empresas clientes (500+)
-- ============================================
CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NULL,
    razao_social VARCHAR(200) NOT NULL,
    nome_fantasia VARCHAR(200) NULL,
    cnpj VARCHAR(18) UNIQUE NOT NULL,
    inscricao_estadual VARCHAR(20) NULL,
    inscricao_municipal VARCHAR(20) NULL,
    regime_tributario ENUM('Simples Nacional', 'Lucro Presumido', 'Lucro Real', 'MEI') NOT NULL,
    porte_empresa ENUM('MEI', 'ME', 'EPP', 'Médio', 'Grande') NOT NULL,
    cnae_principal VARCHAR(10) NULL,
    data_abertura DATE NULL,
    
    -- Endereço
    cep VARCHAR(9) NULL,
    logradouro VARCHAR(200) NULL,
    numero VARCHAR(10) NULL,
    complemento VARCHAR(100) NULL,
    bairro VARCHAR(100) NULL,
    cidade VARCHAR(100) NULL,
    estado VARCHAR(2) NULL,
    
    -- Contato
    telefone VARCHAR(20) NULL,
    celular VARCHAR(20) NULL,
    email VARCHAR(100) NULL,
    site VARCHAR(200) NULL,
    
    -- Responsável
    responsavel_nome VARCHAR(100) NULL,
    responsavel_cpf VARCHAR(14) NULL,
    responsavel_email VARCHAR(100) NULL,
    responsavel_telefone VARCHAR(20) NULL,
    
    -- Contrato
    data_inicio_contrato DATE NULL,
    valor_mensalidade DECIMAL(10,2) NULL,
    dia_vencimento INT DEFAULT 10,
    status_contrato ENUM('Ativo', 'Suspenso', 'Cancelado', 'Inadimplente') DEFAULT 'Ativo',
    
    -- Responsável interno
    contador_responsavel_id INT NULL,
    
    observacoes TEXT NULL,
    ativo BOOLEAN DEFAULT TRUE,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL,
    FOREIGN KEY (contador_responsavel_id) REFERENCES usuarios(id) ON DELETE SET NULL,
    INDEX idx_cnpj (cnpj),
    INDEX idx_razao_social (razao_social),
    INDEX idx_regime (regime_tributario),
    INDEX idx_status (status_contrato),
    INDEX idx_ativo (ativo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABELA: servicos
-- Serviços oferecidos pela JCA
-- ============================================
CREATE TABLE servicos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT NULL,
    categoria ENUM('Contábil', 'Fiscal', 'RH', 'Abertura/Fechamento', 'Consultoria', 'Outros') NOT NULL,
    valor_base DECIMAL(10,2) NULL,
    icone VARCHAR(50) DEFAULT 'fa-file',
    cor VARCHAR(7) DEFAULT '#0A2463',
    ativo BOOLEAN DEFAULT TRUE,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABELA: cliente_servicos
-- Serviços contratados por cada cliente
-- ============================================
CREATE TABLE cliente_servicos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    servico_id INT NOT NULL,
    valor_cobrado DECIMAL(10,2) NULL,
    data_inicio DATE NOT NULL,
    data_fim DATE NULL,
    ativo BOOLEAN DEFAULT TRUE,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE,
    FOREIGN KEY (servico_id) REFERENCES servicos(id) ON DELETE CASCADE,
    INDEX idx_cliente (cliente_id),
    INDEX idx_servico (servico_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABELA: obrigacoes_fiscais
-- Obrigações fiscais e prazos
-- ============================================
CREATE TABLE obrigacoes_fiscais (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT NULL,
    tipo ENUM('Federal', 'Estadual', 'Municipal') NOT NULL,
    periodicidade ENUM('Mensal', 'Trimestral', 'Semestral', 'Anual', 'Eventual') NOT NULL,
    dia_vencimento INT NULL,
    mes_vencimento INT NULL,
    regime_aplicavel VARCHAR(100) NULL COMMENT 'Simples, Presumido, Real, Todos',
    ativo BOOLEAN DEFAULT TRUE,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABELA: cliente_obrigacoes
-- Obrigações específicas de cada cliente
-- ============================================
CREATE TABLE cliente_obrigacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    obrigacao_id INT NOT NULL,
    mes_referencia INT NOT NULL,
    ano_referencia INT NOT NULL,
    data_vencimento DATE NOT NULL,
    status ENUM('Pendente', 'Em Andamento', 'Concluída', 'Atrasada') DEFAULT 'Pendente',
    responsavel_id INT NULL,
    data_conclusao DATETIME NULL,
    observacoes TEXT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE,
    FOREIGN KEY (obrigacao_id) REFERENCES obrigacoes_fiscais(id) ON DELETE CASCADE,
    FOREIGN KEY (responsavel_id) REFERENCES usuarios(id) ON DELETE SET NULL,
    INDEX idx_cliente (cliente_id),
    INDEX idx_vencimento (data_vencimento),
    INDEX idx_status (status),
    INDEX idx_periodo (mes_referencia, ano_referencia)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABELA: tarefas
-- Sistema de tarefas e workflow
-- ============================================
CREATE TABLE tarefas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    descricao TEXT NULL,
    cliente_id INT NULL,
    tipo ENUM('Contábil', 'Fiscal', 'RH', 'Consultoria', 'Administrativo', 'Outros') NOT NULL,
    prioridade ENUM('Baixa', 'Média', 'Alta', 'Urgente') DEFAULT 'Média',
    status ENUM('Pendente', 'Em Andamento', 'Aguardando', 'Concluída', 'Cancelada') DEFAULT 'Pendente',

    criado_por_id INT NOT NULL,
    responsavel_id INT NULL,
    setor_id INT NULL,

    data_inicio DATE NULL,
    data_vencimento DATE NULL,
    data_conclusao DATETIME NULL,

    tempo_estimado INT NULL COMMENT 'Tempo em minutos',
    tempo_gasto INT NULL COMMENT 'Tempo em minutos',

    anexos TEXT NULL COMMENT 'JSON com arquivos anexados',
    tags VARCHAR(255) NULL,

    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE,
    FOREIGN KEY (criado_por_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (responsavel_id) REFERENCES usuarios(id) ON DELETE SET NULL,
    FOREIGN KEY (setor_id) REFERENCES setores(id) ON DELETE SET NULL,
    INDEX idx_cliente (cliente_id),
    INDEX idx_responsavel (responsavel_id),
    INDEX idx_status (status),
    INDEX idx_prioridade (prioridade),
    INDEX idx_vencimento (data_vencimento)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABELA: documentos
-- Gestão de documentos dos clientes
-- ============================================
CREATE TABLE documentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    nome_arquivo VARCHAR(255) NOT NULL,
    nome_original VARCHAR(255) NOT NULL,
    tipo_documento ENUM('Contrato', 'Certidão', 'Balanço', 'DRE', 'Folha', 'Guia', 'Nota Fiscal', 'Outros') NOT NULL,
    categoria VARCHAR(100) NULL,
    tamanho_bytes BIGINT NOT NULL,
    extensao VARCHAR(10) NOT NULL,
    caminho_arquivo VARCHAR(500) NOT NULL,

    mes_referencia INT NULL,
    ano_referencia INT NULL,

    enviado_por_id INT NOT NULL,
    descricao TEXT NULL,
    tags VARCHAR(255) NULL,

    data_upload TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE,
    FOREIGN KEY (enviado_por_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_cliente (cliente_id),
    INDEX idx_tipo (tipo_documento),
    INDEX idx_periodo (mes_referencia, ano_referencia)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABELA: alertas
-- Sistema de alertas e notificações
-- ============================================
CREATE TABLE alertas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo ENUM('Vencimento', 'Atraso', 'Pendência', 'Sistema', 'Financeiro', 'Outros') NOT NULL,
    titulo VARCHAR(200) NOT NULL,
    mensagem TEXT NOT NULL,
    nivel ENUM('Info', 'Aviso', 'Urgente', 'Crítico') DEFAULT 'Info',

    cliente_id INT NULL,
    usuario_id INT NULL COMMENT 'Destinatário do alerta',

    link_relacionado VARCHAR(500) NULL,
    lido BOOLEAN DEFAULT FALSE,
    data_leitura DATETIME NULL,

    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_usuario (usuario_id),
    INDEX idx_lido (lido),
    INDEX idx_nivel (nivel),
    INDEX idx_tipo (tipo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABELA: financeiro
-- Controle financeiro (mensalidades, pagamentos)
-- ============================================
CREATE TABLE financeiro (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    tipo ENUM('Receita', 'Despesa') NOT NULL DEFAULT 'Receita',
    descricao VARCHAR(255) NOT NULL,
    valor DECIMAL(10,2) NOT NULL,

    mes_referencia INT NOT NULL,
    ano_referencia INT NOT NULL,
    data_vencimento DATE NOT NULL,
    data_pagamento DATE NULL,

    status ENUM('Pendente', 'Pago', 'Atrasado', 'Cancelado') DEFAULT 'Pendente',
    forma_pagamento VARCHAR(50) NULL,

    observacoes TEXT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE,
    INDEX idx_cliente (cliente_id),
    INDEX idx_vencimento (data_vencimento),
    INDEX idx_status (status),
    INDEX idx_periodo (mes_referencia, ano_referencia)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABELA: logs_sistema
-- Auditoria e logs do sistema
-- ============================================
CREATE TABLE logs_sistema (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NULL,
    acao VARCHAR(100) NOT NULL,
    modulo VARCHAR(50) NOT NULL,
    descricao TEXT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    dados_anteriores TEXT NULL COMMENT 'JSON',
    dados_novos TEXT NULL COMMENT 'JSON',
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL,
    INDEX idx_usuario (usuario_id),
    INDEX idx_modulo (modulo),
    INDEX idx_data (data_criacao)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABELA: configuracoes
-- Configurações do sistema
-- ============================================
CREATE TABLE configuracoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chave VARCHAR(100) UNIQUE NOT NULL,
    valor TEXT NULL,
    tipo ENUM('texto', 'numero', 'boolean', 'json') DEFAULT 'texto',
    descricao VARCHAR(255) NULL,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- INSERÇÃO DE DADOS INICIAIS
-- ============================================

-- Setores
INSERT INTO setores (nome, descricao, cor, icone) VALUES
('Contábil', 'Departamento de Contabilidade', '#0A2463', 'fa-calculator'),
('Fiscal', 'Departamento Fiscal e Tributário', '#3E92CC', 'fa-file-invoice'),
('RH', 'Recursos Humanos e Folha de Pagamento', '#FB8500', 'fa-users'),
('Administrativo', 'Administração e Gestão', '#FFB703', 'fa-briefcase'),
('Atendimento', 'Atendimento ao Cliente', '#06D6A0', 'fa-headset');

-- Usuário Admin Padrão (senha: admin123)
INSERT INTO usuarios (nome, email, senha, tipo_usuario, setor_id) VALUES
('Administrador', 'admin@jcacontabilidade.com.br', '$2y$10$6IXfOroewhB/iZvqlDhQqu7TVmcqELrq0slqUramaJ.5oKzR6ULVq', 'admin', 1);

-- Serviços
INSERT INTO servicos (nome, descricao, categoria, icone, cor) VALUES
('Assessoria Contábil', 'Escrituração contábil completa', 'Contábil', 'fa-calculator', '#0A2463'),
('Assessoria em RH', 'Gestão de recursos humanos', 'RH', 'fa-users', '#FB8500'),
('Abertura de Empresa', 'Processo completo de abertura', 'Abertura/Fechamento', 'fa-building', '#3E92CC'),
('Fechamento de Empresa', 'Processo de encerramento', 'Abertura/Fechamento', 'fa-door-closed', '#3E92CC'),
('Contabilidade MEI', 'Serviços para MEI', 'Contábil', 'fa-user-tie', '#FFB703'),
('Folha de Pagamento', 'Processamento de folha', 'RH', 'fa-money-check', '#FB8500'),
('Imposto de Renda', 'Declaração de IR', 'Fiscal', 'fa-file-invoice-dollar', '#3E92CC'),
('Malha Fiscal', 'Regularização fiscal', 'Fiscal', 'fa-shield-alt', '#3E92CC'),
('Planejamento Tributário', 'Otimização tributária', 'Consultoria', 'fa-chart-line', '#06D6A0');

-- Obrigações Fiscais Comuns
INSERT INTO obrigacoes_fiscais (nome, descricao, tipo, periodicidade, dia_vencimento, regime_aplicavel) VALUES
('DAS - Simples Nacional', 'Documento de Arrecadação do Simples Nacional', 'Federal', 'Mensal', 20, 'Simples Nacional'),
('DCTF', 'Declaração de Débitos e Créditos Tributários Federais', 'Federal', 'Mensal', 15, 'Todos'),
('EFD-Contribuições', 'Escrituração Fiscal Digital das Contribuições', 'Federal', 'Mensal', 10, 'Lucro Presumido,Lucro Real'),
('SPED Contábil', 'Escrituração Contábil Digital', 'Federal', 'Anual', 31, 'Todos'),
('SPED Fiscal', 'Escrituração Fiscal Digital', 'Estadual', 'Mensal', 20, 'Todos'),
('DCTF-Web', 'Declaração de Débitos e Créditos Tributários Federais Web', 'Federal', 'Mensal', 15, 'Simples Nacional'),
('eSocial', 'Sistema de Escrituração Digital das Obrigações Fiscais', 'Federal', 'Mensal', 7, 'Todos'),
('DIRF', 'Declaração do Imposto de Renda Retido na Fonte', 'Federal', 'Anual', 28, 'Todos'),
('RAIS', 'Relação Anual de Informações Sociais', 'Federal', 'Anual', 31, 'Todos'),
('DEFIS', 'Declaração de Informações Socioeconômicas e Fiscais', 'Federal', 'Anual', 31, 'Simples Nacional');

-- Configurações Iniciais
INSERT INTO configuracoes (chave, valor, tipo, descricao) VALUES
('nome_empresa', 'JCA Soluções Contábeis', 'texto', 'Nome da empresa'),
('email_empresa', 'contato@jcacontabilidadecwb.com.br', 'texto', 'Email principal'),
('telefone_empresa', '(41) 98858-4456', 'texto', 'Telefone de contato'),
('endereco_empresa', 'Curitiba - PR', 'texto', 'Endereço da empresa'),
('dias_alerta_vencimento', '7', 'numero', 'Dias de antecedência para alertas'),
('limite_upload_mb', '10', 'numero', 'Limite de upload em MB'),
('itens_por_pagina', '20', 'numero', 'Itens por página nas listagens');

