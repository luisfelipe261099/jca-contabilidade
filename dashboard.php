<?php
/**
 * JCA ERP - Dashboard Principal
 * 
 * @package JCA_ERP
 * @version 1.0.0
 */

require_once 'config/config.php';

// Verifica se está logado
if (!isLoggedIn()) {
    redirect('index.php');
}

$usuario = getUsuarioLogado();
$db = getDB();

// Estatísticas
$stats = [];

// Total de clientes ativos
$stmt = $db->query("SELECT COUNT(*) as total FROM clientes WHERE ativo = 1");
$stats['total_clientes'] = $stmt->fetch()['total'];

// Clientes com contrato ativo
$stmt = $db->query("SELECT COUNT(*) as total FROM clientes WHERE status_contrato = 'Ativo'");
$stats['clientes_ativos'] = $stmt->fetch()['total'];

// Obrigações pendentes este mês
$stmt = $db->query("SELECT COUNT(*) as total FROM cliente_obrigacoes 
                    WHERE status IN ('Pendente', 'Em Andamento') 
                    AND MONTH(data_vencimento) = MONTH(CURRENT_DATE())
                    AND YEAR(data_vencimento) = YEAR(CURRENT_DATE())");
$stats['obrigacoes_pendentes'] = $stmt->fetch()['total'];

// Tarefas pendentes
$stmt = $db->query("SELECT COUNT(*) as total FROM tarefas WHERE status IN ('Pendente', 'Em Andamento')");
$stats['tarefas_pendentes'] = $stmt->fetch()['total'];

// Alertas não lidos
$stmt = $db->prepare("SELECT COUNT(*) as total FROM alertas WHERE usuario_id = ? AND lido = 0");
$stmt->execute([$usuario['id']]);
$stats['alertas_nao_lidos'] = $stmt->fetch()['total'];

// Obrigações próximas do vencimento (próximos 7 dias)
$stmt = $db->query("SELECT co.*, c.razao_social, c.nome_fantasia, o.nome as obrigacao_nome
                    FROM cliente_obrigacoes co
                    JOIN clientes c ON co.cliente_id = c.id
                    JOIN obrigacoes_fiscais o ON co.obrigacao_id = o.id
                    WHERE co.status IN ('Pendente', 'Em Andamento')
                    AND co.data_vencimento BETWEEN CURRENT_DATE() AND DATE_ADD(CURRENT_DATE(), INTERVAL 7 DAY)
                    ORDER BY co.data_vencimento ASC
                    LIMIT 10");
$obrigacoes_proximas = $stmt->fetchAll();

// Tarefas recentes
$stmt = $db->prepare("SELECT t.*, c.razao_social, c.nome_fantasia, u.nome as responsavel_nome
                      FROM tarefas t
                      LEFT JOIN clientes c ON t.cliente_id = c.id
                      LEFT JOIN usuarios u ON t.responsavel_id = u.id
                      WHERE t.status IN ('Pendente', 'Em Andamento')
                      ORDER BY t.data_vencimento ASC
                      LIMIT 10");
$stmt->execute();
$tarefas_recentes = $stmt->fetchAll();

// Clientes recentes
$stmt = $db->query("SELECT * FROM clientes ORDER BY data_criacao DESC LIMIT 5");
$clientes_recentes = $stmt->fetchAll();

$pageTitle = 'Dashboard';
include 'includes/header.php';
?>

<div class="p-6 space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="building-2" class="w-6 h-6 text-primary-600"></i>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-900"><?php echo $stats['total_clientes']; ?></p>
                    <p class="text-sm text-gray-600">Total de Clientes</p>
                    <p class="text-xs text-green-600 flex items-center gap-1 mt-1">
                        <i data-lucide="check-circle" class="w-3 h-3"></i>
                        <?php echo $stats['clientes_ativos']; ?> ativos
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-accent-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="file-text" class="w-6 h-6 text-accent-600"></i>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-900"><?php echo $stats['obrigacoes_pendentes']; ?></p>
                    <p class="text-sm text-gray-600">Obrigações Este Mês</p>
                    <p class="text-xs text-amber-600 flex items-center gap-1 mt-1">
                        <i data-lucide="clock" class="w-3 h-3"></i>
                        Pendentes
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="list-checks" class="w-6 h-6 text-green-600"></i>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-900"><?php echo $stats['tarefas_pendentes']; ?></p>
                    <p class="text-sm text-gray-600">Tarefas Abertas</p>
                    <p class="text-xs text-blue-600 flex items-center gap-1 mt-1">
                        <i data-lucide="loader" class="w-3 h-3"></i>
                        Em andamento
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="bell" class="w-6 h-6 text-red-600"></i>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-900"><?php echo $stats['alertas_nao_lidos']; ?></p>
                    <p class="text-sm text-gray-600">Alertas Novos</p>
                    <p class="text-xs text-red-600 flex items-center gap-1 mt-1">
                        <i data-lucide="alert-circle" class="w-3 h-3"></i>
                        Não lidos
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Obrigações Próximas -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i data-lucide="calendar" class="w-5 h-5 text-amber-600"></i>
                    <h5 class="text-lg font-semibold text-gray-900">Obrigações Próximas</h5>
                </div>
                <a href="obrigacoes.php" class="text-sm font-medium text-primary-600 hover:text-primary-700">Ver Todas</a>
            </div>
            <div class="p-6">
                <?php if (empty($obrigacoes_proximas)): ?>
                    <div class="text-center py-8">
                        <i data-lucide="check-circle" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                        <p class="text-gray-500">Nenhuma obrigação próxima do vencimento</p>
                    </div>
                <?php else: ?>
                    <div class="space-y-3">
                        <?php foreach ($obrigacoes_proximas as $obrigacao): ?>
                            <div class="flex items-start justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="flex-1">
                                    <h6 class="font-semibold text-gray-900 mb-1"><?php echo htmlspecialchars($obrigacao['obrigacao_nome']); ?></h6>
                                    <p class="text-sm text-gray-600">
                                        <?php echo htmlspecialchars($obrigacao['nome_fantasia'] ?: $obrigacao['razao_social']); ?>
                                    </p>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700 ml-3">
                                    <?php echo formatarData($obrigacao['data_vencimento']); ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Tarefas Recentes -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i data-lucide="list-checks" class="w-5 h-5 text-blue-600"></i>
                    <h5 class="text-lg font-semibold text-gray-900">Tarefas Recentes</h5>
                </div>
                <a href="tarefas.php" class="text-sm font-medium text-primary-600 hover:text-primary-700">Ver Todas</a>
            </div>
            <div class="p-6">
                <?php if (empty($tarefas_recentes)): ?>
                    <div class="text-center py-8">
                        <i data-lucide="clipboard-check" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                        <p class="text-gray-500">Nenhuma tarefa pendente</p>
                    </div>
                <?php else: ?>
                    <div class="space-y-3">
                        <?php foreach ($tarefas_recentes as $tarefa): ?>
                            <div class="flex items-start justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="flex-1">
                                    <h6 class="font-semibold text-gray-900 mb-1"><?php echo htmlspecialchars($tarefa['titulo']); ?></h6>
                                    <?php if ($tarefa['razao_social']): ?>
                                        <p class="text-sm text-gray-600 flex items-center gap-1">
                                            <i data-lucide="building-2" class="w-3 h-3"></i>
                                            <?php echo htmlspecialchars($tarefa['nome_fantasia'] ?: $tarefa['razao_social']); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                                <?php
                                $prioridadeConfig = [
                                    'Urgente' => 'bg-red-100 text-red-700',
                                    'Alta' => 'bg-amber-100 text-amber-700',
                                    'Média' => 'bg-blue-100 text-blue-700',
                                    'Baixa' => 'bg-gray-100 text-gray-700'
                                ];
                                ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $prioridadeConfig[$tarefa['prioridade']] ?? 'bg-gray-100 text-gray-700'; ?> ml-3">
                                    <?php echo $tarefa['prioridade']; ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

