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

// Busca certificados
$stmt = $db->prepare("SELECT * FROM certificados_digitais WHERE cliente_id = ? ORDER BY data_validade ASC");
$stmt->execute([$clienteId]);
$certificados = $stmt->fetchAll();

$pageTitle = 'Certificados Digitais - ' . $cliente['razao_social'];
$breadcrumb = '<a href="dashboard.php">Dashboard</a> / <a href="clientes.php">Clientes</a> / <a href="cliente-detalhes.php?id='.$clienteId.'">'.htmlspecialchars($cliente['razao_social']).'</a> / Certificados';
include 'includes/header.php';
?>

<div class="p-6 space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <i data-lucide="shield-check" class="w-8 h-8 text-green-600"></i>
                Certificados Digitais
            </h1>
            <p class="text-gray-600 mt-1"><?php echo htmlspecialchars($cliente['razao_social']); ?></p>
        </div>
        <div class="flex gap-2">
            <a href="cliente-centro-controle.php?id=<?php echo $clienteId; ?>" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors flex items-center gap-2">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Voltar
            </a>
            <a href="cliente-certificado-novo.php?cliente_id=<?php echo $clienteId; ?>" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Novo Certificado
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <?php
        $total = count($certificados);
        $ativos = count(array_filter($certificados, fn($c) => $c['status'] === 'Ativo'));
        $vencidos = count(array_filter($certificados, fn($c) => $c['status'] === 'Vencido'));
        $vencendo = 0;
        foreach ($certificados as $cert) {
            $diasRestantes = (strtotime($cert['data_validade']) - time()) / 86400;
            if ($diasRestantes <= 30 && $diasRestantes > 0) $vencendo++;
        }
        ?>
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="shield" class="w-6 h-6 text-blue-600"></i>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-900"><?php echo $total; ?></p>
                    <p class="text-sm text-gray-600">Total</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-6 h-6 text-green-600"></i>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-900"><?php echo $ativos; ?></p>
                    <p class="text-sm text-gray-600">Ativos</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="alert-triangle" class="w-6 h-6 text-amber-600"></i>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-900"><?php echo $vencendo; ?></p>
                    <p class="text-sm text-gray-600">Vencendo (30 dias)</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="x-circle" class="w-6 h-6 text-red-600"></i>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-900"><?php echo $vencidos; ?></p>
                    <p class="text-sm text-gray-600">Vencidos</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Certificados -->
    <div class="bg-white rounded-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Certificados Cadastrados</h2>
        </div>
        <div class="p-6">
            <?php if (!$certificados): ?>
                <div class="text-center py-12">
                    <i data-lucide="shield-off" class="w-16 h-16 text-gray-300 mx-auto mb-4"></i>
                    <p class="text-gray-500 mb-4">Nenhum certificado digital cadastrado</p>
                    <a href="cliente-certificado-novo.php?cliente_id=<?php echo $clienteId; ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        Cadastrar Primeiro Certificado
                    </a>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php foreach ($certificados as $cert): 
                        $diasRestantes = (strtotime($cert['data_validade']) - time()) / 86400;
                        $corStatus = $diasRestantes <= 0 ? 'red' : ($diasRestantes <= 30 ? 'amber' : 'green');
                    ?>
                        <div class="border-2 border-<?php echo $corStatus; ?>-200 rounded-lg p-5 hover:shadow-lg transition-all">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-<?php echo $corStatus; ?>-100 rounded-lg flex items-center justify-center">
                                        <i data-lucide="shield-check" class="w-6 h-6 text-<?php echo $corStatus; ?>-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900"><?php echo htmlspecialchars($cert['tipo']); ?></p>
                                        <p class="text-sm text-gray-600"><?php echo htmlspecialchars($cert['titular']); ?></p>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-<?php echo $corStatus; ?>-100 text-<?php echo $corStatus; ?>-700">
                                    <?php echo $cert['status']; ?>
                                </span>
                            </div>
                            
                            <div class="space-y-2 text-sm mb-4">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">CPF/CNPJ:</span>
                                    <span class="font-medium"><?php echo htmlspecialchars($cert['cpf_cnpj']); ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Emissão:</span>
                                    <span class="font-medium"><?php echo formatarData($cert['data_emissao']); ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Validade:</span>
                                    <span class="font-bold text-<?php echo $corStatus; ?>-700"><?php echo formatarData($cert['data_validade']); ?></span>
                                </div>
                                <div class="flex justify-between pt-2 border-t border-gray-200">
                                    <span class="text-gray-600">Dias restantes:</span>
                                    <span class="font-bold text-<?php echo $corStatus; ?>-700 text-lg"><?php echo max(0, round($diasRestantes)); ?> dias</span>
                                </div>
                                <?php if ($cert['certificadora']): ?>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Certificadora:</span>
                                    <span class="font-medium"><?php echo htmlspecialchars($cert['certificadora']); ?></span>
                                </div>
                                <?php endif; ?>
                            </div>

                            <div class="flex gap-2">
                                <a href="cliente-certificado-editar.php?id=<?php echo $cert['id']; ?>" class="flex-1 text-center px-3 py-2 text-sm bg-primary-50 text-primary-700 rounded-lg hover:bg-primary-100 transition-colors font-medium">
                                    Editar
                                </a>
                                <a href="cliente-certificado-renovar.php?id=<?php echo $cert['id']; ?>" class="flex-1 text-center px-3 py-2 text-sm bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors font-medium">
                                    Renovar
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

