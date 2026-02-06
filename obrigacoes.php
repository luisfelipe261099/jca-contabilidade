<?php
require_once 'config/config.php';
if (!isLoggedIn()) redirect('index.php');
$db = getDB();
$usuario = getUsuarioLogado();

if (isset($_GET['concluir']) && $_GET['concluir']) {
    $id = (int)$_GET['concluir'];
    $stmt = $db->prepare("UPDATE cliente_obrigacoes SET status='Concluída', data_conclusao=NOW() WHERE id=?" . (!isAdmin() ? " AND responsavel_id=?" : ""));
    $stmt->execute(!isAdmin() ? [$id,$usuario['id']] : [$id]);
    setSuccess('Obrigação atualizada.');
    redirect('obrigacoes.php');
}

$status = isset($_GET['status']) ? sanitize($_GET['status']) : '';
$clienteId = !empty($_GET['cliente_id']) ? (int)$_GET['cliente_id'] : 0;
$mes = !empty($_GET['mes']) ? (int)$_GET['mes'] : 0;
$ano = !empty($_GET['ano']) ? (int)$_GET['ano'] : 0;

$where=["1=1"]; $params=[];
if (!isAdmin()) { $where[]="co.responsavel_id=?"; $params[]=$usuario['id']; }
if ($status!=='') { $where[]="co.status=?"; $params[]=$status; }
if ($clienteId) { $where[]="co.cliente_id=?"; $params[]=$clienteId; }
if ($mes) { $where[]="co.mes_referencia=?"; $params[]=$mes; }
if ($ano) { $where[]="co.ano_referencia=?"; $params[]=$ano; }

$clientes = $db->query("SELECT id, razao_social FROM clientes WHERE ativo=1 ORDER BY razao_social")->fetchAll();
$stmt = $db->prepare("SELECT co.*, c.razao_social as cliente_nome, o.nome as obrigacao_nome, u.nome as responsavel
                      FROM cliente_obrigacoes co
                      INNER JOIN clientes c ON co.cliente_id=c.id
                      INNER JOIN obrigacoes_fiscais o ON co.obrigacao_id=o.id
                      LEFT JOIN usuarios u ON co.responsavel_id=u.id
                      WHERE ".implode(' AND ',$where)."
                      ORDER BY co.data_vencimento ASC LIMIT 300");
$stmt->execute($params);
$rows = $stmt->fetchAll();

$pageTitle='Obrigações';
$breadcrumb='<a href="dashboard.php">Dashboard</a> / Obrigações';
include 'includes/header.php';
?>

<div class="p-6 space-y-6">
  <div class="bg-white rounded-lg border border-gray-200 p-6">
    <form class="grid grid-cols-1 md:grid-cols-5 gap-4" method="get">
      <div class="md:col-span-2">
        <select name="cliente_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
          <option value="">Todos os clientes</option>
          <?php foreach($clientes as $c): ?>
            <option value="<?php echo $c['id']; ?>" <?php echo ($clienteId==$c['id']?'selected':''); ?>><?php echo htmlspecialchars($c['razao_social']); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
          <option value="">Todos os status</option>
          <?php foreach(['Pendente','Em Andamento','Concluída','Atrasada'] as $s): ?>
            <option <?php echo ($status===$s?'selected':''); ?>><?php echo $s; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <input name="mes" placeholder="Mês" value="<?php echo $mes?:''; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
      </div>
      <div>
        <input name="ano" placeholder="Ano" value="<?php echo $ano?:''; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
      </div>
      <div class="md:col-span-5">
        <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors flex items-center gap-2">
          <i data-lucide="filter" class="w-4 h-4"></i>
          Filtrar
        </button>
      </div>
    </form>
  </div>

  <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Cliente</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Obrigação</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Venc.</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Resp.</th>
            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Ações</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <?php if(!$rows): ?>
            <tr>
              <td colspan="6" class="px-6 py-12 text-center">
                <i data-lucide="inbox" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                <p class="text-gray-500">Nenhuma obrigação</p>
              </td>
            </tr>
          <?php endif; ?>
          <?php foreach($rows as $r): ?>
            <tr class="hover:bg-gray-50 transition-colors">
              <td class="px-6 py-4 text-sm text-gray-900"><?php echo htmlspecialchars($r['cliente_nome']); ?></td>
              <td class="px-6 py-4">
                <div class="font-medium text-gray-900"><?php echo htmlspecialchars($r['obrigacao_nome']); ?></div>
                <div class="text-xs text-gray-500"><?php echo $r['mes_referencia'].'/'.$r['ano_referencia']; ?></div>
              </td>
              <td class="px-6 py-4 text-sm text-gray-600"><?php echo formatarData($r['data_vencimento']); ?></td>
              <td class="px-6 py-4 text-sm text-gray-900"><?php echo htmlspecialchars($r['status']); ?></td>
              <td class="px-6 py-4 text-sm text-gray-600"><?php echo htmlspecialchars($r['responsavel'] ?? '—'); ?></td>
              <td class="px-6 py-4 text-right">
                <?php if($r['status']!=='Concluída'): ?>
                  <a href="obrigacoes.php?concluir=<?php echo $r['id']; ?>" class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-green-700 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                    <i data-lucide="check" class="w-4 h-4"></i>
                  </a>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include 'includes/footer.php';

