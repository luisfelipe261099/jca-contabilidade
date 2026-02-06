<?php
require_once __DIR__ . '/../config/config.php';

try {
    $db = getDB();
    
    echo "Iniciando inserção de funcionários...\n\n";
    
    // Buscar IDs dos clientes
    $stmt = $db->query("SELECT id, nome_fantasia FROM clientes ORDER BY id");
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Clientes disponíveis:\n";
    foreach ($clientes as $c) {
        echo "  ID {$c['id']}: {$c['nome_fantasia']}\n";
    }
    echo "\n";
    
    // Funcionários (usando IDs reais dos clientes)
    $funcionarios = [
        // Cliente ID 13 - AlfaTech
        [13, 'Lucas Henrique Almeida', '211.222.333-44', '22.345.678-9', '1990-05-15', 'M', 'Casado(a)', '80010-100', 'Rua das Flores', '123', 'Centro', 'Curitiba', 'PR', '(41) 3333-1111', '(41) 98765-4321', 'lucas@alfatech.com.br', 'Desenvolvedor Full Stack', 'TI', '2020-03-01', 'CLT', 5500.00, 200.00, 25.00, '223.45678.90-1', '223456', '0001', 'PR', '001', 'Banco do Brasil', '1234-5', '12345-6', 'Corrente', 2, 'Ativo'],
        [13, 'Camila Rodrigues Santos', '222.333.444-55', '23.456.789-0', '1992-08-22', 'F', 'Solteiro(a)', '80020-200', 'Av. Brasil', '456', 'Batel', 'Curitiba', 'PR', '(41) 3333-2222', '(41) 98765-1234', 'camila@alfatech.com.br', 'Designer UX/UI', 'TI', '2020-06-15', 'CLT', 4200.00, 200.00, 25.00, '234.56789.01-2', '234567', '0002', 'PR', '237', 'Bradesco', '2345-6', '23456-7', 'Corrente', 0, 'Ativo'],
        [13, 'Rafael Costa Oliveira', '233.444.555-66', '24.567.890-1', '1988-11-10', 'M', 'Casado(a)', '80030-300', 'Rua XV de Novembro', '789', 'Centro', 'Curitiba', 'PR', '(41) 3333-3333', '(41) 98765-5678', 'rafael@alfatech.com.br', 'Gerente de Projetos', 'Gestão', '2019-01-10', 'CLT', 7000.00, 200.00, 25.00, '245.67890.12-3', '345678', '0003', 'PR', '341', 'Itaú', '3456-7', '34567-8', 'Corrente', 1, 'Ativo'],
        
        // Cliente ID 14 - Beta Alimentos
        [14, 'Juliana Ferreira Lima', '344.555.666-77', '35.678.901-2', '1995-03-18', 'F', 'Solteiro(a)', '80040-400', 'Rua das Palmeiras', '321', 'Água Verde', 'Curitiba', 'PR', '(41) 3344-1111', '(41) 97654-3210', 'juliana@betaalimentos.com.br', 'Gerente de Vendas', 'Comercial', '2019-05-01', 'CLT', 4800.00, 200.00, 25.00, '356.78901.23-4', '456789', '0004', 'PR', '104', 'Caixa Econômica', '4567-8', '45678-9', 'Corrente', 0, 'Ativo'],
        [14, 'Bruno Henrique Souza', '455.666.777-88', '46.789.012-3', '1987-07-25', 'M', 'Casado(a)', '80050-500', 'Av. Paraná', '654', 'Centro', 'Curitiba', 'PR', '(41) 3344-2222', '(41) 97654-6789', 'bruno@betaalimentos.com.br', 'Supervisor de Estoque', 'Logística', '2018-08-15', 'CLT', 3500.00, 200.00, 25.00, '467.89012.34-5', '567890', '0005', 'PR', '001', 'Banco do Brasil', '5678-9', '56789-0', 'Corrente', 3, 'Ativo'],
        [14, 'Amanda Silva Pereira', '566.777.888-99', '57.890.123-4', '1993-12-05', 'F', 'União Estável', '80060-600', 'Rua Marechal Deodoro', '987', 'Centro', 'Curitiba', 'PR', '(41) 3344-3333', '(41) 97654-9876', 'amanda@betaalimentos.com.br', 'Assistente Administrativo', 'Administrativo', '2020-02-01', 'CLT', 2800.00, 200.00, 25.00, '578.90123.45-6', '678901', '0006', 'PR', '237', 'Bradesco', '6789-0', '67890-1', 'Corrente', 1, 'Ativo'],
        [14, 'Thiago Martins Rocha', '677.888.999-00', '68.901.234-5', '1991-09-14', 'M', 'Solteiro(a)', '80070-700', 'Rua Comendador Araújo', '147', 'Centro', 'Curitiba', 'PR', '(41) 3344-4444', '(41) 97654-3456', 'thiago@betaalimentos.com.br', 'Motorista Entregador', 'Logística', '2019-11-20', 'CLT', 2500.00, 200.00, 25.00, '689.01234.56-7', '789012', '0007', 'PR', '341', 'Itaú', '7890-1', '78901-2', 'Corrente', 0, 'Ativo'],
        
        // Cliente ID 15 - Gamma Construções
        [15, 'Rodrigo Alves Santos', '788.999.000-11', '79.012.345-6', '1985-04-20', 'M', 'Casado(a)', '80080-800', 'Av. Sete de Setembro', '258', 'Batel', 'Curitiba', 'PR', '(41) 3355-1111', '(41) 96543-2109', 'rodrigo@gammaconstrucoes.com.br', 'Engenheiro Civil', 'Engenharia', '2015-03-10', 'CLT', 9500.00, 200.00, 25.00, '790.12345.67-8', '890123', '0008', 'PR', '001', 'Banco do Brasil', '8901-2', '89012-3', 'Corrente', 2, 'Ativo'],
        [15, 'Patrícia Oliveira Costa', '899.000.111-22', '80.123.456-7', '1989-06-30', 'F', 'Solteiro(a)', '80090-900', 'Rua Visconde de Guarapuava', '369', 'Centro', 'Curitiba', 'PR', '(41) 3355-2222', '(41) 96543-6789', 'patricia@gammaconstrucoes.com.br', 'Arquiteta', 'Projetos', '2016-07-01', 'CLT', 7200.00, 200.00, 25.00, '801.23456.78-9', '901234', '0009', 'PR', '237', 'Bradesco', '9012-3', '90123-4', 'Corrente', 0, 'Ativo'],
        [15, 'Marcelo Henrique Silva', '900.111.222-33', '91.234.567-8', '1992-01-12', 'M', 'Casado(a)', '80100-000', 'Rua Barão do Rio Branco', '741', 'Centro', 'Curitiba', 'PR', '(41) 3355-3333', '(41) 96543-9876', 'marcelo@gammaconstrucoes.com.br', 'Mestre de Obras', 'Produção', '2017-09-15', 'CLT', 4500.00, 200.00, 25.00, '912.34567.89-0', '012345', '0010', 'PR', '341', 'Itaú', '0123-4', '01234-5', 'Corrente', 1, 'Ativo'],
        [15, 'Fernanda Souza Lima', '911.222.333-45', '92.345.678-0', '1994-10-08', 'F', 'Solteiro(a)', '80110-100', 'Av. Cândido de Abreu', '852', 'Centro Cívico', 'Curitiba', 'PR', '(41) 3355-4444', '(41) 96543-3456', 'fernanda@gammaconstrucoes.com.br', 'Assistente de Compras', 'Suprimentos', '2018-12-01', 'CLT', 3200.00, 200.00, 25.00, '923.45678.90-2', '123456', '0011', 'PR', '104', 'Caixa Econômica', '1234-6', '12346-7', 'Corrente', 0, 'Ativo'],
        [15, 'Diego Costa Pereira', '922.333.444-56', '93.456.789-1', '1986-08-17', 'M', 'Divorciado(a)', '80120-200', 'Rua Presidente Faria', '963', 'Centro', 'Curitiba', 'PR', '(41) 3355-5555', '(41) 96543-6543', 'diego@gammaconstrucoes.com.br', 'Pedreiro', 'Produção', '2014-05-20', 'CLT', 3000.00, 200.00, 25.00, '934.56789.01-3', '234567', '0012', 'PR', '001', 'Banco do Brasil', '2345-7', '23457-8', 'Corrente', 1, 'Ativo'],
        
        // Cliente ID 17 - Epsilon Transportes
        [17, 'José Carlos Motorista', '955.666.777-89', '96.789.012-4', '1982-11-30', 'M', 'Casado(a)', '80150-500', 'Rua Marechal Floriano', '321', 'Centro', 'Curitiba', 'PR', '(41) 3377-1111', '(41) 94321-0987', 'jose@epsilontransportes.com.br', 'Motorista Carreteiro', 'Operacional', '2015-06-01', 'CLT', 4200.00, 200.00, 25.00, '967.89012.34-6', '567890', '0015', 'PR', '001', 'Banco do Brasil', '5678-0', '56780-1', 'Corrente', 3, 'Ativo'],
        [17, 'Paulo Henrique Mecânico', '966.777.888-90', '97.890.123-5', '1988-04-18', 'M', 'União Estável', '80160-600', 'Av. Paraná', '654', 'Água Verde', 'Curitiba', 'PR', '(41) 3377-2222', '(41) 94321-5432', 'paulo@epsilontransportes.com.br', 'Mecânico de Veículos Pesados', 'Manutenção', '2016-09-10', 'CLT', 3900.00, 200.00, 25.00, '978.90123.45-7', '678901', '0016', 'PR', '341', 'Itaú', '6789-1', '67891-2', 'Corrente', 1, 'Ativo'],
        
        // Cliente ID 18 - Zeta Fashion
        [18, 'Beatriz Santos Vendedora', '977.888.999-01', '98.901.234-6', '1995-09-05', 'F', 'Solteiro(a)', '80170-700', 'Rua Riachuelo', '147', 'Centro', 'Curitiba', 'PR', '(41) 3388-1111', '(41) 93210-9876', 'beatriz@zetafashion.com.br', 'Vendedora', 'Vendas', '2020-10-01', 'CLT', 1800.00, 200.00, 25.00, '989.01234.56-8', '789012', '0017', 'PR', '104', 'Caixa Econômica', '7890-2', '78902-3', 'Corrente', 0, 'Ativo'],
        [18, 'Larissa Costa Costureira', '988.999.000-12', '99.012.345-7', '1987-12-20', 'F', 'Casado(a)', '80180-800', 'Rua Conselheiro Laurindo', '258', 'Centro', 'Curitiba', 'PR', '(41) 3388-2222', '(41) 93210-5432', 'larissa@zetafashion.com.br', 'Costureira', 'Produção', '2021-02-15', 'CLT', 2200.00, 200.00, 25.00, '990.12345.67-9', '890123', '0018', 'PR', '237', 'Bradesco', '8901-3', '89013-4', 'Corrente', 2, 'Ativo'],
        
        // Cliente ID 19 - Eta Consultoria
        [19, 'Marcos Consultor', '991.000.111-23', '00.123.456-8', '1983-06-15', 'M', 'Casado(a)', '80190-900', 'Rua Marechal Deodoro', '369', 'Centro', 'Curitiba', 'PR', '(41) 3399-1111', '(41) 92109-8765', 'marcos@etaconsultoria.com.br', 'Consultor Empresarial', 'Consultoria', '2021-04-01', 'CLT', 6500.00, 200.00, 25.00, '001.23456.78-0', '901234', '0019', 'PR', '341', 'Itaú', '9012-4', '90124-5', 'Corrente', 1, 'Ativo'],
        
        // Cliente ID 20 - Theta Fitness
        [20, 'Carlos Personal Trainer', '992.111.222-34', '01.234.567-9', '1990-08-20', 'M', 'Solteiro(a)', '80200-000', 'Av. Marechal Floriano', '480', 'Rebouças', 'Curitiba', 'PR', '(41) 3300-1111', '(41) 91098-7654', 'carlos@thetafitness.com.br', 'Personal Trainer', 'Educação Física', '2018-03-15', 'CLT', 3500.00, 200.00, 25.00, '012.34567.89-1', '012345', '0020', 'PR', '001', 'Banco do Brasil', '0123-5', '01235-6', 'Corrente', 0, 'Ativo'],
        [20, 'Renata Nutricionista', '993.222.333-45', '02.345.678-0', '1992-11-10', 'F', 'Casado(a)', '80210-100', 'Rua XV de Novembro', '591', 'Centro', 'Curitiba', 'PR', '(41) 3300-2222', '(41) 90987-6543', 'renata@thetafitness.com.br', 'Nutricionista', 'Saúde', '2019-06-01', 'CLT', 4200.00, 200.00, 25.00, '123.45678.90-3', '123456', '0021', 'PR', '237', 'Bradesco', '1234-7', '12347-8', 'Corrente', 1, 'Ativo'],
        
        // Cliente ID 21 - Iota Gourmet
        [21, 'André Chef', '994.333.444-56', '03.456.789-1', '1985-04-25', 'M', 'União Estável', '80220-200', 'Rua Presidente Faria', '702', 'Centro', 'Curitiba', 'PR', '(41) 3311-1111', '(41) 89876-5432', 'andre@iotagourmet.com.br', 'Chef de Cozinha', 'Cozinha', '2019-11-01', 'CLT', 5500.00, 200.00, 25.00, '234.56789.01-4', '234567', '0022', 'PR', '341', 'Itaú', '2345-8', '23458-9', 'Corrente', 2, 'Ativo'],
        [21, 'Gabriela Garçonete', '995.444.555-67', '04.567.890-2', '1996-07-30', 'F', 'Solteiro(a)', '80230-300', 'Av. Sete de Setembro', '813', 'Batel', 'Curitiba', 'PR', '(41) 3311-2222', '(41) 88765-4321', 'gabriela@iotagourmet.com.br', 'Garçonete', 'Atendimento', '2020-03-15', 'CLT', 1900.00, 200.00, 25.00, '345.67890.12-5', '345678', '0023', 'PR', '104', 'Caixa Econômica', '3456-9', '34569-0', 'Corrente', 0, 'Ativo']
    ];
    
    $sql = "INSERT INTO funcionarios_clientes (cliente_id, nome_completo, cpf, rg, data_nascimento, sexo, estado_civil, cep, logradouro, numero, bairro, cidade, estado, telefone, celular, email, cargo, departamento, data_admissao, tipo_contrato, salario_base, vale_transporte, vale_refeicao, pis_pasep, ctps_numero, ctps_serie, ctps_uf, banco_codigo, banco_nome, agencia, conta, tipo_conta, numero_dependentes, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $db->prepare($sql);
    $funcionariosInseridos = 0;
    
    foreach ($funcionarios as $func) {
        try {
            $stmt->execute($func);
            $funcionariosInseridos++;
            echo "✓ Funcionário inserido: {$func[1]} (Cliente ID: {$func[0]})\n";
        } catch (PDOException $e) {
            echo "✗ Erro ao inserir {$func[1]}: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n$funcionariosInseridos funcionários inseridos com sucesso!\n\n";
    
    echo "========================================\n";
    echo "RESUMO FINAL\n";
    echo "========================================\n";
    
    $stmt = $db->query("SELECT COUNT(*) as total FROM clientes");
    echo "Total de Clientes: " . $stmt->fetch(PDO::FETCH_ASSOC)['total'] . "\n";
    
    $stmt = $db->query("SELECT COUNT(*) as total FROM funcionarios_clientes");
    echo "Total de Funcionários: " . $stmt->fetch(PDO::FETCH_ASSOC)['total'] . "\n";
    
    // Estatísticas por status
    $stmt = $db->query("SELECT status, COUNT(*) as total FROM funcionarios_clientes GROUP BY status");
    $stats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "\nFuncionários por Status:\n";
    foreach ($stats as $stat) {
        echo "  {$stat['status']}: {$stat['total']}\n";
    }
    
    echo "========================================\n";
    echo "\n✅ Processo concluído!\n";
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}

