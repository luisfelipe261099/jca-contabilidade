<?php
require_once 'config/config.php';
if (!isLoggedIn()) redirect('index.php');
$db = getDB();
$usuario = getUsuarioLogado();

$clienteId = isset($_GET['cliente_id']) ? (int)$_GET['cliente_id'] : 0;
if (!$clienteId) redirect('clientes.php');

// Busca dados do cliente
$stmt = $db->prepare("SELECT * FROM clientes WHERE id = ?");
$stmt->execute([$clienteId]);
$cliente = $stmt->fetch();
if (!$cliente) redirect('clientes.php');

$mensagem = '';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = $_POST['tipo'] ?? '';
    $titular = $_POST['titular'] ?? '';
    $cpf_cnpj = somenteNumeros($_POST['cpf_cnpj'] ?? '');
    $data_emissao = $_POST['data_emissao'] ?? '';
    $data_validade = $_POST['data_validade'] ?? '';
    $certificadora = $_POST['certificadora'] ?? '';
    $senha_certificado = $_POST['senha_certificado'] ?? '';
    $dias_alerta = (int)($_POST['dias_alerta_vencimento'] ?? 30);
    $observacoes = $_POST['observacoes'] ?? '';
    
    if ($tipo && $titular && $cpf_cnpj && $data_emissao && $data_validade) {
        try {
            // Criptografa a senha do certificado
            $senha_criptografada = $senha_certificado ? password_hash($senha_certificado, PASSWORD_BCRYPT) : null;
            
            $stmt = $db->prepare("INSERT INTO certificados_digitais (
                cliente_id, tipo, titular, cpf_cnpj, data_emissao, data_validade,
                certificadora, senha_certificado, dias_alerta_vencimento, status, observacoes, cadastrado_por_id
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Ativo', ?, ?)");
            
            $stmt->execute([
                $clienteId, $tipo, $titular, $cpf_cnpj, $data_emissao, $data_validade,
                $certificadora, $senha_criptografada, $dias_alerta, $observacoes, $usuario['id']
            ]);
            
            redirect('cliente-certificados.php?cliente_id=' . $clienteId . '&msg=Certificado cadastrado com sucesso!');
        } catch (Exception $e) {
            $erro = 'Erro ao cadastrar certificado: ' . $e->getMessage();
        }
    } else {
        $erro = 'Preencha todos os campos obrigatórios';
    }
}

$pageTitle = 'Novo Certificado Digital - ' . $cliente['razao_social'];
$breadcrumb = '<a href="dashboard.php">Dashboard</a> / <a href="clientes.php">Clientes</a> / <a href="cliente-detalhes.php?id='.$clienteId.'">'.htmlspecialchars($cliente['razao_social']).'</a> / <a href="cliente-certificados.php?cliente_id='.$clienteId.'">Certificados</a> / Novo';
include 'includes/header.php';
?>

<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <i data-lucide="shield-plus" class="w-8 h-8 text-green-600"></i>
                Novo Certificado Digital
            </h1>
            <p class="text-gray-600 mt-1"><?php echo htmlspecialchars($cliente['razao_social']); ?></p>
        </div>

        <?php if ($erro): ?>
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg flex items-center gap-3">
                <i data-lucide="alert-circle" class="w-5 h-5 text-red-600"></i>
                <p class="text-red-700"><?php echo htmlspecialchars($erro); ?></p>
            </div>
        <?php endif; ?>

        <!-- Formulário -->
        <form method="POST" class="bg-white rounded-lg border border-gray-200 p-6 space-y-6">
            <!-- Tipo e Titular -->
            <div>
                <h6 class="text-base font-semibold text-gray-900 pb-2 mb-4 border-b border-gray-200 flex items-center gap-2">
                    <i data-lucide="info" class="w-5 h-5"></i>
                    Informações Básicas
                </h6>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Certificado <span class="text-red-600">*</span></label>
                        <select name="tipo" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <option value="">Selecione...</option>
                            <option value="e-CNPJ A1">e-CNPJ A1</option>
                            <option value="e-CNPJ A3">e-CNPJ A3</option>
                            <option value="e-CPF A1">e-CPF A1</option>
                            <option value="e-CPF A3">e-CPF A3</option>
                            <option value="NF-e A1">NF-e A1</option>
                            <option value="NF-e A3">NF-e A3</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Titular <span class="text-red-600">*</span></label>
                        <input type="text" name="titular" required value="<?php echo htmlspecialchars($cliente['razao_social']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">CPF/CNPJ <span class="text-red-600">*</span></label>
                        <input type="text" name="cpf_cnpj" required value="<?php echo htmlspecialchars($cliente['cnpj']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" maxlength="18">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Certificadora</label>
                        <input type="text" name="certificadora" placeholder="Ex: Certisign, Serasa, Valid" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Datas -->
            <div>
                <h6 class="text-base font-semibold text-gray-900 pb-2 mb-4 border-b border-gray-200 flex items-center gap-2">
                    <i data-lucide="calendar" class="w-5 h-5"></i>
                    Validade
                </h6>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Data de Emissão <span class="text-red-600">*</span></label>
                        <input type="date" name="data_emissao" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Data de Validade <span class="text-red-600">*</span></label>
                        <input type="date" name="data_validade" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alertar com (dias)</label>
                        <input type="number" name="dias_alerta_vencimento" value="30" min="1" max="365" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">Alertar X dias antes do vencimento</p>
                    </div>
                </div>
            </div>

            <!-- Segurança -->
            <div>
                <h6 class="text-base font-semibold text-gray-900 pb-2 mb-4 border-b border-gray-200 flex items-center gap-2">
                    <i data-lucide="lock" class="w-5 h-5"></i>
                    Segurança
                </h6>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Senha do Certificado</label>
                        <input type="password" name="senha_certificado" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">A senha será armazenada de forma criptografada</p>
                    </div>
                </div>
            </div>

            <!-- Observações -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Observações</label>
                <textarea name="observacoes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"></textarea>
            </div>

            <!-- Botões -->
            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <button type="submit" class="px-6 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2 font-medium">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Salvar Certificado
                </button>
                <a href="cliente-certificados.php?cliente_id=<?php echo $clienteId; ?>" class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

