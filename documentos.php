<?php
require_once 'config/config.php';
if (!isLoggedIn()) redirect('index.php');
$db = getDB();
$usuario = getUsuarioLogado();

$clienteId = !empty($_GET['cliente_id']) ? (int)$_GET['cliente_id'] : 0;
$tipo = isset($_GET['tipo']) ? sanitize($_GET['tipo']) : '';

$where = ["1=1"]; $params = [];
if ($clienteId) { $where[] = "d.cliente_id = ?"; $params[] = $clienteId; }
if ($tipo !== '') { $where[] = "d.tipo_documento = ?"; $params[] = $tipo; }

$clientes = $db->query("SELECT id, razao_social FROM clientes WHERE ativo=1 ORDER BY razao_social")->fetchAll();
$stmt = $db->prepare("SELECT d.*, c.razao_social as cliente_nome, u.nome as enviado_por
                      FROM documentos d
                      LEFT JOIN clientes c ON d.cliente_id=c.id
                      LEFT JOIN usuarios u ON d.enviado_por_id=u.id
                      WHERE ".implode(' AND ',$where)."
                      ORDER BY d.data_upload DESC LIMIT 200");
$stmt->execute($params);
$docs = $stmt->fetchAll();

$pageTitle = 'Documentos';
$breadcrumb = '<a href="dashboard.php">Dashboard</a> / Documentos';
include 'includes/header.php';
?>

<div class="p-6 space-y-6">
  <!-- Filtros -->
  <div class="bg-white rounded-lg border border-gray-200 p-6">
    <form class="grid grid-cols-1 md:grid-cols-3 gap-4" method="get">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Cliente</label>
        <select name="cliente_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
          <option value="">Todos os clientes</option>
          <?php foreach($clientes as $c): ?>
            <option value="<?php echo $c['id']; ?>" <?php echo ($clienteId==$c['id']?'selected':''); ?>><?php echo htmlspecialchars($c['razao_social']); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Tipo</label>
        <select name="tipo" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
          <option value="">Todos os tipos</option>
          <?php foreach(['Fiscal','Contábil','RH','Contrato','Outros'] as $t): ?>
            <option value="<?php echo $t; ?>" <?php echo ($tipo===$t?'selected':''); ?>><?php echo $t; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="flex items-end">
        <button type="submit" class="w-full px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors flex items-center justify-center gap-2">
          <i data-lucide="filter" class="w-4 h-4"></i>
          Filtrar
        </button>
      </div>
    </form>
  </div>

  <!-- Header com botão Upload -->
  <div class="flex items-center justify-between">
    <h5 class="text-lg font-semibold text-gray-900">Últimos documentos</h5>
    <a href="documento-upload.php<?php echo $clienteId?('?cliente_id='.$clienteId):''; ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
      <i data-lucide="upload" class="w-4 h-4"></i>
      Upload
    </a>
  </div>

  <!-- Tabela -->
  <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Arquivo</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Cliente</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tipo</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Enviado por</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Data</th>
            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Ações</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <?php if(!$docs): ?>
            <tr>
              <td colspan="6" class="px-6 py-12 text-center">
                <i data-lucide="inbox" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                <p class="text-gray-500">Nenhum documento</p>
              </td>
            </tr>
          <?php endif; ?>
          <?php foreach($docs as $d): ?>
            <tr class="hover:bg-gray-50 transition-colors">
              <td class="px-6 py-4">
                <div class="flex items-center gap-2">
                  <i data-lucide="file-text" class="w-5 h-5 text-gray-400"></i>
                  <span class="font-medium text-gray-900"><?php echo htmlspecialchars($d['nome_arquivo']); ?></span>
                </div>
              </td>
              <td class="px-6 py-4 text-sm text-gray-600"><?php echo htmlspecialchars($d['cliente_nome'] ?? '—'); ?></td>
              <td class="px-6 py-4">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                  <?php echo htmlspecialchars($d['tipo_documento']); ?>
                </span>
              </td>
              <td class="px-6 py-4 text-sm text-gray-600"><?php echo htmlspecialchars($d['enviado_por'] ?? '—'); ?></td>
              <td class="px-6 py-4 text-sm text-gray-600"><?php echo formatarDataHora($d['data_upload']); ?></td>
              <td class="px-6 py-4 text-right">
                <a href="documento-download.php?id=<?php echo $d['id']; ?>" class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-primary-700 bg-primary-50 rounded-lg hover:bg-primary-100 transition-colors">
                  <i data-lucide="download" class="w-4 h-4"></i>
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

