<?php
require_once 'config/config.php';

// Verificar se está logado
if (!isLoggedIn()) {
    redirect('index.php');
}

$funcionario_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($funcionario_id === 0) {
    header('Location: funcionarios-clientes.php');
    exit;
}

$db = getDB();

// Buscar dados do funcionário
$stmt = $db->prepare("
    SELECT fc.*, c.razao_social, c.nome_fantasia, c.cnpj
    FROM funcionarios_clientes fc
    INNER JOIN clientes c ON fc.cliente_id = c.id
    WHERE fc.id = ?
");
$stmt->execute([$funcionario_id]);
$funcionario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$funcionario) {
    header('Location: funcionarios-clientes.php');
    exit;
}

$pageTitle = 'Detalhes do Funcionário';
include 'includes/header.php';
?>

<div class="p-6 space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 rounded-lg p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <a href="funcionarios-clientes.php" class="hover:bg-white/20 p-2 rounded-lg transition-colors">
                        <i data-lucide="arrow-left" class="w-5 h-5"></i>
                    </a>
                    <h1 class="text-3xl font-bold"><?php echo htmlspecialchars($funcionario['nome_completo']); ?></h1>
                </div>
                <p class="text-primary-100"><?php echo htmlspecialchars($funcionario['cargo']); ?> - <?php echo htmlspecialchars($funcionario['nome_fantasia']); ?></p>
            </div>
            <div class="flex gap-3">
                <a href="funcionario-cliente-editar.php?id=<?php echo $funcionario_id; ?>" class="bg-white text-primary-700 px-4 py-2 rounded-lg font-semibold hover:bg-primary-50 transition-colors flex items-center gap-2">
                    <i data-lucide="edit" class="w-4 h-4"></i>
                    Editar
                </a>
                <span class="px-4 py-2 rounded-lg font-semibold <?php 
                    echo $funcionario['status'] === 'Ativo' ? 'bg-green-500' : 
                        ($funcionario['status'] === 'Férias' ? 'bg-blue-500' : 
                        ($funcionario['status'] === 'Afastado' ? 'bg-yellow-500' : 'bg-gray-500')); 
                ?>">
                    <?php echo htmlspecialchars($funcionario['status']); ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Grid de Informações -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Coluna 1: Dados Pessoais -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i data-lucide="user" class="w-5 h-5 text-primary-600"></i>
                Dados Pessoais
            </h2>
            <div class="space-y-3">
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase">CPF</label>
                    <p class="text-gray-900"><?php echo htmlspecialchars($funcionario['cpf']); ?></p>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase">RG</label>
                    <p class="text-gray-900"><?php echo htmlspecialchars($funcionario['rg']); ?></p>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase">Data de Nascimento</label>
                    <p class="text-gray-900"><?php echo date('d/m/Y', strtotime($funcionario['data_nascimento'])); ?></p>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase">Sexo</label>
                    <p class="text-gray-900"><?php echo $funcionario['sexo'] === 'M' ? 'Masculino' : 'Feminino'; ?></p>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase">Estado Civil</label>
                    <p class="text-gray-900"><?php echo htmlspecialchars($funcionario['estado_civil']); ?></p>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase">Dependentes</label>
                    <p class="text-gray-900"><?php echo $funcionario['numero_dependentes']; ?></p>
                </div>
            </div>
        </div>

        <!-- Coluna 2: Contato e Endereço -->
        <div class="space-y-6">
            <!-- Contato -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i data-lucide="phone" class="w-5 h-5 text-primary-600"></i>
                    Contato
                </h2>
                <div class="space-y-3">
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase">Telefone</label>
                        <p class="text-gray-900"><?php echo htmlspecialchars($funcionario['telefone'] ?: '-'); ?></p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase">Celular</label>
                        <p class="text-gray-900"><?php echo htmlspecialchars($funcionario['celular']); ?></p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase">E-mail</label>
                        <p class="text-gray-900"><?php echo htmlspecialchars($funcionario['email'] ?: '-'); ?></p>
                    </div>
                </div>
            </div>

            <!-- Endereço -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i data-lucide="map-pin" class="w-5 h-5 text-primary-600"></i>
                    Endereço
                </h2>
                <div class="space-y-2 text-gray-900">
                    <p><?php echo htmlspecialchars($funcionario['logradouro']) . ', ' . htmlspecialchars($funcionario['numero']); ?></p>
                    <p><?php echo htmlspecialchars($funcionario['bairro']); ?></p>
                    <p><?php echo htmlspecialchars($funcionario['cidade']) . ' - ' . htmlspecialchars($funcionario['estado']); ?></p>
                    <p>CEP: <?php echo htmlspecialchars($funcionario['cep']); ?></p>
                </div>
            </div>
        </div>

        <!-- Coluna 3: Dados Trabalhistas -->
        <div class="space-y-6">
            <!-- Empresa -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i data-lucide="building" class="w-5 h-5 text-primary-600"></i>
                    Empresa
                </h2>
                <div class="space-y-3">
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase">Razão Social</label>
                        <p class="text-gray-900"><?php echo htmlspecialchars($funcionario['razao_social']); ?></p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase">CNPJ</label>
                        <p class="text-gray-900"><?php echo htmlspecialchars($funcionario['cnpj']); ?></p>
                    </div>
                    <a href="cliente-detalhes.php?id=<?php echo $funcionario['cliente_id']; ?>" class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 font-semibold text-sm">
                        Ver detalhes da empresa
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>

            <!-- Dados Trabalhistas -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i data-lucide="briefcase" class="w-5 h-5 text-primary-600"></i>
                    Dados Trabalhistas
                </h2>
                <div class="space-y-3">
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase">Cargo</label>
                        <p class="text-gray-900"><?php echo htmlspecialchars($funcionario['cargo']); ?></p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase">Departamento</label>
                        <p class="text-gray-900"><?php echo htmlspecialchars($funcionario['departamento']); ?></p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase">Data de Admissão</label>
                        <p class="text-gray-900"><?php echo date('d/m/Y', strtotime($funcionario['data_admissao'])); ?></p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase">Tipo de Contrato</label>
                        <p class="text-gray-900"><?php echo htmlspecialchars($funcionario['tipo_contrato']); ?></p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase">Salário Base</label>
                        <p class="text-gray-900 text-lg font-bold text-green-600">R$ <?php echo number_format($funcionario['salario_base'], 2, ',', '.'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mais Informações em Tabs -->
    <div class="bg-white rounded-lg border border-gray-200" x-data="{ activeTab: 'documentos' }">
        <!-- Tabs Header -->
        <div class="border-b border-gray-200">
            <div class="flex gap-4 px-6">
                <button @click="activeTab = 'documentos'" :class="activeTab === 'documentos' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    Documentos
                </button>
                <button @click="activeTab = 'bancarios'" :class="activeTab === 'bancarios' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    Dados Bancários
                </button>
                <button @click="activeTab = 'beneficios'" :class="activeTab === 'beneficios' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    Benefícios
                </button>
            </div>
        </div>

        <!-- Tabs Content -->
        <div class="p-6">
            <!-- Tab: Documentos -->
            <div x-show="activeTab === 'documentos'" class="space-y-4">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Documentos Trabalhistas</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase">PIS/PASEP</label>
                        <p class="text-gray-900"><?php echo htmlspecialchars($funcionario['pis_pasep'] ?: '-'); ?></p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase">CTPS</label>
                        <p class="text-gray-900">
                            <?php
                            if ($funcionario['ctps_numero']) {
                                echo htmlspecialchars($funcionario['ctps_numero']) . ' / ' .
                                     htmlspecialchars($funcionario['ctps_serie']) . ' - ' .
                                     htmlspecialchars($funcionario['ctps_uf']);
                            } else {
                                echo '-';
                            }
                            ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Tab: Dados Bancários -->
            <div x-show="activeTab === 'bancarios'" class="space-y-4">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Dados Bancários</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase">Banco</label>
                        <p class="text-gray-900"><?php echo htmlspecialchars($funcionario['banco_codigo'] ?: '-') . ' - ' . htmlspecialchars($funcionario['banco_nome'] ?: '-'); ?></p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase">Agência</label>
                        <p class="text-gray-900"><?php echo htmlspecialchars($funcionario['agencia'] ?: '-'); ?></p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase">Conta</label>
                        <p class="text-gray-900"><?php echo htmlspecialchars($funcionario['conta'] ?: '-'); ?></p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase">Tipo de Conta</label>
                        <p class="text-gray-900"><?php echo htmlspecialchars($funcionario['tipo_conta'] ?: '-'); ?></p>
                    </div>
                </div>
            </div>

            <!-- Tab: Benefícios -->
            <div x-show="activeTab === 'beneficios'" class="space-y-4">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Benefícios</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i data-lucide="bus" class="w-5 h-5 text-blue-600"></i>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase">Vale Transporte</label>
                                <p class="text-lg font-bold text-gray-900">R$ <?php echo number_format($funcionario['vale_transporte'], 2, ',', '.'); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <i data-lucide="utensils" class="w-5 h-5 text-green-600"></i>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase">Vale Refeição</label>
                                <p class="text-lg font-bold text-gray-900">R$ <?php echo number_format($funcionario['vale_refeicao'], 2, ',', '.'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Observações -->
    <?php if (!empty($funcionario['observacoes'])): ?>
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i data-lucide="file-text" class="w-5 h-5 text-primary-600"></i>
            Observações
        </h2>
        <p class="text-gray-700 whitespace-pre-wrap"><?php echo htmlspecialchars($funcionario['observacoes']); ?></p>
    </div>
    <?php endif; ?>
</div>

<script>
    lucide.createIcons();
</script>

<?php include 'includes/footer.php'; ?>

