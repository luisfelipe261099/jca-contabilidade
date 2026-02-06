<?php
require_once 'config/config.php';

if (!isLoggedIn()) {
    redirect('index.php');
}

$db = getDB();
$usuario = getUsuarioLogado();

$clienteId = (int)($_GET['cliente_id'] ?? 0);
if ($clienteId <= 0) {
    setError('Cliente inválido.');
    redirect('clientes.php');
}

$stmt = $db->prepare("SELECT id, razao_social, nome_fantasia FROM clientes WHERE id = ?");
$stmt->execute([$clienteId]);
$cliente = $stmt->fetch();
if (!$cliente) {
    setError('Cliente não encontrado.');
    redirect('clientes.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $servicoId = (int)($_POST['servico_id'] ?? 0);
        $valorRaw = trim($_POST['valor_cobrado'] ?? '');
        $valor = $valorRaw === '' ? null : (float)str_replace(',', '.', preg_replace('/[^0-9,\.]/', '', $valorRaw));
        $dataInicio = $_POST['data_inicio'] ?? date('Y-m-d');

        if ($servicoId <= 0) {
            setError('Selecione um serviço.');
        } else {
            $stmt = $db->prepare("SELECT id, ativo FROM cliente_servicos WHERE cliente_id=? AND servico_id=? ORDER BY id DESC LIMIT 1");
            $stmt->execute([$clienteId, $servicoId]);
            $ex = $stmt->fetch();

            if ($ex && (int)$ex['ativo'] === 1) {
                setError('Este serviço já está ativo para este cliente.');
            } elseif ($ex) {
                $stmt = $db->prepare("UPDATE cliente_servicos SET ativo=1, valor_cobrado=?, data_inicio=?, data_fim=NULL WHERE id=? AND cliente_id=?");
                $stmt->execute([$valor, $dataInicio, (int)$ex['id'], $clienteId]);
                registrarLog('cliente_servicos', (int)$ex['id'], 'update', 'Reativou serviço do cliente');
                setSuccess('Serviço reativado com sucesso.');
            } else {
                $stmt = $db->prepare("INSERT INTO cliente_servicos (cliente_id, servico_id, valor_cobrado, data_inicio, ativo) VALUES (?,?,?,?,1)");
                $stmt->execute([$clienteId, $servicoId, $valor, $dataInicio]);
                registrarLog('cliente_servicos', (int)$db->lastInsertId(), 'create', 'Adicionou serviço ao cliente');
                setSuccess('Serviço adicionado com sucesso.');
            }
        }
    }

    if ($action === 'desativar') {
        $csId = (int)($_POST['cliente_servico_id'] ?? 0);
        if ($csId > 0) {
            $stmt = $db->prepare("UPDATE cliente_servicos SET ativo=0, data_fim=CURDATE() WHERE id=? AND cliente_id=?");
            $stmt->execute([$csId, $clienteId]);
            registrarLog('cliente_servicos', $csId, 'update', 'Desativou serviço do cliente');
            setSuccess('Serviço desativado.');
        }
    }

    redirect('cliente-servicos.php?cliente_id=' . $clienteId);
}

$servicosAtivos = $db->query("SELECT id, nome, categoria, icone, cor, valor_base FROM servicos WHERE ativo=1 ORDER BY categoria, nome")->fetchAll();
$stmt = $db->prepare("SELECT cs.*, s.nome, s.categoria, s.icone, s.cor, s.valor_base
                      FROM cliente_servicos cs
                      JOIN servicos s ON cs.servico_id = s.id
                      WHERE cs.cliente_id=?
                      ORDER BY cs.ativo DESC, s.categoria, s.nome");
$stmt->execute([$clienteId]);
$contratados = $stmt->fetchAll();

$pageTitle = 'Serviços do Cliente';
$breadcrumb = '<a href="dashboard.php">Dashboard</a> / <a href="clientes.php">Clientes</a> / <a href="cliente-detalhes.php?id=' . $clienteId . '">Detalhes</a> / Serviços';
include 'includes/header.php';
?>

<div class="content-area">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h4 class="mb-0">Serviços - <?php echo htmlspecialchars($cliente['razao_social']); ?></h4>
      <small class="text-muted">Gerencie os serviços/soluções contratados por este cliente.</small>
    </div>
    <div class="d-flex gap-2">
      <a class="btn btn-secondary" href="cliente-detalhes.php?id=<?php echo $clienteId; ?>"><i class="fas fa-arrow-left"></i> Voltar</a>
    </div>
  </div>

  <div class="card mb-4">
    <div class="card-header"><strong>Adicionar serviço</strong></div>
    <div class="card-body">
      <form method="post" class="row g-2 align-items-end">
        <input type="hidden" name="action" value="add">
        <div class="col-md-6">
          <label class="form-label">Serviço</label>
          <select class="form-select" name="servico_id" required>
            <option value="">Selecione</option>
            <?php foreach ($servicosAtivos as $s): ?>
              <option value="<?php echo $s['id']; ?>"><?php echo htmlspecialchars($s['categoria'] . ' - ' . $s['nome']); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">Valor cobrado (opcional)</label>
          <input class="form-control" name="valor_cobrado" placeholder="Ex: 850,00">
        </div>
        <div class="col-md-3">
          <label class="form-label">Data início</label>
          <input class="form-control" type="date" name="data_inicio" value="<?php echo date('Y-m-d'); ?>" required>
        </div>
        <div class="col-12 d-grid">
          <button class="btn btn-primary" type="submit"><i class="fas fa-plus"></i> Adicionar</button>
        </div>
      </form>
    </div>
  </div>

  <div class="card">
    <div class="card-header"><strong>Serviços vinculados</strong></div>
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead class="table-light"><tr><th>Serviço</th><th>Categoria</th><th>Valor</th><th>Início</th><th>Status</th><th class="text-end">Ações</th></tr></thead>
        <tbody>
        <?php if (!$contratados): ?>
          <tr><td colspan="6" class="text-center text-muted p-4">Nenhum serviço vinculado.</td></tr>
        <?php endif; ?>
        <?php foreach ($contratados as $cs): ?>
          <tr>
            <td><i class="fas <?php echo htmlspecialchars($cs['icone']); ?> me-2" style="color: <?php echo htmlspecialchars($cs['cor']); ?>"></i><?php echo htmlspecialchars($cs['nome']); ?></td>
            <td><?php echo htmlspecialchars($cs['categoria']); ?></td>
            <td><?php echo formatarMoeda($cs['valor_cobrado'] ?? $cs['valor_base']); ?></td>
            <td><?php echo formatarData($cs['data_inicio']); ?></td>
            <td>
              <?php if ((int)$cs['ativo'] === 1): ?>
                <span class="badge bg-success">Ativo</span>
              <?php else: ?>
                <span class="badge bg-secondary">Inativo</span>
              <?php endif; ?>
            </td>
            <td class="text-end">
              <?php if ((int)$cs['ativo'] === 1): ?>
                <form method="post" class="d-inline" onsubmit="return confirm('Desativar este serviço?');">
                  <input type="hidden" name="action" value="desativar">
                  <input type="hidden" name="cliente_servico_id" value="<?php echo (int)$cs['id']; ?>">
                  <button class="btn btn-sm btn-outline-danger" type="submit"><i class="fas fa-ban"></i></button>
                </form>
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

