<?php
require_once 'config/config.php';
if (!isLoggedIn()) redirect('index.php');
$db = getDB();
$usuario = getUsuarioLogado();

$clientes = (int)$db->query("SELECT COUNT(*) FROM clientes WHERE ativo=1")->fetchColumn();
$tPend = (int)$db->query("SELECT COUNT(*) FROM tarefas WHERE status='Pendente'")->fetchColumn();
$obAtras = (int)$db->query("SELECT COUNT(*) FROM cliente_obrigacoes WHERE status='Atrasada'")->fetchColumn();
$mes=(int)date('n'); $ano=(int)date('Y');
$stmt=$db->prepare("SELECT SUM(valor) FROM financeiro WHERE tipo='Receita' AND mes_referencia=? AND ano_referencia=? AND status IN ('Pendente','Pago','Atrasado')");
$stmt->execute([$mes,$ano]);
$receitaMes = (float)($stmt->fetchColumn() ?: 0);

$pageTitle='Relatórios';
$breadcrumb='<a href="dashboard.php">Dashboard</a> / Relatórios';
include 'includes/header.php';
?>

<div class="p-6 space-y-6">
  <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
    <div class="bg-white rounded-lg border border-gray-200 p-6">
      <p class="text-sm text-gray-600 mb-2">Clientes ativos</p>
      <h3 class="text-3xl font-bold text-gray-900"><?php echo $clientes; ?></h3>
    </div>
    <div class="bg-white rounded-lg border border-gray-200 p-6">
      <p class="text-sm text-gray-600 mb-2">Tarefas pendentes</p>
      <h3 class="text-3xl font-bold text-gray-900"><?php echo $tPend; ?></h3>
    </div>
    <div class="bg-white rounded-lg border border-gray-200 p-6">
      <p class="text-sm text-gray-600 mb-2">Obrigações atrasadas</p>
      <h3 class="text-3xl font-bold text-red-600"><?php echo $obAtras; ?></h3>
    </div>
    <div class="bg-white rounded-lg border border-gray-200 p-6">
      <p class="text-sm text-gray-600 mb-2">Receita (mês)</p>
      <h3 class="text-3xl font-bold text-gray-900"><?php echo formatarMoeda($receitaMes); ?></h3>
    </div>
  </div>

  <div class="bg-white rounded-lg border border-gray-200 p-6">
    <h5 class="text-lg font-semibold text-gray-900 mb-4">Atalhos</h5>
    <div class="flex flex-wrap gap-2">
      <a href="clientes.php" class="inline-flex items-center gap-2 px-4 py-2 border border-primary-600 text-primary-600 rounded-lg hover:bg-primary-50 transition-colors">
        <i data-lucide="building-2" class="w-4 h-4"></i>
        Clientes
      </a>
      <a href="tarefas.php" class="inline-flex items-center gap-2 px-4 py-2 border border-primary-600 text-primary-600 rounded-lg hover:bg-primary-50 transition-colors">
        <i data-lucide="list-checks" class="w-4 h-4"></i>
        Tarefas
      </a>
      <a href="obrigacoes.php" class="inline-flex items-center gap-2 px-4 py-2 border border-primary-600 text-primary-600 rounded-lg hover:bg-primary-50 transition-colors">
        <i data-lucide="calendar-check" class="w-4 h-4"></i>
        Obrigações
      </a>
      <a href="financeiro.php" class="inline-flex items-center gap-2 px-4 py-2 border border-primary-600 text-primary-600 rounded-lg hover:bg-primary-50 transition-colors">
        <i data-lucide="dollar-sign" class="w-4 h-4"></i>
        Financeiro
      </a>
      <a href="documentos.php" class="inline-flex items-center gap-2 px-4 py-2 border border-primary-600 text-primary-600 rounded-lg hover:bg-primary-50 transition-colors">
        <i data-lucide="folder" class="w-4 h-4"></i>
        Documentos
      </a>
    </div>
    <p class="text-sm text-gray-500 mt-4">Relatórios avançados (PDF/Excel) podem ser adicionados depois.</p>
  </div>
</div>

<?php include 'includes/footer.php';

