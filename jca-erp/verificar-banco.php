<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=jca_erp', 'root', '');
    echo "✅ Banco de dados 'jca_erp' existe!\n";
    
    // Verifica se a tabela usuarios existe
    $stmt = $pdo->query("SHOW TABLES LIKE 'usuarios'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Tabela 'usuarios' existe!\n";
        
        // Verifica se o admin existe
        $stmt = $pdo->query("SELECT * FROM usuarios WHERE email = 'admin@jcacontabilidade.com.br'");
        if ($stmt->rowCount() > 0) {
            echo "✅ Usuário admin existe!\n";
            echo "\nAcesse: http://localhost/jd/jca-erp/reset-senha-admin.php\n";
            echo "Para resetar a senha para: admin123\n";
        } else {
            echo "❌ Usuário admin NÃO existe!\n";
            echo "Execute: http://localhost/jd/jca-erp/install.php\n";
        }
    } else {
        echo "❌ Tabela 'usuarios' NÃO existe!\n";
        echo "Execute: http://localhost/jd/jca-erp/install.php\n";
    }
} catch(Exception $e) {
    echo "❌ Banco de dados 'jca_erp' NÃO existe!\n";
    echo "Execute: http://localhost/jd/jca-erp/install.php\n";
}

