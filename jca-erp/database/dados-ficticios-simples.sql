-- ============================================
-- DADOS FICTÍCIOS SIMPLIFICADOS
-- JCA ERP - Sistema de Gestão Contábil
-- ============================================

USE jca_erp;

-- Limpar dados existentes (cuidado!)
-- DELETE FROM funcionarios_clientes;
-- DELETE FROM tarefas;
-- DELETE FROM documentos;
-- DELETE FROM alertas;
-- DELETE FROM clientes WHERE id > 1;

-- ============================================
-- CLIENTES FICTÍCIOS (IDs 2-11)
-- ============================================

INSERT INTO clientes (razao_social, nome_fantasia, cnpj, inscricao_estadual, inscricao_municipal, regime_tributario, porte_empresa, cnae_principal, data_abertura, cep, logradouro, numero, complemento, bairro, cidade, estado, telefone, celular, email, responsavel_nome, responsavel_cpf, responsavel_email, responsavel_telefone, data_inicio_contrato, valor_mensalidade, dia_vencimento, status_contrato, contador_responsavel_id) VALUES
('ALFATECH SOLUÇÕES EM TECNOLOGIA LTDA', 'AlfaTech', '22.333.444/0001-55', '223.334.445.556', '98765432', 'Simples Nacional', 'ME', '6201-5/00', '2018-03-15', '80010-000', 'Rua XV de Novembro', '1500', 'Sala 301', 'Centro', 'Curitiba', 'PR', '(41) 3333-4444', '(41) 99999-8888', 'contato@alfatech.com.br', 'João Silva Santos', '223.334.445-56', 'joao@alfatech.com.br', '(41) 99999-8888', '2020-01-01', 850.00, 10, 'Ativo', 1),

('BETA COMÉRCIO DE ALIMENTOS LTDA', 'Beta Alimentos', '33.444.555/0001-66', '334.445.556.667', '87654321', 'Simples Nacional', 'EPP', '4711-3/02', '2015-06-20', '80020-100', 'Av. Sete de Setembro', '2800', NULL, 'Batel', 'Curitiba', 'PR', '(41) 3344-5566', '(41) 98888-7777', 'contato@betaalimentos.com.br', 'Maria Oliveira Costa', '334.445.556-67', 'maria@betaalimentos.com.br', '(41) 98888-7777', '2019-03-15', 1200.00, 10, 'Ativo', 1),

('GAMMA CONSTRUÇÕES E REFORMAS LTDA', 'Gamma Construções', '44.555.666/0001-77', '445.556.667.778', '76543210', 'Lucro Presumido', 'Médio', '4120-4/00', '2012-09-10', '80030-200', 'Rua Marechal Deodoro', '450', 'Galpão 2', 'Centro', 'Curitiba', 'PR', '(41) 3355-6677', '(41) 97777-6666', 'contato@gammaconstrucoes.com.br', 'Pedro Henrique Souza', '445.556.667-78', 'pedro@gammaconstrucoes.com.br', '(41) 97777-6666', '2018-06-01', 2500.00, 10, 'Ativo', 1),

('DELTA SERVIÇOS MÉDICOS LTDA', 'Clínica Delta', '55.666.777/0001-88', '556.667.778.889', '65432109', 'Simples Nacional', 'ME', '8630-5/03', '2019-02-14', '80040-300', 'Rua Comendador Araújo', '720', 'Conjunto 501', 'Centro', 'Curitiba', 'PR', '(41) 3366-7788', '(41) 96666-5555', 'contato@clinicadelta.com.br', 'Dra. Ana Paula Lima', '556.667.778-89', 'ana@clinicadelta.com.br', '(41) 96666-5555', '2019-08-01', 950.00, 10, 'Ativo', 1),

('EPSILON TRANSPORTES RODOVIÁRIOS LTDA', 'Epsilon Transportes', '66.777.888/0001-99', '667.778.889.990', '54321098', 'Lucro Real', 'Grande', '4930-2/02', '2010-11-25', '80050-400', 'Av. Cândido de Abreu', '3200', NULL, 'Centro Cívico', 'Curitiba', 'PR', '(41) 3377-8899', '(41) 95555-4444', 'contato@epsilontransportes.com.br', 'Carlos Eduardo Ferreira', '667.778.889-90', 'carlos@epsilontransportes.com.br', '(41) 95555-4444', '2017-01-15', 3800.00, 10, 'Ativo', 1),

('ZETA MODA E CONFECÇÕES LTDA', 'Zeta Fashion', '77.888.999/0001-00', '778.889.990.001', '43210987', 'Simples Nacional', 'ME', '1412-6/01', '2020-04-10', '80060-500', 'Rua Barão do Rio Branco', '890', 'Loja 3', 'Centro', 'Curitiba', 'PR', '(41) 3388-9900', '(41) 94444-3333', 'contato@zetafashion.com.br', 'Juliana Martins Rocha', '778.889.990-01', 'juliana@zetafashion.com.br', '(41) 94444-3333', '2020-09-01', 750.00, 10, 'Ativo', 1),

('ETA CONSULTORIA EMPRESARIAL LTDA', 'Eta Consultoria', '88.999.000/0001-11', '889.990.001.112', '32109876', 'Simples Nacional', 'ME', '7020-4/00', '2021-01-20', '80070-600', 'Rua Visconde de Guarapuava', '1200', 'Sala 1005', 'Centro', 'Curitiba', 'PR', '(41) 3399-0011', '(41) 93333-2222', 'contato@etaconsultoria.com.br', 'Roberto Alves Pereira', '889.990.001-12', 'roberto@etaconsultoria.com.br', '(41) 93333-2222', '2021-03-01', 680.00, 10, 'Ativo', 1),

('THETA ACADEMIA E FITNESS LTDA', 'Theta Fitness', '99.000.111/0001-22', '990.001.112.223', '21098765', 'Simples Nacional', 'ME', '9313-1/00', '2017-07-05', '80080-700', 'Av. Marechal Floriano Peixoto', '560', NULL, 'Rebouças', 'Curitiba', 'PR', '(41) 3300-1122', '(41) 92222-1111', 'contato@thetafitness.com.br', 'Marcos Vinícius Santos', '990.001.112-23', 'marcos@thetafitness.com.br', '(41) 92222-1111', '2018-02-01', 820.00, 10, 'Ativo', 1),

('IOTA RESTAURANTE E LANCHONETE LTDA', 'Iota Gourmet', '00.111.222/0001-33', '001.112.223.334', '10987654', 'Simples Nacional', 'ME', '5611-2/01', '2019-05-18', '80090-800', 'Rua Presidente Faria', '340', NULL, 'Centro', 'Curitiba', 'PR', '(41) 3311-2233', '(41) 91111-0000', 'contato@iotagourmet.com.br', 'Fernanda Costa Ribeiro', '001.112.223-34', 'fernanda@iotagourmet.com.br', '(41) 91111-0000', '2019-10-01', 890.00, 10, 'Ativo', 1),

('KAPPA FARMÁCIA E DROGARIA LTDA', 'Kappa Farma', '11.222.333/0001-44', '112.223.334.445', '09876543', 'Simples Nacional', 'EPP', '4771-7/01', '2016-08-30', '80100-900', 'Rua Conselheiro Laurindo', '670', NULL, 'Centro', 'Curitiba', 'PR', '(41) 3322-3344', '(41) 90000-9999', 'contato@kappafarma.com.br', 'Ricardo Mendes Silva', '112.223.334-45', 'ricardo@kappafarma.com.br', '(41) 90000-9999', '2017-05-01', 1100.00, 10, 'Ativo', 1);

-- ============================================
-- FUNCIONÁRIOS DOS CLIENTES
-- ============================================

-- Funcionários da AlfaTech (cliente_id = 2)
INSERT INTO funcionarios_clientes (cliente_id, nome_completo, cpf, rg, data_nascimento, sexo, estado_civil, cep, logradouro, numero, bairro, cidade, estado, telefone, celular, email, cargo, departamento, data_admissao, tipo_contrato, salario_base, vale_transporte, vale_refeicao, pis_pasep, ctps_numero, ctps_serie, ctps_uf, banco_codigo, banco_nome, agencia, conta, tipo_conta, numero_dependentes, status) VALUES
(2, 'Lucas Henrique Almeida', '211.222.333-44', '22.345.678-9', '1990-05-15', 'M', 'Casado(a)', '80010-100', 'Rua das Flores', '123', 'Centro', 'Curitiba', 'PR', '(41) 3333-1111', '(41) 98765-4321', 'lucas@alfatech.com.br', 'Desenvolvedor Full Stack', 'TI', '2020-03-01', 'CLT', 5500.00, 200.00, 25.00, '223.45678.90-1', '223456', '0001', 'PR', '001', 'Banco do Brasil', '1234-5', '12345-6', 'Corrente', 2, 'Ativo'),
(2, 'Camila Rodrigues Santos', '222.333.444-55', '23.456.789-0', '1992-08-22', 'F', 'Solteiro(a)', '80020-200', 'Av. Brasil', '456', 'Batel', 'Curitiba', 'PR', '(41) 3333-2222', '(41) 98765-1234', 'camila@alfatech.com.br', 'Designer UX/UI', 'TI', '2020-06-15', 'CLT', 4200.00, 200.00, 25.00, '234.56789.01-2', '234567', '0002', 'PR', '237', 'Bradesco', '2345-6', '23456-7', 'Corrente', 0, 'Ativo'),
(2, 'Rafael Costa Oliveira', '233.444.555-66', '24.567.890-1', '1988-11-10', 'M', 'Casado(a)', '80030-300', 'Rua XV de Novembro', '789', 'Centro', 'Curitiba', 'PR', '(41) 3333-3333', '(41) 98765-5678', 'rafael@alfatech.com.br', 'Gerente de Projetos', 'Gestão', '2019-01-10', 'CLT', 7000.00, 200.00, 25.00, '245.67890.12-3', '345678', '0003', 'PR', '341', 'Itaú', '3456-7', '34567-8', 'Corrente', 1, 'Ativo'),

-- Funcionários da Beta Alimentos (cliente_id = 3)
(3, 'Juliana Ferreira Lima', '344.555.666-77', '35.678.901-2', '1995-03-18', 'F', 'Solteiro(a)', '80040-400', 'Rua das Palmeiras', '321', 'Água Verde', 'Curitiba', 'PR', '(41) 3344-1111', '(41) 97654-3210', 'juliana@betaalimentos.com.br', 'Gerente de Vendas', 'Comercial', '2019-05-01', 'CLT', 4800.00, 200.00, 25.00, '356.78901.23-4', '456789', '0004', 'PR', '104', 'Caixa Econômica', '4567-8', '45678-9', 'Corrente', 0, 'Ativo'),
(3, 'Bruno Henrique Souza', '455.666.777-88', '46.789.012-3', '1987-07-25', 'M', 'Casado(a)', '80050-500', 'Av. Paraná', '654', 'Centro', 'Curitiba', 'PR', '(41) 3344-2222', '(41) 97654-6789', 'bruno@betaalimentos.com.br', 'Supervisor de Estoque', 'Logística', '2018-08-15', 'CLT', 3500.00, 200.00, 25.00, '467.89012.34-5', '567890', '0005', 'PR', '001', 'Banco do Brasil', '5678-9', '56789-0', 'Corrente', 3, 'Ativo'),
(3, 'Amanda Silva Pereira', '566.777.888-99', '57.890.123-4', '1993-12-05', 'F', 'União Estável', '80060-600', 'Rua Marechal Deodoro', '987', 'Centro', 'Curitiba', 'PR', '(41) 3344-3333', '(41) 97654-9876', 'amanda@betaalimentos.com.br', 'Assistente Administrativo', 'Administrativo', '2020-02-01', 'CLT', 2800.00, 200.00, 25.00, '578.90123.45-6', '678901', '0006', 'PR', '237', 'Bradesco', '6789-0', '67890-1', 'Corrente', 1, 'Ativo'),
(3, 'Thiago Martins Rocha', '677.888.999-00', '68.901.234-5', '1991-09-14', 'M', 'Solteiro(a)', '80070-700', 'Rua Comendador Araújo', '147', 'Centro', 'Curitiba', 'PR', '(41) 3344-4444', '(41) 97654-3456', 'thiago@betaalimentos.com.br', 'Motorista Entregador', 'Logística', '2019-11-20', 'CLT', 2500.00, 200.00, 25.00, '689.01234.56-7', '789012', '0007', 'PR', '341', 'Itaú', '7890-1', '78901-2', 'Corrente', 0, 'Ativo'),

-- Funcionários da Gamma Construções (cliente_id = 4)
(4, 'Rodrigo Alves Santos', '788.999.000-11', '79.012.345-6', '1985-04-20', 'M', 'Casado(a)', '80080-800', 'Av. Sete de Setembro', '258', 'Batel', 'Curitiba', 'PR', '(41) 3355-1111', '(41) 96543-2109', 'rodrigo@gammaconstrucoes.com.br', 'Engenheiro Civil', 'Engenharia', '2015-03-10', 'CLT', 9500.00, 200.00, 25.00, '790.12345.67-8', '890123', '0008', 'PR', '001', 'Banco do Brasil', '8901-2', '89012-3', 'Corrente', 2, 'Ativo'),
(4, 'Patrícia Oliveira Costa', '899.000.111-22', '80.123.456-7', '1989-06-30', 'F', 'Solteiro(a)', '80090-900', 'Rua Visconde de Guarapuava', '369', 'Centro', 'Curitiba', 'PR', '(41) 3355-2222', '(41) 96543-6789', 'patricia@gammaconstrucoes.com.br', 'Arquiteta', 'Projetos', '2016-07-01', 'CLT', 7200.00, 200.00, 25.00, '801.23456.78-9', '901234', '0009', 'PR', '237', 'Bradesco', '9012-3', '90123-4', 'Corrente', 0, 'Ativo'),
(4, 'Marcelo Henrique Silva', '900.111.222-33', '91.234.567-8', '1992-01-12', 'M', 'Casado(a)', '80100-000', 'Rua Barão do Rio Branco', '741', 'Centro', 'Curitiba', 'PR', '(41) 3355-3333', '(41) 96543-9876', 'marcelo@gammaconstrucoes.com.br', 'Mestre de Obras', 'Produção', '2017-09-15', 'CLT', 4500.00, 200.00, 25.00, '912.34567.89-0', '012345', '0010', 'PR', '341', 'Itaú', '0123-4', '01234-5', 'Corrente', 1, 'Ativo'),
(4, 'Fernanda Souza Lima', '911.222.333-45', '92.345.678-0', '1994-10-08', 'F', 'Solteiro(a)', '80110-100', 'Av. Cândido de Abreu', '852', 'Centro Cívico', 'Curitiba', 'PR', '(41) 3355-4444', '(41) 96543-3456', 'fernanda@gammaconstrucoes.com.br', 'Assistente de Compras', 'Suprimentos', '2018-12-01', 'CLT', 3200.00, 200.00, 25.00, '923.45678.90-2', '123456', '0011', 'PR', '104', 'Caixa Econômica', '1234-6', '12346-7', 'Corrente', 0, 'Ativo'),
(4, 'Diego Costa Pereira', '922.333.444-56', '93.456.789-1', '1986-08-17', 'M', 'Divorciado(a)', '80120-200', 'Rua Presidente Faria', '963', 'Centro', 'Curitiba', 'PR', '(41) 3355-5555', '(41) 96543-6543', 'diego@gammaconstrucoes.com.br', 'Pedreiro', 'Produção', '2014-05-20', 'CLT', 3000.00, 200.00, 25.00, '934.56789.01-3', '234567', '0012', 'PR', '001', 'Banco do Brasil', '2345-7', '23457-8', 'Corrente', 1, 'Ativo'),

-- Funcionários da Clínica Delta (cliente_id = 5)
(5, 'Dra. Carla Mendes', '933.444.555-67', '94.567.890-2', '1985-03-25', 'F', 'Casado(a)', '80130-300', 'Rua Emiliano Perneta', '456', 'Centro', 'Curitiba', 'PR', '(41) 3366-1111', '(41) 95432-1098', 'carla@clinicadelta.com.br', 'Médica Clínica Geral', 'Médico', '2019-03-01', 'CLT', 12000.00, 0.00, 0.00, '945.67890.12-4', '345678', '0013', 'PR', '341', 'Itaú', '3456-8', '34568-9', 'Corrente', 2, 'Ativo'),
(5, 'Enf. Roberto Silva', '944.555.666-78', '95.678.901-3', '1990-07-12', 'M', 'Solteiro(a)', '80140-400', 'Av. Vicente Machado', '789', 'Batel', 'Curitiba', 'PR', '(41) 3366-2222', '(41) 95432-5678', 'roberto@clinicadelta.com.br', 'Enfermeiro', 'Enfermagem', '2020-01-15', 'CLT', 3800.00, 200.00, 25.00, '956.78901.23-5', '456789', '0014', 'PR', '237', 'Bradesco', '4567-9', '45679-0', 'Corrente', 0, 'Ativo'),

-- Funcionários da Epsilon Transportes (cliente_id = 6)
(6, 'José Carlos Motorista', '955.666.777-89', '96.789.012-4', '1982-11-30', 'M', 'Casado(a)', '80150-500', 'Rua Marechal Floriano', '321', 'Centro', 'Curitiba', 'PR', '(41) 3377-1111', '(41) 94321-0987', 'jose@epsilontransportes.com.br', 'Motorista Carreteiro', 'Operacional', '2015-06-01', 'CLT', 4200.00, 200.00, 25.00, '967.89012.34-6', '567890', '0015', 'PR', '001', 'Banco do Brasil', '5678-0', '56780-1', 'Corrente', 3, 'Ativo'),
(6, 'Paulo Henrique Mecânico', '966.777.888-90', '97.890.123-5', '1988-04-18', 'M', 'União Estável', '80160-600', 'Av. Paraná', '654', 'Água Verde', 'Curitiba', 'PR', '(41) 3377-2222', '(41) 94321-5432', 'paulo@epsilontransportes.com.br', 'Mecânico de Veículos Pesados', 'Manutenção', '2016-09-10', 'CLT', 3900.00, 200.00, 25.00, '978.90123.45-7', '678901', '0016', 'PR', '341', 'Itaú', '6789-1', '67891-2', 'Ativo'),

-- Funcionários da Zeta Fashion (cliente_id = 7)
(7, 'Beatriz Santos Vendedora', '977.888.999-01', '98.901.234-6', '1995-09-05', 'F', 'Solteiro(a)', '80170-700', 'Rua Riachuelo', '147', 'Centro', 'Curitiba', 'PR', '(41) 3388-1111', '(41) 93210-9876', 'beatriz@zetafashion.com.br', 'Vendedora', 'Vendas', '2020-10-01', 'CLT', 1800.00, 200.00, 25.00, '989.01234.56-8', '789012', '0017', 'PR', '104', 'Caixa Econômica', '7890-2', '78902-3', 'Corrente', 0, 'Ativo'),
(7, 'Larissa Costa Costureira', '988.999.000-12', '99.012.345-7', '1987-12-20', 'F', 'Casado(a)', '80180-800', 'Rua Conselheiro Laurindo', '258', 'Centro', 'Curitiba', 'PR', '(41) 3388-2222', '(41) 93210-5432', 'larissa@zetafashion.com.br', 'Costureira', 'Produção', '2021-02-15', 'CLT', 2200.00, 200.00, 25.00, '990.12345.67-9', '890123', '0018', 'PR', '237', 'Bradesco', '8901-3', '89013-4', 'Corrente', 2, 'Ativo');

-- ============================================
-- TAREFAS
-- ============================================

INSERT INTO tarefas (cliente_id, titulo, descricao, prioridade, status, data_vencimento, responsavel_id, criado_por_id, setor_id) VALUES
(2, 'Enviar SPED Fiscal - Janeiro/2024', 'Preparar e enviar SPED Fiscal referente ao mês de janeiro de 2024', 'Alta', 'Concluída', '2024-02-20', 1, 1, 1),
(2, 'Gerar Holerites - Fevereiro/2024', 'Gerar e enviar holerites dos funcionários referente a fevereiro', 'Urgente', 'Em Andamento', '2024-03-05', 1, 1, 1),
(3, 'Renovar Certificado Digital', 'Certificado e-CNPJ vence em 30 dias, providenciar renovação', 'Alta', 'Pendente', '2024-03-15', 1, 1, 1),
(3, 'Balancete Mensal - Janeiro/2024', 'Elaborar balancete mensal de janeiro', 'Média', 'Concluída', '2024-02-10', 1, 1, 1),
(4, 'DCTF - Dezembro/2023', 'Enviar DCTF referente a dezembro de 2023', 'Alta', 'Concluída', '2024-01-15', 1, 1, 1),
(4, 'Análise de Inadimplência', 'Verificar situação de inadimplência e entrar em contato', 'Média', 'Pendente', '2024-03-10', 1, 1, 1),
(5, 'Folha de Pagamento - Fevereiro', 'Processar folha de pagamento de fevereiro', 'Urgente', 'Em Andamento', '2024-03-05', 1, 1, 1),
(6, 'Emitir Guias FGTS', 'Emitir guias de FGTS referente a fevereiro', 'Alta', 'Pendente', '2024-03-07', 1, 1, 1),
(7, 'Atualização Cadastral', 'Atualizar dados cadastrais da empresa na Receita Federal', 'Baixa', 'Pendente', '2024-03-20', 1, 1, 1),
(8, 'Reunião de Planejamento Tributário', 'Agendar reunião para discutir planejamento tributário 2024', 'Média', 'Pendente', '2024-03-12', 1, 1, 1);

-- ============================================
-- DOCUMENTOS
-- ============================================

INSERT INTO documentos (cliente_id, nome_arquivo, nome_original, tipo_documento, tamanho_bytes, extensao, caminho_arquivo, mes_referencia, ano_referencia, enviado_por_id, descricao) VALUES
(2, 'contrato_social_alfatech_2024.pdf', 'Contrato Social AlfaTech.pdf', 'Contrato', 524288, 'pdf', '/uploads/documentos/2/contrato_social_alfatech_2024.pdf', NULL, NULL, 1, 'Contrato social atualizado'),
(2, 'balancete_jan_2024.pdf', 'Balancete Janeiro 2024.pdf', 'Balanço', 245760, 'pdf', '/uploads/documentos/2/balancete_jan_2024.pdf', 1, 2024, 1, 'Balancete mensal de janeiro'),
(3, 'certidao_negativa_2024.pdf', 'Certidão Negativa.pdf', 'Certidão', 102400, 'pdf', '/uploads/documentos/3/certidao_negativa_2024.pdf', NULL, NULL, 1, 'Certidão negativa de débitos'),
(4, 'folha_pagamento_fev_2024.pdf', 'Folha Fevereiro 2024.pdf', 'Folha', 358400, 'pdf', '/uploads/documentos/4/folha_pagamento_fev_2024.pdf', 2, 2024, 1, 'Folha de pagamento de fevereiro'),
(5, 'dre_2023.pdf', 'DRE Anual 2023.pdf', 'DRE', 512000, 'pdf', '/uploads/documentos/5/dre_2023.pdf', 12, 2023, 1, 'Demonstração do Resultado do Exercício 2023'),
(6, 'guia_inss_fev_2024.pdf', 'GPS Fevereiro 2024.pdf', 'Guia', 81920, 'pdf', '/uploads/documentos/6/guia_inss_fev_2024.pdf', 2, 2024, 1, 'Guia de INSS de fevereiro');

-- ============================================
-- ALERTAS
-- ============================================

INSERT INTO alertas (tipo, titulo, mensagem, nivel, cliente_id, usuario_id, lido) VALUES
('Vencimento', 'Certificado Digital Vencendo', 'O certificado e-CNPJ da Beta Alimentos vence em 25 dias', 'Urgente', 3, 1, 0),
('Pendência', 'Documentos Pendentes', 'AlfaTech possui documentos pendentes de envio', 'Aviso', 2, 1, 0),
('Financeiro', 'Mensalidade em Atraso', 'Cliente Zeta Fashion está com mensalidade em atraso há 5 dias', 'Crítico', 7, 1, 0),
('Vencimento', 'Obrigação Fiscal Próxima', 'SPED Contribuições vence em 3 dias', 'Urgente', 4, 1, 0),
('Sistema', 'Backup Realizado', 'Backup automático do sistema realizado com sucesso', 'Info', NULL, 1, 1),
('Atraso', 'Tarefa Atrasada', 'Tarefa "Análise de Inadimplência" está atrasada', 'Aviso', 4, 1, 0);

SELECT '✅ Dados fictícios inseridos com sucesso!' as Mensagem;
SELECT COUNT(*) as 'Total de Clientes' FROM clientes;
SELECT COUNT(*) as 'Total de Funcionários' FROM funcionarios_clientes;
SELECT COUNT(*) as 'Total de Tarefas' FROM tarefas;
SELECT COUNT(*) as 'Total de Documentos' FROM documentos;
SELECT COUNT(*) as 'Total de Alertas' FROM alertas;

