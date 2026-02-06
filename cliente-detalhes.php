<?php
require_once 'config/config.php';
if (!isLoggedIn())
    redirect('index.php');

$usuario = getUsuarioLogado();
$db = getDB();
$clienteId = $_GET['id'] ?? 0;

// Busca cliente
$stmt = $db->prepare("SELECT c.*, u.nome as contador_nome 
                      FROM clientes c
                      LEFT JOIN usuarios u ON c.contador_responsavel_id = u.id
                      WHERE c.id = ?");
$stmt->execute([$clienteId]);
$cliente = $stmt->fetch();

if (!$cliente) {
    setError('Cliente não encontrado!');
    redirect('clientes.php');
}

// Estatísticas rápidas
$stmt = $db->prepare("SELECT COUNT(*) FROM funcionarios_clientes WHERE cliente_id = ? AND status = 'Ativo'");
$stmt->execute([$clienteId]);
$totalFuncionarios = (int) $stmt->fetchColumn();

$stmt = $db->prepare("SELECT COUNT(*) FROM tarefas WHERE cliente_id = ? AND status != 'Concluída'");
$stmt->execute([$clienteId]);
$tarefasPendentes = (int) $stmt->fetchColumn();

$stmt = $db->prepare("SELECT COUNT(*) FROM documentos WHERE cliente_id = ?");
$stmt->execute([$clienteId]);
$totalDocumentos = (int) $stmt->fetchColumn();

$stmt = $db->prepare("SELECT COUNT(*) FROM certificados_digitais WHERE cliente_id = ? AND status = 'Ativo'");
$stmt->execute([$clienteId]);
$totalCertificados = (int) $stmt->fetchColumn();

$pageTitle = $cliente['razao_social'];
$breadcrumb = '<a href="dashboard.php">Dashboard</a> / <a href="clientes.php">Clientes</a> / Detalhes';
include 'includes/header.php';
?>

<div class="p-6 space-y-6">
    <!-- Header com Ações -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 rounded-lg p-6 text-white shadow-lg">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <h1 class="text-3xl font-bold mb-2"><?php echo htmlspecialchars($cliente['razao_social']); ?></h1>
                <?php if ($cliente['nome_fantasia']): ?>
                    <p class="text-primary-100 text-lg mb-3"><?php echo htmlspecialchars($cliente['nome_fantasia']); ?></p>
                <?php endif; ?>
                <div class="flex flex-wrap gap-2">
                    <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-sm font-medium">
                        <?php echo formatarCNPJ($cliente['cnpj']); ?>
                    </span>
                    <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-sm font-medium">
                        <?php echo $cliente['regime_tributario']; ?>
                    </span>
                    <?php
                    $statusColors = [
                        'Ativo' => 'bg-green-500',
                        'Suspenso' => 'bg-amber-500',
                        'Cancelado' => 'bg-gray-500',
                        'Inadimplente' => 'bg-red-500'
                    ];
                    $statusColor = $statusColors[$cliente['status_contrato']] ?? 'bg-gray-500';
                    ?>
                    <span class="px-3 py-1 <?php echo $statusColor; ?> rounded-full text-sm font-semibold">
                        <?php echo $cliente['status_contrato']; ?>
                    </span>
                </div>
            </div>
            <div class="flex flex-col gap-2">
                <a href="cliente-centro-controle.php?id=<?php echo $clienteId; ?>"
                    class="px-6 py-3 bg-white text-primary-700 rounded-lg hover:bg-primary-50 transition-all shadow-md hover:shadow-lg font-semibold flex items-center gap-2">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    Centro de Controle
                </a>
                <div class="flex gap-2">
                    <a href="cliente-editar.php?id=<?php echo $clienteId; ?>"
                        class="px-4 py-2 bg-white/20 backdrop-blur-sm hover:bg-white/30 rounded-lg transition-colors flex items-center gap-2">
                        <i data-lucide="edit" class="w-4 h-4"></i>
                        Editar
                    </a>
                    <a href="clientes.php"
                        class="px-4 py-2 bg-white/20 backdrop-blur-sm hover:bg-white/30 rounded-lg transition-colors flex items-center gap-2">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                        Voltar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Cards de Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <a href="cliente-funcionarios.php?id=<?php echo $clienteId; ?>"
            class="bg-white rounded-lg border-2 border-blue-200 p-6 hover:border-blue-400 hover:shadow-lg transition-all">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="users" class="w-7 h-7 text-blue-600"></i>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-900"><?php echo $totalFuncionarios; ?></p>
                    <p class="text-sm text-gray-600">Funcionários</p>
                </div>
            </div>
        </a>
        <a href="tarefas.php?cliente_id=<?php echo $clienteId; ?>"
            class="bg-white rounded-lg border-2 border-amber-200 p-6 hover:border-amber-400 hover:shadow-lg transition-all">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-amber-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="check-square" class="w-7 h-7 text-amber-600"></i>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-900"><?php echo $tarefasPendentes; ?></p>
                    <p class="text-sm text-gray-600">Tarefas Pendentes</p>
                </div>
            </div>
        </a>
        <!-- <a href="documentos.php?cliente_id=<?php echo $clienteId; ?>" class="bg-white rounded-lg border-2 border-purple-200 p-6 hover:border-purple-400 hover:shadow-lg transition-all">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="folder" class="w-7 h-7 text-purple-600"></i>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-900"><?php echo $totalDocumentos; ?></p>
                    <p class="text-sm text-gray-600">Documentos</p>
                </div>
            </div>
        </a> -->
        <a href="cliente-certificados.php?cliente_id=<?php echo $clienteId; ?>"
            class="bg-white rounded-lg border-2 border-green-200 p-6 hover:border-green-400 hover:shadow-lg transition-all">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-green-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="shield-check" class="w-7 h-7 text-green-600"></i>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-900"><?php echo $totalCertificados; ?></p>
                    <p class="text-sm text-gray-600">Certificados</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Grid de Informações -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Dados Cadastrais -->
        <div class="bg-white rounded-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <i data-lucide="building-2" class="w-5 h-5 text-gray-600"></i>
                    Dados Cadastrais
                </h2>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">CNPJ</p>
                        <p class="font-semibold text-gray-900"><?php echo formatarCNPJ($cliente['cnpj']); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Inscrição Estadual</p>
                        <p class="font-semibold text-gray-900"><?php echo $cliente['inscricao_estadual'] ?: '-'; ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Inscrição Municipal</p>
                        <p class="font-semibold text-gray-900"><?php echo $cliente['inscricao_municipal'] ?: '-'; ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Cliente desde</p>
                        <p class="font-semibold text-gray-900"><?php echo formatarData($cliente['data_criacao']); ?></p>
                    </div>
                </div>
                <div class="pt-4 border-t border-gray-200">
                    <p class="text-sm text-gray-600 mb-1">Endereço</p>
                    <?php if ($cliente['logradouro']): ?>
                        <p class="font-medium text-gray-900">
                            <?php echo htmlspecialchars($cliente['logradouro']); ?>,
                            <?php echo htmlspecialchars($cliente['numero'] ?? 'S/N'); ?>
                            <?php if ($cliente['complemento']): ?> -
                                <?php echo htmlspecialchars($cliente['complemento']); ?>    <?php endif; ?>
                        </p>
                        <p class="text-gray-700">
                            <?php echo htmlspecialchars($cliente['bairro']); ?> -
                            <?php echo htmlspecialchars($cliente['cidade']); ?>/<?php echo htmlspecialchars($cliente['estado']); ?>
                        </p>
                        <p class="text-gray-700">CEP: <?php echo htmlspecialchars($cliente['cep']); ?></p>
                    <?php else: ?>
                        <p class="text-gray-500">-</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Contato -->
        <div class="bg-white rounded-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <i data-lucide="phone" class="w-5 h-5 text-gray-600"></i>
                    Contato
                </h2>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Telefone</p>
                    <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($cliente['telefone']) ?: '-'; ?>
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Celular</p>
                    <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($cliente['celular']) ?: '-'; ?>
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">E-mail</p>
                    <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($cliente['email']) ?: '-'; ?></p>
                </div>
                <div class="pt-4 border-t border-gray-200">
                    <p class="text-sm text-gray-600 mb-2">Responsável Legal</p>
                    <p class="font-semibold text-gray-900">
                        <?php echo htmlspecialchars($cliente['responsavel_nome']) ?: '-'; ?></p>
                    <?php if ($cliente['responsavel_cpf']): ?>
                        <p class="text-sm text-gray-700">CPF: <?php echo formatarCPF($cliente['responsavel_cpf']); ?></p>
                    <?php endif; ?>
                    <?php if ($cliente['responsavel_email']): ?>
                        <p class="text-sm text-gray-700"><?php echo htmlspecialchars($cliente['responsavel_email']); ?></p>
                    <?php endif; ?>
                    <?php if ($cliente['responsavel_telefone']): ?>
                        <p class="text-sm text-gray-700"><?php echo htmlspecialchars($cliente['responsavel_telefone']); ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Informações Fiscais -->
        <div class="bg-white rounded-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <i data-lucide="file-text" class="w-5 h-5 text-gray-600"></i>
                    Informações Fiscais
                </h2>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Regime Tributário</p>
                        <p class="font-semibold text-gray-900"><?php echo $cliente['regime_tributario']; ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Porte da Empresa</p>
                        <p class="font-semibold text-gray-900"><?php echo $cliente['porte_empresa'] ?: '-'; ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Data de Abertura</p>
                        <p class="font-semibold text-gray-900">
                            <?php echo $cliente['data_abertura'] ? formatarData($cliente['data_abertura']) : '-'; ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">CNAE Principal</p>
                        <p class="font-semibold text-gray-900"><?php echo $cliente['cnae_principal'] ?: '-'; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contrato -->
        <div class="bg-white rounded-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <i data-lucide="file-signature" class="w-5 h-5 text-gray-600"></i>
                    Contrato
                </h2>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Contador Responsável</p>
                        <p class="font-semibold text-gray-900">
                            <?php echo htmlspecialchars($cliente['contador_nome']) ?: '-'; ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Valor Mensalidade</p>
                        <p class="font-semibold text-gray-900">
                            <?php echo formatarMoeda($cliente['valor_mensalidade']); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Vencimento</p>
                        <p class="font-semibold text-gray-900">Dia <?php echo $cliente['dia_vencimento']; ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Status</p>
                        <?php
                        $statusColors = [
                            'Ativo' => 'bg-green-100 text-green-700',
                            'Suspenso' => 'bg-amber-100 text-amber-700',
                            'Cancelado' => 'bg-gray-100 text-gray-700',
                            'Inadimplente' => 'bg-red-100 text-red-700'
                        ];
                        $statusColor = $statusColors[$cliente['status_contrato']] ?? 'bg-gray-100 text-gray-700';
                        ?>
                        <span
                            class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold <?php echo $statusColor; ?>">
                            <?php echo $cliente['status_contrato']; ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ações Rápidas -->
    <div class="bg-white rounded-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                <i data-lucide="zap" class="w-5 h-5 text-gray-600"></i>
                Ações Rápidas
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <a href="tarefa-nova.php?cliente_id=<?php echo $clienteId; ?>"
                    class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-lg hover:border-primary-400 hover:shadow-md transition-all">
                    <i data-lucide="plus-circle" class="w-8 h-8 text-primary-600"></i>
                    <div>
                        <p class="font-semibold text-gray-900">Nova Tarefa</p>
                        <p class="text-xs text-gray-600">Criar tarefa</p>
                    </div>
                </a>
                <!-- <a href="documento-upload.php?cliente_id=<?php echo $clienteId; ?>" class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-lg hover:border-blue-400 hover:shadow-md transition-all">
                    <i data-lucide="upload" class="w-8 h-8 text-blue-600"></i>
                    <div>
                        <p class="font-semibold text-gray-900">Upload</p>
                        <p class="text-xs text-gray-600">Enviar documento</p>
                    </div>
                </a> -->
                <a href="cliente-funcionario-novo.php?cliente_id=<?php echo $clienteId; ?>"
                    class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-lg hover:border-green-400 hover:shadow-md transition-all">
                    <i data-lucide="user-plus" class="w-8 h-8 text-green-600"></i>
                    <div>
                        <p class="font-semibold text-gray-900">Funcionário</p>
                        <p class="text-xs text-gray-600">Cadastrar</p>
                    </div>
                </a>
                <a href="cliente-certificado-novo.php?cliente_id=<?php echo $clienteId; ?>"
                    class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-lg hover:border-amber-400 hover:shadow-md transition-all">
                    <i data-lucide="shield-plus" class="w-8 h-8 text-amber-600"></i>
                    <div>
                        <p class="font-semibold text-gray-900">Certificado</p>
                        <p class="text-xs text-gray-600">Adicionar</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>