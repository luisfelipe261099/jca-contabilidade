<?php
require_once __DIR__ . '/../config/config.php';

try {
    $db = getDB();
    
    echo "Executando migration de holerites...\n\n";
    
    $sql = file_get_contents(__DIR__ . '/migration-holerites.sql');
    $db->exec($sql);
    
    echo "✅ Migration de holerites executada com sucesso!\n\n";
    
    // Verificar se a tabela foi criada
    $stmt = $db->query("SHOW TABLES LIKE 'holerites'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Tabela 'holerites' criada com sucesso!\n";
        
        // Mostrar estrutura
        $stmt = $db->query("DESCRIBE holerites");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "\nColunas da tabela holerites: " . count($columns) . "\n";
    } else {
        echo "❌ Erro: Tabela 'holerites' não foi criada!\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
}

