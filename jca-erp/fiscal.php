<?php
require_once 'config/config.php';
if (!isLoggedIn()) redirect('index.php');
$db = getDB();
$usuario = getUsuarioLogado();

$filterUser = !isAdmin() ? " AND co.responsavel_id = " . (int)$usuario['id'] : "";
$abertas = (int)$db->query("SELECT COUNT(*) FROM cliente_obrigacoes co WHERE co.status IN ('Pendente','Em Andamento','Atrasada'){$filterUser}")->fetchColumn();
$atras = (int)$db->query("SELECT COUNT(*) FROM cliente_obrigacoes co WHERE co.status='Atrasada'{$filterUser}")->fetchColumn();

$stmt = $db->prepare("SELECT co.*, c.razao_social as cliente_nome, o.nome as obrigacao_nome
                      FROM cliente_obrigacoes co
                      INNER JOIN clientes c ON co.cliente_id=c.id
                      INNER JOIN obrigacoes_fiscais o ON co.obrigacao_id=o.id
                      WHERE co.status IN ('Pendente','Em Andamento','Atrasada')" . (!isAdmin()?" AND co.responsavel_id=?":"") . "
                      ORDER BY co.data_vencimento ASC LIMIT 10");
$stmt->execute(!isAdmin()?[$usuario['id']]:[]);
$prox = $stmt->fetchAll();

$pageTitle='Módulo Fiscal';
$breadcrumb='<a href="dashboard.php">Dashboard</a> / Fiscal';
include 'includes/header.php';
?>

<div class="p-6 space-y-6">
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="bg-white rounded-lg border border-gray-200 p-6">
      <p class="text-sm text-gray-600 mb-2">Obrigações em aberto</p>
      <h3 class="text-3xl font-bold text-gray-900"><?php echo $abertas; ?></h3>
    </div>
    <div class="bg-white rounded-lg border border-gray-200 p-6">
      <p class="text-sm text-gray-600 mb-2">Atrasadas</p>
      <h3 class="text-3xl font-bold text-red-600"><?php echo $atras; ?></h3>
    </div>
  </div>

  <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
      <h5 class="text-lg font-semibold text-gray-900">Próximas obrigações</h5>
      <a href="obrigacoes.php" class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-primary-600 bg-primary-50 rounded-lg hover:bg-primary-100 transition-colors">
        <i data-lucide="list" class="w-4 h-4"></i>
        Ver todas
      </a>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Cliente</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Obrigação</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Venc.</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <?php if(!$prox): ?>
            <tr>
              <td colspan="4" class="px-6 py-12 text-center">
                <i data-lucide="inbox" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                <p class="text-gray-500">Sem obrigações</p>
              </td>
            </tr>
          <?php endif; ?>
          <?php foreach($prox as $r): ?>
            <tr class="hover:bg-gray-50 transition-colors">
              <td class="px-6 py-4 text-sm text-gray-900"><?php echo htmlspecialchars($r['cliente_nome']); ?></td>
              <td class="px-6 py-4 text-sm text-gray-900"><?php echo htmlspecialchars($r['obrigacao_nome']); ?></td>
              <td class="px-6 py-4 text-sm text-gray-600"><?php echo formatarData($r['data_vencimento']); ?></td>
              <td class="px-6 py-4 text-sm text-gray-900"><?php echo htmlspecialchars($r['status']); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include 'includes/footer.php';

