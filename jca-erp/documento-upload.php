<?php
require_once 'config/config.php';
if (!isLoggedIn()) redirect('index.php');
$db = getDB();
$usuario = getUsuarioLogado();

$clientes = $db->query("SELECT id, razao_social FROM clientes WHERE ativo=1 ORDER BY razao_social")->fetchAll();
$clienteId = !empty($_GET['cliente_id']) ? (int)$_GET['cliente_id'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clienteId = (int)($_POST['cliente_id'] ?? 0);
    $tipo = sanitize($_POST['tipo_documento'] ?? 'Outros');
    $mes = !empty($_POST['mes_referencia']) ? (int)$_POST['mes_referencia'] : null;
    $ano = !empty($_POST['ano_referencia']) ? (int)$_POST['ano_referencia'] : null;
    $descricao = trim($_POST['descricao'] ?? '');
    $tags = trim($_POST['tags'] ?? '');

    if ($clienteId <= 0) {
        setError('Selecione um cliente.');
    } elseif (!isset($_FILES['arquivo']) || $_FILES['arquivo']['error'] !== UPLOAD_ERR_OK) {
        setError('Envie um arquivo válido.');
    } else {
        $f = $_FILES['arquivo'];
        if ($f['size'] > MAX_UPLOAD_SIZE) {
            setError('Arquivo acima do limite.');
        } else {
            $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, ALLOWED_EXTENSIONS, true)) {
                setError('Tipo de arquivo não permitido.');
            } else {
                $dir = UPLOAD_PATH . 'documentos/' . $clienteId . '/';
                if (!is_dir($dir)) mkdir($dir, 0775, true);
                $safeName = preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($f['name']));
                $finalName = date('Ymd_His') . '_' . $safeName;
                $dest = $dir . $finalName;

                if (!move_uploaded_file($f['tmp_name'], $dest)) {
                    setError('Falha ao salvar arquivo. Verifique permissões da pasta uploads/.');
                } else {
                    $relPath = 'uploads/documentos/' . $clienteId . '/' . $finalName;
                    $stmt = $db->prepare("INSERT INTO documentos (cliente_id,tipo_documento,nome_arquivo,caminho_arquivo,mes_referencia,ano_referencia,enviado_por_id,descricao,tags)
                                          VALUES (?,?,?,?,?,?,?,?,?)");
                    $stmt->execute([$clienteId,$tipo,$safeName,$relPath,$mes,$ano,$usuario['id'],$descricao,$tags]);
                    registrarLog('documentos', $db->lastInsertId(), 'upload', 'Upload de documento');
                    setSuccess('Documento enviado com sucesso.');
                    redirect('documentos.php?cliente_id='.$clienteId);
                }
            }
        }
    }
}

$pageTitle = 'Upload de Documento';
$breadcrumb = '<a href="dashboard.php">Dashboard</a> / <a href="documentos.php">Documentos</a> / Upload';
include 'includes/header.php';
?>

<div class="p-6">
  <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
      <h5 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
        <i data-lucide="upload" class="w-5 h-5"></i>
        Upload de Documento
      </h5>
    </div>
    <div class="p-6">
      <form method="post" enctype="multipart/form-data" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Cliente <span class="text-red-600">*</span></label>
            <select name="cliente_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
              <option value="">Selecione</option>
              <?php foreach($clientes as $c): ?>
                <option value="<?php echo $c['id']; ?>" <?php echo ($clienteId==$c['id']?'selected':''); ?>><?php echo htmlspecialchars($c['razao_social']); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Tipo</label>
            <select name="tipo_documento" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
              <?php foreach(['Fiscal','Contábil','RH','Contrato','Outros'] as $t): ?>
                <option><?php echo $t; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Arquivo <span class="text-red-600">*</span></label>
          <input type="file" name="arquivo" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Mês</label>
            <input name="mes_referencia" placeholder="1-12" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Ano</label>
            <input name="ano_referencia" placeholder="2026" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
          </div>
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
            <input name="tags" placeholder="fiscal, contrato" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Descrição</label>
          <textarea name="descricao" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"></textarea>
        </div>

        <div class="flex gap-2 pt-4 border-t border-gray-200">
          <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
            <i data-lucide="upload" class="w-4 h-4"></i>
            Enviar
          </button>
          <a href="documentos.php" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
            Voltar
          </a>
        </div>

        <p class="text-sm text-gray-500 mt-4">
          Pasta: <code class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs">jca-erp/uploads/</code> precisa estar com permissão de escrita.
        </p>
      </form>
    </div>
  </div>
</div>

<?php include 'includes/footer.php';

