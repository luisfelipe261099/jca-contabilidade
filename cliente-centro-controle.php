<?php
require_once 'config/config.php';
if (!isLoggedIn()) redirect('index.php');
$db = getDB();
$usuario = getUsuarioLogado();

$clienteId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$clienteId) redirect('clientes.php');

// Busca dados do cliente
$stmt = $db->prepare("SELECT * FROM clientes WHERE id = ?");
$stmt->execute([$clienteId]);
$cliente = $stmt->fetch();
if (!$cliente) redirect('clientes.php');

// === ESTATÍSTICAS RH ===
$totalFuncionarios = (int)$db->prepare("SELECT COUNT(*) FROM funcionarios_clientes WHERE cliente_id = ? AND status = 'Ativo'")->execute([$clienteId]) ? $db->query("SELECT FOUND_ROWS()")->fetchColumn() : 0;
$stmt = $db->prepare("SELECT COUNT(*) FROM funcionarios_clientes WHERE cliente_id = ? AND status = 'Ativo'");
$stmt->execute([$clienteId]);
$totalFuncionarios = (int)$stmt->fetchColumn();

$stmt = $db->prepare("SELECT COALESCE(SUM(salario_base), 0) FROM funcionarios_clientes WHERE cliente_id = ? AND status = 'Ativo'");
$stmt->execute([$clienteId]);
$folhaMensal = (float)$stmt->fetchColumn();

$stmt = $db->prepare("SELECT COUNT(*) FROM holerites WHERE cliente_id = ? AND mes_referencia = MONTH(CURDATE()) AND ano_referencia = YEAR(CURDATE()) AND status = 'Pendente'");
$stmt->execute([$clienteId]);
$holeritePendentes = (int)$stmt->fetchColumn();

$stmt = $db->prepare("SELECT COUNT(*) FROM fgts_depositos WHERE cliente_id = ? AND status = 'Pendente'");
$stmt->execute([$clienteId]);
$fgtsPendentes = (int)$stmt->fetchColumn();

// === CERTIFICADOS DIGITAIS ===
$stmt = $db->prepare("SELECT * FROM certificados_digitais WHERE cliente_id = ? ORDER BY data_validade ASC");
$stmt->execute([$clienteId]);
$certificados = $stmt->fetchAll();

$certificadosVencendo = 0;
foreach ($certificados as $cert) {
    $diasRestantes = (strtotime($cert['data_validade']) - time()) / 86400;
    if ($diasRestantes <= 30 && $diasRestantes > 0) $certificadosVencendo++;
}

// === NOTAS FISCAIS ===
$stmt = $db->prepare("SELECT COUNT(*) FROM notas_fiscais WHERE cliente_id = ? AND MONTH(data_emissao) = MONTH(CURDATE()) AND YEAR(data_emissao) = YEAR(CURDATE())");
$stmt->execute([$clienteId]);
$nfMesAtual = (int)$stmt->fetchColumn();

$stmt = $db->prepare("SELECT COALESCE(SUM(valor_total), 0) FROM notas_fiscais WHERE cliente_id = ? AND MONTH(data_emissao) = MONTH(CURDATE()) AND YEAR(data_emissao) = YEAR(CURDATE()) AND status = 'Autorizada'");
$stmt->execute([$clienteId]);
$faturamentoMes = (float)$stmt->fetchColumn();

// === CONTÁBIL ===
$stmt = $db->prepare("SELECT COUNT(*) FROM lancamentos_contabeis WHERE cliente_id = ? AND mes_competencia = MONTH(CURDATE()) AND ano_competencia = YEAR(CURDATE())");
$stmt->execute([$clienteId]);
$lancamentosMes = (int)$stmt->fetchColumn();

$pageTitle = 'Centro de Controle - ' . $cliente['razao_social'];
$breadcrumb = '<a href="dashboard.php">Dashboard</a> / <a href="clientes.php">Clientes</a> / <a href="cliente-detalhes.php?id='.$clienteId.'">'.htmlspecialchars($cliente['razao_social']).'</a> / Centro de Controle';
include 'includes/header.php';
?>

<div class="p-6 space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 rounded-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2"><?php echo htmlspecialchars($cliente['razao_social']); ?></h1>
                <p class="text-primary-100">Centro de Controle - Gestão Completa</p>
            </div>
            <div class="flex gap-2">
                <a href="cliente-detalhes.php?id=<?php echo $clienteId; ?>" class="px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg transition-colors flex items-center gap-2">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Voltar
                </a>
            </div>
        </div>
    </div>

    <!-- Menu de Navegação Rápida -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="#rh" class="bg-white rounded-lg border-2 border-blue-200 p-4 hover:border-blue-400 hover:shadow-md transition-all">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="users" class="w-6 h-6 text-blue-600"></i>
                </div>
                <div>
                    <p class="font-semibold text-gray-900">RH</p>
                    <p class="text-xs text-gray-600"><?php echo $totalFuncionarios; ?> funcionários</p>
                </div>
            </div>
        </a>
        <a href="#certificados" class="bg-white rounded-lg border-2 border-green-200 p-4 hover:border-green-400 hover:shadow-md transition-all">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="shield-check" class="w-6 h-6 text-green-600"></i>
                </div>
                <div>
                    <p class="font-semibold text-gray-900">Certificados</p>
                    <p class="text-xs text-gray-600"><?php echo count($certificados); ?> ativos</p>
                </div>
            </div>
        </a>
        <a href="#notas" class="bg-white rounded-lg border-2 border-purple-200 p-4 hover:border-purple-400 hover:shadow-md transition-all">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="file-text" class="w-6 h-6 text-purple-600"></i>
                </div>
                <div>
                    <p class="font-semibold text-gray-900">Notas Fiscais</p>
                    <p class="text-xs text-gray-600"><?php echo $nfMesAtual; ?> este mês</p>
                </div>
            </div>
        </a>
        <a href="#contabil" class="bg-white rounded-lg border-2 border-amber-200 p-4 hover:border-amber-400 hover:shadow-md transition-all">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="calculator" class="w-6 h-6 text-amber-600"></i>
                </div>
                <div>
                    <p class="font-semibold text-gray-900">Contábil</p>
                    <p class="text-xs text-gray-600"><?php echo $lancamentosMes; ?> lançamentos</p>
                </div>
            </div>
        </a>
    </div>

    <!-- SEÇÃO RH -->
    <div id="rh" class="scroll-mt-6">
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-blue-50 border-b border-blue-200">
                <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                    <i data-lucide="users" class="w-6 h-6 text-blue-600"></i>
                    Recursos Humanos
                </h2>
            </div>
            <div class="p-6">
                <!-- Stats RH -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4 border border-blue-200">
                        <p class="text-sm text-blue-700 mb-1">Funcionários Ativos</p>
                        <p class="text-3xl font-bold text-blue-900"><?php echo $totalFuncionarios; ?></p>
                    </div>
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 border border-green-200">
                        <p class="text-sm text-green-700 mb-1">Folha Mensal</p>
                        <p class="text-2xl font-bold text-green-900"><?php echo formatarMoeda($folhaMensal); ?></p>
                    </div>
                    <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-lg p-4 border border-amber-200">
                        <p class="text-sm text-amber-700 mb-1">Holerites Pendentes</p>
                        <p class="text-3xl font-bold text-amber-900"><?php echo $holeritePendentes; ?></p>
                    </div>
                    <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-lg p-4 border border-red-200">
                        <p class="text-sm text-red-700 mb-1">FGTS Pendente</p>
                        <p class="text-3xl font-bold text-red-900"><?php echo $fgtsPendentes; ?></p>
                    </div>
                </div>

                <!-- Ações RH -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <a href="cliente-funcionarios.php?id=<?php echo $clienteId; ?>" class="flex items-center gap-3 p-4 bg-white border-2 border-gray-200 rounded-lg hover:border-blue-400 hover:shadow-md transition-all">
                        <i data-lucide="users" class="w-8 h-8 text-blue-600"></i>
                        <div>
                            <p class="font-semibold text-gray-900">Funcionários</p>
                            <p class="text-xs text-gray-600">Gerenciar equipe</p>
                        </div>
                    </a>
                    <a href="cliente-holerites.php?cliente_id=<?php echo $clienteId; ?>" class="flex items-center gap-3 p-4 bg-white border-2 border-gray-200 rounded-lg hover:border-green-400 hover:shadow-md transition-all">
                        <i data-lucide="file-text" class="w-8 h-8 text-green-600"></i>
                        <div>
                            <p class="font-semibold text-gray-900">Holerites</p>
                            <p class="text-xs text-gray-600">Gerar contracheques</p>
                        </div>
                    </a>
                    <a href="cliente-rescisoes.php?cliente_id=<?php echo $clienteId; ?>" class="flex items-center gap-3 p-4 bg-white border-2 border-gray-200 rounded-lg hover:border-red-400 hover:shadow-md transition-all">
                        <i data-lucide="user-x" class="w-8 h-8 text-red-600"></i>
                        <div>
                            <p class="font-semibold text-gray-900">Rescisões</p>
                            <p class="text-xs text-gray-600">Desligamentos</p>
                        </div>
                    </a>
                    <a href="cliente-ferias.php?cliente_id=<?php echo $clienteId; ?>" class="flex items-center gap-3 p-4 bg-white border-2 border-gray-200 rounded-lg hover:border-purple-400 hover:shadow-md transition-all">
                        <i data-lucide="calendar" class="w-8 h-8 text-purple-600"></i>
                        <div>
                            <p class="font-semibold text-gray-900">Férias</p>
                            <p class="text-xs text-gray-600">Programar férias</p>
                        </div>
                    </a>
                    <a href="cliente-fgts.php?cliente_id=<?php echo $clienteId; ?>" class="flex items-center gap-3 p-4 bg-white border-2 border-gray-200 rounded-lg hover:border-amber-400 hover:shadow-md transition-all">
                        <i data-lucide="piggy-bank" class="w-8 h-8 text-amber-600"></i>
                        <div>
                            <p class="font-semibold text-gray-900">FGTS</p>
                            <p class="text-xs text-gray-600">Controle de depósitos</p>
                        </div>
                    </a>
                    <a href="cliente-decimo-terceiro.php?cliente_id=<?php echo $clienteId; ?>" class="flex items-center gap-3 p-4 bg-white border-2 border-gray-200 rounded-lg hover:border-indigo-400 hover:shadow-md transition-all">
                        <i data-lucide="gift" class="w-8 h-8 text-indigo-600"></i>
                        <div>
                            <p class="font-semibold text-gray-900">13º Salário</p>
                            <p class="text-xs text-gray-600">Calcular e pagar</p>
                        </div>
                    </a>
                    <a href="cliente-esocial.php?cliente_id=<?php echo $clienteId; ?>" class="flex items-center gap-3 p-4 bg-white border-2 border-gray-200 rounded-lg hover:border-cyan-400 hover:shadow-md transition-all">
                        <i data-lucide="send" class="w-8 h-8 text-cyan-600"></i>
                        <div>
                            <p class="font-semibold text-gray-900">eSocial</p>
                            <p class="text-xs text-gray-600">Eventos e envios</p>
                        </div>
                    </a>
                    <a href="cliente-guias-rh.php?cliente_id=<?php echo $clienteId; ?>" class="flex items-center gap-3 p-4 bg-white border-2 border-gray-200 rounded-lg hover:border-pink-400 hover:shadow-md transition-all">
                        <i data-lucide="receipt" class="w-8 h-8 text-pink-600"></i>
                        <div>
                            <p class="font-semibold text-gray-900">Guias</p>
                            <p class="text-xs text-gray-600">GPS, DARF, FGTS</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

