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

// Busca funcionários do cliente
$stmt = $db->prepare("SELECT * FROM funcionarios_clientes WHERE cliente_id = ? ORDER BY nome_completo");
$stmt->execute([$clienteId]);
$funcionarios = $stmt->fetchAll();

// Estatísticas
$totalFuncionarios = count($funcionarios);
$ativos = count(array_filter($funcionarios, fn($f) => $f['status'] === 'Ativo'));
$folhaMensal = array_sum(array_column($funcionarios, 'salario_base'));

$pageTitle = 'Funcionários - ' . $cliente['razao_social'];
$breadcrumb = '<a href="dashboard.php">Dashboard</a> / <a href="clientes.php">Clientes</a> / <a href="cliente-detalhes.php?id='.$clienteId.'">'.htmlspecialchars($cliente['razao_social']).'</a> / Funcionários';
include 'includes/header.php';
?>

<div class="p-6 space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900"><?php echo htmlspecialchars($cliente['razao_social']); ?></h1>
            <p class="text-sm text-gray-600 mt-1">Gestão de Funcionários</p>
        </div>
        <a href="cliente-funcionario-novo.php?cliente_id=<?php echo $clienteId; ?>" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
            <i data-lucide="user-plus" class="w-4 h-4"></i>
            Novo Funcionário
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="users" class="w-6 h-6 text-blue-600"></i>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-900"><?php echo $totalFuncionarios; ?></p>
                    <p class="text-sm text-gray-600">Total de Funcionários</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="user-check" class="w-6 h-6 text-green-600"></i>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-900"><?php echo $ativos; ?></p>
                    <p class="text-sm text-gray-600">Funcionários Ativos</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="dollar-sign" class="w-6 h-6 text-amber-600"></i>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-900"><?php echo formatarMoeda($folhaMensal); ?></p>
                    <p class="text-sm text-gray-600">Folha Mensal (Base)</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela de Funcionários -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h5 class="text-lg font-semibold text-gray-900">Funcionários</h5>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nome</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">CPF</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Cargo</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Admissão</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Salário</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if (!$funcionarios): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <i data-lucide="users" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                                <p class="text-gray-500">Nenhum funcionário cadastrado</p>
                                <a href="cliente-funcionario-novo.php?cliente_id=<?php echo $clienteId; ?>" class="inline-flex items-center gap-2 mt-3 px-4 py-2 text-sm font-medium text-primary-700 bg-primary-50 rounded-lg hover:bg-primary-100 transition-colors">
                                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                                    Cadastrar Primeiro Funcionário
                                </a>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($funcionarios as $f): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900"><?php echo htmlspecialchars($f['nome_completo']); ?></td>
                            <td class="px-6 py-4 text-sm text-gray-600"><?php echo formatarCPF($f['cpf']); ?></td>
                            <td class="px-6 py-4 text-sm text-gray-600"><?php echo htmlspecialchars($f['cargo']); ?></td>
                            <td class="px-6 py-4 text-sm text-gray-600"><?php echo formatarData($f['data_admissao']); ?></td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900"><?php echo formatarMoeda($f['salario_base']); ?></td>
                            <td class="px-6 py-4">
                                <?php
                                $statusColors = [
                                    'Ativo' => 'bg-green-100 text-green-700',
                                    'Férias' => 'bg-blue-100 text-blue-700',
                                    'Afastado' => 'bg-amber-100 text-amber-700',
                                    'Demitido' => 'bg-red-100 text-red-700'
                                ];
                                $color = $statusColors[$f['status']] ?? 'bg-gray-100 text-gray-700';
                                ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $color; ?>">
                                    <?php echo $f['status']; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="cliente-funcionario-editar.php?id=<?php echo $f['id']; ?>" class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-primary-700 bg-primary-50 rounded-lg hover:bg-primary-100 transition-colors">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </a>
                                <a href="cliente-holerites.php?funcionario_id=<?php echo $f['id']; ?>" class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-green-700 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                                    <i data-lucide="file-text" class="w-4 h-4"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

