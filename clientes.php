<?php
/**
 * JCA ERP - Gestão de Clientes
 * 
 * @package JCA_ERP
 * @version 1.0.0
 */

require_once 'config/config.php';

if (!isLoggedIn()) {
    redirect('index.php');
}

$usuario = getUsuarioLogado();
$db = getDB();

// Paginação
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = ITEMS_PER_PAGE;
$offset = ($page - 1) * $limit;

// Filtros
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$regime = isset($_GET['regime']) ? sanitize($_GET['regime']) : '';
$status = isset($_GET['status']) ? sanitize($_GET['status']) : '';

// Query base
$where = ["c.ativo = 1"];
$params = [];

if (!empty($search)) {
    $where[] = "(c.razao_social LIKE ? OR c.nome_fantasia LIKE ? OR c.cnpj LIKE ?)";
    $searchParam = "%$search%";
    $params[] = $searchParam;
    $params[] = $searchParam;
    $params[] = $searchParam;
}

if (!empty($regime)) {
    $where[] = "c.regime_tributario = ?";
    $params[] = $regime;
}

if (!empty($status)) {
    $where[] = "c.status_contrato = ?";
    $params[] = $status;
}

$whereClause = implode(" AND ", $where);

// Total de registros
$stmt = $db->prepare("SELECT COUNT(*) as total FROM clientes c WHERE $whereClause");
$stmt->execute($params);
$totalRecords = $stmt->fetch()['total'];
$totalPages = ceil($totalRecords / $limit);

// Busca clientes
$stmt = $db->prepare("SELECT c.*, u.nome as contador_nome 
                      FROM clientes c
                      LEFT JOIN usuarios u ON c.contador_responsavel_id = u.id
                      WHERE $whereClause
                      ORDER BY c.razao_social ASC
                      LIMIT $limit OFFSET $offset");
$stmt->execute($params);
$clientes = $stmt->fetchAll();

// Estatísticas rápidas
$stmt = $db->query("SELECT COUNT(*) as total FROM clientes WHERE ativo = 1");
$stats['total_clientes'] = $stmt->fetch()['total'];

$stmt = $db->query("SELECT COUNT(*) as total FROM clientes WHERE status_contrato = 'Ativo'");
$stats['clientes_ativos'] = $stmt->fetch()['total'];

$stmt = $db->query("SELECT COUNT(*) as total FROM clientes WHERE status_contrato = 'Inadimplente'");
$stats['inadimplentes'] = $stmt->fetch()['total'];

$pageTitle = 'Gestão de Clientes';
$breadcrumb = '<a href="dashboard.php">Dashboard</a> / Clientes';
include 'includes/header.php';
?>

<div class="p-6 space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="building-2" class="w-6 h-6 text-primary-600"></i>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-900"><?php echo $stats['total_clientes']; ?></p>
                    <p class="text-sm text-gray-600">Total de Clientes</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-6 h-6 text-green-600"></i>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-900"><?php echo $stats['clientes_ativos']; ?></p>
                    <p class="text-sm text-gray-600">Contratos Ativos</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="alert-triangle" class="w-6 h-6 text-red-600"></i>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-900"><?php echo $stats['inadimplentes']; ?></p>
                    <p class="text-sm text-gray-600">Inadimplentes</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros e Ações -->
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                <input type="text" id="searchInput" placeholder="Razão social, CNPJ..." value="<?php echo htmlspecialchars($search); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Regime</label>
                <select id="regimeFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="">Todos</option>
                    <option value="Simples Nacional" <?php echo $regime === 'Simples Nacional' ? 'selected' : ''; ?>>Simples Nacional</option>
                    <option value="Lucro Presumido" <?php echo $regime === 'Lucro Presumido' ? 'selected' : ''; ?>>Lucro Presumido</option>
                    <option value="Lucro Real" <?php echo $regime === 'Lucro Real' ? 'selected' : ''; ?>>Lucro Real</option>
                    <option value="MEI" <?php echo $regime === 'MEI' ? 'selected' : ''; ?>>MEI</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="statusFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="">Todos</option>
                    <option value="Ativo" <?php echo $status === 'Ativo' ? 'selected' : ''; ?>>Ativo</option>
                    <option value="Suspenso" <?php echo $status === 'Suspenso' ? 'selected' : ''; ?>>Suspenso</option>
                    <option value="Inadimplente" <?php echo $status === 'Inadimplente' ? 'selected' : ''; ?>>Inadimplente</option>
                    <option value="Cancelado" <?php echo $status === 'Cancelado' ? 'selected' : ''; ?>>Cancelado</option>
                </select>
            </div>
            <div class="flex items-end">
                <button onclick="aplicarFiltros()" class="w-full px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors flex items-center justify-center gap-2">
                    <i data-lucide="filter" class="w-4 h-4"></i>
                    Filtrar
                </button>
            </div>
            <div class="flex items-end">
                <a href="cliente-novo.php" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center justify-center gap-2">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Novo Cliente
                </a>
            </div>
        </div>
    </div>

    <!-- Tabela de Clientes -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i data-lucide="list" class="w-5 h-5 text-gray-600"></i>
                <h5 class="text-lg font-semibold text-gray-900">Lista de Clientes</h5>
                <span class="px-2.5 py-0.5 bg-primary-100 text-primary-700 text-sm font-medium rounded-full"><?php echo $totalRecords; ?> registros</span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Razão Social</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">CNPJ</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Regime</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Responsável</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if (empty($clientes)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <i data-lucide="inbox" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                                <p class="text-gray-500">Nenhum cliente encontrado</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($clientes as $cliente): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900"><?php echo htmlspecialchars($cliente['razao_social']); ?></div>
                                    <?php if ($cliente['nome_fantasia']): ?>
                                        <div class="text-sm text-gray-500"><?php echo htmlspecialchars($cliente['nome_fantasia']); ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600"><?php echo formatarCNPJ($cliente['cnpj']); ?></td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                        <?php echo $cliente['regime_tributario']; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600"><?php echo $cliente['contador_nome'] ? htmlspecialchars($cliente['contador_nome']) : '-'; ?></td>
                                <td class="px-6 py-4">
                                    <?php
                                    $statusConfig = [
                                        'Ativo' => 'bg-green-100 text-green-700',
                                        'Suspenso' => 'bg-amber-100 text-amber-700',
                                        'Inadimplente' => 'bg-red-100 text-red-700',
                                        'Cancelado' => 'bg-gray-100 text-gray-700'
                                    ];
                                    ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $statusConfig[$cliente['status_contrato']] ?? 'bg-gray-100 text-gray-700'; ?>">
                                        <?php echo $cliente['status_contrato']; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <a href="cliente-detalhes.php?id=<?php echo $cliente['id']; ?>" class="inline-flex items-center gap-1 px-2.5 py-1.5 text-sm font-medium text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors" title="Ver Detalhes">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </a>
                                        <a href="cliente-editar.php?id=<?php echo $cliente['id']; ?>" class="inline-flex items-center gap-1 px-2.5 py-1.5 text-sm font-medium text-amber-700 bg-amber-50 rounded-lg hover:bg-amber-100 transition-colors" title="Editar">
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

        <?php if ($totalPages > 1): ?>
        <div class="px-6 py-4 border-t border-gray-200">
            <nav class="flex justify-center">
                <div class="flex gap-1">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&regime=<?php echo urlencode($regime); ?>&status=<?php echo urlencode($status); ?>" class="px-3 py-2 text-sm font-medium rounded-lg transition-colors <?php echo $i === $page ? 'bg-primary-600 text-white' : 'text-gray-700 hover:bg-gray-100'; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                </div>
            </nav>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
function aplicarFiltros() {
    const search = document.getElementById('searchInput').value;
    const regime = document.getElementById('regimeFilter').value;
    const status = document.getElementById('statusFilter').value;
    
    let url = 'clientes.php?';
    if (search) url += 'search=' + encodeURIComponent(search) + '&';
    if (regime) url += 'regime=' + encodeURIComponent(regime) + '&';
    if (status) url += 'status=' + encodeURIComponent(status);
    
    window.location.href = url;
}

// Enter para buscar
document.getElementById('searchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        aplicarFiltros();
    }
});
</script>

<?php include 'includes/footer.php'; ?>

