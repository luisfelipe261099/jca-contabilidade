-- ============================================
-- MIGRATION: Tabela de Holerites
-- Sistema de Folha de Pagamento
-- ============================================

USE jca_erp;

-- ============================================
-- TABELA: holerites
-- Holerites mensais dos funcionários
-- ============================================
CREATE TABLE IF NOT EXISTS holerites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    funcionario_id INT NOT NULL,
    cliente_id INT NOT NULL,
    
    -- Período
    mes_referencia INT NOT NULL COMMENT '1-12',
    ano_referencia INT NOT NULL,
    
    -- Dados do funcionário no momento
    cargo VARCHAR(100) NOT NULL,
    departamento VARCHAR(100) NULL,
    data_admissao DATE NOT NULL,
    
    -- Jornada
    dias_trabalhados INT DEFAULT 30,
    horas_trabalhadas DECIMAL(6,2) DEFAULT 220.00,
    faltas INT DEFAULT 0,
    horas_extras DECIMAL(6,2) DEFAULT 0,
    horas_extras_valor DECIMAL(10,2) DEFAULT 0,
    
    -- PROVENTOS (Ganhos)
    salario_base DECIMAL(10,2) NOT NULL,
    adicional_noturno DECIMAL(10,2) DEFAULT 0,
    adicional_insalubridade DECIMAL(10,2) DEFAULT 0,
    adicional_periculosidade DECIMAL(10,2) DEFAULT 0,
    comissoes DECIMAL(10,2) DEFAULT 0,
    bonus DECIMAL(10,2) DEFAULT 0,
    dsr DECIMAL(10,2) DEFAULT 0 COMMENT 'Descanso Semanal Remunerado',
    outros_proventos DECIMAL(10,2) DEFAULT 0,
    total_proventos DECIMAL(10,2) NOT NULL,
    
    -- DESCONTOS
    inss_base DECIMAL(10,2) DEFAULT 0,
    inss_aliquota DECIMAL(5,2) DEFAULT 0,
    inss_valor DECIMAL(10,2) DEFAULT 0,
    
    irrf_base DECIMAL(10,2) DEFAULT 0,
    irrf_aliquota DECIMAL(5,2) DEFAULT 0,
    irrf_deducao DECIMAL(10,2) DEFAULT 0,
    irrf_valor DECIMAL(10,2) DEFAULT 0,
    
    fgts_base DECIMAL(10,2) DEFAULT 0,
    fgts_valor DECIMAL(10,2) DEFAULT 0,
    
    vale_transporte DECIMAL(10,2) DEFAULT 0,
    vale_refeicao DECIMAL(10,2) DEFAULT 0,
    plano_saude DECIMAL(10,2) DEFAULT 0,
    plano_odontologico DECIMAL(10,2) DEFAULT 0,
    
    adiantamento DECIMAL(10,2) DEFAULT 0,
    pensao_alimenticia DECIMAL(10,2) DEFAULT 0,
    emprestimo_consignado DECIMAL(10,2) DEFAULT 0,
    
    faltas_valor DECIMAL(10,2) DEFAULT 0,
    outros_descontos DECIMAL(10,2) DEFAULT 0,
    total_descontos DECIMAL(10,2) NOT NULL,
    
    -- LÍQUIDO
    salario_liquido DECIMAL(10,2) NOT NULL,
    
    -- Informações adicionais
    base_calculo_fgts DECIMAL(10,2) DEFAULT 0,
    fgts_mes DECIMAL(10,2) DEFAULT 0,
    base_calculo_13 DECIMAL(10,2) DEFAULT 0,
    
    -- Dependentes para IR
    numero_dependentes INT DEFAULT 0,
    valor_dependente DECIMAL(10,2) DEFAULT 189.59,
    
    -- Arquivos
    arquivo_pdf VARCHAR(500) NULL,
    
    -- Status
    status ENUM('Rascunho', 'Processado', 'Enviado', 'Pago', 'Cancelado') DEFAULT 'Rascunho',
    data_pagamento DATE NULL,
    forma_pagamento ENUM('Dinheiro', 'Transferência', 'Depósito', 'PIX', 'Cheque') NULL,
    
    -- Observações
    observacoes TEXT NULL,
    
    -- Auditoria
    gerado_por_id INT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (funcionario_id) REFERENCES funcionarios_clientes(id) ON DELETE CASCADE,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE,
    FOREIGN KEY (gerado_por_id) REFERENCES usuarios(id) ON DELETE SET NULL,
    
    INDEX idx_funcionario (funcionario_id),
    INDEX idx_cliente (cliente_id),
    INDEX idx_periodo (mes_referencia, ano_referencia),
    INDEX idx_status (status),
    UNIQUE KEY unique_holerite (funcionario_id, mes_referencia, ano_referencia)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Inserir alguns holerites de exemplo
-- ============================================

-- Mensagem de conclusão
SELECT 'Tabela de holerites criada com sucesso!' as Mensagem;

