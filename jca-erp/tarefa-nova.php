<?php
require_once 'config/config.php';
if (!isLoggedIn()) redirect('index.php');
$db = getDB();
$usuario = getUsuarioLogado();

$clientes = $db->query("SELECT id, razao_social FROM clientes WHERE ativo=1 ORDER BY razao_social")->fetchAll();
$usuarios = $db->query("SELECT id, nome FROM usuarios WHERE tipo_usuario IN ('admin','funcionario') AND ativo=1 ORDER BY nome")->fetchAll();
$clienteIdSelecionado = !empty($_GET['cliente_id']) ? (int)$_GET['cliente_id'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $clienteId = !empty($_POST['cliente_id']) ? (int)$_POST['cliente_id'] : null;
    $tipo = sanitize($_POST['tipo'] ?? '');
    $prioridade = sanitize($_POST['prioridade'] ?? 'Média');
    $status = sanitize($_POST['status'] ?? 'Pendente');
    $responsavelId = !empty($_POST['responsavel_id']) ? (int)$_POST['responsavel_id'] : null;
    $dataVenc = !empty($_POST['data_vencimento']) ? $_POST['data_vencimento'] : null;
    $tags = trim($_POST['tags'] ?? '');

    if ($titulo === '' || $tipo === '') {
        setError('Título e Tipo são obrigatórios.');
    } else {
        $stmt = $db->prepare("INSERT INTO tarefas (titulo,descricao,cliente_id,tipo,prioridade,status,criado_por_id,responsavel_id,data_vencimento,tags)
                              VALUES (?,?,?,?,?,?,?,?,?,?)");
        $stmt->execute([$titulo,$descricao,$clienteId,$tipo,$prioridade,$status,$usuario['id'],$responsavelId,$dataVenc,$tags]);
        registrarLog('tarefas', $db->lastInsertId(), 'create', 'Criou tarefa');
        setSuccess('Tarefa criada com sucesso.');
        redirect('tarefas.php');
    }
}

$pageTitle = 'Nova Tarefa';
$breadcrumb = '<a href="dashboard.php">Dashboard</a> / <a href="tarefas.php">Tarefas</a> / Nova';
include 'includes/header.php';
?>

<div class="p-6">
  <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
      <h5 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
        <i data-lucide="plus-circle" class="w-5 h-5"></i>
        Nova Tarefa
      </h5>
    </div>
    <div class="p-6">
      <form method="post" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">Título <span class="text-red-600">*</span></label>
            <input name="titulo" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Cliente</label>
            <select name="cliente_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
              <option value="">(Sem cliente)</option>
              <?php foreach($clientes as $c): ?>
                <option value="<?php echo $c['id']; ?>" <?php echo ($clienteIdSelecionado == (int)$c['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($c['razao_social']); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Tipo <span class="text-red-600">*</span></label>
            <select name="tipo" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
              <?php foreach(['Contábil','Fiscal','RH','Consultoria','Administrativo','Outros'] as $t): ?>
                <option value="<?php echo $t; ?>"><?php echo $t; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Prioridade</label>
            <select name="prioridade" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
              <?php foreach(['Baixa','Média','Alta','Urgente'] as $p): ?>
                <option><?php echo $p; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
              <?php foreach(['Pendente','Em Andamento','Aguardando','Concluída','Cancelada'] as $s): ?>
                <option><?php echo $s; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Vencimento</label>
            <input type="date" name="data_vencimento" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Responsável</label>
            <select name="responsavel_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
              <option value="">(Sem responsável)</option>
              <?php foreach($usuarios as $u): ?>
                <option value="<?php echo $u['id']; ?>"><?php echo htmlspecialchars($u['nome']); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
            <input name="tags" placeholder="demo, fiscal, urgencia" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Descrição</label>
          <textarea name="descricao" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"></textarea>
        </div>

        <div class="flex gap-2 pt-4 border-t border-gray-200">
          <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
            <i data-lucide="save" class="w-4 h-4"></i>
            Salvar
          </button>
          <a href="tarefas.php" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
            Cancelar
          </a>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include 'includes/footer.php';

