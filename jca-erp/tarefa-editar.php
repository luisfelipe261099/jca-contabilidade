<?php
require_once 'config/config.php';
if (!isLoggedIn()) redirect('index.php');
$db = getDB();
$usuario = getUsuarioLogado();
$id = (int)($_GET['id'] ?? 0);

$stmt = $db->prepare("SELECT * FROM tarefas WHERE id=?");
$stmt->execute([$id]);
$tarefa = $stmt->fetch();
if (!$tarefa) { setError('Tarefa não encontrada.'); redirect('tarefas.php'); }

$canEdit = isAdmin() || $tarefa['criado_por_id'] == $usuario['id'] || ($tarefa['responsavel_id'] && $tarefa['responsavel_id'] == $usuario['id']);
if (!$canEdit) { setError('Sem permissão.'); redirect('tarefas.php'); }

$clientes = $db->query("SELECT id, razao_social FROM clientes WHERE ativo=1 ORDER BY razao_social")->fetchAll();
$usuarios = $db->query("SELECT id, nome FROM usuarios WHERE tipo_usuario IN ('admin','funcionario') AND ativo=1 ORDER BY nome")->fetchAll();

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
        $stmt = $db->prepare("UPDATE tarefas SET titulo=?,descricao=?,cliente_id=?,tipo=?,prioridade=?,status=?,responsavel_id=?,data_vencimento=?,tags=? WHERE id=?");
        $stmt->execute([$titulo,$descricao,$clienteId,$tipo,$prioridade,$status,$responsavelId,$dataVenc,$tags,$id]);
        registrarLog('tarefas', $id, 'update', 'Editou tarefa');
        setSuccess('Tarefa atualizada.');
        redirect('tarefa-detalhes.php?id='.$id);
    }
}

$pageTitle = 'Editar Tarefa';
$breadcrumb = '<a href="dashboard.php">Dashboard</a> / <a href="tarefas.php">Tarefas</a> / Editar';
include 'includes/header.php';
?>

<div class="content-area">
  <div class="card"><div class="card-body">
    <form method="post">
      <div class="row g-3">
        <div class="col-md-8"><label class="form-label">Título *</label><input name="titulo" class="form-control" value="<?php echo htmlspecialchars($tarefa['titulo']); ?>" required></div>
        <div class="col-md-4"><label class="form-label">Cliente</label><select name="cliente_id" class="form-select"><option value="">(Sem cliente)</option><?php foreach($clientes as $c): ?><option value="<?php echo $c['id']; ?>" <?php echo ($tarefa['cliente_id']==$c['id']?'selected':''); ?>><?php echo htmlspecialchars($c['razao_social']); ?></option><?php endforeach; ?></select></div>
        <div class="col-md-3"><label class="form-label">Tipo *</label><select name="tipo" class="form-select" required><?php foreach(['Contábil','Fiscal','RH','Consultoria','Administrativo','Outros'] as $t): ?><option <?php echo ($tarefa['tipo']===$t?'selected':''); ?>><?php echo $t; ?></option><?php endforeach; ?></select></div>
        <div class="col-md-3"><label class="form-label">Prioridade</label><select name="prioridade" class="form-select"><?php foreach(['Baixa','Média','Alta','Urgente'] as $p): ?><option <?php echo ($tarefa['prioridade']===$p?'selected':''); ?>><?php echo $p; ?></option><?php endforeach; ?></select></div>
        <div class="col-md-3"><label class="form-label">Status</label><select name="status" class="form-select"><?php foreach(['Pendente','Em Andamento','Aguardando','Concluída','Cancelada'] as $s): ?><option <?php echo ($tarefa['status']===$s?'selected':''); ?>><?php echo $s; ?></option><?php endforeach; ?></select></div>
        <div class="col-md-3"><label class="form-label">Vencimento</label><input type="date" name="data_vencimento" class="form-control" value="<?php echo htmlspecialchars($tarefa['data_vencimento']); ?>"></div>
        <div class="col-md-6"><label class="form-label">Responsável</label><select name="responsavel_id" class="form-select"><option value="">(Sem responsável)</option><?php foreach($usuarios as $u): ?><option value="<?php echo $u['id']; ?>" <?php echo ($tarefa['responsavel_id']==$u['id']?'selected':''); ?>><?php echo htmlspecialchars($u['nome']); ?></option><?php endforeach; ?></select></div>
        <div class="col-md-6"><label class="form-label">Tags</label><input name="tags" class="form-control" value="<?php echo htmlspecialchars($tarefa['tags']); ?>"></div>
        <div class="col-12"><label class="form-label">Descrição</label><textarea name="descricao" class="form-control" rows="4"><?php echo htmlspecialchars($tarefa['descricao']); ?></textarea></div>
      </div>
      <div class="mt-3 d-flex gap-2">
        <button class="btn btn-success" type="submit"><i class="fas fa-save"></i> Salvar</button>
        <a class="btn btn-secondary" href="tarefa-detalhes.php?id=<?php echo $id; ?>">Cancelar</a>
      </div>
    </form>
  </div></div>
</div>

<?php include 'includes/footer.php';

