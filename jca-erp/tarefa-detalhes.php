<?php
require_once 'config/config.php';
if (!isLoggedIn()) redirect('index.php');
$db = getDB();
$usuario = getUsuarioLogado();
$id = (int)($_GET['id'] ?? 0);

$stmt = $db->prepare("SELECT t.*, c.razao_social as cliente_nome, u.nome as responsavel_nome, uc.nome as criador_nome
                      FROM tarefas t
                      LEFT JOIN clientes c ON t.cliente_id = c.id
                      LEFT JOIN usuarios u ON t.responsavel_id = u.id
                      LEFT JOIN usuarios uc ON t.criado_por_id = uc.id
                      WHERE t.id=?");
$stmt->execute([$id]);
$tarefa = $stmt->fetch();
if (!$tarefa) { setError('Tarefa não encontrada.'); redirect('tarefas.php'); }

$pageTitle = 'Detalhes da Tarefa';
$breadcrumb = '<a href="dashboard.php">Dashboard</a> / <a href="tarefas.php">Tarefas</a> / Detalhes';
include 'includes/header.php';
?>

<div class="p-6">
  <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
      <div class="flex items-start justify-between">
        <div>
          <h4 class="text-2xl font-bold text-gray-900 mb-2"><?php echo htmlspecialchars($tarefa['titulo']); ?></h4>
          <div class="flex items-center gap-2 text-sm text-gray-600">
            <i data-lucide="building-2" class="w-4 h-4"></i>
            <span>Cliente: <?php echo htmlspecialchars($tarefa['cliente_nome'] ?? '—'); ?></span>
          </div>
        </div>
        <div class="flex gap-2">
          <a href="tarefa-editar.php?id=<?php echo $id; ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors">
            <i data-lucide="edit" class="w-4 h-4"></i>
            Editar
          </a>
          <a href="tarefas.php" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
            Voltar
          </a>
        </div>
      </div>
    </div>
    <div class="p-6">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
          <div class="text-gray-900"><?php echo htmlspecialchars($tarefa['status']); ?></div>
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Prioridade</label>
          <div class="text-gray-900"><?php echo htmlspecialchars($tarefa['prioridade']); ?></div>
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Tipo</label>
          <div class="text-gray-900"><?php echo htmlspecialchars($tarefa['tipo']); ?></div>
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Vencimento</label>
          <div class="text-gray-900"><?php echo $tarefa['data_vencimento'] ? formatarData($tarefa['data_vencimento']) : '—'; ?></div>
        </div>
        <div class="md:col-span-2">
          <label class="block text-sm font-semibold text-gray-700 mb-1">Responsável</label>
          <div class="text-gray-900"><?php echo htmlspecialchars($tarefa['responsavel_nome'] ?? '—'); ?></div>
        </div>
        <div class="md:col-span-2">
          <label class="block text-sm font-semibold text-gray-700 mb-1">Criada por</label>
          <div class="text-gray-900"><?php echo htmlspecialchars($tarefa['criador_nome'] ?? '—'); ?></div>
        </div>
        <div class="md:col-span-4">
          <label class="block text-sm font-semibold text-gray-700 mb-2">Descrição</label>
          <div class="text-gray-900 whitespace-pre-wrap bg-gray-50 p-4 rounded-lg border border-gray-200"><?php echo htmlspecialchars($tarefa['descricao'] ?? ''); ?></div>
        </div>
        <div class="md:col-span-4">
          <label class="block text-sm font-semibold text-gray-700 mb-1">Tags</label>
          <div class="text-gray-900"><?php echo htmlspecialchars($tarefa['tags'] ?? ''); ?></div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include 'includes/footer.php';

