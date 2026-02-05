<?php
require_once 'config/config.php';
if (!isLoggedIn()) redirect('index.php');
if (!isAdmin()) { setError('Acesso negado.'); redirect('dashboard.php'); }
$db = getDB();
$usuario = getUsuarioLogado();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach (($_POST['cfg'] ?? []) as $id => $valor) {
        $stmt = $db->prepare("UPDATE configuracoes SET valor=? WHERE id=?");
        $stmt->execute([trim((string)$valor), (int)$id]);
    }
    registrarLog('configuracoes', 0, 'update', 'Atualizou configurações');
    setSuccess('Configurações atualizadas.');
    redirect('configuracoes.php');
}

$cfg = $db->query("SELECT * FROM configuracoes ORDER BY chave")->fetchAll();
$pageTitle='Configurações';
$breadcrumb='<a href="dashboard.php">Dashboard</a> / Configurações';
include 'includes/header.php';
?>

<div class="p-6">
  <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
    <form method="post">
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Chave</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Valor</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tipo</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Descrição</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <?php foreach($cfg as $c): ?>
              <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4"><code class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs"><?php echo htmlspecialchars($c['chave']); ?></code></td>
                <td class="px-6 py-4">
                  <input name="cfg[<?php echo $c['id']; ?>]" value="<?php echo htmlspecialchars($c['valor']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </td>
                <td class="px-6 py-4 text-sm text-gray-900"><?php echo htmlspecialchars($c['tipo']); ?></td>
                <td class="px-6 py-4 text-sm text-gray-600"><?php echo htmlspecialchars($c['descricao']); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="px-6 py-4 border-t border-gray-200">
        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
          <i data-lucide="save" class="w-4 h-4"></i>
          Salvar
        </button>
      </div>
    </form>
  </div>
</div>

<?php include 'includes/footer.php';

