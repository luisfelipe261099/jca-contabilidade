-- ============================================
-- DADOS FICTÍCIOS PARA TESTES
-- JCA ERP - Sistema de Gestão Contábil
-- ============================================

USE jca_erp;

-- ============================================
-- CLIENTES FICTÍCIOS
-- ============================================

INSERT INTO clientes (razao_social, nome_fantasia, cnpj, inscricao_estadual, inscricao_municipal, regime_tributario, porte_empresa, cnae_principal, data_abertura, cep, logradouro, numero, complemento, bairro, cidade, estado, telefone, celular, email, responsavel_nome, responsavel_cpf, responsavel_email, responsavel_telefone, data_inicio_contrato, valor_mensalidade, dia_vencimento, status_contrato, contador_responsavel_id) VALUES
('ALFATECH SOLUÇÕES EM TECNOLOGIA LTDA', 'AlfaTech', '11.222.333/0001-44', '123.456.789.012', '98765432', 'Simples Nacional', 'ME', '6201-5/00', '2018-03-15', '80010-000', 'Rua XV de Novembro', '1500', 'Sala 301', 'Centro', 'Curitiba', 'PR', '(41) 3333-4444', '(41) 99999-8888', 'contato@alfatech.com.br', 'João Silva Santos', '123.456.789-00', 'joao@alfatech.com.br', '(41) 99999-8888', '2020-01-01', 850.00, 10, 'Ativo', 1),

('BETA COMÉRCIO DE ALIMENTOS LTDA', 'Beta Alimentos', '22.333.444/0001-55', '234.567.890.123', '87654321', 'Simples Nacional', 'EPP', '4711-3/02', '2015-06-20', '80020-100', 'Av. Sete de Setembro', '2800', NULL, 'Batel', 'Curitiba', 'PR', '(41) 3344-5566', '(41) 98888-7777', 'contato@betaalimentos.com.br', 'Maria Oliveira Costa', '234.567.890-11', 'maria@betaalimentos.com.br', '(41) 98888-7777', '2019-03-15', 1200.00, 10, 'Ativo', 1),

('GAMMA CONSTRUÇÕES E REFORMAS LTDA', 'Gamma Construções', '33.444.555/0001-66', '345.678.901.234', '76543210', 'Lucro Presumido', 'Médio', '4120-4/00', '2012-09-10', '80030-200', 'Rua Marechal Deodoro', '450', 'Galpão 2', 'Centro', 'Curitiba', 'PR', '(41) 3355-6677', '(41) 97777-6666', 'contato@gammaconstrucoes.com.br', 'Pedro Henrique Souza', '345.678.901-22', 'pedro@gammaconstrucoes.com.br', '(41) 97777-6666', '2018-06-01', 2500.00, 10, 'Ativo', 1),

('DELTA SERVIÇOS MÉDICOS LTDA', 'Clínica Delta', '44.555.666/0001-77', '456.789.012.345', '65432109', 'Simples Nacional', 'ME', '8630-5/03', '2019-02-14', '80040-300', 'Rua Comendador Araújo', '720', 'Conjunto 501', 'Centro', 'Curitiba', 'PR', '(41) 3366-7788', '(41) 96666-5555', 'contato@clinicadelta.com.br', 'Dra. Ana Paula Lima', '456.789.012-33', 'ana@clinicadelta.com.br', '(41) 96666-5555', '2019-08-01', 950.00, 10, 'Ativo', 1),

('EPSILON TRANSPORTES RODOVIÁRIOS LTDA', 'Epsilon Transportes', '55.666.777/0001-88', '567.890.123.456', '54321098', 'Lucro Real', 'Grande', '4930-2/02', '2010-11-25', '80050-400', 'Av. Cândido de Abreu', '3200', NULL, 'Centro Cívico', 'Curitiba', 'PR', '(41) 3377-8899', '(41) 95555-4444', 'contato@epsilontransportes.com.br', 'Carlos Eduardo Ferreira', '567.890.123-44', 'carlos@epsilontransportes.com.br', '(41) 95555-4444', '2017-01-15', 3800.00, 10, 'Ativo', 1),

('ZETA MODA E CONFECÇÕES LTDA', 'Zeta Fashion', '66.777.888/0001-99', '678.901.234.567', '43210987', 'Simples Nacional', 'ME', '1412-6/01', '2020-04-10', '80060-500', 'Rua Barão do Rio Branco', '890', 'Loja 3', 'Centro', 'Curitiba', 'PR', '(41) 3388-9900', '(41) 94444-3333', 'contato@zetafashion.com.br', 'Juliana Martins Rocha', '678.901.234-55', 'juliana@zetafashion.com.br', '(41) 94444-3333', '2020-09-01', 750.00, 10, 'Ativo', 1),

('ETA CONSULTORIA EMPRESARIAL LTDA', 'Eta Consultoria', '77.888.999/0001-00', '789.012.345.678', '32109876', 'Simples Nacional', 'ME', '7020-4/00', '2021-01-20', '80070-600', 'Rua Visconde de Guarapuava', '1200', 'Sala 1005', 'Centro', 'Curitiba', 'PR', '(41) 3399-0011', '(41) 93333-2222', 'contato@etaconsultoria.com.br', 'Roberto Alves Pereira', '789.012.345-66', 'roberto@etaconsultoria.com.br', '(41) 93333-2222', '2021-03-01', 680.00, 10, 'Ativo', 1),

('THETA ACADEMIA E FITNESS LTDA', 'Theta Fitness', '88.999.000/0001-11', '890.123.456.789', '21098765', 'Simples Nacional', 'ME', '9313-1/00', '2017-07-05', '80080-700', 'Av. Marechal Floriano Peixoto', '560', NULL, 'Rebouças', 'Curitiba', 'PR', '(41) 3300-1122', '(41) 92222-1111', 'contato@thetafitness.com.br', 'Marcos Vinícius Santos', '890.123.456-77', 'marcos@thetafitness.com.br', '(41) 92222-1111', '2018-02-01', 820.00, 10, 'Ativo', 1),

('IOTA RESTAURANTE E LANCHONETE LTDA', 'Iota Gourmet', '99.000.111/0001-22', '901.234.567.890', '10987654', 'Simples Nacional', 'ME', '5611-2/01', '2019-05-18', '80090-800', 'Rua Presidente Faria', '340', NULL, 'Centro', 'Curitiba', 'PR', '(41) 3311-2233', '(41) 91111-0000', 'contato@iotagourmet.com.br', 'Fernanda Costa Ribeiro', '901.234.567-88', 'fernanda@iotagourmet.com.br', '(41) 91111-0000', '2019-10-01', 890.00, 10, 'Ativo', 1),

('KAPPA FARMÁCIA E DROGARIA LTDA', 'Kappa Farma', '00.111.222/0001-33', '012.345.678.901', '09876543', 'Simples Nacional', 'EPP', '4771-7/01', '2016-08-30', '80100-900', 'Rua Conselheiro Laurindo', '670', NULL, 'Centro', 'Curitiba', 'PR', '(41) 3322-3344', '(41) 90000-9999', 'contato@kappafarma.com.br', 'Ricardo Mendes Silva', '012.345.678-99', 'ricardo@kappafarma.com.br', '(41) 90000-9999', '2017-05-01', 1100.00, 10, 'Ativo', 1);

-- ============================================
-- FUNCIONÁRIOS DOS CLIENTES
-- ============================================

-- Funcionários da AlfaTech (cliente_id = 1)
INSERT INTO funcionarios_clientes (cliente_id, nome_completo, cpf, rg, data_nascimento, sexo, estado_civil, cep, logradouro, numero, bairro, cidade, estado, telefone, celular, email, cargo, departamento, data_admissao, tipo_contrato, salario_base, vale_transporte, vale_refeicao, pis_pasep, ctps_numero, ctps_serie, ctps_uf, banco_codigo, banco_nome, agencia, conta, tipo_conta, numero_dependentes, status) VALUES
(1, 'Lucas Henrique Almeida', '111.222.333-44', '12.345.678-9', '1990-05-15', 'M', 'Casado(a)', '80010-100', 'Rua das Flores', '123', 'Centro', 'Curitiba', 'PR', '(41) 3333-1111', '(41) 98765-4321', 'lucas@alfatech.com.br', 'Desenvolvedor Full Stack', 'TI', '2020-03-01', 'CLT', 5500.00, 200.00, 25.00, '123.45678.90-1', '123456', '0001', 'PR', '001', 'Banco do Brasil', '1234-5', '12345-6', 'Corrente', 2, 'Ativo'),

(1, 'Camila Rodrigues Santos', '222.333.444-55', '23.456.789-0', '1992-08-22', 'F', 'Solteiro(a)', '80020-200', 'Av. Brasil', '456', 'Batel', 'Curitiba', 'PR', '(41) 3333-2222', '(41) 98765-1234', 'camila@alfatech.com.br', 'Designer UX/UI', 'TI', '2020-06-15', 'CLT', 4200.00, 200.00, 25.00, '234.56789.01-2', '234567', '0002', 'PR', '237', 'Bradesco', '2345-6', '23456-7', 'Corrente', 0, 'Ativo'),

(1, 'Rafael Costa Oliveira', '333.444.555-66', '34.567.890-1', '1988-11-10', 'M', 'Casado(a)', '80030-300', 'Rua XV de Novembro', '789', 'Centro', 'Curitiba', 'PR', '(41) 3333-3333', '(41) 98765-5678', 'rafael@alfatech.com.br', 'Gerente de Projetos', 'Gestão', '2019-01-10', 'CLT', 7000.00, 200.00, 25.00, '345.67890.12-3', '345678', '0003', 'PR', '341', 'Itaú', '3456-7', '34567-8', 'Corrente', 1, 'Ativo'),

-- Funcionários da Beta Alimentos (cliente_id = 2)
(2, 'Juliana Ferreira Lima', '444.555.666-77', '45.678.901-2', '1995-03-18', 'F', 'Solteiro(a)', '80040-400', 'Rua das Palmeiras', '321', 'Água Verde', 'Curitiba', 'PR', '(41) 3344-1111', '(41) 97654-3210', 'juliana@betaalimentos.com.br', 'Gerente de Vendas', 'Comercial', '2019-05-01', 'CLT', 4800.00, 200.00, 25.00, '456.78901.23-4', '456789', '0004', 'PR', '104', 'Caixa Econômica', '4567-8', '45678-9', 'Corrente', 0, 'Ativo'),

(2, 'Bruno Henrique Souza', '555.666.777-88', '56.789.012-3', '1987-07-25', 'M', 'Casado(a)', '80050-500', 'Av. Paraná', '654', 'Centro', 'Curitiba', 'PR', '(41) 3344-2222', '(41) 97654-6789', 'bruno@betaalimentos.com.br', 'Supervisor de Estoque', 'Logística', '2018-08-15', 'CLT', 3500.00, 200.00, 25.00, '567.89012.34-5', '567890', '0005', 'PR', '001', 'Banco do Brasil', '5678-9', '56789-0', 'Corrente', 3, 'Ativo'),

(2, 'Amanda Silva Pereira', '666.777.888-99', '67.890.123-4', '1993-12-05', 'F', 'União Estável', '80060-600', 'Rua Marechal Deodoro', '987', 'Centro', 'Curitiba', 'PR', '(41) 3344-3333', '(41) 97654-9876', 'amanda@betaalimentos.com.br', 'Assistente Administrativo', 'Administrativo', '2020-02-01', 'CLT', 2800.00, 200.00, 25.00, '678.90123.45-6', '678901', '0006', 'PR', '237', 'Bradesco', '6789-0', '67890-1', 'Corrente', 1, 'Ativo'),

(2, 'Thiago Martins Rocha', '777.888.999-00', '78.901.234-5', '1991-09-14', 'M', 'Solteiro(a)', '80070-700', 'Rua Comendador Araújo', '147', 'Centro', 'Curitiba', 'PR', '(41) 3344-4444', '(41) 97654-3456', 'thiago@betaalimentos.com.br', 'Motorista Entregador', 'Logística', '2019-11-20', 'CLT', 2500.00, 200.00, 25.00, '789.01234.56-7', '789012', '0007', 'PR', '341', 'Itaú', '7890-1', '78901-2', 'Corrente', 0, 'Ativo'),

-- Funcionários da Gamma Construções (cliente_id = 3)
(3, 'Rodrigo Alves Santos', '888.999.000-11', '89.012.345-6', '1985-04-20', 'M', 'Casado(a)', '80080-800', 'Av. Sete de Setembro', '258', 'Batel', 'Curitiba', 'PR', '(41) 3355-1111', '(41) 96543-2109', 'rodrigo@gammaconstrucoes.com.br', 'Engenheiro Civil', 'Engenharia', '2015-03-10', 'CLT', 9500.00, 200.00, 25.00, '890.12345.67-8', '890123', '0008', 'PR', '001', 'Banco do Brasil', '8901-2', '89012-3', 'Corrente', 2, 'Ativo'),

(3, 'Patrícia Oliveira Costa', '999.000.111-22', '90.123.456-7', '1989-06-30', 'F', 'Solteiro(a)', '80090-900', 'Rua Visconde de Guarapuava', '369', 'Centro', 'Curitiba', 'PR', '(41) 3355-2222', '(41) 96543-6789', 'patricia@gammaconstrucoes.com.br', 'Arquiteta', 'Projetos', '2016-07-01', 'CLT', 7200.00, 200.00, 25.00, '901.23456.78-9', '901234', '0009', 'PR', '237', 'Bradesco', '9012-3', '90123-4', 'Corrente', 0, 'Ativo'),

(3, 'Marcelo Henrique Silva', '000.111.222-33', '01.234.567-8', '1992-01-12', 'M', 'Casado(a)', '80100-000', 'Rua Barão do Rio Branco', '741', 'Centro', 'Curitiba', 'PR', '(41) 3355-3333', '(41) 96543-9876', 'marcelo@gammaconstrucoes.com.br', 'Mestre de Obras', 'Produção', '2017-09-15', 'CLT', 4500.00, 200.00, 25.00, '012.34567.89-0', '012345', '0010', 'PR', '341', 'Itaú', '0123-4', '01234-5', 'Corrente', 1, 'Ativo'),

(3, 'Fernanda Souza Lima', '111.222.333-45', '12.345.678-0', '1994-10-08', 'F', 'Solteiro(a)', '80110-100', 'Av. Cândido de Abreu', '852', 'Centro Cívico', 'Curitiba', 'PR', '(41) 3355-4444', '(41) 96543-3456', 'fernanda@gammaconstrucoes.com.br', 'Assistente de Compras', 'Suprimentos', '2018-12-01', 'CLT', 3200.00, 200.00, 25.00, '123.45678.90-2', '123456', '0011', 'PR', '104', 'Caixa Econômica', '1234-6', '12346-7', 'Corrente', 0, 'Ativo'),

(3, 'Diego Costa Pereira', '222.333.444-56', '23.456.789-1', '1986-08-17', 'M', 'Divorciado(a)', '80120-200', 'Rua Presidente Faria', '963', 'Centro', 'Curitiba', 'PR', '(41) 3355-5555', '(41) 96543-6543', 'diego@gammaconstrucoes.com.br', 'Pedreiro', 'Produção', '2014-05-20', 'CLT', 3000.00, 200.00, 25.00, '234.56789.01-3', '234567', '0012', 'PR', '001', 'Banco do Brasil', '2345-7', '23457-8', 'Corrente', 1, 'Ativo');

-- ============================================
-- TAREFAS
-- ============================================

INSERT INTO tarefas (cliente_id, titulo, descricao, prioridade, status, data_vencimento, responsavel_id, criado_por_id, setor_id) VALUES
(1, 'Enviar SPED Fiscal - Janeiro/2024', 'Preparar e enviar SPED Fiscal referente ao mês de janeiro de 2024', 'Alta', 'Concluída', '2024-02-20', 1, 1, 1),
(1, 'Gerar Holerites - Fevereiro/2024', 'Gerar e enviar holerites dos funcionários referente a fevereiro', 'Urgente', 'Em Andamento', '2024-03-05', 1, 1, 1),
(2, 'Renovar Certificado Digital', 'Certificado e-CNPJ vence em 30 dias, providenciar renovação', 'Alta', 'Pendente', '2024-03-15', 1, 1, 1),
(2, 'Balancete Mensal - Janeiro/2024', 'Elaborar balancete mensal de janeiro', 'Média', 'Concluída', '2024-02-10', 1, 1, 1),
(3, 'DCTF - Dezembro/2023', 'Enviar DCTF referente a dezembro de 2023', 'Alta', 'Concluída', '2024-01-15', 1, 1, 1),
(3, 'Análise de Inadimplência', 'Verificar situação de inadimplência e entrar em contato', 'Média', 'Pendente', '2024-03-10', 1, 1, 1),
(4, 'Folha de Pagamento - Fevereiro', 'Processar folha de pagamento de fevereiro', 'Urgente', 'Em Andamento', '2024-03-05', 1, 1, 1),
(5, 'Emitir Guias FGTS', 'Emitir guias de FGTS referente a fevereiro', 'Alta', 'Pendente', '2024-03-07', 1, 1, 1),
(6, 'Atualização Cadastral', 'Atualizar dados cadastrais da empresa na Receita Federal', 'Baixa', 'Pendente', '2024-03-20', 1, 1, 1),
(7, 'Reunião de Planejamento Tributário', 'Agendar reunião para discutir planejamento tributário 2024', 'Média', 'Pendente', '2024-03-12', 1, 1, 1);

-- ============================================
-- DOCUMENTOS
-- ============================================

INSERT INTO documentos (cliente_id, nome_arquivo, nome_original, tipo_documento, tamanho_bytes, extensao, caminho_arquivo, mes_referencia, ano_referencia, enviado_por_id, descricao) VALUES
(1, 'contrato_social_alfatech_2024.pdf', 'Contrato Social AlfaTech.pdf', 'Contrato', 524288, 'pdf', '/uploads/documentos/1/contrato_social_alfatech_2024.pdf', NULL, NULL, 1, 'Contrato social atualizado'),
(1, 'balancete_jan_2024.pdf', 'Balancete Janeiro 2024.pdf', 'Balanço', 245760, 'pdf', '/uploads/documentos/1/balancete_jan_2024.pdf', 1, 2024, 1, 'Balancete mensal de janeiro'),
(2, 'certidao_negativa_2024.pdf', 'Certidão Negativa.pdf', 'Certidão', 102400, 'pdf', '/uploads/documentos/2/certidao_negativa_2024.pdf', NULL, NULL, 1, 'Certidão negativa de débitos'),
(3, 'folha_pagamento_fev_2024.pdf', 'Folha Fevereiro 2024.pdf', 'Folha', 358400, 'pdf', '/uploads/documentos/3/folha_pagamento_fev_2024.pdf', 2, 2024, 1, 'Folha de pagamento de fevereiro'),
(4, 'dre_2023.pdf', 'DRE Anual 2023.pdf', 'DRE', 512000, 'pdf', '/uploads/documentos/4/dre_2023.pdf', 12, 2023, 1, 'Demonstração do Resultado do Exercício 2023'),
(5, 'guia_inss_fev_2024.pdf', 'GPS Fevereiro 2024.pdf', 'Guia', 81920, 'pdf', '/uploads/documentos/5/guia_inss_fev_2024.pdf', 2, 2024, 1, 'Guia de INSS de fevereiro');

-- ============================================
-- ALERTAS
-- ============================================

INSERT INTO alertas (tipo, titulo, mensagem, nivel, cliente_id, usuario_id, lido) VALUES
('Vencimento', 'Certificado Digital Vencendo', 'O certificado e-CNPJ da Beta Alimentos vence em 25 dias', 'Urgente', 2, 1, 0),
('Pendência', 'Documentos Pendentes', 'AlfaTech possui documentos pendentes de envio', 'Aviso', 1, 1, 0),
('Financeiro', 'Mensalidade em Atraso', 'Cliente Zeta Fashion está com mensalidade em atraso há 5 dias', 'Crítico', 6, 1, 0),
('Vencimento', 'Obrigação Fiscal Próxima', 'SPED Contribuições vence em 3 dias', 'Urgente', 3, 1, 0),
('Sistema', 'Backup Realizado', 'Backup automático do sistema realizado com sucesso', 'Info', NULL, 1, 1),
('Atraso', 'Tarefa Atrasada', 'Tarefa "Análise de Inadimplência" está atrasada', 'Aviso', 3, 1, 0);

-- ============================================
-- CERTIFICADOS DIGITAIS
-- ============================================

INSERT INTO certificados_digitais (cliente_id, tipo, titular, cpf_cnpj, data_emissao, data_validade, certificadora, senha_certificado, arquivo_certificado, dias_alerta_vencimento, status) VALUES
(1, 'e-CNPJ A1', 'ALFATECH SOLUÇÕES EM TECNOLOGIA LTDA', '11.222.333/0001-44', '2023-03-15', '2024-03-14', 'Certisign', 'senha123', '/certificados/alfatech_ecnpj_2023.pfx', 30, 'Ativo'),
(2, 'e-CNPJ A3', 'BETA COMÉRCIO DE ALIMENTOS LTDA', '22.333.444/0001-55', '2021-06-20', '2024-06-19', 'Serasa', 'beta2021', '/certificados/beta_ecnpj_2021.pfx', 30, 'Ativo'),
(3, 'e-CNPJ A1', 'GAMMA CONSTRUÇÕES E REFORMAS LTDA', '33.444.555/0001-66', '2023-09-10', '2024-09-09', 'Valid', 'gamma@2023', '/certificados/gamma_ecnpj_2023.pfx', 30, 'Ativo'),
(4, 'NF-e A1', 'DELTA SERVIÇOS MÉDICOS LTDA', '44.555.666/0001-77', '2023-02-14', '2024-02-13', 'Certisign', 'delta#nfe', '/certificados/delta_nfe_2023.pfx', 30, 'Vencido'),
(5, 'e-CNPJ A3', 'EPSILON TRANSPORTES RODOVIÁRIOS LTDA', '55.666.777/0001-88', '2022-11-25', '2025-11-24', 'Serasa', 'epsilon2022', '/certificados/epsilon_ecnpj_2022.pfx', 30, 'Ativo'),
(6, 'NF-e A1', 'ZETA MODA E CONFECÇÕES LTDA', '66.777.888/0001-99', '2023-04-10', '2024-04-09', 'Valid', 'zeta@nfe', '/certificados/zeta_nfe_2023.pfx', 30, 'Ativo'),
(7, 'e-CPF A1', 'Roberto Alves Pereira', '789.012.345-66', '2023-01-20', '2024-01-19', 'Certisign', 'roberto123', '/certificados/roberto_ecpf_2023.pfx', 30, 'Vencido'),
(8, 'e-CNPJ A1', 'THETA ACADEMIA E FITNESS LTDA', '88.999.000/0001-11', '2023-07-05', '2024-07-04', 'Serasa', 'theta@2023', '/certificados/theta_ecnpj_2023.pfx', 30, 'Ativo'),
(9, 'NF-e A1', 'IOTA RESTAURANTE E LANCHONETE LTDA', '99.000.111/0001-22', '2023-05-18', '2024-05-17', 'Valid', 'iota#nfe', '/certificados/iota_nfe_2023.pfx', 30, 'Ativo'),
(10, 'e-CNPJ A3', 'KAPPA FARMÁCIA E DROGARIA LTDA', '00.111.222/0001-33', '2022-08-30', '2025-08-29', 'Certisign', 'kappa2022', '/certificados/kappa_ecnpj_2022.pfx', 30, 'Ativo');

-- ============================================
-- HOLERITES
-- ============================================

INSERT INTO holerites (funcionario_id, cliente_id, mes_referencia, ano_referencia, salario_base, horas_extras, adicional_noturno, comissoes, bonus, total_proventos, inss, irrf, vale_transporte_desc, vale_refeicao_desc, outros_descontos, total_descontos, salario_liquido, data_pagamento, status) VALUES
-- Holerites de Janeiro 2024
(1, 1, 1, 2024, 5500.00, 0.00, 0.00, 0.00, 0.00, 5500.00, 671.49, 413.42, 0.00, 0.00, 0.00, 1084.91, 4415.09, '2024-02-05', 'Pago'),
(2, 1, 1, 2024, 4200.00, 0.00, 0.00, 0.00, 0.00, 4200.00, 462.00, 198.53, 0.00, 0.00, 0.00, 660.53, 3539.47, '2024-02-05', 'Pago'),
(3, 1, 1, 2024, 7000.00, 0.00, 0.00, 0.00, 500.00, 7500.00, 828.38, 1065.37, 0.00, 0.00, 0.00, 1893.75, 5606.25, '2024-02-05', 'Pago'),
(4, 2, 1, 2024, 4800.00, 0.00, 0.00, 350.00, 0.00, 5150.00, 566.50, 315.78, 0.00, 0.00, 0.00, 882.28, 4267.72, '2024-02-05', 'Pago'),
(5, 2, 1, 2024, 3500.00, 0.00, 0.00, 0.00, 0.00, 3500.00, 350.00, 69.20, 0.00, 0.00, 0.00, 419.20, 3080.80, '2024-02-05', 'Pago'),
(6, 2, 1, 2024, 2800.00, 0.00, 0.00, 0.00, 0.00, 2800.00, 252.00, 0.00, 0.00, 0.00, 0.00, 252.00, 2548.00, '2024-02-05', 'Pago'),
(7, 2, 1, 2024, 2500.00, 0.00, 0.00, 0.00, 0.00, 2500.00, 225.00, 0.00, 0.00, 0.00, 0.00, 225.00, 2275.00, '2024-02-05', 'Pago'),
(8, 3, 1, 2024, 9500.00, 0.00, 0.00, 0.00, 1000.00, 10500.00, 828.38, 1965.37, 0.00, 0.00, 0.00, 2793.75, 7706.25, '2024-02-05', 'Pago'),
(9, 3, 1, 2024, 7200.00, 0.00, 0.00, 0.00, 0.00, 7200.00, 828.38, 978.37, 0.00, 0.00, 0.00, 1806.75, 5393.25, '2024-02-05', 'Pago'),
(10, 3, 1, 2024, 4500.00, 200.00, 0.00, 0.00, 0.00, 4700.00, 517.00, 265.78, 0.00, 0.00, 0.00, 782.78, 3917.22, '2024-02-05', 'Pago'),

-- Holerites de Fevereiro 2024
(1, 1, 2, 2024, 5500.00, 0.00, 0.00, 0.00, 0.00, 5500.00, 671.49, 413.42, 0.00, 0.00, 0.00, 1084.91, 4415.09, NULL, 'Pendente'),
(2, 1, 2, 2024, 4200.00, 0.00, 0.00, 0.00, 0.00, 4200.00, 462.00, 198.53, 0.00, 0.00, 0.00, 660.53, 3539.47, NULL, 'Pendente'),
(3, 1, 2, 2024, 7000.00, 0.00, 0.00, 0.00, 0.00, 7000.00, 828.38, 965.37, 0.00, 0.00, 0.00, 1793.75, 5206.25, NULL, 'Pendente'),
(4, 2, 2, 2024, 4800.00, 0.00, 0.00, 280.00, 0.00, 5080.00, 558.80, 308.28, 0.00, 0.00, 0.00, 867.08, 4212.92, NULL, 'Pendente'),
(5, 2, 2, 2024, 3500.00, 0.00, 0.00, 0.00, 0.00, 3500.00, 350.00, 69.20, 0.00, 0.00, 0.00, 419.20, 3080.80, NULL, 'Pendente');

-- ============================================
-- FÉRIAS
-- ============================================

INSERT INTO ferias (funcionario_id, cliente_id, periodo_aquisitivo_inicio, periodo_aquisitivo_fim, periodo_concessivo_inicio, periodo_concessivo_fim, data_inicio_ferias, data_fim_ferias, dias_ferias, abono_pecuniario_dias, valor_ferias, valor_um_terco, valor_abono, valor_total, data_pagamento, status) VALUES
(1, 1, '2020-03-01', '2021-02-28', '2021-03-01', '2022-02-28', '2021-12-20', '2022-01-18', 30, 0, 5500.00, 1833.33, 0.00, 7333.33, '2021-12-15', 'Concluído'),
(2, 1, '2020-06-15', '2021-06-14', '2021-06-15', '2022-06-14', '2022-01-10', '2022-02-08', 30, 0, 4200.00, 1400.00, 0.00, 5600.00, '2022-01-05', 'Concluído'),
(3, 1, '2019-01-10', '2020-01-09', '2020-01-10', '2021-01-09', '2020-07-01', '2020-07-30', 30, 0, 7000.00, 2333.33, 0.00, 9333.33, '2020-06-25', 'Concluído'),
(4, 2, '2019-05-01', '2020-04-30', '2020-05-01', '2021-04-30', '2020-12-21', '2021-01-19', 30, 0, 4800.00, 1600.00, 0.00, 6400.00, '2020-12-15', 'Concluído'),
(5, 2, '2018-08-15', '2019-08-14', '2019-08-15', '2020-08-14', '2020-01-06', '2020-02-04', 30, 0, 3500.00, 1166.67, 0.00, 4666.67, '2020-01-02', 'Concluído'),
(8, 3, '2015-03-10', '2016-03-09', '2016-03-10', '2017-03-09', '2016-07-01', '2016-07-30', 30, 0, 9500.00, 3166.67, 0.00, 12666.67, '2016-06-25', 'Concluído'),
(1, 1, '2023-03-01', '2024-02-29', '2024-03-01', '2025-02-28', '2024-07-01', '2024-07-30', 30, 0, 5500.00, 1833.33, 0.00, 7333.33, NULL, 'Programado'),
(4, 2, '2023-05-01', '2024-04-30', '2024-05-01', '2025-04-30', '2024-12-20', '2025-01-18', 30, 0, 4800.00, 1600.00, 0.00, 6400.00, NULL, 'Programado');

-- ============================================
-- RESCISÕES
-- ============================================

INSERT INTO rescisoes (funcionario_id, cliente_id, data_demissao, tipo_rescisao, motivo, aviso_previo_tipo, aviso_previo_dias, saldo_salario, ferias_vencidas, ferias_proporcionais, um_terco_ferias, decimo_terceiro_proporcional, multa_fgts, total_proventos, total_descontos, valor_liquido, data_pagamento, status) VALUES
(11, 3, '2024-01-31', 'Sem Justa Causa', 'Redução de quadro', 'Indenizado', 30, 3200.00, 0.00, 266.67, 88.89, 266.67, 1280.00, 5102.23, 0.00, 5102.23, '2024-02-05', 'Pago'),
(12, 3, '2023-12-15', 'Pedido de Demissão', 'Proposta em outra empresa', 'Trabalhado', 30, 3000.00, 0.00, 2750.00, 916.67, 2750.00, 0.00, 9416.67, 0.00, 9416.67, '2023-12-20', 'Pago');

-- ============================================
-- DEPÓSITOS FGTS
-- ============================================

INSERT INTO fgts_depositos (funcionario_id, cliente_id, mes_referencia, ano_referencia, salario_base, percentual, valor_deposito, data_vencimento, data_pagamento, status) VALUES
-- FGTS Janeiro 2024
(1, 1, 1, 2024, 5500.00, 8.00, 440.00, '2024-02-07', '2024-02-05', 'Pago'),
(2, 1, 1, 2024, 4200.00, 8.00, 336.00, '2024-02-07', '2024-02-05', 'Pago'),
(3, 1, 1, 2024, 7000.00, 8.00, 560.00, '2024-02-07', '2024-02-05', 'Pago'),
(4, 2, 1, 2024, 4800.00, 8.00, 384.00, '2024-02-07', '2024-02-05', 'Pago'),
(5, 2, 1, 2024, 3500.00, 8.00, 280.00, '2024-02-07', '2024-02-05', 'Pago'),
(6, 2, 1, 2024, 2800.00, 8.00, 224.00, '2024-02-07', '2024-02-05', 'Pago'),
(7, 2, 1, 2024, 2500.00, 8.00, 200.00, '2024-02-07', '2024-02-05', 'Pago'),
(8, 3, 1, 2024, 9500.00, 8.00, 760.00, '2024-02-07', '2024-02-05', 'Pago'),
(9, 3, 1, 2024, 7200.00, 8.00, 576.00, '2024-02-07', '2024-02-05', 'Pago'),
(10, 3, 1, 2024, 4500.00, 8.00, 360.00, '2024-02-07', '2024-02-05', 'Pago'),

-- FGTS Fevereiro 2024
(1, 1, 2, 2024, 5500.00, 8.00, 440.00, '2024-03-07', NULL, 'Pendente'),
(2, 1, 2, 2024, 4200.00, 8.00, 336.00, '2024-03-07', NULL, 'Pendente'),
(3, 1, 2, 2024, 7000.00, 8.00, 560.00, '2024-03-07', NULL, 'Pendente'),
(4, 2, 2, 2024, 4800.00, 8.00, 384.00, '2024-03-07', NULL, 'Pendente'),
(5, 2, 2, 2024, 3500.00, 8.00, 280.00, '2024-03-07', NULL, 'Pendente');

-- ============================================
-- NOTAS FISCAIS
-- ============================================

INSERT INTO notas_fiscais (cliente_id, tipo_nota, numero_nota, serie, chave_acesso, data_emissao, destinatario_nome, destinatario_cnpj_cpf, valor_produtos, valor_servicos, valor_icms, valor_ipi, valor_pis, valor_cofins, valor_iss, valor_total, status, data_autorizacao, protocolo_autorizacao, xml_nota, pdf_nota) VALUES
(1, 'NF-e', 1001, '1', '41240111222333000144550010000010011234567890', '2024-01-15', 'Cliente Exemplo LTDA', '12.345.678/0001-90', 15000.00, 0.00, 2550.00, 0.00, 247.50, 1140.00, 0.00, 15000.00, 'Autorizada', '2024-01-15 10:30:00', '141240000012345', '/nfe/xml/1001.xml', '/nfe/pdf/1001.pdf'),
(1, 'NF-e', 1002, '1', '41240111222333000144550010000010021234567891', '2024-01-20', 'Empresa ABC S.A.', '98.765.432/0001-10', 8500.00, 0.00, 1445.00, 0.00, 140.25, 646.00, 0.00, 8500.00, 'Autorizada', '2024-01-20 14:15:00', '141240000012346', '/nfe/xml/1002.xml', '/nfe/pdf/1002.pdf'),
(2, 'NF-e', 2001, '1', '41240122333444000155550010000020011234567892', '2024-01-10', 'Supermercado XYZ LTDA', '11.222.333/0001-44', 25000.00, 0.00, 4250.00, 0.00, 412.50, 1900.00, 0.00, 25000.00, 'Autorizada', '2024-01-10 09:00:00', '141240000012347', '/nfe/xml/2001.xml', '/nfe/pdf/2001.pdf'),
(2, 'NF-e', 2002, '1', '41240122333444000155550010000020021234567893', '2024-01-25', 'Restaurante Bom Sabor', '22.333.444/0001-55', 12000.00, 0.00, 2040.00, 0.00, 198.00, 912.00, 0.00, 12000.00, 'Autorizada', '2024-01-25 11:45:00', '141240000012348', '/nfe/xml/2002.xml', '/nfe/pdf/2002.pdf'),
(3, 'NF-e', 3001, '1', '41240133444555000166550010000030011234567894', '2024-01-05', 'Construtora Delta LTDA', '33.444.555/0001-66', 85000.00, 0.00, 14450.00, 0.00, 1402.50, 6460.00, 0.00, 85000.00, 'Autorizada', '2024-01-05 16:20:00', '141240000012349', '/nfe/xml/3001.xml', '/nfe/pdf/3001.pdf'),
(4, 'NFS-e', 4001, '1', NULL, '2024-01-12', 'João da Silva', '123.456.789-00', 0.00, 1500.00, 0.00, 0.00, 0.00, 0.00, 75.00, 1500.00, 'Autorizada', '2024-01-12 10:00:00', 'NFSE-2024-4001', '/nfse/xml/4001.xml', '/nfse/pdf/4001.pdf'),
(4, 'NFS-e', 4002, '1', NULL, '2024-01-18', 'Maria Santos', '987.654.321-00', 0.00, 2200.00, 0.00, 0.00, 0.00, 0.00, 110.00, 2200.00, 'Autorizada', '2024-01-18 14:30:00', 'NFSE-2024-4002', '/nfse/xml/4002.xml', '/nfse/pdf/4002.pdf'),
(5, 'CT-e', 5001, '1', '41240155666777000188570010000050011234567895', '2024-01-08', 'Indústria Metalúrgica S.A.', '44.555.666/0001-77', 0.00, 3500.00, 0.00, 0.00, 57.75, 266.00, 0.00, 3500.00, 'Autorizada', '2024-01-08 08:15:00', '141240000012350', '/cte/xml/5001.xml', '/cte/pdf/5001.pdf'),
(6, 'NFC-e', 6001, '1', '41240166777888000199550010000060011234567896', '2024-01-22', 'Consumidor Final', NULL, 450.00, 0.00, 76.50, 0.00, 7.43, 34.20, 0.00, 450.00, 'Autorizada', '2024-01-22 15:45:00', '141240000012351', '/nfce/xml/6001.xml', '/nfce/pdf/6001.pdf'),
(6, 'NFC-e', 6002, '1', '41240166777888000199550010000060021234567897', '2024-01-28', 'Consumidor Final', NULL, 780.00, 0.00, 132.60, 0.00, 12.87, 59.28, 0.00, 780.00, 'Autorizada', '2024-01-28 17:20:00', '141240000012352', '/nfce/xml/6002.xml', '/nfce/pdf/6002.pdf');

-- ============================================
-- LANÇAMENTOS CONTÁBEIS
-- ============================================

INSERT INTO lancamentos_contabeis (cliente_id, data_lancamento, tipo_lancamento, conta_debito, conta_credito, historico, valor, documento_origem, mes_competencia, ano_competencia) VALUES
(1, '2024-01-05', 'Débito', '1.1.1.01.001', '2.1.1.01.001', 'Pagamento de fornecedor - Material de escritório', 1500.00, 'NF 12345', 1, 2024),
(1, '2024-01-10', 'Crédito', '1.1.1.01.001', '3.1.1.01.001', 'Recebimento de cliente - Serviços prestados', 8500.00, 'NF 1001', 1, 2024),
(1, '2024-01-15', 'Débito', '4.1.1.01.001', '1.1.1.01.001', 'Pagamento de salários - Janeiro/2024', 17200.00, 'Folha 01/2024', 1, 2024),
(2, '2024-01-08', 'Crédito', '1.1.1.01.001', '3.1.1.01.001', 'Venda de mercadorias', 25000.00, 'NF 2001', 1, 2024),
(2, '2024-01-12', 'Débito', '4.1.1.02.001', '1.1.1.01.001', 'Pagamento de aluguel - Janeiro/2024', 3500.00, 'Recibo 001', 1, 2024),
(3, '2024-01-05', 'Crédito', '1.1.2.01.001', '3.1.1.01.001', 'Recebimento de obra - Fase 1', 85000.00, 'NF 3001', 1, 2024),
(3, '2024-01-20', 'Débito', '4.1.1.01.001', '1.1.1.01.001', 'Pagamento de salários - Janeiro/2024', 24200.00, 'Folha 01/2024', 1, 2024),
(4, '2024-01-12', 'Crédito', '1.1.1.01.001', '3.1.1.02.001', 'Recebimento de consultas médicas', 1500.00, 'NFS-e 4001', 1, 2024),
(5, '2024-01-08', 'Crédito', '1.1.2.01.001', '3.1.1.02.001', 'Recebimento de frete', 3500.00, 'CT-e 5001', 1, 2024),
(6, '2024-01-22', 'Crédito', '1.1.1.01.001', '3.1.1.01.001', 'Venda de roupas', 450.00, 'NFC-e 6001', 1, 2024);

-- Mensagem de conclusão
SELECT 'Dados fictícios inseridos com sucesso!' as Mensagem;
SELECT COUNT(*) as 'Total de Clientes' FROM clientes;
SELECT COUNT(*) as 'Total de Funcionários' FROM funcionarios_clientes;
SELECT COUNT(*) as 'Total de Tarefas' FROM tarefas;
SELECT COUNT(*) as 'Total de Documentos' FROM documentos;
SELECT COUNT(*) as 'Total de Alertas' FROM alertas;
SELECT COUNT(*) as 'Total de Certificados' FROM certificados_digitais;
SELECT COUNT(*) as 'Total de Holerites' FROM holerites;
SELECT COUNT(*) as 'Total de Férias' FROM ferias;
SELECT COUNT(*) as 'Total de Rescisões' FROM rescisoes;
SELECT COUNT(*) as 'Total de FGTS' FROM fgts_depositos;
SELECT COUNT(*) as 'Total de Notas Fiscais' FROM notas_fiscais;
SELECT COUNT(*) as 'Total de Lançamentos' FROM lancamentos_contabeis;

