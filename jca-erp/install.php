<?php
/**
 * JCA ERP - Instalador Automático
 * 
 * @package JCA_ERP
 * @version 1.0.0
 */

// Configurações
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'jca_erp';

$errors = [];
$success = [];

// Processa instalação
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // 1. Conecta ao MySQL
        $conn = new mysqli($db_host, $db_user, $db_pass);
        
        if ($conn->connect_error) {
            throw new Exception("Erro de conexão: " . $conn->connect_error);
        }
        
        $success[] = "✓ Conexão com MySQL estabelecida";
        
        // 2. Cria o banco de dados
        $conn->query("CREATE DATABASE IF NOT EXISTS $db_name CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $success[] = "✓ Banco de dados '$db_name' criado";
        
        // 3. Seleciona o banco
        $conn->select_db($db_name);
        
        // 4. Lê e executa o schema SQL
        $sql = file_get_contents(__DIR__ . '/database/schema.sql');
        
        // Remove comentários e linhas vazias
        $sql = preg_replace('/^--.*$/m', '', $sql);
        $sql = preg_replace('/^\s*$/m', '', $sql);
        
        // Separa por ponto e vírgula
        $queries = array_filter(array_map('trim', explode(';', $sql)));
        
        foreach ($queries as $query) {
            if (!empty($query) && !preg_match('/^(CREATE DATABASE|USE)/i', $query)) {
                if (!$conn->query($query)) {
                    throw new Exception("Erro ao executar query: " . $conn->error);
                }
            }
        }
        
        $success[] = "✓ Tabelas criadas com sucesso";
        $success[] = "✓ Dados iniciais inseridos";
        
        // 5. Cria pasta de uploads
        $uploadDir = __DIR__ . '/uploads';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
            $success[] = "✓ Pasta de uploads criada";
        }
        
        // 6. Cria subpastas
        $subfolders = ['documentos', 'temp', 'clientes'];
        foreach ($subfolders as $folder) {
            $path = $uploadDir . '/' . $folder;
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }
        }
        $success[] = "✓ Estrutura de pastas criada";
        
        $conn->close();
        
        $success[] = "<br><strong>🎉 INSTALAÇÃO CONCLUÍDA COM SUCESSO!</strong>";
        $success[] = "<br>Credenciais de acesso:";
        $success[] = "Email: <strong>admin@jcacontabilidade.com.br</strong>";
        $success[] = "Senha: <strong>admin123</strong>";
        $success[] = "<br><a href='index.php' class='btn btn-success mt-3'>Acessar o Sistema</a>";
        
    } catch (Exception $e) {
        $errors[] = "❌ " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalação - JCA ERP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #0A2463 0%, #3E92CC 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .install-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo-jca {
            font-size: 3rem;
            font-weight: 800;
            background: linear-gradient(135deg, #FB8500 0%, #FFB703 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .logo-text {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0A2463;
        }
        .success-list {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }
        .success-list li {
            color: #155724;
            margin-bottom: 10px;
        }
        .error-list {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }
        .error-list li {
            color: #721c24;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="install-card">
        <div class="logo">
            <div>
                <span class="logo-jca">JCA</span>
                <span class="logo-text">ERP</span>
            </div>
            <h2 class="mt-3">Instalação do Sistema</h2>
            <p class="text-muted">Sistema de Gestão para JCA Soluções Contábeis</p>
        </div>
        
        <?php if (!empty($errors)): ?>
            <div class="error-list">
                <h5><i class="fas fa-exclamation-triangle"></i> Erros Encontrados:</h5>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="success-list">
                <h5><i class="fas fa-check-circle"></i> Progresso da Instalação:</h5>
                <ul class="list-unstyled">
                    <?php foreach ($success as $msg): ?>
                        <li><?php echo $msg; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                <h5><i class="fas fa-info-circle"></i> Antes de Começar:</h5>
                <ul>
                    <li>Certifique-se que o MySQL está rodando</li>
                    <li>Verifique as credenciais do banco de dados</li>
                    <li>O processo criará o banco de dados automaticamente</li>
                </ul>
            </div>
            
            <form method="POST">
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-download"></i> Iniciar Instalação
                    </button>
                </div>
            </form>
        <?php endif; ?>
        
        <div class="text-center mt-4">
            <small class="text-muted">
                © 2024 JCA Soluções Contábeis. Todos os direitos reservados.
            </small>
        </div>
    </div>
</body>
</html>

