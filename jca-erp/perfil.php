<?php
require_once 'config/config.php';
if (!isLoggedIn()) redirect('index.php');
$db = getDB();
$usuario = getUsuarioLogado();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $atual = $_POST['senha_atual'] ?? '';
    $nova = $_POST['nova_senha'] ?? '';
    $conf = $_POST['confirmar_senha'] ?? '';

    if ($nova === '' || $nova !== $conf) {
        setError('Nova senha e confirmação não conferem.');
    } else {
        $stmt = $db->prepare("SELECT senha FROM usuarios WHERE id=?");
        $stmt->execute([$usuario['id']]);
        $hash = $stmt->fetchColumn();
        if (!$hash || !password_verify($atual, $hash)) {
            setError('Senha atual inválida.');
        } else {
            $stmt = $db->prepare("UPDATE usuarios SET senha=? WHERE id=?");
            $stmt->execute([password_hash($nova, PASSWORD_DEFAULT), $usuario['id']]);
            registrarLog('perfil', $usuario['id'], 'update', 'Alterou senha');
            setSuccess('Senha alterada com sucesso.');
            redirect('perfil.php');
        }
    }
}

$pageTitle='Meu Perfil';
$breadcrumb='<a href="dashboard.php">Dashboard</a> / Meu Perfil';
include 'includes/header.php';
?>

<div class="p-6">
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-200">
        <h5 class="text-lg font-semibold text-gray-900">Dados</h5>
      </div>
      <div class="p-6 space-y-4">
        <div>
          <p class="text-sm text-gray-500 mb-1">Nome</p>
          <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($usuario['nome']); ?></p>
        </div>
        <div>
          <p class="text-sm text-gray-500 mb-1">Email</p>
          <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($usuario['email']); ?></p>
        </div>
        <div>
          <p class="text-sm text-gray-500 mb-1">Tipo</p>
          <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($usuario['tipo_usuario']); ?></p>
        </div>
      </div>
    </div>
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-200">
        <h5 class="text-lg font-semibold text-gray-900">Alterar senha</h5>
      </div>
      <div class="p-6">
        <form method="post" class="space-y-4">
          <div>
            <input type="password" name="senha_atual" placeholder="Senha atual" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
          </div>
          <div>
            <input type="password" name="nova_senha" placeholder="Nova senha" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
          </div>
          <div>
            <input type="password" name="confirmar_senha" placeholder="Confirmar nova senha" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
          </div>
          <div>
            <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center justify-center gap-2">
              <i data-lucide="save" class="w-4 h-4"></i>
              Salvar
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include 'includes/footer.php';

