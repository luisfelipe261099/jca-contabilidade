<?php
/**
 * JCA ERP - Página de Login
 * 
 * @package JCA_ERP
 * @version 1.0.0
 */

require_once 'config/config.php';

// Se já estiver logado, redireciona para dashboard
if (isLoggedIn()) {
    redirect('dashboard.php');
}

// Processa login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    
    if (empty($email) || empty($senha)) {
        setError('Por favor, preencha todos os campos.');
    } else {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE email = ? AND ativo = 1");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();
        
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            // Login bem-sucedido
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'];
            $_SESSION['setor_id'] = $usuario['setor_id'];
            
            // Atualiza último acesso
            $stmt = $db->prepare("UPDATE usuarios SET ultimo_acesso = NOW() WHERE id = ?");
            $stmt->execute([$usuario['id']]);
            
            redirect('dashboard.php');
        } else {
            setError('Email ou senha incorretos.');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo SITE_NAME; ?></title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="logo">
                    <span class="logo-jca">JCA</span>
                    <span class="logo-text">ERP</span>
                </div>
                <h2>Bem-vindo de volta!</h2>
                <p>Faça login para acessar o sistema</p>
            </div>
            
            <div class="login-body">
                <?php echo showMessages(); ?>
                
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope"></i> Email
                        </label>
                        <input type="email" class="form-control" id="email" name="email" 
                               placeholder="seu@email.com" required autofocus>
                    </div>
                    
                    <div class="mb-3">
                        <label for="senha" class="form-label">
                            <i class="fas fa-lock"></i> Senha
                        </label>
                        <input type="password" class="form-control" id="senha" name="senha" 
                               placeholder="••••••••" required>
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="lembrar">
                        <label class="form-check-label" for="lembrar">
                            Lembrar-me
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-sign-in-alt"></i> Entrar
                    </button>
                </form>
            </div>
            
            <div class="login-footer">
                <p>
                    <a href="recuperar-senha.php">Esqueceu sua senha?</a>
                </p>
                <p class="text-muted">
                    <small>&copy; 2024 JCA Soluções Contábeis. Todos os direitos reservados.</small>
                </p>
            </div>
        </div>
        
        <div class="login-info">
            <div class="info-content">
                <h3>Sistema ERP Completo</h3>
                <p>Gerencie sua empresa de contabilidade com eficiência</p>
                
                <div class="features">
                    <div class="feature-item">
                        <i class="fas fa-users"></i>
                        <span>Gestão de 500+ Clientes</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-file-invoice"></i>
                        <span>Controle Fiscal Completo</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-bell"></i>
                        <span>Alertas Automáticos</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-chart-line"></i>
                        <span>Relatórios Gerenciais</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

