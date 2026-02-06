<?php
require_once 'config/config.php';

if (!isLoggedIn()) {
    redirect('index.php');
}

$usuario = getUsuarioLogado();
$db = getDB();

// Busca contadores para o select
$stmt = $db->query("SELECT id, nome FROM usuarios WHERE tipo_usuario IN ('admin', 'funcionario') AND ativo = 1 ORDER BY nome");
$contadores = $stmt->fetchAll();

// Processa o formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $db->prepare("INSERT INTO clientes (
            razao_social, nome_fantasia, cnpj, inscricao_estadual, inscricao_municipal,
            regime_tributario, porte_empresa, cnae_principal, cep, logradouro,
            numero, complemento, bairro, cidade, estado, telefone, celular, email,
            responsavel_nome, responsavel_cpf, responsavel_email, responsavel_telefone,
            contador_responsavel_id, data_inicio_contrato, valor_mensalidade, dia_vencimento,
            status_contrato, observacoes
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            sanitize($_POST['razao_social']),
            sanitize($_POST['nome_fantasia']),
            sanitize($_POST['cnpj']),
            sanitize($_POST['inscricao_estadual']),
            sanitize($_POST['inscricao_municipal']),
            sanitize($_POST['regime_tributario']),
            sanitize($_POST['porte_empresa']),
            sanitize($_POST['cnae_principal']),
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
            sanitize($_POST['responsavel_nome']),
            sanitize($_POST['responsavel_cpf']),
            sanitize($_POST['responsavel_email']),
            sanitize($_POST['responsavel_telefone']),
            $_POST['contador_responsavel_id'] ?: null,
            $_POST['data_inicio_contrato'],
            $_POST['valor_mensalidade'],
            $_POST['dia_vencimento'],
            sanitize($_POST['status_contrato']),
            sanitize($_POST['observacoes'])
        ]);
        
        $clienteId = $db->lastInsertId();
        
        // Registra log
        registrarLog('clientes', $clienteId, 'create', "Cliente {$_POST['razao_social']} cadastrado");
        
        setSuccess('Cliente cadastrado com sucesso!');
        redirect('clientes.php');
        
    } catch (Exception $e) {
        setError('Erro ao cadastrar cliente: ' . $e->getMessage());
    }
}

$pageTitle = 'Novo Cliente';
$breadcrumb = '<a href="dashboard.php">Dashboard</a> / <a href="clientes.php">Clientes</a> / Novo';
include 'includes/header.php';
?>

<div class="p-6">
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h5 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                <i data-lucide="plus-circle" class="w-5 h-5"></i>
                Cadastrar Novo Cliente
            </h5>
        </div>
        <div class="p-6">
            <form method="POST" id="formCliente" class="space-y-8">
                <!-- Dados da Empresa -->
                <div>
                    <h6 class="text-base font-semibold text-gray-900 pb-2 mb-4 border-b border-gray-200 flex items-center gap-2">
                        <i data-lucide="building-2" class="w-5 h-5"></i>
                        Dados da Empresa
                    </h6>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Razão Social <span class="text-red-600">*</span></label>
                            <input type="text" name="razao_social" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nome Fantasia</label>
                            <input type="text" name="nome_fantasia" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">CNPJ <span class="text-red-600">*</span></label>
                            <input type="text" name="cnpj" id="cnpj" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Inscrição Estadual</label>
                            <input type="text" name="inscricao_estadual" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Inscrição Municipal</label>
                            <input type="text" name="inscricao_municipal" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Regime Tributário <span class="text-red-600">*</span></label>
                            <select name="regime_tributario" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                <option value="">Selecione...</option>
                                <option value="Simples Nacional">Simples Nacional</option>
                                <option value="Lucro Presumido">Lucro Presumido</option>
                                <option value="Lucro Real">Lucro Real</option>
                                <option value="MEI">MEI</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Porte da Empresa</label>
                            <select name="porte_empresa" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                <option value="">Selecione...</option>
                                <option value="MEI">MEI</option>
                                <option value="ME">Microempresa (ME)</option>
                                <option value="EPP">Empresa de Pequeno Porte (EPP)</option>
                                <option value="Médio">Médio Porte</option>
                                <option value="Grande">Grande Porte</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">CNAE Principal</label>
                            <input type="text" name="cnae_principal" placeholder="Ex: 4711301" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                    </div>
                </div>

                <!-- Endereço -->
                <div>
                    <h6 class="text-base font-semibold text-gray-900 pb-2 mb-4 border-b border-gray-200 flex items-center gap-2">
                        <i data-lucide="map-pin" class="w-5 h-5"></i>
                        Endereço
                    </h6>
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-4">
                        <div class="md:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 mb-2">CEP</label>
                            <input type="text" name="cep" id="cep" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                        <div class="md:col-span-7">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Logradouro</label>
                            <input type="text" name="logradouro" id="logradouro" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Número</label>
                        <input type="text" class="form-control" name="numero">
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Complemento</label>
                        <input type="text" class="form-control" name="complemento">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Bairro</label>
                        <input type="text" class="form-control" name="bairro" id="bairro">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Cidade</label>
                        <input type="text" class="form-control" name="cidade" id="cidade">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">UF</label>
                        <input type="text" class="form-control" name="estado" id="estado" maxlength="2">
                    </div>
                </div>

                <!-- Contato -->
                <h6 class="border-bottom pb-2 mb-3 mt-4"><i class="fas fa-phone"></i> Contato</h6>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label">Telefone</label>
                        <input type="text" class="form-control" name="telefone" id="telefone">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Celular</label>
                        <input type="text" class="form-control" name="celular" id="celular">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email">
                    </div>
                </div>

                <!-- Responsável Legal -->
                <h6 class="border-bottom pb-2 mb-3 mt-4"><i class="fas fa-user-tie"></i> Responsável Legal</h6>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Nome Completo</label>
                        <input type="text" class="form-control" name="responsavel_nome">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">CPF</label>
                        <input type="text" class="form-control" name="responsavel_cpf" id="responsavel_cpf">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="responsavel_email">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Telefone</label>
                        <input type="text" class="form-control" name="responsavel_telefone" id="responsavel_telefone">
                    </div>
                </div>

                <!-- Dados do Contrato -->
                <h6 class="border-bottom pb-2 mb-3 mt-4"><i class="fas fa-file-contract"></i> Dados do Contrato</h6>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label">Contador Responsável</label>
                        <select class="form-select" name="contador_responsavel_id">
                            <option value="">Selecione...</option>
                            <?php foreach ($contadores as $contador): ?>
                                <option value="<?php echo $contador['id']; ?>"><?php echo htmlspecialchars($contador['nome']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Data Início <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="data_inicio_contrato" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Valor Mensalidade <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="valor_mensalidade" step="0.01" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Dia Vencimento <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="dia_vencimento" min="1" max="31" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label">Status do Contrato <span class="text-danger">*</span></label>
                        <select class="form-select" name="status_contrato" required>
                            <option value="Ativo">Ativo</option>
                            <option value="Suspenso">Suspenso</option>
                            <option value="Inadimplente">Inadimplente</option>
                            <option value="Cancelado">Cancelado</option>
                        </select>
                    </div>
                    <div class="col-md-9">
                        <label class="form-label">Observações</label>
                        <textarea class="form-control" name="observacoes" rows="3"></textarea>
                    </div>
                </div>

                <!-- Botões -->
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="clientes.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Salvar Cliente
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Máscara CNPJ
document.getElementById('cnpj').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length <= 14) {
        value = value.replace(/^(\d{2})(\d)/, '$1.$2');
        value = value.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
        value = value.replace(/\.(\d{3})(\d)/, '.$1/$2');
        value = value.replace(/(\d{4})(\d)/, '$1-$2');
    }
    e.target.value = value;
});

// Máscara CPF
document.getElementById('responsavel_cpf').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length <= 11) {
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
    }
    e.target.value = value;
});

// Máscara CEP
document.getElementById('cep').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length <= 8) {
        value = value.replace(/(\d{5})(\d)/, '$1-$2');
    }
    e.target.value = value;
});

// Máscara Telefone
function mascaraTelefone(input) {
    input.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length <= 11) {
            if (value.length <= 10) {
                value = value.replace(/(\d{2})(\d)/, '($1) $2');
                value = value.replace(/(\d{4})(\d)/, '$1-$2');
            } else {
                value = value.replace(/(\d{2})(\d)/, '($1) $2');
                value = value.replace(/(\d{5})(\d)/, '$1-$2');
            }
        }
        e.target.value = value;
    });
}

mascaraTelefone(document.getElementById('telefone'));
mascaraTelefone(document.getElementById('celular'));
mascaraTelefone(document.getElementById('responsavel_telefone'));

// Busca CEP
document.getElementById('cep').addEventListener('blur', function() {
    const cep = this.value.replace(/\D/g, '');
    if (cep.length === 8) {
        fetch(`https://viacep.com.br/ws/${cep}/json/`)
            .then(response => response.json())
            .then(data => {
                if (!data.erro) {
                    document.getElementById('logradouro').value = data.logradouro;
                    document.getElementById('bairro').value = data.bairro;
                    document.getElementById('cidade').value = data.localidade;
                    document.getElementById('estado').value = data.uf;
                }
            });
    }
});
</script>

<?php include 'includes/footer.php'; ?>

