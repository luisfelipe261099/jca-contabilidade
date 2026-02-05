<?php
require_once 'config/config.php';
if (!isLoggedIn()) redirect('index.php');

$usuario = getUsuarioLogado();
$db = getDB();

// Filtros
$clienteId = $_GET['cliente_id'] ?? '';
$status = $_GET['status'] ?? '';
$busca = $_GET['busca'] ?? '';

// Query base
$sql = "SELECT fc.*, c.razao_social as cliente_nome, c.nome_fantasia
        FROM funcionarios_clientes fc
        INNER JOIN clientes c ON fc.cliente_id = c.id
        WHERE 1=1";

$params = [];

if ($clienteId) {
    $sql .= " AND fc.cliente_id = ?";
    $params[] = $clienteId;
}

if ($status) {
    $sql .= " AND fc.status = ?";
    $params[] = $status;
}

if ($busca) {
    $sql .= " AND (fc.nome_completo LIKE ? OR fc.cpf LIKE ? OR fc.cargo LIKE ?)";
    $params[] = "%$busca%";
    $params[] = "%$busca%";
    $params[] = "%$busca%";
}

$sql .= " ORDER BY fc.nome_completo ASC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$funcionarios = $stmt->fetchAll();

// Buscar clientes para o filtro
$stmtClientes = $db->query("SELECT id, razao_social, nome_fantasia FROM clientes WHERE ativo = 1 ORDER BY razao_social");
$clientes = $stmtClientes->fetchAll();

// Estatísticas
$stmtStats = $db->query("SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status = 'Ativo' THEN 1 ELSE 0 END) as ativos,
    SUM(CASE WHEN status = 'Férias' THEN 1 ELSE 0 END) as ferias,
    SUM(CASE WHEN status = 'Afastado' THEN 1 ELSE 0 END) as afastados,
    SUM(CASE WHEN status = 'Demitido' THEN 1 ELSE 0 END) as demitidos
    FROM funcionarios_clientes");
$stats = $stmtStats->fetch();

$pageTitle = 'Funcionários dos Clientes';
$breadcrumb = '<a href="dashboard.php">Dashboard</a> / Funcionários';
include 'includes/header.php';
?>

<div class="p-6 space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Funcionários dos Clientes</h1>
            <p class="text-gray-600 mt-1">Gestão de funcionários das empresas clientes</p>
        </div>
        <a href="funcionario-cliente-novo.php<?php echo $clienteId ? '?cliente_id=' . $clienteId : ''; ?>" class="px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors flex items-center gap-2 shadow-lg hover:shadow-xl">
            <i data-lucide="user-plus" class="w-5 h-5"></i>
            Novo Funcionário
        </a>
    </div>

    <!-- Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="users" class="w-6 h-6 text-blue-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900"><?php echo $stats['total']; ?></p>
                    <p class="text-sm text-gray-600">Total</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg border border-green-200 p-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="user-check" class="w-6 h-6 text-green-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900"><?php echo $stats['ativos']; ?></p>
                    <p class="text-sm text-gray-600">Ativos</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg border border-blue-200 p-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="plane" class="w-6 h-6 text-blue-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900"><?php echo $stats['ferias']; ?></p>
                    <p class="text-sm text-gray-600">Férias</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg border border-amber-200 p-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="user-x" class="w-6 h-6 text-amber-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900"><?php echo $stats['afastados']; ?></p>
                    <p class="text-sm text-gray-600">Afastados</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="user-minus" class="w-6 h-6 text-gray-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900"><?php echo $stats['demitidos']; ?></p>
                    <p class="text-sm text-gray-600">Demitidos</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cliente</label>
                <select name="cliente_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="">Todos os clientes</option>
                    <?php foreach ($clientes as $c): ?>
                        <option value="<?php echo $c['id']; ?>" <?php echo $clienteId == $c['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($c['nome_fantasia'] ?: $c['razao_social']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="">Todos os status</option>
                    <option value="Ativo" <?php echo $status == 'Ativo' ? 'selected' : ''; ?>>Ativo</option>
                    <option value="Férias" <?php echo $status == 'Férias' ? 'selected' : ''; ?>>Férias</option>
                    <option value="Afastado" <?php echo $status == 'Afastado' ? 'selected' : ''; ?>>Afastado</option>
                    <option value="Demitido" <?php echo $status == 'Demitido' ? 'selected' : ''; ?>>Demitido</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                <input type="text" name="busca" value="<?php echo htmlspecialchars($busca); ?>" placeholder="Nome, CPF ou cargo..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors flex items-center justify-center gap-2">
                    <i data-lucide="search" class="w-4 h-4"></i>
                    Filtrar
                </button>
                <a href="funcionarios-clientes.php" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Tabela -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Funcionário</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Cargo</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Admissão</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Salário</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if (empty($funcionarios)): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <i data-lucide="users" class="w-12 h-12 mx-auto mb-3 text-gray-400"></i>
                                <p class="text-lg font-medium">Nenhum funcionário encontrado</p>
                                <p class="text-sm mt-1">Cadastre o primeiro funcionário para começar</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($funcionarios as $func): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($func['nome_completo']); ?></p>
                                        <p class="text-sm text-gray-600"><?php echo formatarCPF($func['cpf']); ?></p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-gray-900"><?php echo htmlspecialchars($func['cliente_nome']); ?></p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-gray-900"><?php echo htmlspecialchars($func['cargo']); ?></p>
                                    <?php if ($func['departamento']): ?>
                                        <p class="text-sm text-gray-600"><?php echo htmlspecialchars($func['departamento']); ?></p>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-gray-900"><?php echo formatarData($func['data_admissao']); ?></p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-gray-900"><?php echo formatarMoeda($func['salario_base']); ?></p>
                                </td>
                                <td class="px-6 py-4">
                                    <?php
                                    $statusColors = [
                                        'Ativo' => 'bg-green-100 text-green-700',
                                        'Férias' => 'bg-blue-100 text-blue-700',
                                        'Afastado' => 'bg-amber-100 text-amber-700',
                                        'Demitido' => 'bg-gray-100 text-gray-700'
                                    ];
                                    $statusColor = $statusColors[$func['status']] ?? 'bg-gray-100 text-gray-700';
                                    ?>
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold <?php echo $statusColor; ?>">
                                        <?php echo $func['status']; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="funcionario-cliente-detalhes.php?id=<?php echo $func['id']; ?>" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Ver detalhes">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </a>
                                        <a href="funcionario-cliente-editar.php?id=<?php echo $func['id']; ?>" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors" title="Editar">
                                            <i data-lucide="edit" class="w-4 h-4"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();
</script>

<?php include 'includes/footer.php'; ?>


