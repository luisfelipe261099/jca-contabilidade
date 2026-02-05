<?php
/**
 * Script para executar a inserção de dados fictícios
 */

require_once __DIR__ . '/../config/config.php';

try {
    $db = getDB();
    
    // Ler o arquivo SQL
    $sql = file_get_contents(__DIR__ . '/dados-ficticios.sql');
    
    // Remover comentários e linhas vazias
    $lines = explode("\n", $sql);
    $queries = [];
    $currentQuery = '';
    
    foreach ($lines as $line) {
        $line = trim($line);
        
        // Ignorar comentários e linhas vazias
        if (empty($line) || strpos($line, '--') === 0 || strpos($line, 'USE ') === 0) {
            continue;
        }
        
        $currentQuery .= ' ' . $line;
        
        // Se a linha termina com ponto e vírgula, é o fim de uma query
        if (substr($line, -1) === ';') {
            $queries[] = trim($currentQuery);
            $currentQuery = '';
        }
    }
    
    // Executar cada query
    $success = 0;
    $errors = 0;
    
    foreach ($queries as $query) {
        if (empty($query)) continue;
        
        try {
            $db->exec($query);
            $success++;
        } catch (PDOException $e) {
            $errors++;
            echo "Erro ao executar query: " . $e->getMessage() . "\n";
            echo "Query: " . substr($query, 0, 100) . "...\n\n";
        }
    }
    
    echo "\n========================================\n";
    echo "RESUMO DA EXECUÇÃO\n";
    echo "========================================\n";
    echo "Queries executadas com sucesso: $success\n";
    echo "Queries com erro: $errors\n";
    echo "========================================\n\n";
    
    // Mostrar estatísticas
    echo "ESTATÍSTICAS DO BANCO DE DADOS:\n";
    echo "========================================\n";
    
    $tables = [
        'clientes' => 'Clientes',
        'funcionarios_clientes' => 'Funcionários',
        'tarefas' => 'Tarefas',
        'documentos' => 'Documentos',
        'alertas' => 'Alertas',
        'certificados_digitais' => 'Certificados Digitais',
        'holerites' => 'Holerites',
        'ferias' => 'Férias',
        'rescisoes' => 'Rescisões',
        'fgts_depositos' => 'Depósitos FGTS',
        'notas_fiscais' => 'Notas Fiscais',
        'lancamentos_contabeis' => 'Lançamentos Contábeis'
    ];
    
    foreach ($tables as $table => $label) {
        try {
            $stmt = $db->query("SELECT COUNT(*) as total FROM $table");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            echo str_pad($label . ':', 30) . $result['total'] . "\n";
        } catch (PDOException $e) {
            echo str_pad($label . ':', 30) . "Tabela não existe\n";
        }
    }
    
    echo "========================================\n";
    echo "\n✅ Dados fictícios inseridos com sucesso!\n\n";
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
}

