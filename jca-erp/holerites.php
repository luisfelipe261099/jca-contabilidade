<?php
require_once 'config/config.php';
if (!isLoggedIn()) redirect('index.php');

$usuario = getUsuarioLogado();
$db = getDB();

// Filtros
$filtroCliente = isset($_GET['cliente_id']) ? (int)$_GET['cliente_id'] : 0;
$filtroMes = isset($_GET['mes']) ? (int)$_GET['mes'] : 0;
$filtroAno = isset($_GET['ano']) ? (int)$_GET['ano'] : 0;
$filtroStatus = isset($_GET['status']) ? $_GET['status'] : '';
$filtroBusca = isset($_GET['busca']) ? trim($_GET['busca']) : '';

// Construir query
$sql = "
    SELECT h.*,
           fc.nome_completo as funcionario_nome,
           fc.cpf as funcionario_cpf,
           c.nome_fantasia as cliente_nome
    FROM holerites h
    INNER JOIN funcionarios_clientes fc ON h.funcionario_id = fc.id
    INNER JOIN clientes c ON h.cliente_id = c.id
    WHERE 1=1
";

$params = [];

if ($filtroCliente > 0) {
    $sql .= " AND h.cliente_id = ?";
    $params[] = $filtroCliente;
}

if ($filtroMes > 0) {
    $sql .= " AND h.mes_referencia = ?";
    $params[] = $filtroMes;
}

if ($filtroAno > 0) {
    $sql .= " AND h.ano_referencia = ?";
    $params[] = $filtroAno;
}

if ($filtroStatus) {
    $sql .= " AND h.status = ?";
    $params[] = $filtroStatus;
}

if ($filtroBusca) {
    $sql .= " AND (fc.nome_completo LIKE ? OR fc.cpf LIKE ?)";
    $params[] = "%$filtroBusca%";
    $params[] = "%$filtroBusca%";
}

$sql .= " ORDER BY h.ano_referencia DESC, h.mes_referencia DESC, fc.nome_completo";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$holerites = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Buscar clientes para filtro
$stmtClientes = $db->query("SELECT id, nome_fantasia FROM clientes ORDER BY nome_fantasia");
$clientes = $stmtClientes->fetchAll(PDO::FETCH_ASSOC);

// Estatísticas
$stmtStats = $db->query("
    SELECT
        COUNT(*) as total,
        SUM(CASE WHEN status = 'Rascunho' THEN 1 ELSE 0 END) as rascunhos,
        SUM(CASE WHEN status = 'Processado' THEN 1 ELSE 0 END) as processados,
        SUM(CASE WHEN status = 'Enviado' THEN 1 ELSE 0 END) as enviados,
        SUM(CASE WHEN status = 'Pago' THEN 1 ELSE 0 END) as pagos,
        SUM(salario_liquido) as total_liquido
    FROM holerites
");
$stats = $stmtStats->fetch(PDO::FETCH_ASSOC);

$pageTitle = 'Holerites';
include 'includes/header.php';
?>

<div class="min-h-screen bg-gray-50 pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Holerites</h1>
                    <p class="text-gray-600 mt-1">Gerencie os holerites dos funcionários</p>
                </div>
                <a href="holerite-gerar.php" class="px-6 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors flex items-center gap-2">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Gerar Holerite
                </a>
            </div>
        </div>

        <!-- Cards de Estatísticas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1"><?php echo number_format($stats['total'] ?? 0); ?></p>
                    </div>
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="file-text" class="w-6 h-6 text-gray-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Rascunhos</p>
                        <p class="text-2xl font-bold text-yellow-600 mt-1"><?php echo number_format($stats['rascunhos'] ?? 0); ?></p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="edit" class="w-6 h-6 text-yellow-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Processados</p>
                        <p class="text-2xl font-bold text-blue-600 mt-1"><?php echo number_format($stats['processados'] ?? 0); ?></p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="check-circle" class="w-6 h-6 text-blue-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Enviados</p>
                        <p class="text-2xl font-bold text-purple-600 mt-1"><?php echo number_format($stats['enviados'] ?? 0); ?></p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="send" class="w-6 h-6 text-purple-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Pagos</p>
                        <p class="text-2xl font-bold text-green-600 mt-1"><?php echo number_format($stats['pagos'] ?? 0); ?></p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="check-check" class="w-6 h-6 text-green-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mensagem vazia -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <i data-lucide="inbox" class="w-16 h-16 mx-auto mb-4 text-gray-400"></i>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Nenhum holerite encontrado</h3>
            <p class="text-gray-600 mb-6">Clique em "Gerar Holerite" para criar o primeiro holerite</p>
            <a href="holerite-gerar.php" class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Gerar Primeiro Holerite
            </a>
        </div>
    </div>
</div>

<script>
lucide.createIcons();
</script>

<?php include 'includes/footer.php'; ?>

