<?php
require_once 'config/config.php';
if (!isLoggedIn()) redirect('index.php');
$db = getDB();
$usuario = getUsuarioLogado();

$clienteId = isset($_GET['cliente_id']) ? (int)$_GET['cliente_id'] : 0;
if (!$clienteId) redirect('clientes.php');

// Busca dados do cliente
$stmt = $db->prepare("SELECT * FROM clientes WHERE id = ?");
$stmt->execute([$clienteId]);
$cliente = $stmt->fetch();
if (!$cliente) redirect('clientes.php');

// Processa o formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $db->prepare("INSERT INTO funcionarios_clientes (
            cliente_id, nome_completo, cpf, rg, data_nascimento, sexo, estado_civil,
            cep, logradouro, numero, complemento, bairro, cidade, estado,
            telefone, celular, email, cargo, departamento, data_admissao, tipo_contrato,
            salario_base, vale_transporte, vale_refeicao, pis_pasep, ctps_numero, ctps_serie, ctps_uf,
            banco_codigo, banco_nome, agencia, conta, tipo_conta, pix, status, observacoes
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $clienteId,
            sanitize($_POST['nome_completo']),
            sanitize($_POST['cpf']),
            sanitize($_POST['rg']),
            $_POST['data_nascimento'] ?: null,
            $_POST['sexo'] ?: null,
            $_POST['estado_civil'] ?: null,
            sanitize($_POST['cep']),
            sanitize($_POST['logradouro']),
            sanitize($_POST['numero']),
            sanitize($_POST['complemento']),
            sanitize($_POST['bairro']),
            sanitize($_POST['cidade']),
            sanitize($_POST['estado']),
            sanitize($_POST['telefone']),
            sanitize($_POST['celular']),
            sanitize($_POST['email']),
            sanitize($_POST['cargo']),
            sanitize($_POST['departamento']),
            $_POST['data_admissao'],
            $_POST['tipo_contrato'],
            $_POST['salario_base'],
            $_POST['vale_transporte'] ?: 0,
            $_POST['vale_refeicao'] ?: 0,
            sanitize($_POST['pis_pasep']),
            sanitize($_POST['ctps_numero']),
            sanitize($_POST['ctps_serie']),
            sanitize($_POST['ctps_uf']),
            sanitize($_POST['banco_codigo']),
            sanitize($_POST['banco_nome']),
            sanitize($_POST['agencia']),
            sanitize($_POST['conta']),
            $_POST['tipo_conta'] ?: null,
            sanitize($_POST['pix']),
            $_POST['status'],
            sanitize($_POST['observacoes'])
        ]);
        
        registrarLog('funcionarios_clientes', $db->lastInsertId(), 'create', 'Funcionário cadastrado');
        setSuccess('Funcionário cadastrado com sucesso!');
        redirect('cliente-funcionarios.php?id=' . $clienteId);
        
    } catch (Exception $e) {
        setError('Erro ao cadastrar funcionário: ' . $e->getMessage());
    }
}

$pageTitle = 'Novo Funcionário - ' . $cliente['razao_social'];
$breadcrumb = '<a href="dashboard.php">Dashboard</a> / <a href="clientes.php">Clientes</a> / <a href="cliente-detalhes.php?id='.$clienteId.'">'.htmlspecialchars($cliente['razao_social']).'</a> / <a href="cliente-funcionarios.php?id='.$clienteId.'">Funcionários</a> / Novo';
include 'includes/header.php';
?>

<div class="p-6">
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h5 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                <i data-lucide="user-plus" class="w-5 h-5"></i>
                Novo Funcionário - <?php echo htmlspecialchars($cliente['razao_social']); ?>
            </h5>
        </div>
        <div class="p-6">
            <form method="POST" class="space-y-8">
                <!-- Dados Pessoais -->
                <div>
                    <h6 class="text-base font-semibold text-gray-900 pb-2 mb-4 border-b border-gray-200 flex items-center gap-2">
                        <i data-lucide="user" class="w-5 h-5"></i>
                        Dados Pessoais
                    </h6>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nome Completo <span class="text-red-600">*</span></label>
                            <input type="text" name="nome_completo" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">CPF <span class="text-red-600">*</span></label>
                            <input type="text" name="cpf" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">RG</label>
                            <input type="text" name="rg" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Data de Nascimento</label>
                            <input type="date" name="data_nascimento" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sexo</label>
                            <select name="sexo" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                <option value="">Selecione...</option>
                                <option value="M">Masculino</option>
                                <option value="F">Feminino</option>
                                <option value="Outro">Outro</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Estado Civil</label>
                            <select name="estado_civil" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                <option value="">Selecione...</option>
                                <option value="Solteiro(a)">Solteiro(a)</option>
                                <option value="Casado(a)">Casado(a)</option>
                                <option value="Divorciado(a)">Divorciado(a)</option>
                                <option value="Viúvo(a)">Viúvo(a)</option>
                                <option value="União Estável">União Estável</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Dados Trabalhistas -->
                <div>
                    <h6 class="text-base font-semibold text-gray-900 pb-2 mb-4 border-b border-gray-200 flex items-center gap-2">
                        <i data-lucide="briefcase" class="w-5 h-5"></i>
                        Dados Trabalhistas
                    </h6>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cargo <span class="text-red-600">*</span></label>
                            <input type="text" name="cargo" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Departamento</label>
                            <input type="text" name="departamento" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Data de Admissão <span class="text-red-600">*</span></label>
                            <input type="date" name="data_admissao" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Contrato</label>
                            <select name="tipo_contrato" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                <option value="CLT">CLT</option>
                                <option value="PJ">PJ</option>
                                <option value="Estágio">Estágio</option>
                                <option value="Temporário">Temporário</option>
                                <option value="Autônomo">Autônomo</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Salário Base <span class="text-red-600">*</span></label>
                            <input type="number" step="0.01" name="salario_base" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                <option value="Ativo">Ativo</option>
                                <option value="Férias">Férias</option>
                                <option value="Afastado">Afastado</option>
                                <option value="Demitido">Demitido</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Vale Transporte</label>
                            <input type="number" step="0.01" name="vale_transporte" value="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Vale Refeição</label>
                            <input type="number" step="0.01" name="vale_refeicao" value="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">PIS/PASEP</label>
                            <input type="text" name="pis_pasep" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                    </div>
                </div>

                <!-- Contato -->
                <div>
                    <h6 class="text-base font-semibold text-gray-900 pb-2 mb-4 border-b border-gray-200 flex items-center gap-2">
                        <i data-lucide="phone" class="w-5 h-5"></i>
                        Contato
                    </h6>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Telefone</label>
                            <input type="text" name="telefone" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Celular</label>
                            <input type="text" name="celular" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">E-mail</label>
                            <input type="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                    </div>
                </div>

                <!-- Endereço -->
                <div>
                    <h6 class="text-base font-semibold text-gray-900 pb-2 mb-4 border-b border-gray-200 flex items-center gap-2">
                        <i data-lucide="map-pin" class="w-5 h-5"></i>
                        Endereço
                    </h6>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">CEP</label>
                            <input type="text" name="cep" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Logradouro</label>
                            <input type="text" name="logradouro" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Número</label>
                            <input type="text" name="numero" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Complemento</label>
                            <input type="text" name="complemento" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bairro</label>
                            <input type="text" name="bairro" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cidade</label>
                            <input type="text" name="cidade" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                            <input type="text" name="estado" maxlength="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                    </div>
                </div>

                <!-- Dados Bancários -->
                <div>
                    <h6 class="text-base font-semibold text-gray-900 pb-2 mb-4 border-b border-gray-200 flex items-center gap-2">
                        <i data-lucide="landmark" class="w-5 h-5"></i>
                        Dados Bancários
                    </h6>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Banco</label>
                            <input type="text" name="banco_nome" placeholder="Ex: Banco do Brasil" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Código</label>
                            <input type="text" name="banco_codigo" placeholder="Ex: 001" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Agência</label>
                            <input type="text" name="agencia" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Conta</label>
                            <input type="text" name="conta" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Conta</label>
                            <select name="tipo_conta" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                <option value="">Selecione...</option>
                                <option value="Corrente">Corrente</option>
                                <option value="Poupança">Poupança</option>
                                <option value="Salário">Salário</option>
                            </select>
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 mb-2">PIX</label>
                            <input type="text" name="pix" placeholder="CPF, e-mail, telefone ou chave aleatória" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                    </div>
                </div>

                <!-- Documentos -->
                <div>
                    <h6 class="text-base font-semibold text-gray-900 pb-2 mb-4 border-b border-gray-200 flex items-center gap-2">
                        <i data-lucide="file-text" class="w-5 h-5"></i>
                        Documentos Trabalhistas
                    </h6>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">CTPS Número</label>
                            <input type="text" name="ctps_numero" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">CTPS Série</label>
                            <input type="text" name="ctps_serie" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">CTPS UF</label>
                            <input type="text" name="ctps_uf" maxlength="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                    </div>
                </div>

                <!-- Observações -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Observações</label>
                    <textarea name="observacoes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"></textarea>
                </div>

                <!-- Botões -->
                <div class="flex gap-2 pt-4 border-t border-gray-200">
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        Salvar
                    </button>
                    <a href="cliente-funcionarios.php?id=<?php echo $clienteId; ?>" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

