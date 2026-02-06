<?php
/**
 * JCA ERP - Configurações Gerais
 *
 * @package JCA_ERP
 * @version 1.0.0
 */

// Inicia sessão se não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Configurações de Timezone
date_default_timezone_set('America/Sao_Paulo');

// Configurações de Erro (Desenvolvimento)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configurações do Sistema
define('SITE_NAME', 'JCA ERP');

// Detecta a URL base dinamicamente
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$baseDir = str_replace('\\', '/', dirname($scriptName));
$baseUrl = $protocol . "://" . $host . ($baseDir === '/' ? '' : $baseDir);

define('SITE_URL', $baseUrl);
define('BASE_PATH', dirname(__DIR__));

// Configurações de Upload
define('UPLOAD_PATH', BASE_PATH . '/uploads/');
define('MAX_UPLOAD_SIZE', 10 * 1024 * 1024); // 10MB
define('ALLOWED_EXTENSIONS', ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png', 'zip']);

// Configurações de Paginação
define('ITEMS_PER_PAGE', 20);

// Configurações de Alertas
define('DIAS_ALERTA_VENCIMENTO', 7);

// Inclui o arquivo de banco de dados
require_once __DIR__ . '/database.php';

/**
 * Função para redirecionar
 */
function redirect($url)
{
    header("Location: " . SITE_URL . "/" . $url);
    exit();
}

/**
 * Função para verificar se usuário está logado
 */
function isLoggedIn()
{
    return isset($_SESSION['usuario_id']);
}

/**
 * Função para verificar tipo de usuário
 */
function checkUserType($tipo)
{
    return isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === $tipo;
}

/**
 * Função para verificar se é admin
 */
function isAdmin()
{
    return checkUserType('admin');
}

/**
 * Função para verificar se é funcionário
 */
function isFuncionario()
{
    return checkUserType('funcionario') || isAdmin();
}

/**
 * Função para obter dados do usuário logado
 */
function getUsuarioLogado()
{
    if (!isLoggedIn()) {
        return null;
    }

    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->execute([$_SESSION['usuario_id']]);
    return $stmt->fetch();
}

/**
 * Função para formatar data brasileira
 */
function formatarData($data)
{
    if (empty($data))
        return '-';
    return date('d/m/Y', strtotime($data));
}

/**
 * Função para formatar data e hora brasileira
 */
function formatarDataHora($data)
{
    if (empty($data))
        return '-';
    return date('d/m/Y H:i', strtotime($data));
}

/**
 * Função para formatar moeda brasileira
 */
function formatarMoeda($valor)
{
    return 'R$ ' . number_format($valor, 2, ',', '.');
}

/**
 * Função para formatar CNPJ
 */
function somenteNumeros($valor)
{
    return preg_replace('/\D+/', '', (string) $valor);
}

function formatarCNPJ($cnpj)
{
    $cnpj = somenteNumeros($cnpj);
    if (empty($cnpj))
        return '-';
    if (strlen($cnpj) !== 14)
        return $cnpj;
    return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "$1.$2.$3/$4-$5", $cnpj);
}

/**
 * Função para formatar CPF
 */
function formatarCPF($cpf)
{
    $cpf = somenteNumeros($cpf);
    if (empty($cpf))
        return '-';
    if (strlen($cpf) !== 11)
        return $cpf;
    return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "$1.$2.$3-$4", $cpf);
}

/**
 * Função para formatar telefone
 */
function formatarTelefone($telefone)
{
    $telefone = preg_replace('/[^0-9]/', '', $telefone);
    if (strlen($telefone) == 11) {
        return preg_replace("/(\d{2})(\d{5})(\d{4})/", "($1) $2-$3", $telefone);
    } elseif (strlen($telefone) == 10) {
        return preg_replace("/(\d{2})(\d{4})(\d{4})/", "($1) $2-$3", $telefone);
    }
    return $telefone;
}

/**
 * Função para sanitizar input
 */
function sanitize($data)
{
    return htmlspecialchars(strip_tags(trim($data)));
}

/**
 * Função para gerar mensagem de sucesso
 */
function setSuccess($mensagem)
{
    $_SESSION['success'] = $mensagem;
}

/**
 * Função para gerar mensagem de erro
 */
function setError($mensagem)
{
    $_SESSION['error'] = $mensagem;
}

/**
 * Função para exibir mensagens
 */
function showMessages()
{
    $html = '';
    if (isset($_SESSION['success'])) {
        $html .= '<div class="alert alert-success alert-dismissible fade show" role="alert">';
        $html .= $_SESSION['success'];
        $html .= '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
        $html .= '</div>';
        unset($_SESSION['success']);
    }
    if (isset($_SESSION['error'])) {
        $html .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
        $html .= $_SESSION['error'];
        $html .= '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
        $html .= '</div>';
        unset($_SESSION['error']);
    }
    return $html;
}

/**
 * Funções auxiliares para auditoria/log
 */
function getClientIP()
{
    return $_SERVER['REMOTE_ADDR'] ?? null;
}

function getUserAgent()
{
    return $_SERVER['HTTP_USER_AGENT'] ?? null;
}

/**
 * Registra log de auditoria no banco (tabela logs_sistema)
 *
 * Uso atual no sistema: registrarLog('clientes', $id, 'create', '...')
 */
function registrarLog($modulo, $registroId, $acao, $descricao = null, $dadosAnteriores = null, $dadosNovos = null)
{
    try {
        $db = getDB();
        $usuarioId = $_SESSION['usuario_id'] ?? null;

        // Sempre incluir o registro_id no JSON para rastreabilidade
        if ($dadosNovos === null) {
            $dadosNovos = ['registro_id' => $registroId];
        } elseif (is_array($dadosNovos)) {
            $dadosNovos['registro_id'] = $dadosNovos['registro_id'] ?? $registroId;
        }

        $stmt = $db->prepare("INSERT INTO logs_sistema (usuario_id, acao, modulo, descricao, ip_address, user_agent, dados_anteriores, dados_novos)
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $usuarioId,
            (string) $acao,
            (string) $modulo,
            $descricao,
            getClientIP(),
            getUserAgent(),
            $dadosAnteriores !== null ? json_encode($dadosAnteriores, JSON_UNESCAPED_UNICODE) : null,
            $dadosNovos !== null ? json_encode($dadosNovos, JSON_UNESCAPED_UNICODE) : null
        ]);
    } catch (Exception $e) {
        // Não interromper fluxo por falha de log
    }
}


