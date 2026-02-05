<?php
require_once 'config/config.php';
if (!isLoggedIn()) redirect('index.php');
$db = getDB();
$usuario = getUsuarioLogado();

$status = isset($_GET['status']) ? sanitize($_GET['status']) : '';
$clienteId = !empty($_GET['cliente_id']) ? (int)$_GET['cliente_id'] : 0;
$mes = !empty($_GET['mes']) ? (int)$_GET['mes'] : 0;
$ano = !empty($_GET['ano']) ? (int)$_GET['ano'] : 0;

$where=["1=1"]; $params=[];
if ($status!=='') { $where[]="f.status=?"; $params[]=$status; }
if ($clienteId) { $where[]="f.cliente_id=?"; $params[]=$clienteId; }
if ($mes) { $where[]="f.mes_referencia=?"; $params[]=$mes; }
if ($ano) { $where[]="f.ano_referencia=?"; $params[]=$ano; }

$clientes = $db->query("SELECT id, razao_social FROM clientes WHERE ativo=1 ORDER BY razao_social")->fetchAll();
$stmt = $db->prepare("SELECT f.*, c.razao_social as cliente_nome
                      FROM financeiro f
                      INNER JOIN clientes c ON f.cliente_id=c.id
                      WHERE ".implode(' AND ',$where)."
                      ORDER BY f.data_vencimento DESC LIMIT 300");
$stmt->execute($params);
$rows = $stmt->fetchAll();

$pageTitle='Financeiro';
$breadcrumb='<a href="dashboard.php">Dashboard</a> / Financeiro';
include 'includes/header.php';
?>

<div class="p-6 space-y-6">
  <div class="bg-white rounded-lg border border-gray-200 p-6">
    <form class="grid grid-cols-1 md:grid-cols-4 gap-4" method="get">
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
          <?php foreach(['Pendente','Pago','Atrasado','Cancelado'] as $s): ?>
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
      <div class="md:col-span-4">
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
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Descrição</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Valor</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Venc.</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <?php if(!$rows): ?>
            <tr>
              <td colspan="5" class="px-6 py-12 text-center">
                <i data-lucide="inbox" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                <p class="text-gray-500">Nenhum lançamento</p>
              </td>
            </tr>
          <?php endif; ?>
          <?php foreach($rows as $r): ?>
            <tr class="hover:bg-gray-50 transition-colors">
              <td class="px-6 py-4 text-sm text-gray-900"><?php echo htmlspecialchars($r['cliente_nome']); ?></td>
              <td class="px-6 py-4">
                <div class="font-medium text-gray-900"><?php echo htmlspecialchars($r['descricao']); ?></div>
                <div class="text-xs text-gray-500"><?php echo $r['mes_referencia'].'/'.$r['ano_referencia']; ?></div>
              </td>
              <td class="px-6 py-4 text-sm font-semibold text-gray-900"><?php echo formatarMoeda($r['valor']); ?></td>
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

