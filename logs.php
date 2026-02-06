<?php
require_once 'config/config.php';
if (!isLoggedIn()) redirect('index.php');
if (!isAdmin()) { setError('Acesso negado.'); redirect('dashboard.php'); }
$db = getDB();
$usuario = getUsuarioLogado();

$mod = isset($_GET['modulo']) ? sanitize($_GET['modulo']) : '';
$where=["1=1"]; $params=[];
if ($mod!=='') { $where[]="l.modulo=?"; $params[]=$mod; }

$stmt = $db->prepare("SELECT l.*, u.nome as usuario_nome FROM logs_sistema l LEFT JOIN usuarios u ON l.usuario_id=u.id
                      WHERE ".implode(' AND ',$where)." ORDER BY l.data_criacao DESC LIMIT 200");
$stmt->execute($params);
$rows = $stmt->fetchAll();

$pageTitle='Logs';
$breadcrumb='<a href="dashboard.php">Dashboard</a> / Logs';
include 'includes/header.php';
?>

<div class="p-6 space-y-6">
  <div class="bg-white rounded-lg border border-gray-200 p-6">
    <form method="get" class="flex gap-4">
      <div class="flex-1">
        <input name="modulo" placeholder="Filtrar por módulo (ex: clientes, tarefas)" value="<?php echo htmlspecialchars($mod); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
      </div>
      <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors flex items-center gap-2">
        <i data-lucide="filter" class="w-4 h-4"></i>
        Filtrar
      </button>
    </form>
  </div>

  <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Data</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Usuário</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Módulo</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Ação</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Descrição</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <?php if(!$rows): ?>
            <tr>
              <td colspan="5" class="px-6 py-12 text-center">
                <i data-lucide="inbox" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                <p class="text-gray-500">Sem logs</p>
              </td>
            </tr>
          <?php endif; ?>
          <?php foreach($rows as $r): ?>
            <tr class="hover:bg-gray-50 transition-colors">
              <td class="px-6 py-4 text-sm text-gray-900"><?php echo formatarDataHora($r['data_criacao']); ?></td>
              <td class="px-6 py-4 text-sm text-gray-900"><?php echo htmlspecialchars($r['usuario_nome'] ?? '—'); ?></td>
              <td class="px-6 py-4"><code class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs"><?php echo htmlspecialchars($r['modulo']); ?></code></td>
              <td class="px-6 py-4 text-sm text-gray-900"><?php echo htmlspecialchars($r['acao']); ?></td>
              <td class="px-6 py-4 text-sm text-gray-600"><?php echo htmlspecialchars($r['descricao'] ?? ''); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include 'includes/footer.php';

