-- ============================================
-- MIGRATION: Adicionar tabelas para gestão de funcionários dos clientes
-- ============================================

USE jca_erp;

-- ============================================
-- TABELA: funcionarios_clientes
-- Funcionários das empresas clientes (para RH)
-- ============================================
CREATE TABLE IF NOT EXISTS funcionarios_clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    
    -- Dados pessoais
    nome_completo VARCHAR(200) NOT NULL,
    cpf VARCHAR(14) UNIQUE NOT NULL,
    rg VARCHAR(20) NULL,
    data_nascimento DATE NULL,
    sexo ENUM('M', 'F', 'Outro') NULL,
    estado_civil ENUM('Solteiro(a)', 'Casado(a)', 'Divorciado(a)', 'Viúvo(a)', 'União Estável') NULL,
    
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
    
    -- Dados trabalhistas
    cargo VARCHAR(100) NOT NULL,
    departamento VARCHAR(100) NULL,
    data_admissao DATE NOT NULL,
    data_demissao DATE NULL,
    tipo_contrato ENUM('CLT', 'PJ', 'Estágio', 'Temporário', 'Autônomo') DEFAULT 'CLT',
    
    -- Remuneração
    salario_base DECIMAL(10,2) NOT NULL,
    vale_transporte DECIMAL(10,2) DEFAULT 0,
    vale_refeicao DECIMAL(10,2) DEFAULT 0,
    outros_beneficios TEXT NULL COMMENT 'JSON com outros benefícios',
    
    -- Documentos trabalhistas
    pis_pasep VARCHAR(20) NULL,
    ctps_numero VARCHAR(20) NULL,
    ctps_serie VARCHAR(10) NULL,
    ctps_uf VARCHAR(2) NULL,
    titulo_eleitor VARCHAR(20) NULL,
    reservista VARCHAR(20) NULL,
    
    -- Banco (para pagamento)
    banco_codigo VARCHAR(5) NULL,
    banco_nome VARCHAR(100) NULL,
    agencia VARCHAR(10) NULL,
    conta VARCHAR(20) NULL,
    tipo_conta ENUM('Corrente', 'Poupança', 'Salário') NULL,
    pix VARCHAR(100) NULL,
    
    -- Dependentes
    numero_dependentes INT DEFAULT 0,
    
    -- Status
    status ENUM('Ativo', 'Férias', 'Afastado', 'Demitido') DEFAULT 'Ativo',
    observacoes TEXT NULL,
    
    ativo BOOLEAN DEFAULT TRUE,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE,
    INDEX idx_cliente (cliente_id),
    INDEX idx_cpf (cpf),
    INDEX idx_nome (nome_completo),
    INDEX idx_status (status),
    INDEX idx_ativo (ativo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABELA: holerites
-- Holerites/contracheques dos funcionários
-- ============================================
CREATE TABLE IF NOT EXISTS holerites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    funcionario_id INT NOT NULL,
    cliente_id INT NOT NULL,
    
    mes_referencia INT NOT NULL,
    ano_referencia INT NOT NULL,
    
    -- Vencimentos
    salario_base DECIMAL(10,2) NOT NULL,
    horas_extras DECIMAL(10,2) DEFAULT 0,
    adicional_noturno DECIMAL(10,2) DEFAULT 0,
    comissoes DECIMAL(10,2) DEFAULT 0,
    bonus DECIMAL(10,2) DEFAULT 0,
    outros_vencimentos DECIMAL(10,2) DEFAULT 0,
    total_vencimentos DECIMAL(10,2) NOT NULL,
    
    -- Descontos
    inss DECIMAL(10,2) DEFAULT 0,
    irrf DECIMAL(10,2) DEFAULT 0,
    fgts DECIMAL(10,2) DEFAULT 0,
    vale_transporte_desc DECIMAL(10,2) DEFAULT 0,
    vale_refeicao_desc DECIMAL(10,2) DEFAULT 0,
    plano_saude DECIMAL(10,2) DEFAULT 0,
    outros_descontos DECIMAL(10,2) DEFAULT 0,
    total_descontos DECIMAL(10,2) NOT NULL,
    
    -- Líquido
    salario_liquido DECIMAL(10,2) NOT NULL,
    
    -- Arquivo PDF
    arquivo_pdf VARCHAR(500) NULL,
    
    -- Status
    status ENUM('Pendente', 'Processado', 'Pago', 'Cancelado') DEFAULT 'Pendente',
    data_pagamento DATE NULL,
    
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
    UNIQUE KEY unique_holerite (funcionario_id, mes_referencia, ano_referencia)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

