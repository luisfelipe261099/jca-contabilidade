<?php
require_once __DIR__ . '/../config/config.php';

try {
    $db = getDB();
    $sql = file_get_contents(__DIR__ . '/dados-ficticios-simples.sql');
    
    // Dividir em queries individuais
    $queries = array_filter(array_map('trim', explode(';', $sql)));
    
    $success = 0;
    $errors = 0;
    
    foreach ($queries as $query) {
        if (empty($query) || strpos($query, '--') === 0 || strpos($query, 'USE ') === 0 || strpos($query, 'SELECT ') === 0) {
            continue;
        }
        
        try {
            $db->exec($query);
            $success++;
        } catch (PDOException $e) {
            $errors++;
            echo "Erro: " . $e->getMessage() . "\n\n";
        }
    }
    
    echo "\n========================================\n";
    echo "RESUMO\n";
    echo "========================================\n";
    echo "Sucesso: $success | Erros: $errors\n";
    echo "========================================\n\n";
    
    // Estatísticas
    $stmt = $db->query("SELECT COUNT(*) as total FROM clientes");
    echo "Clientes: " . $stmt->fetch(PDO::FETCH_ASSOC)['total'] . "\n";
    
    $stmt = $db->query("SELECT COUNT(*) as total FROM funcionarios_clientes");
    echo "Funcionários: " . $stmt->fetch(PDO::FETCH_ASSOC)['total'] . "\n";
    
    $stmt = $db->query("SELECT COUNT(*) as total FROM tarefas");
    echo "Tarefas: " . $stmt->fetch(PDO::FETCH_ASSOC)['total'] . "\n";
    
    $stmt = $db->query("SELECT COUNT(*) as total FROM documentos");
    echo "Documentos: " . $stmt->fetch(PDO::FETCH_ASSOC)['total'] . "\n";
    
    $stmt = $db->query("SELECT COUNT(*) as total FROM alertas");
    echo "Alertas: " . $stmt->fetch(PDO::FETCH_ASSOC)['total'] . "\n";
    
    echo "\n✅ Concluído!\n";
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
}

