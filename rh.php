<?php
require_once 'config/config.php';
if (!isLoggedIn()) redirect('index.php');
$db = getDB();
$usuario = getUsuarioLogado();

$abertas = (int)$db->query("SELECT COUNT(*) FROM tarefas WHERE tipo='RH' AND status IN ('Pendente','Em Andamento','Aguardando')" . (!isAdmin()?" AND responsavel_id=".(int)$usuario['id']:'') )->fetchColumn();

$stmt = $db->prepare("SELECT t.*, c.razao_social as cliente_nome FROM tarefas t LEFT JOIN clientes c ON t.cliente_id=c.id
                      WHERE t.tipo='RH'" . (!isAdmin()?" AND t.responsavel_id=?":"") . " ORDER BY t.data_vencimento ASC LIMIT 10");
$stmt->execute(!isAdmin()?[$usuario['id']]:[]);
$rows = $stmt->fetchAll();

$pageTitle='RH e Folha';
$breadcrumb='<a href="dashboard.php">Dashboard</a> / RH e Folha';
include 'includes/header.php';
?>

<div class="p-6 space-y-6">
  <div class="bg-white rounded-lg border border-gray-200 p-6">
    <p class="text-sm text-gray-600 mb-2">Tarefas em aberto (RH)</p>
    <h3 class="text-3xl font-bold text-gray-900"><?php echo $abertas; ?></h3>
  </div>

  <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
      <h5 class="text-lg font-semibold text-gray-900">Próximas tarefas de RH</h5>
      <a href="tarefa-nova.php" class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors">
        <i data-lucide="plus" class="w-4 h-4"></i>
        Nova
      </a>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Título</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Cliente</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Venc.</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Ações</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <?php if(!$rows): ?>
            <tr>
              <td colspan="5" class="px-6 py-12 text-center">
                <i data-lucide="inbox" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                <p class="text-gray-500">Sem tarefas</p>
              </td>
            </tr>
          <?php endif; ?>
          <?php foreach($rows as $t): ?>
            <tr class="hover:bg-gray-50 transition-colors">
              <td class="px-6 py-4 text-sm font-medium text-gray-900"><?php echo htmlspecialchars($t['titulo']); ?></td>
              <td class="px-6 py-4 text-sm text-gray-600"><?php echo htmlspecialchars($t['cliente_nome'] ?? '—'); ?></td>
              <td class="px-6 py-4 text-sm text-gray-600"><?php echo $t['data_vencimento']?formatarData($t['data_vencimento']):'—'; ?></td>
              <td class="px-6 py-4 text-sm text-gray-900"><?php echo htmlspecialchars($t['status']); ?></td>
              <td class="px-6 py-4 text-right">
                <a href="tarefa-detalhes.php?id=<?php echo $t['id']; ?>" class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-primary-700 bg-primary-50 rounded-lg hover:bg-primary-100 transition-colors">
                  <i data-lucide="eye" class="w-4 h-4"></i>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include 'includes/footer.php';

