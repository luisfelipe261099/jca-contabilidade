<?php
require_once 'config/config.php';

if (!isLoggedIn()) {
    redirect('index.php');
}

$db = getDB();
$usuario = getUsuarioLogado();

// Filtros
$tipo = isset($_GET['tipo']) ? sanitize($_GET['tipo']) : '';
$nivel = isset($_GET['nivel']) ? sanitize($_GET['nivel']) : '';
$lido = isset($_GET['lido']) ? sanitize($_GET['lido']) : '';

// Marcar como lido
if (isset($_GET['marcar_lido']) && $_GET['marcar_lido']) {
    $alertaId = (int)$_GET['marcar_lido'];
    $stmt = $db->prepare("UPDATE alertas SET lido = 1, data_leitura = NOW() WHERE id = ? AND usuario_id = ?");
    $stmt->execute([$alertaId, $usuario['id']]);
    redirect('alertas.php');
}

// Marcar todos como lidos
if (isset($_POST['marcar_todos_lidos'])) {
    $stmt = $db->prepare("UPDATE alertas SET lido = 1, data_leitura = NOW() WHERE usuario_id = ? AND lido = 0");
    $stmt->execute([$usuario['id']]);
    setSuccess('Todos os alertas foram marcados como lidos!');
    redirect('alertas.php');
}

// Query base
$where = ["a.usuario_id = ?"];
$params = [$usuario['id']];

if (!empty($tipo)) {
    $where[] = "a.tipo = ?";
    $params[] = $tipo;
}

if (!empty($nivel)) {
    $where[] = "a.nivel = ?";
    $params[] = $nivel;
}

if ($lido !== '') {
    $where[] = "a.lido = ?";
    $params[] = $lido;
}

$whereClause = implode(" AND ", $where);

// Busca alertas
$stmt = $db->prepare("SELECT a.*, c.razao_social as cliente_nome
                      FROM alertas a
                      LEFT JOIN clientes c ON a.cliente_id = c.id
                      WHERE $whereClause
                      ORDER BY a.lido ASC, a.data_criacao DESC");
$stmt->execute($params);
$alertas = $stmt->fetchAll();

// Estatísticas
$stmt = $db->prepare("SELECT COUNT(*) as total FROM alertas WHERE usuario_id = ? AND lido = 0");
$stmt->execute([$usuario['id']]);
$naoLidos = $stmt->fetch()['total'];

$stmt = $db->prepare("SELECT COUNT(*) as total FROM alertas WHERE usuario_id = ? AND nivel = 'Crítico' AND lido = 0");
$stmt->execute([$usuario['id']]);
$criticos = $stmt->fetch()['total'];

$pageTitle = 'Alertas e Notificações';
$breadcrumb = '<a href="dashboard.php">Dashboard</a> / Alertas';
include 'includes/header.php';
?>

<div class="p-6 space-y-6">
    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="bell" class="w-6 h-6 text-amber-600"></i>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-900"><?php echo $naoLidos; ?></p>
                    <p class="text-sm text-gray-600">Alertas Não Lidos</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="alert-triangle" class="w-6 h-6 text-red-600"></i>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-900"><?php echo $criticos; ?></p>
                    <p class="text-sm text-gray-600">Alertas Críticos</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros e Ações -->
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipo</label>
                <select id="tipoFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="">Todos</option>
                    <option value="Vencimento" <?php echo $tipo === 'Vencimento' ? 'selected' : ''; ?>>Vencimento</option>
                    <option value="Atraso" <?php echo $tipo === 'Atraso' ? 'selected' : ''; ?>>Atraso</option>
                    <option value="Pendência" <?php echo $tipo === 'Pendência' ? 'selected' : ''; ?>>Pendência</option>
                    <option value="Financeiro" <?php echo $tipo === 'Financeiro' ? 'selected' : ''; ?>>Financeiro</option>
                    <option value="Sistema" <?php echo $tipo === 'Sistema' ? 'selected' : ''; ?>>Sistema</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nível</label>
                <select id="nivelFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="">Todos</option>
                    <option value="Info" <?php echo $nivel === 'Info' ? 'selected' : ''; ?>>Info</option>
                    <option value="Aviso" <?php echo $nivel === 'Aviso' ? 'selected' : ''; ?>>Aviso</option>
                    <option value="Urgente" <?php echo $nivel === 'Urgente' ? 'selected' : ''; ?>>Urgente</option>
                    <option value="Crítico" <?php echo $nivel === 'Crítico' ? 'selected' : ''; ?>>Crítico</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="lidoFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="">Todos</option>
                    <option value="0" <?php echo $lido === '0' ? 'selected' : ''; ?>>Não Lidos</option>
                    <option value="1" <?php echo $lido === '1' ? 'selected' : ''; ?>>Lidos</option>
                </select>
            </div>
            <div class="flex items-end">
                <button onclick="aplicarFiltros()" class="w-full px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors flex items-center justify-center gap-2">
                    <i data-lucide="filter" class="w-4 h-4"></i>
                    Filtrar
                </button>
            </div>
            <div class="flex items-end">
                <form method="POST" class="w-full">
                    <button type="submit" name="marcar_todos_lidos" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center justify-center gap-2">
                        <i data-lucide="check-check" class="w-4 h-4"></i>
                        Marcar Todos
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Lista de Alertas -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i data-lucide="list" class="w-5 h-5 text-gray-600"></i>
                <h5 class="text-lg font-semibold text-gray-900">Todos os Alertas</h5>
                <span class="px-2.5 py-0.5 bg-primary-100 text-primary-700 text-sm font-medium rounded-full"><?php echo count($alertas); ?> alertas</span>
            </div>
        </div>
        <div>
            <?php if (empty($alertas)): ?>
                <div class="text-center py-12">
                    <i data-lucide="inbox" class="w-16 h-16 text-gray-300 mx-auto mb-4"></i>
                    <p class="text-gray-500">Nenhum alerta encontrado</p>
                </div>
            <?php else: ?>
                <div class="divide-y divide-gray-200">
                    <?php foreach ($alertas as $alerta): ?>
                        <div class="p-4 hover:bg-gray-50 transition-colors <?php echo !$alerta['lido'] ? 'bg-blue-50/30' : ''; ?>">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <div class="flex items-start gap-3 mb-2">
                                        <?php
                                        $nivelConfig = [
                                            'Info' => ['icon' => 'info', 'color' => 'text-blue-600', 'bg' => 'bg-blue-100'],
                                            'Aviso' => ['icon' => 'alert-triangle', 'color' => 'text-amber-600', 'bg' => 'bg-amber-100'],
                                            'Urgente' => ['icon' => 'alert-circle', 'color' => 'text-red-600', 'bg' => 'bg-red-100'],
                                            'Crítico' => ['icon' => 'x-circle', 'color' => 'text-gray-900', 'bg' => 'bg-gray-200']
                                        ];
                                        $config = $nivelConfig[$alerta['nivel']] ?? ['icon' => 'bell', 'color' => 'text-gray-600', 'bg' => 'bg-gray-100'];
                                        ?>
                                        <div class="w-10 h-10 <?php echo $config['bg']; ?> rounded-lg flex items-center justify-center flex-shrink-0">
                                            <i data-lucide="<?php echo $config['icon']; ?>" class="w-5 h-5 <?php echo $config['color']; ?>"></i>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                <h6 class="font-semibold text-gray-900"><?php echo htmlspecialchars($alerta['titulo']); ?></h6>
                                                <?php if (!$alerta['lido']): ?>
                                                    <span class="px-2 py-0.5 bg-blue-600 text-white text-xs font-medium rounded-full">Novo</span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                                <span class="px-2 py-0.5 bg-gray-100 text-gray-700 text-xs font-medium rounded"><?php echo $alerta['tipo']; ?></span>
                                                <span class="px-2 py-0.5 <?php echo ['Info' => 'bg-blue-100 text-blue-700', 'Aviso' => 'bg-amber-100 text-amber-700', 'Urgente' => 'bg-red-100 text-red-700', 'Crítico' => 'bg-gray-200 text-gray-900'][$alerta['nivel']] ?? 'bg-gray-100 text-gray-700'; ?> text-xs font-medium rounded">
                                                    <?php echo $alerta['nivel']; ?>
                                                </span>
                                                <?php if ($alerta['cliente_nome']): ?>
                                                    <span class="flex items-center gap-1 text-xs text-gray-600">
                                                        <i data-lucide="building-2" class="w-3 h-3"></i>
                                                        <?php echo htmlspecialchars($alerta['cliente_nome']); ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            <p class="text-sm text-gray-700 mb-2"><?php echo htmlspecialchars($alerta['mensagem']); ?></p>
                                            <div class="flex items-center gap-1 text-xs text-gray-500">
                                                <i data-lucide="clock" class="w-3 h-3"></i>
                                                <?php echo formatarDataHora($alerta['data_criacao']); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <?php if (!$alerta['lido']): ?>
                                        <a href="?marcar_lido=<?php echo $alerta['id']; ?>" class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors" title="Marcar como lido">
                                            <i data-lucide="check" class="w-4 h-4"></i>
                                        </a>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1 text-sm text-green-600">
                                            <i data-lucide="check-circle" class="w-4 h-4"></i>
                                            Lido
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function aplicarFiltros() {
    const tipo = document.getElementById('tipoFilter').value;
    const nivel = document.getElementById('nivelFilter').value;
    const lido = document.getElementById('lidoFilter').value;
    
    let url = 'alertas.php?';
    if (tipo) url += 'tipo=' + encodeURIComponent(tipo) + '&';
    if (nivel) url += 'nivel=' + encodeURIComponent(nivel) + '&';
    if (lido !== '') url += 'lido=' + encodeURIComponent(lido);
    
    window.location.href = url;
}
</script>

<?php include 'includes/footer.php'; ?>

