<?php
require_once 'config/config.php';
if (!isLoggedIn()) redirect('index.php');

$usuario = getUsuarioLogado();
$db = getDB();

// Processar formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clienteId = $_POST['cliente_id'];
    $nomeCompleto = $_POST['nome_completo'];
    $cpf = somenteNumeros($_POST['cpf']);
    $rg = $_POST['rg'] ?? null;
    $dataNascimento = $_POST['data_nascimento'] ?? null;
    $sexo = $_POST['sexo'] ?? null;
    $estadoCivil = $_POST['estado_civil'] ?? null;
    
    // Endereço
    $cep = somenteNumeros($_POST['cep'] ?? '');
    $logradouro = $_POST['logradouro'] ?? null;
    $numero = $_POST['numero'] ?? null;
    $complemento = $_POST['complemento'] ?? null;
    $bairro = $_POST['bairro'] ?? null;
    $cidade = $_POST['cidade'] ?? null;
    $estado = $_POST['estado'] ?? null;
    
    // Contato
    $telefone = $_POST['telefone'] ?? null;
    $celular = $_POST['celular'] ?? null;
    $email = $_POST['email'] ?? null;
    
    // Dados trabalhistas
    $cargo = $_POST['cargo'];
    $departamento = $_POST['departamento'] ?? null;
    $dataAdmissao = $_POST['data_admissao'];
    $tipoContrato = $_POST['tipo_contrato'];
    
    // Remuneração
    $salarioBase = str_replace(['.', ','], ['', '.'], $_POST['salario_base']);
    $valeTransporte = str_replace(['.', ','], ['', '.'], $_POST['vale_transporte'] ?? '0');
    $valeRefeicao = str_replace(['.', ','], ['', '.'], $_POST['vale_refeicao'] ?? '0');
    
    // Documentos
    $pisPasep = $_POST['pis_pasep'] ?? null;
    $ctpsNumero = $_POST['ctps_numero'] ?? null;
    $ctpsSerie = $_POST['ctps_serie'] ?? null;
    $ctpsUf = $_POST['ctps_uf'] ?? null;
    
    // Banco
    $bancoCodigo = $_POST['banco_codigo'] ?? null;
    $bancoNome = $_POST['banco_nome'] ?? null;
    $agencia = $_POST['agencia'] ?? null;
    $conta = $_POST['conta'] ?? null;
    $tipoConta = $_POST['tipo_conta'] ?? null;
    $pix = $_POST['pix'] ?? null;
    
    $numeroDependentes = $_POST['numero_dependentes'] ?? 0;
    $observacoes = $_POST['observacoes'] ?? null;
    
    try {
        $stmt = $db->prepare("INSERT INTO funcionarios_clientes (
            cliente_id, nome_completo, cpf, rg, data_nascimento, sexo, estado_civil,
            cep, logradouro, numero, complemento, bairro, cidade, estado,
            telefone, celular, email,
            cargo, departamento, data_admissao, tipo_contrato,
            salario_base, vale_transporte, vale_refeicao,
            pis_pasep, ctps_numero, ctps_serie, ctps_uf,
            banco_codigo, banco_nome, agencia, conta, tipo_conta, pix,
            numero_dependentes, observacoes, status
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?, ?, ?,
            ?, ?, ?,
            ?, ?, ?, ?,
            ?, ?, ?,
            ?, ?, ?, ?,
            ?, ?, ?, ?, ?, ?,
            ?, ?, 'Ativo'
        )");
        
        $stmt->execute([
            $clienteId, $nomeCompleto, $cpf, $rg, $dataNascimento, $sexo, $estadoCivil,
            $cep, $logradouro, $numero, $complemento, $bairro, $cidade, $estado,
            $telefone, $celular, $email,
            $cargo, $departamento, $dataAdmissao, $tipoContrato,
            $salarioBase, $valeTransporte, $valeRefeicao,
            $pisPasep, $ctpsNumero, $ctpsSerie, $ctpsUf,
            $bancoCodigo, $bancoNome, $agencia, $conta, $tipoConta, $pix,
            $numeroDependentes, $observacoes
        ]);
        
        $funcionarioId = $db->lastInsertId();
        redirect("funcionario-cliente-detalhes.php?id=$funcionarioId&success=1");
    } catch (Exception $e) {
        $erro = "Erro ao cadastrar funcionário: " . $e->getMessage();
    }
}

// Buscar clientes
$stmtClientes = $db->query("SELECT id, razao_social, nome_fantasia FROM clientes WHERE ativo = 1 ORDER BY razao_social");
$clientes = $stmtClientes->fetchAll();

// Cliente pré-selecionado
$clienteIdPre = $_GET['cliente_id'] ?? '';

$pageTitle = 'Novo Funcionário';
$breadcrumb = '<a href="dashboard.php">Dashboard</a> / <a href="funcionarios-clientes.php">Funcionários</a> / Novo';
include 'includes/header.php';
?>

<div class="p-6 space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Novo Funcionário</h1>
            <p class="text-gray-600 mt-1">Cadastrar novo funcionário de cliente</p>
        </div>
        <a href="funcionarios-clientes.php" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors flex items-center gap-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Voltar
        </a>
    </div>

    <?php if (isset($erro)): ?>
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            <?php echo $erro; ?>
        </div>
    <?php endif; ?>

    <!-- Formulário -->
    <form method="POST" class="space-y-6">
        <!-- Cliente -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i data-lucide="building-2" class="w-5 h-5 text-gray-600"></i>
                Cliente
            </h2>
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cliente *</label>
                    <select name="cliente_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">Selecione o cliente</option>
                        <?php foreach ($clientes as $c): ?>
                            <option value="<?php echo $c['id']; ?>" <?php echo $clienteIdPre == $c['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($c['nome_fantasia'] ?: $c['razao_social']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <!-- Dados Pessoais -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i data-lucide="user" class="w-5 h-5 text-gray-600"></i>
                Dados Pessoais
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nome Completo *</label>
                    <input type="text" name="nome_completo" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">CPF *</label>
                    <input type="text" name="cpf" required maxlength="14" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="000.000.000-00">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">RG</label>
                    <input type="text" name="rg" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Data de Nascimento</label>
                    <input type="date" name="data_nascimento" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sexo</label>
                    <select name="sexo" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">Selecione</option>
                        <option value="M">Masculino</option>
                        <option value="F">Feminino</option>
                        <option value="Outro">Outro</option>
                    </select>
                </div>
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado Civil</label>
                    <select name="estado_civil" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">Selecione</option>
                        <option value="Solteiro(a)">Solteiro(a)</option>
                        <option value="Casado(a)">Casado(a)</option>
                        <option value="Divorciado(a)">Divorciado(a)</option>
                        <option value="Viúvo(a)">Viúvo(a)</option>
                        <option value="União Estável">União Estável</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Contato -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i data-lucide="phone" class="w-5 h-5 text-gray-600"></i>
                Contato
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Telefone</label>
                    <input type="text" name="telefone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="(00) 0000-0000">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Celular</label>
                    <input type="text" name="celular" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="(00) 00000-0000">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">E-mail</label>
                    <input type="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
            </div>
        </div>

        <!-- Endereço -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i data-lucide="map-pin" class="w-5 h-5 text-gray-600"></i>
                Endereço
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">CEP</label>
                    <input type="text" name="cep" maxlength="9" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="00000-000">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Logradouro</label>
                    <input type="text" name="logradouro" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Número</label>
                    <input type="text" name="numero" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Complemento</label>
                    <input type="text" name="complemento" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bairro</label>
                    <input type="text" name="bairro" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cidade</label>
                    <input type="text" name="cidade" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                    <select name="estado" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">Selecione</option>
                        <option value="PR">PR</option>
                        <option value="SC">SC</option>
                        <option value="RS">RS</option>
                        <option value="SP">SP</option>
                        <!-- Adicionar outros estados conforme necessário -->
                    </select>
                </div>
            </div>
        </div>

        <!-- Dados Trabalhistas -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i data-lucide="briefcase" class="w-5 h-5 text-gray-600"></i>
                Dados Trabalhistas
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cargo *</label>
                    <input type="text" name="cargo" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Departamento</label>
                    <input type="text" name="departamento" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Data de Admissão *</label>
                    <input type="date" name="data_admissao" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Contrato *</label>
                    <select name="tipo_contrato" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="CLT">CLT</option>
                        <option value="PJ">PJ</option>
                        <option value="Estágio">Estágio</option>
                        <option value="Temporário">Temporário</option>
                        <option value="Autônomo">Autônomo</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Salário Base *</label>
                    <input type="text" name="salario_base" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="0,00">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Vale Transporte</label>
                    <input type="text" name="vale_transporte" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="0,00">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Vale Refeição</label>
                    <input type="text" name="vale_refeicao" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="0,00">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Número de Dependentes</label>
                    <input type="number" name="numero_dependentes" min="0" value="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
            </div>
        </div>

        <!-- Documentos -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i data-lucide="file-text" class="w-5 h-5 text-gray-600"></i>
                Documentos Trabalhistas
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">PIS/PASEP</label>
                    <input type="text" name="pis_pasep" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">CTPS Número</label>
                    <input type="text" name="ctps_numero" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">CTPS Série</label>
                    <input type="text" name="ctps_serie" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">CTPS UF</label>
                    <input type="text" name="ctps_uf" maxlength="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="PR">
                </div>
            </div>
        </div>

        <!-- Dados Bancários -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i data-lucide="landmark" class="w-5 h-5 text-gray-600"></i>
                Dados Bancários
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Código do Banco</label>
                    <input type="text" name="banco_codigo" maxlength="5" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="001">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nome do Banco</label>
                    <input type="text" name="banco_nome" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Agência</label>
                    <input type="text" name="agencia" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Conta</label>
                    <input type="text" name="conta" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Conta</label>
                    <select name="tipo_conta" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">Selecione</option>
                        <option value="Corrente">Corrente</option>
                        <option value="Poupança">Poupança</option>
                        <option value="Salário">Salário</option>
                    </select>
                </div>
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Chave PIX</label>
                    <input type="text" name="pix" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="CPF, e-mail, telefone ou chave aleatória">
                </div>
            </div>
        </div>

        <!-- Observações -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i data-lucide="message-square" class="w-5 h-5 text-gray-600"></i>
                Observações
            </h2>
            <div>
                <textarea name="observacoes" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="Informações adicionais sobre o funcionário..."></textarea>
            </div>
        </div>

        <!-- Botões -->
        <div class="flex items-center justify-end gap-4">
            <a href="funcionarios-clientes.php" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors flex items-center gap-2">
                <i data-lucide="save" class="w-5 h-5"></i>
                Cadastrar Funcionário
            </button>
        </div>
    </form>
</div>

<script>
    lucide.createIcons();
</script>

<?php include 'includes/footer.php'; ?>


