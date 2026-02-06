<?php
require_once 'config/config.php';
if (!isLoggedIn()) redirect('index.php');
if (!isFuncionario()) { setError('Acesso negado.'); redirect('dashboard.php'); }
$db = getDB();
$usuario = getUsuarioLogado();

$stmt = $db->query("SELECT u.*, s.nome as setor_nome
                    FROM usuarios u
                    LEFT JOIN setores s ON u.setor_id=s.id
                    WHERE u.tipo_usuario IN ('admin','funcionario')
                    ORDER BY u.nome");
$rows = $stmt->fetchAll();

$pageTitle='Funcionários';
$breadcrumb='<a href="dashboard.php">Dashboard</a> / Funcionários';
include 'includes/header.php';
?>

<div class="p-6">
  <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nome</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tipo</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Setor</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Ativo</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Criado em</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <?php foreach($rows as $r): ?>
            <tr class="hover:bg-gray-50 transition-colors">
              <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-primary-600 rounded-full flex items-center justify-center text-white font-semibold">
                    <?php echo strtoupper(substr($r['nome'], 0, 1)); ?>
                  </div>
                  <span class="font-medium text-gray-900"><?php echo htmlspecialchars($r['nome']); ?></span>
                </div>
              </td>
              <td class="px-6 py-4 text-sm text-gray-600"><?php echo htmlspecialchars($r['email']); ?></td>
              <td class="px-6 py-4">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $r['tipo_usuario'] === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700'; ?>">
                  <?php echo htmlspecialchars($r['tipo_usuario']); ?>
                </span>
              </td>
              <td class="px-6 py-4 text-sm text-gray-600"><?php echo htmlspecialchars($r['setor_nome'] ?? '—'); ?></td>
              <td class="px-6 py-4">
                <?php if ($r['ativo']): ?>
                  <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                    <i data-lucide="check-circle" class="w-3 h-3"></i>
                    Sim
                  </span>
                <?php else: ?>
                  <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                    <i data-lucide="x-circle" class="w-3 h-3"></i>
                    Não
                  </span>
                <?php endif; ?>
              </td>
              <td class="px-6 py-4 text-sm text-gray-600"><?php echo formatarDataHora($r['data_criacao']); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <p class="text-sm text-gray-500 mt-4">Obs.: cadastro/edição completa pode ser adicionado depois (CRUD de funcionários).</p>
</div>

<?php include 'includes/footer.php';

