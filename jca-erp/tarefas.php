<?php
require_once 'config/config.php';

if (!isLoggedIn()) {
    redirect('index.php');
}

$db = getDB();
$usuario = getUsuarioLogado();

// Filtros
$status = isset($_GET['status']) ? sanitize($_GET['status']) : '';
$prioridade = isset($_GET['prioridade']) ? sanitize($_GET['prioridade']) : '';
$tipo = isset($_GET['tipo']) ? sanitize($_GET['tipo']) : '';
$clienteId = !empty($_GET['cliente_id']) ? (int)$_GET['cliente_id'] : 0;
$responsavel = isset($_GET['responsavel']) ? sanitize($_GET['responsavel']) : '';

// Query base
$where = ["1=1"];
$params = [];

// Se não for admin, mostra apenas tarefas do usuário
if (!isAdmin()) {
    $where[] = "t.responsavel_id = ?";
    $params[] = $usuario['id'];
}

if (!empty($status)) {
    $where[] = "t.status = ?";
    $params[] = $status;
}

if (!empty($prioridade)) {
    $where[] = "t.prioridade = ?";
    $params[] = $prioridade;
}

if (!empty($tipo)) {
    $where[] = "t.tipo = ?";
    $params[] = $tipo;
}

if ($clienteId > 0) {
    $where[] = "t.cliente_id = ?";
    $params[] = $clienteId;
}

if (!empty($responsavel) && isAdmin()) {
    $where[] = "t.responsavel_id = ?";
    $params[] = $responsavel;
}

$whereClause = implode(" AND ", $where);

// Busca tarefas
$stmt = $db->prepare("SELECT t.*,
                      c.razao_social as cliente_nome,
                      u.nome as responsavel_nome,
                      uc.nome as criador_nome
                      FROM tarefas t
                      LEFT JOIN clientes c ON t.cliente_id = c.id
                      LEFT JOIN usuarios u ON t.responsavel_id = u.id
                      LEFT JOIN usuarios uc ON t.criado_por_id = uc.id
                      WHERE $whereClause
                      ORDER BY
                        CASE t.prioridade
                            WHEN 'Urgente' THEN 1
                            WHEN 'Alta' THEN 2
                            WHEN 'Média' THEN 3
                            WHEN 'Baixa' THEN 4
                        END,
                        t.data_vencimento ASC");
$stmt->execute($params);
$tarefas = $stmt->fetchAll();

// Busca funcionários para filtro
$stmt = $db->query("SELECT id, nome FROM usuarios WHERE tipo_usuario IN ('admin', 'funcionario') AND ativo = 1 ORDER BY nome");
$funcionarios = $stmt->fetchAll();

// Busca clientes para filtro
$stmt = $db->query("SELECT id, razao_social FROM clientes WHERE ativo = 1 ORDER BY razao_social");
$clientes = $stmt->fetchAll();


// Estatísticas
$stmt = $db->prepare("SELECT COUNT(*) as total FROM tarefas WHERE status = 'Pendente'" . (!isAdmin() ? " AND responsavel_id = ?" : ""));
$params = !isAdmin() ? [$usuario['id']] : [];
$stmt->execute($params);
$pendentes = $stmt->fetch()['total'];

$stmt = $db->prepare("SELECT COUNT(*) as total FROM tarefas WHERE status = 'Em Andamento'" . (!isAdmin() ? " AND responsavel_id = ?" : ""));
$stmt->execute($params);
$emAndamento = $stmt->fetch()['total'];

$stmt = $db->prepare("SELECT COUNT(*) as total FROM tarefas WHERE data_vencimento < CURDATE() AND status NOT IN ('Concluída','Cancelada')" . (!isAdmin() ? " AND responsavel_id = ?" : ""));
$stmt->execute($params);
$atrasadas = $stmt->fetch()['total'];

$pageTitle = 'Tarefas';
$breadcrumb = '<a href="dashboard.php">Dashboard</a> / Tarefas';
include 'includes/header.php';
?>

<div class="p-6 space-y-6">
    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="clock" class="w-6 h-6 text-amber-600"></i>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-900"><?php echo $pendentes; ?></p>
                    <p class="text-sm text-gray-600">Tarefas Pendentes</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="loader" class="w-6 h-6 text-blue-600"></i>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-900"><?php echo $emAndamento; ?></p>
                    <p class="text-sm text-gray-600">Em Andamento</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="alert-triangle" class="w-6 h-6 text-red-600"></i>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-900"><?php echo $atrasadas; ?></p>
                    <p class="text-sm text-gray-600">Atrasadas</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros e Ações -->
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <div class="grid grid-cols-1 md:grid-cols-<?php echo isAdmin() ? '7' : '6'; ?> gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="statusFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="">Todos</option>
                    <option value="Pendente" <?php echo $status === 'Pendente' ? 'selected' : ''; ?>>Pendente</option>
                    <option value="Em Andamento" <?php echo $status === 'Em Andamento' ? 'selected' : ''; ?>>Em Andamento</option>
                    <option value="Aguardando" <?php echo $status === 'Aguardando' ? 'selected' : ''; ?>>Aguardando</option>
                    <option value="Concluída" <?php echo $status === 'Concluída' ? 'selected' : ''; ?>>Concluída</option>
                    <option value="Cancelada" <?php echo $status === 'Cancelada' ? 'selected' : ''; ?>>Cancelada</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Prioridade</label>
                <select id="prioridadeFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="">Todas</option>
                    <option value="Urgente" <?php echo $prioridade === 'Urgente' ? 'selected' : ''; ?>>Urgente</option>
                    <option value="Alta" <?php echo $prioridade === 'Alta' ? 'selected' : ''; ?>>Alta</option>
                    <option value="Média" <?php echo $prioridade === 'Média' ? 'selected' : ''; ?>>Média</option>
                    <option value="Baixa" <?php echo $prioridade === 'Baixa' ? 'selected' : ''; ?>>Baixa</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipo</label>
                <select id="tipoFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="">Todos</option>
                    <option value="Contábil" <?php echo $tipo === 'Contábil' ? 'selected' : ''; ?>>Contábil</option>
                    <option value="Fiscal" <?php echo $tipo === 'Fiscal' ? 'selected' : ''; ?>>Fiscal</option>
                    <option value="RH" <?php echo $tipo === 'RH' ? 'selected' : ''; ?>>RH</option>
                    <option value="Consultoria" <?php echo $tipo === 'Consultoria' ? 'selected' : ''; ?>>Consultoria</option>
                    <option value="Administrativo" <?php echo $tipo === 'Administrativo' ? 'selected' : ''; ?>>Administrativo</option>
                    <option value="Outros" <?php echo $tipo === 'Outros' ? 'selected' : ''; ?>>Outros</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cliente</label>
                <select id="clienteFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="">Todos</option>
                    <?php foreach ($clientes as $c): ?>
                        <option value="<?php echo $c['id']; ?>" <?php echo $clienteId == $c['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($c['razao_social']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php if (isAdmin()): ?>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Responsável</label>
                <select id="responsavelFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="">Todos</option>
                    <?php foreach ($funcionarios as $func): ?>
                        <option value="<?php echo $func['id']; ?>" <?php echo $responsavel == $func['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($func['nome']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>
            <div class="flex items-end">
                <button onclick="aplicarFiltros()" class="w-full px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors flex items-center justify-center gap-2">
                    <i data-lucide="filter" class="w-4 h-4"></i>
                    Filtrar
                </button>
            </div>
            <div class="flex items-end">
                <a href="tarefa-nova.php" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center justify-center gap-2">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Nova Tarefa
                </a>
            </div>
        </div>
    </div>

    <!-- Lista de Tarefas -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i data-lucide="list-checks" class="w-5 h-5 text-gray-600"></i>
                <h5 class="text-lg font-semibold text-gray-900">Minhas Tarefas</h5>
                <span class="px-2.5 py-0.5 bg-primary-100 text-primary-700 text-sm font-medium rounded-full"><?php echo count($tarefas); ?> tarefas</span>
            </div>
        </div>
        <div>
            <?php if (empty($tarefas)): ?>
                <div class="text-center py-12">
                    <i data-lucide="inbox" class="w-16 h-16 text-gray-300 mx-auto mb-4"></i>
                    <p class="text-gray-500">Nenhuma tarefa encontrada</p>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider" style="width: 35%">Tarefa</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Cliente</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Responsável</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Vencimento</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Prioridade</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach ($tarefas as $tarefa): ?>
                                <?php
                                $atrasada = $tarefa['data_vencimento'] < date('Y-m-d') && !in_array($tarefa['status'], ['Concluída', 'Cancelada'], true);
                                ?>
                                <tr class="hover:bg-gray-50 transition-colors <?php echo $atrasada ? 'bg-red-50' : ''; ?>">
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900"><?php echo htmlspecialchars($tarefa['titulo']); ?></div>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                                <?php echo $tarefa['tipo']; ?>
                                            </span>
                                            <?php if ($atrasada): ?>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-700">
                                                    Atrasada
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <?php if ($tarefa['cliente_nome']): ?>
                                            <a href="cliente-detalhes.php?id=<?php echo $tarefa['cliente_id']; ?>" class="text-primary-600 hover:text-primary-700 font-medium">
                                                <?php echo htmlspecialchars($tarefa['cliente_nome']); ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-gray-500">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600"><?php echo $tarefa['responsavel_nome'] ? htmlspecialchars($tarefa['responsavel_nome']) : '-'; ?></td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900"><?php echo formatarData($tarefa['data_vencimento']); ?></div>
                                        <?php if ($atrasada): ?>
                                            <div class="flex items-center gap-1 text-xs text-red-600 mt-1">
                                                <i data-lucide="alert-triangle" class="w-3 h-3"></i>
                                                <?php
                                                $dias = (strtotime(date('Y-m-d')) - strtotime($tarefa['data_vencimento'])) / 86400;
                                                echo floor($dias) . ' dia(s)';
                                                ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php
                                        $prioridadeConfig = [
                                            'Baixa' => 'bg-gray-100 text-gray-700',
                                            'Média' => 'bg-blue-100 text-blue-700',
                                            'Alta' => 'bg-amber-100 text-amber-700',
                                            'Urgente' => 'bg-red-100 text-red-700'
                                        ];
                                        ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $prioridadeConfig[$tarefa['prioridade']] ?? 'bg-gray-100 text-gray-700'; ?>">
                                            <?php echo $tarefa['prioridade']; ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php
                                        $statusConfig = [
                                            'Pendente' => 'bg-amber-100 text-amber-700',
                                            'Em Andamento' => 'bg-blue-100 text-blue-700',
                                            'Aguardando' => 'bg-gray-100 text-gray-700',
                                            'Concluída' => 'bg-green-100 text-green-700',
                                            'Cancelada' => 'bg-gray-200 text-gray-900'
                                        ];
                                        ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $statusConfig[$tarefa['status']] ?? 'bg-gray-100 text-gray-700'; ?>">
                                            <?php echo $tarefa['status']; ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <a href="tarefa-detalhes.php?id=<?php echo $tarefa['id']; ?>" class="inline-flex items-center gap-1 px-2.5 py-1.5 text-sm font-medium text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors" title="Ver Detalhes">
                                                <i data-lucide="eye" class="w-4 h-4"></i>
                                            </a>
                                            <a href="tarefa-editar.php?id=<?php echo $tarefa['id']; ?>" class="inline-flex items-center gap-1 px-2.5 py-1.5 text-sm font-medium text-amber-700 bg-amber-50 rounded-lg hover:bg-amber-100 transition-colors" title="Editar">
                                                <i data-lucide="edit" class="w-4 h-4"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function aplicarFiltros() {
    const params = new URLSearchParams();

    const status = document.getElementById('statusFilter').value;
    const prioridade = document.getElementById('prioridadeFilter').value;
    const tipo = document.getElementById('tipoFilter').value;
    const clienteId = document.getElementById('clienteFilter').value;
    <?php if (isAdmin()): ?>
    const responsavel = document.getElementById('responsavelFilter').value;
    <?php endif; ?>

    if (status) params.set('status', status);
    if (prioridade) params.set('prioridade', prioridade);
    if (tipo) params.set('tipo', tipo);
    if (clienteId) params.set('cliente_id', clienteId);
    <?php if (isAdmin()): ?>
    if (responsavel) params.set('responsavel', responsavel);
    <?php endif; ?>

    const qs = params.toString();
    window.location.href = 'tarefas.php' + (qs ? ('?' + qs) : '');
}
</script>

<?php include 'includes/footer.php'; ?>

