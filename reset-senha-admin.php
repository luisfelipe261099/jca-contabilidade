<?php
/**
 * Script para resetar a senha do admin
 * Execute este arquivo para resetar a senha para: admin123
 */

// Configurações do banco (Lendo de variáveis de ambiente se disponíveis)
$db_host = getenv('DB_HOST') ?: 'localhost';
$db_user = getenv('DB_USER') ?: 'root';
$db_pass = getenv('DB_PASS') ?: '';
$db_name = getenv('DB_NAME') ?: 'jca_erp';

try {
    // Conecta ao banco
    $conn = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Nova senha
    $novaSenha = 'admin123';
    $hash = password_hash($novaSenha, PASSWORD_DEFAULT);

    // Atualiza a senha do admin
    $stmt = $conn->prepare("UPDATE usuarios SET senha = ? WHERE email = 'admin@jcacontabilidade.com.br'");
    $stmt->execute([$hash]);

    if ($stmt->rowCount() > 0) {
        echo "✅ Senha resetada com sucesso!\n\n";
        echo "Email: admin@jcacontabilidade.com.br\n";
        echo "Senha: admin123\n\n";
        echo "Você já pode fazer login no sistema!\n";
    } else {
        echo "❌ Usuário admin não encontrado no banco de dados.\n";
        echo "Execute o instalador primeiro: install.php\n";
    }

} catch (PDOException $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n\n";
    echo "Certifique-se que:\n";
    echo "1. O MySQL está rodando\n";
    echo "2. O banco de dados 'jca_erp' existe\n";
    echo "3. As credenciais estão corretas\n\n";
    echo "Se o banco não existe, execute: install.php\n";
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset de Senha - JCA ERP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0A2463 0%, #3E92CC 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .card {
            max-width: 500px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="card-body p-5 text-center">
            <h2 class="mb-4">🔑 Reset de Senha</h2>
            <div class="alert alert-success">
                <h5>✅ Senha Resetada!</h5>
                <hr>
                <p class="mb-0"><strong>Email:</strong> admin@jcacontabilidade.com.br</p>
                <p class="mb-0"><strong>Senha:</strong> admin123</p>
            </div>
            <a href="index.php" class="btn btn-primary btn-lg mt-3">
                Fazer Login
            </a>
        </div>
    </div>
</body>

</html>