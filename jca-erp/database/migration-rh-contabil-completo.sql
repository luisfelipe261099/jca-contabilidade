-- ============================================
-- MIGRATION: Sistema Completo de RH e Contábil
-- ============================================

USE jca_erp;

-- ============================================
-- TABELA: rescisoes
-- Rescisões de contrato de trabalho
-- ============================================
CREATE TABLE IF NOT EXISTS rescisoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    funcionario_id INT NOT NULL,
    cliente_id INT NOT NULL,
    
    data_demissao DATE NOT NULL,
    tipo_rescisao ENUM('Sem Justa Causa', 'Justa Causa', 'Pedido de Demissão', 'Acordo', 'Término de Contrato') NOT NULL,
    aviso_previo ENUM('Trabalhado', 'Indenizado', 'Não Aplicável') DEFAULT 'Trabalhado',
    dias_aviso_previo INT DEFAULT 30,
    
    -- Verbas rescisórias
    saldo_salario DECIMAL(10,2) DEFAULT 0,
    ferias_vencidas DECIMAL(10,2) DEFAULT 0,
    ferias_proporcionais DECIMAL(10,2) DEFAULT 0,
    um_terco_ferias DECIMAL(10,2) DEFAULT 0,
    decimo_terceiro DECIMAL(10,2) DEFAULT 0,
    aviso_previo_valor DECIMAL(10,2) DEFAULT 0,
    multa_fgts DECIMAL(10,2) DEFAULT 0,
    outras_verbas DECIMAL(10,2) DEFAULT 0,
    
    -- Descontos
    aviso_previo_desconto DECIMAL(10,2) DEFAULT 0,
    outros_descontos DECIMAL(10,2) DEFAULT 0,
    
    total_bruto DECIMAL(10,2) NOT NULL,
    total_descontos DECIMAL(10,2) NOT NULL,
    total_liquido DECIMAL(10,2) NOT NULL,
    
    -- FGTS
    saldo_fgts DECIMAL(10,2) DEFAULT 0,
    multa_40_fgts DECIMAL(10,2) DEFAULT 0,
    
    -- Documentos
    arquivo_termo_rescisao VARCHAR(500) NULL,
    arquivo_chave_conectividade VARCHAR(500) NULL,
    
    status ENUM('Pendente', 'Processada', 'Paga', 'Cancelada') DEFAULT 'Pendente',
    data_pagamento DATE NULL,
    
    observacoes TEXT NULL,
    gerado_por_id INT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (funcionario_id) REFERENCES funcionarios_clientes(id) ON DELETE CASCADE,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE,
    FOREIGN KEY (gerado_por_id) REFERENCES usuarios(id) ON DELETE SET NULL,
    INDEX idx_funcionario (funcionario_id),
    INDEX idx_cliente (cliente_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABELA: ferias
-- Controle de férias dos funcionários
-- ============================================
CREATE TABLE IF NOT EXISTS ferias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    funcionario_id INT NOT NULL,
    cliente_id INT NOT NULL,
    
    periodo_aquisitivo_inicio DATE NOT NULL,
    periodo_aquisitivo_fim DATE NOT NULL,
    
    data_inicio_ferias DATE NOT NULL,
    data_fim_ferias DATE NOT NULL,
    dias_ferias INT NOT NULL,
    
    abono_pecuniario BOOLEAN DEFAULT FALSE COMMENT 'Venda de 10 dias',
    dias_abono INT DEFAULT 0,
    
    -- Valores
    valor_ferias DECIMAL(10,2) NOT NULL,
    valor_um_terco DECIMAL(10,2) NOT NULL,
    valor_abono DECIMAL(10,2) DEFAULT 0,
    total DECIMAL(10,2) NOT NULL,
    
    arquivo_recibo VARCHAR(500) NULL,
    
    status ENUM('Programadas', 'Em Gozo', 'Concluídas', 'Canceladas') DEFAULT 'Programadas',
    
    observacoes TEXT NULL,
    gerado_por_id INT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (funcionario_id) REFERENCES funcionarios_clientes(id) ON DELETE CASCADE,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE,
    FOREIGN KEY (gerado_por_id) REFERENCES usuarios(id) ON DELETE SET NULL,
    INDEX idx_funcionario (funcionario_id),
    INDEX idx_cliente (cliente_id),
    INDEX idx_periodo (periodo_aquisitivo_inicio, periodo_aquisitivo_fim)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABELA: fgts_depositos
-- Controle de depósitos de FGTS
-- ============================================
CREATE TABLE IF NOT EXISTS fgts_depositos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    funcionario_id INT NOT NULL,
    cliente_id INT NOT NULL,
    
    mes_referencia INT NOT NULL,
    ano_referencia INT NOT NULL,
    
    base_calculo DECIMAL(10,2) NOT NULL COMMENT 'Salário + adicionais',
    aliquota DECIMAL(5,2) DEFAULT 8.00 COMMENT 'Percentual FGTS',
    valor_deposito DECIMAL(10,2) NOT NULL,
    
    data_vencimento DATE NOT NULL,
    data_pagamento DATE NULL,
    
    codigo_recolhimento VARCHAR(10) DEFAULT '115',
    numero_guia VARCHAR(50) NULL,
    
    status ENUM('Pendente', 'Pago', 'Atrasado') DEFAULT 'Pendente',
    
    observacoes TEXT NULL,
    gerado_por_id INT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (funcionario_id) REFERENCES funcionarios_clientes(id) ON DELETE CASCADE,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE,
    FOREIGN KEY (gerado_por_id) REFERENCES usuarios(id) ON DELETE SET NULL,
    INDEX idx_funcionario (funcionario_id),
    INDEX idx_cliente (cliente_id),
    INDEX idx_periodo (mes_referencia, ano_referencia),
    INDEX idx_status (status),
    UNIQUE KEY unique_fgts (funcionario_id, mes_referencia, ano_referencia)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABELA: certificados_digitais
-- Gestão de certificados digitais das empresas
-- ============================================
CREATE TABLE IF NOT EXISTS certificados_digitais (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    
    tipo ENUM('e-CNPJ A1', 'e-CNPJ A3', 'e-CPF A1', 'e-CPF A3', 'NF-e A1', 'NF-e A3') NOT NULL,
    titular VARCHAR(200) NOT NULL COMMENT 'Nome da empresa ou pessoa',
    cpf_cnpj VARCHAR(18) NOT NULL,
    
    data_emissao DATE NOT NULL,
    data_validade DATE NOT NULL,
    
    certificadora VARCHAR(100) NULL COMMENT 'Ex: Certisign, Serasa, Valid',
    
    -- Arquivo do certificado
    arquivo_certificado VARCHAR(500) NULL COMMENT 'Caminho do arquivo .pfx ou token',
    senha_certificado VARCHAR(255) NULL COMMENT 'Senha criptografada',
    
    -- Alertas
    dias_alerta_vencimento INT DEFAULT 30 COMMENT 'Alertar X dias antes',
    
    status ENUM('Ativo', 'Vencido', 'Revogado', 'Suspenso') DEFAULT 'Ativo',
    
    observacoes TEXT NULL,
    cadastrado_por_id INT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE,
    FOREIGN KEY (cadastrado_por_id) REFERENCES usuarios(id) ON DELETE SET NULL,
    INDEX idx_cliente (cliente_id),
    INDEX idx_validade (data_validade),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABELA: notas_fiscais
-- Emissão e controle de notas fiscais
-- ============================================
CREATE TABLE IF NOT EXISTS notas_fiscais (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,

    tipo ENUM('NF-e', 'NFS-e', 'NFC-e', 'CT-e') NOT NULL DEFAULT 'NF-e',
    numero_nota VARCHAR(20) NOT NULL,
    serie VARCHAR(5) DEFAULT '1',

    data_emissao DATETIME NOT NULL,
    data_saida DATETIME NULL,

    -- Destinatário
    destinatario_nome VARCHAR(200) NOT NULL,
    destinatario_cnpj_cpf VARCHAR(18) NOT NULL,
    destinatario_endereco TEXT NULL,

    -- Valores
    valor_produtos DECIMAL(10,2) NOT NULL,
    valor_servicos DECIMAL(10,2) DEFAULT 0,
    valor_frete DECIMAL(10,2) DEFAULT 0,
    valor_seguro DECIMAL(10,2) DEFAULT 0,
    valor_desconto DECIMAL(10,2) DEFAULT 0,
    valor_outras_despesas DECIMAL(10,2) DEFAULT 0,

    -- Impostos
    base_calculo_icms DECIMAL(10,2) DEFAULT 0,
    valor_icms DECIMAL(10,2) DEFAULT 0,
    valor_ipi DECIMAL(10,2) DEFAULT 0,
    valor_pis DECIMAL(10,2) DEFAULT 0,
    valor_cofins DECIMAL(10,2) DEFAULT 0,
    valor_iss DECIMAL(10,2) DEFAULT 0,

    valor_total DECIMAL(10,2) NOT NULL,

    -- Controle
    chave_acesso VARCHAR(44) NULL COMMENT 'Chave de 44 dígitos',
    protocolo_autorizacao VARCHAR(50) NULL,

    arquivo_xml VARCHAR(500) NULL,
    arquivo_pdf VARCHAR(500) NULL,

    status ENUM('Rascunho', 'Autorizada', 'Cancelada', 'Denegada', 'Rejeitada') DEFAULT 'Rascunho',
    motivo_cancelamento TEXT NULL,

    natureza_operacao VARCHAR(100) DEFAULT 'Venda de mercadoria',
    cfop VARCHAR(10) NULL COMMENT 'Código Fiscal de Operações',

    observacoes TEXT NULL,
    emitida_por_id INT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE,
    FOREIGN KEY (emitida_por_id) REFERENCES usuarios(id) ON DELETE SET NULL,
    INDEX idx_cliente (cliente_id),
    INDEX idx_numero (numero_nota),
    INDEX idx_chave (chave_acesso),
    INDEX idx_status (status),
    INDEX idx_data_emissao (data_emissao)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABELA: lancamentos_contabeis
-- Lançamentos contábeis (partidas dobradas)
-- ============================================
CREATE TABLE IF NOT EXISTS lancamentos_contabeis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,

    data_lancamento DATE NOT NULL,
    numero_lancamento INT NOT NULL,

    tipo ENUM('Débito', 'Crédito') NOT NULL,
    conta_contabil VARCHAR(20) NOT NULL COMMENT 'Ex: 1.1.1.01.001',
    historico VARCHAR(500) NOT NULL,

    valor DECIMAL(10,2) NOT NULL,

    documento_origem VARCHAR(100) NULL COMMENT 'Ex: NF-e 12345, Recibo 001',

    mes_competencia INT NOT NULL,
    ano_competencia INT NOT NULL,

    conciliado BOOLEAN DEFAULT FALSE,
    data_conciliacao DATE NULL,

    observacoes TEXT NULL,
    lancado_por_id INT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE,
    FOREIGN KEY (lancado_por_id) REFERENCES usuarios(id) ON DELETE SET NULL,
    INDEX idx_cliente (cliente_id),
    INDEX idx_data (data_lancamento),
    INDEX idx_conta (conta_contabil),
    INDEX idx_competencia (mes_competencia, ano_competencia)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABELA: plano_contas
-- Plano de contas contábil
-- ============================================
CREATE TABLE IF NOT EXISTS plano_contas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NULL COMMENT 'NULL = plano padrão para todos',

    codigo VARCHAR(20) NOT NULL COMMENT 'Ex: 1.1.1.01.001',
    nome VARCHAR(200) NOT NULL,
    tipo ENUM('Ativo', 'Passivo', 'Receita', 'Despesa', 'Patrimônio Líquido') NOT NULL,
    natureza ENUM('Devedora', 'Credora') NOT NULL,

    conta_pai_id INT NULL COMMENT 'Para hierarquia',
    nivel INT DEFAULT 1 COMMENT 'Nível na hierarquia',

    aceita_lancamento BOOLEAN DEFAULT TRUE,

    ativo BOOLEAN DEFAULT TRUE,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE,
    FOREIGN KEY (conta_pai_id) REFERENCES plano_contas(id) ON DELETE SET NULL,
    INDEX idx_cliente (cliente_id),
    INDEX idx_codigo (codigo),
    INDEX idx_tipo (tipo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABELA: balancetes
-- Balancetes mensais
-- ============================================
CREATE TABLE IF NOT EXISTS balancetes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,

    mes_referencia INT NOT NULL,
    ano_referencia INT NOT NULL,

    tipo ENUM('Analítico', 'Sintético') DEFAULT 'Analítico',

    arquivo_pdf VARCHAR(500) NULL,

    total_ativo DECIMAL(15,2) DEFAULT 0,
    total_passivo DECIMAL(15,2) DEFAULT 0,
    total_receitas DECIMAL(15,2) DEFAULT 0,
    total_despesas DECIMAL(15,2) DEFAULT 0,
    resultado DECIMAL(15,2) DEFAULT 0 COMMENT 'Lucro ou Prejuízo',

    status ENUM('Em Elaboração', 'Concluído', 'Aprovado') DEFAULT 'Em Elaboração',

    observacoes TEXT NULL,
    gerado_por_id INT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE,
    FOREIGN KEY (gerado_por_id) REFERENCES usuarios(id) ON DELETE SET NULL,
    INDEX idx_cliente (cliente_id),
    INDEX idx_periodo (mes_referencia, ano_referencia),
    UNIQUE KEY unique_balancete (cliente_id, mes_referencia, ano_referencia, tipo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


