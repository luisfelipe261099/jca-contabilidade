<?php
require_once __DIR__ . '/../config/config.php';

try {
    $db = getDB();
    
    echo "Iniciando inserção de dados...\n\n";
    
    // ============================================
    // CLIENTES
    // ============================================
    
    $clientes = [
        ['ALFATECH SOLUÇÕES EM TECNOLOGIA LTDA', 'AlfaTech', '22.333.444/0001-55', '223.334.445.556', '98765432', 'Simples Nacional', 'ME', '6201-5/00', '2018-03-15', '80010-000', 'Rua XV de Novembro', '1500', 'Sala 301', 'Centro', 'Curitiba', 'PR', '(41) 3333-4444', '(41) 99999-8888', 'contato@alfatech.com.br', 'João Silva Santos', '223.334.445-56', 'joao@alfatech.com.br', '(41) 99999-8888', '2020-01-01', 850.00, 10, 'Ativo', 1],
        ['BETA COMÉRCIO DE ALIMENTOS LTDA', 'Beta Alimentos', '33.444.555/0001-66', '334.445.556.667', '87654321', 'Simples Nacional', 'EPP', '4711-3/02', '2015-06-20', '80020-100', 'Av. Sete de Setembro', '2800', NULL, 'Batel', 'Curitiba', 'PR', '(41) 3344-5566', '(41) 98888-7777', 'contato@betaalimentos.com.br', 'Maria Oliveira Costa', '334.445.556-67', 'maria@betaalimentos.com.br', '(41) 98888-7777', '2019-03-15', 1200.00, 10, 'Ativo', 1],
        ['GAMMA CONSTRUÇÕES E REFORMAS LTDA', 'Gamma Construções', '44.555.666/0001-77', '445.556.667.778', '76543210', 'Lucro Presumido', 'Médio', '4120-4/00', '2012-09-10', '80030-200', 'Rua Marechal Deodoro', '450', 'Galpão 2', 'Centro', 'Curitiba', 'PR', '(41) 3355-6677', '(41) 97777-6666', 'contato@gammaconstrucoes.com.br', 'Pedro Henrique Souza', '445.556.667-78', 'pedro@gammaconstrucoes.com.br', '(41) 97777-6666', '2018-06-01', 2500.00, 10, 'Ativo', 1],
        ['DELTA SERVIÇOS MÉDICOS LTDA', 'Clínica Delta', '55.666.777/0001-88', '556.667.778.889', '65432109', 'Simples Nacional', 'ME', '8630-5/03', '2019-02-14', '80040-300', 'Rua Comendador Araújo', '720', 'Conjunto 501', 'Centro', 'Curitiba', 'PR', '(41) 3366-7788', '(41) 96666-5555', 'contato@clinicadelta.com.br', 'Dra. Ana Paula Lima', '556.667.778-89', 'ana@clinicadelta.com.br', '(41) 96666-5555', '2019-08-01', 950.00, 10, 'Ativo', 1],
        ['EPSILON TRANSPORTES RODOVIÁRIOS LTDA', 'Epsilon Transportes', '66.777.888/0001-99', '667.778.889.990', '54321098', 'Lucro Real', 'Grande', '4930-2/02', '2010-11-25', '80050-400', 'Av. Cândido de Abreu', '3200', NULL, 'Centro Cívico', 'Curitiba', 'PR', '(41) 3377-8899', '(41) 95555-4444', 'contato@epsilontransportes.com.br', 'Carlos Eduardo Ferreira', '667.778.889-90', 'carlos@epsilontransportes.com.br', '(41) 95555-4444', '2017-01-15', 3800.00, 10, 'Ativo', 1],
        ['ZETA MODA E CONFECÇÕES LTDA', 'Zeta Fashion', '77.888.999/0001-00', '778.889.990.001', '43210987', 'Simples Nacional', 'ME', '1412-6/01', '2020-04-10', '80060-500', 'Rua Barão do Rio Branco', '890', 'Loja 3', 'Centro', 'Curitiba', 'PR', '(41) 3388-9900', '(41) 94444-3333', 'contato@zetafashion.com.br', 'Juliana Martins Rocha', '778.889.990-01', 'juliana@zetafashion.com.br', '(41) 94444-3333', '2020-09-01', 750.00, 10, 'Ativo', 1],
        ['ETA CONSULTORIA EMPRESARIAL LTDA', 'Eta Consultoria', '88.999.000/0001-11', '889.990.001.112', '32109876', 'Simples Nacional', 'ME', '7020-4/00', '2021-01-20', '80070-600', 'Rua Visconde de Guarapuava', '1200', 'Sala 1005', 'Centro', 'Curitiba', 'PR', '(41) 3399-0011', '(41) 93333-2222', 'contato@etaconsultoria.com.br', 'Roberto Alves Pereira', '889.990.001-12', 'roberto@etaconsultoria.com.br', '(41) 93333-2222', '2021-03-01', 680.00, 10, 'Ativo', 1],
        ['THETA ACADEMIA E FITNESS LTDA', 'Theta Fitness', '99.000.111/0001-22', '990.001.112.223', '21098765', 'Simples Nacional', 'ME', '9313-1/00', '2017-07-05', '80080-700', 'Av. Marechal Floriano Peixoto', '560', NULL, 'Rebouças', 'Curitiba', 'PR', '(41) 3300-1122', '(41) 92222-1111', 'contato@thetafitness.com.br', 'Marcos Vinícius Santos', '990.001.112-23', 'marcos@thetafitness.com.br', '(41) 92222-1111', '2018-02-01', 820.00, 10, 'Ativo', 1],
        ['IOTA RESTAURANTE E LANCHONETE LTDA', 'Iota Gourmet', '00.111.222/0001-33', '001.112.223.334', '10987654', 'Simples Nacional', 'ME', '5611-2/01', '2019-05-18', '80090-800', 'Rua Presidente Faria', '340', NULL, 'Centro', 'Curitiba', 'PR', '(41) 3311-2233', '(41) 91111-0000', 'contato@iotagourmet.com.br', 'Fernanda Costa Ribeiro', '001.112.223-34', 'fernanda@iotagourmet.com.br', '(41) 91111-0000', '2019-10-01', 890.00, 10, 'Ativo', 1],
        ['KAPPA FARMÁCIA E DROGARIA LTDA', 'Kappa Farma', '11.222.333/0001-44', '112.223.334.445', '09876543', 'Simples Nacional', 'EPP', '4771-7/01', '2016-08-30', '80100-900', 'Rua Conselheiro Laurindo', '670', NULL, 'Centro', 'Curitiba', 'PR', '(41) 3322-3344', '(41) 90000-9999', 'contato@kappafarma.com.br', 'Ricardo Mendes Silva', '112.223.334-45', 'ricardo@kappafarma.com.br', '(41) 90000-9999', '2017-05-01', 1100.00, 10, 'Ativo', 1]
    ];
    
    $sql = "INSERT INTO clientes (razao_social, nome_fantasia, cnpj, inscricao_estadual, inscricao_municipal, regime_tributario, porte_empresa, cnae_principal, data_abertura, cep, logradouro, numero, complemento, bairro, cidade, estado, telefone, celular, email, responsavel_nome, responsavel_cpf, responsavel_email, responsavel_telefone, data_inicio_contrato, valor_mensalidade, dia_vencimento, status_contrato, contador_responsavel_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $db->prepare($sql);
    $clientesInseridos = 0;
    
    foreach ($clientes as $cliente) {
        try {
            $stmt->execute($cliente);
            $clientesInseridos++;
            echo "✓ Cliente inserido: {$cliente[1]}\n";
        } catch (PDOException $e) {
            echo "✗ Erro ao inserir {$cliente[1]}: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n$clientesInseridos clientes inseridos com sucesso!\n\n";
    
    // Pegar IDs dos clientes recém-inseridos
    $stmt = $db->query("SELECT id, nome_fantasia FROM clientes ORDER BY id DESC LIMIT 10");
    $clientesIds = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "IDs dos clientes:\n";
    foreach ($clientesIds as $c) {
        echo "  ID {$c['id']}: {$c['nome_fantasia']}\n";
    }
    
    echo "\n========================================\n";
    echo "RESUMO FINAL\n";
    echo "========================================\n";
    
    $stmt = $db->query("SELECT COUNT(*) as total FROM clientes");
    echo "Total de Clientes: " . $stmt->fetch(PDO::FETCH_ASSOC)['total'] . "\n";
    
    $stmt = $db->query("SELECT COUNT(*) as total FROM funcionarios_clientes");
    echo "Total de Funcionários: " . $stmt->fetch(PDO::FETCH_ASSOC)['total'] . "\n";
    
    echo "========================================\n";
    echo "\n✅ Processo concluído!\n";
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}

