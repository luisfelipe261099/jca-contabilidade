<?php
require_once 'config/config.php';

if (!isLoggedIn()) {
    redirect('index.php');
}

$usuario = getUsuarioLogado();
$db = getDB();
$clienteId = $_GET['id'] ?? 0;

// Busca cliente
$stmt = $db->prepare("SELECT * FROM clientes WHERE id = ?");
$stmt->execute([$clienteId]);
$cliente = $stmt->fetch();

if (!$cliente) {
    setError('Cliente não encontrado!');
    redirect('clientes.php');
}

// Busca contadores
$stmt = $db->query("SELECT id, nome FROM usuarios WHERE tipo_usuario IN ('admin', 'funcionario') AND ativo = 1 ORDER BY nome");
$contadores = $stmt->fetchAll();

// Processa o formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $db->prepare("UPDATE clientes SET
            razao_social = ?, nome_fantasia = ?, cnpj = ?, inscricao_estadual = ?, inscricao_municipal = ?,
            regime_tributario = ?, porte_empresa = ?, cnae_principal = ?, cep = ?, logradouro = ?,
            numero = ?, complemento = ?, bairro = ?, cidade = ?, estado = ?, telefone = ?, celular = ?, email = ?,
            responsavel_nome = ?, responsavel_cpf = ?, responsavel_email = ?, responsavel_telefone = ?,
            contador_responsavel_id = ?, data_inicio_contrato = ?, valor_mensalidade = ?, dia_vencimento = ?,
            status_contrato = ?, observacoes = ?
            WHERE id = ?");
        
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
            sanitize($_POST['observacoes']),
            $clienteId
        ]);
        
        registrarLog('clientes', $clienteId, 'update', "Cliente {$_POST['razao_social']} atualizado");
        
        setSuccess('Cliente atualizado com sucesso!');
        redirect('cliente-detalhes.php?id=' . $clienteId);
        
    } catch (Exception $e) {
        setError('Erro ao atualizar cliente: ' . $e->getMessage());
    }
}

$pageTitle = 'Editar Cliente';
$breadcrumb = '<a href="dashboard.php">Dashboard</a> / <a href="clientes.php">Clientes</a> / Editar';
include 'includes/header.php';
?>

<div class="content-area">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-edit"></i> Editar Cliente: <?php echo htmlspecialchars($cliente['razao_social']); ?>
            </h5>
        </div>
        <div class="card-body">
            <form method="POST">
                <!-- Dados da Empresa -->
                <h6 class="border-bottom pb-2 mb-3"><i class="fas fa-building"></i> Dados da Empresa</h6>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Razão Social <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="razao_social" value="<?php echo htmlspecialchars($cliente['razao_social']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nome Fantasia</label>
                        <input type="text" class="form-control" name="nome_fantasia" value="<?php echo htmlspecialchars($cliente['nome_fantasia']); ?>">
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">CNPJ <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="cnpj" id="cnpj" value="<?php echo htmlspecialchars($cliente['cnpj']); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Inscrição Estadual</label>
                        <input type="text" class="form-control" name="inscricao_estadual" value="<?php echo htmlspecialchars($cliente['inscricao_estadual']); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Inscrição Municipal</label>
                        <input type="text" class="form-control" name="inscricao_municipal" value="<?php echo htmlspecialchars($cliente['inscricao_municipal']); ?>">
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Regime Tributário <span class="text-danger">*</span></label>
                        <select class="form-select" name="regime_tributario" required>
                            <option value="">Selecione...</option>
                            <option value="Simples Nacional" <?php echo $cliente['regime_tributario'] === 'Simples Nacional' ? 'selected' : ''; ?>>Simples Nacional</option>
                            <option value="Lucro Presumido" <?php echo $cliente['regime_tributario'] === 'Lucro Presumido' ? 'selected' : ''; ?>>Lucro Presumido</option>
                            <option value="Lucro Real" <?php echo $cliente['regime_tributario'] === 'Lucro Real' ? 'selected' : ''; ?>>Lucro Real</option>
                            <option value="MEI" <?php echo $cliente['regime_tributario'] === 'MEI' ? 'selected' : ''; ?>>MEI</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Porte da Empresa</label>
                        <select class="form-select" name="porte_empresa">
                            <option value="">Selecione...</option>
                            <option value="MEI" <?php echo $cliente['porte_empresa'] === 'MEI' ? 'selected' : ''; ?>>MEI</option>
                            <option value="ME" <?php echo $cliente['porte_empresa'] === 'ME' ? 'selected' : ''; ?>>Microempresa (ME)</option>
                            <option value="EPP" <?php echo $cliente['porte_empresa'] === 'EPP' ? 'selected' : ''; ?>>Empresa de Pequeno Porte (EPP)</option>
                            <option value="Médio" <?php echo $cliente['porte_empresa'] === 'Médio' ? 'selected' : ''; ?>>Médio Porte</option>
                            <option value="Grande" <?php echo $cliente['porte_empresa'] === 'Grande' ? 'selected' : ''; ?>>Grande Porte</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">CNAE Principal</label>
                        <input type="text" class="form-control" name="cnae_principal" value="<?php echo htmlspecialchars($cliente['cnae_principal']); ?>">
                    </div>
                </div>

                <!-- Endereço -->
                <h6 class="border-bottom pb-2 mb-3 mt-4"><i class="fas fa-map-marker-alt"></i> Endereço</h6>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label">CEP</label>
                        <input type="text" class="form-control" name="cep" id="cep" value="<?php echo htmlspecialchars($cliente['cep']); ?>">
                    </div>
                    <div class="col-md-7">
                        <label class="form-label">Logradouro</label>
                        <input type="text" class="form-control" name="logradouro" id="logradouro" value="<?php echo htmlspecialchars($cliente['logradouro']); ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Número</label>
                        <input type="text" class="form-control" name="numero" value="<?php echo htmlspecialchars($cliente['numero']); ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Complemento</label>
                        <input type="text" class="form-control" name="complemento" value="<?php echo htmlspecialchars($cliente['complemento']); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Bairro</label>
                        <input type="text" class="form-control" name="bairro" id="bairro" value="<?php echo htmlspecialchars($cliente['bairro']); ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Cidade</label>
                        <input type="text" class="form-control" name="cidade" id="cidade" value="<?php echo htmlspecialchars($cliente['cidade']); ?>">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">UF</label>
                        <input type="text" class="form-control" name="estado" id="estado" value="<?php echo htmlspecialchars($cliente['estado']); ?>" maxlength="2">
                    </div>
                </div>

                <!-- Contato -->
                <h6 class="border-bottom pb-2 mb-3 mt-4"><i class="fas fa-phone"></i> Contato</h6>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label">Telefone</label>
                        <input type="text" class="form-control" name="telefone" id="telefone" value="<?php echo htmlspecialchars($cliente['telefone']); ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Celular</label>
                        <input type="text" class="form-control" name="celular" id="celular" value="<?php echo htmlspecialchars($cliente['celular']); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($cliente['email']); ?>">
                    </div>
                </div>

                <!-- Responsável Legal -->
                <h6 class="border-bottom pb-2 mb-3 mt-4"><i class="fas fa-user-tie"></i> Responsável Legal</h6>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Nome Completo</label>
                        <input type="text" class="form-control" name="responsavel_nome" value="<?php echo htmlspecialchars($cliente['responsavel_nome']); ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">CPF</label>
                        <input type="text" class="form-control" name="responsavel_cpf" id="responsavel_cpf" value="<?php echo htmlspecialchars($cliente['responsavel_cpf']); ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="responsavel_email" value="<?php echo htmlspecialchars($cliente['responsavel_email']); ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Telefone</label>
                        <input type="text" class="form-control" name="responsavel_telefone" id="responsavel_telefone" value="<?php echo htmlspecialchars($cliente['responsavel_telefone']); ?>">
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
                                <option value="<?php echo $contador['id']; ?>" <?php echo $cliente['contador_responsavel_id'] == $contador['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($contador['nome']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Data Início <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="data_inicio_contrato" value="<?php echo $cliente['data_inicio_contrato']; ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Valor Mensalidade <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="valor_mensalidade" step="0.01" value="<?php echo $cliente['valor_mensalidade']; ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Dia Vencimento <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="dia_vencimento" min="1" max="31" value="<?php echo $cliente['dia_vencimento']; ?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label">Status do Contrato <span class="text-danger">*</span></label>
                        <select class="form-select" name="status_contrato" required>
                            <option value="Ativo" <?php echo $cliente['status_contrato'] === 'Ativo' ? 'selected' : ''; ?>>Ativo</option>
                            <option value="Suspenso" <?php echo $cliente['status_contrato'] === 'Suspenso' ? 'selected' : ''; ?>>Suspenso</option>
                            <option value="Inadimplente" <?php echo $cliente['status_contrato'] === 'Inadimplente' ? 'selected' : ''; ?>>Inadimplente</option>
                            <option value="Cancelado" <?php echo $cliente['status_contrato'] === 'Cancelado' ? 'selected' : ''; ?>>Cancelado</option>
                        </select>
                    </div>
                    <div class="col-md-9">
                        <label class="form-label">Observações</label>
                        <textarea class="form-control" name="observacoes" rows="3"><?php echo htmlspecialchars($cliente['observacoes']); ?></textarea>
                    </div>
                </div>

                <!-- Botões -->
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="cliente-detalhes.php?id=<?php echo $clienteId; ?>" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function on(id, event, handler) {
    const el = document.getElementById(id);
    if (el) el.addEventListener(event, handler);
}

// Máscaras
on('cnpj', 'input', function(e) {
    let v = e.target.value.replace(/\D/g, '').slice(0, 14);
    v = v.replace(/^(\d{2})(\d)/, '$1.$2');
    v = v.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
    v = v.replace(/\.(\d{3})(\d)/, '.$1/$2');
    v = v.replace(/(\d{4})(\d)/, '$1-$2');
    e.target.value = v;
});

on('responsavel_cpf', 'input', function(e) {
    let v = e.target.value.replace(/\D/g, '').slice(0, 11);
    v = v.replace(/(\d{3})(\d)/, '$1.$2');
    v = v.replace(/(\d{3})\.(\d{3})(\d)/, '$1.$2.$3');
    v = v.replace(/\.(\d{3})(\d)/, '.$1-$2');
    e.target.value = v;
});

on('cep', 'input', function(e) {
    let v = e.target.value.replace(/\D/g, '').slice(0, 8);
    v = v.replace(/(\d{5})(\d)/, '$1-$2');
    e.target.value = v;
});

function mascaraTelefone(id) {
    on(id, 'input', function(e) {
        let v = e.target.value.replace(/\D/g, '').slice(0, 11);
        if (v.length <= 10) {
            v = v.replace(/(\d{2})(\d)/, '($1) $2');
            v = v.replace(/(\d{4})(\d)/, '$1-$2');
        } else {
            v = v.replace(/(\d{2})(\d)/, '($1) $2');
            v = v.replace(/(\d{5})(\d)/, '$1-$2');
        }
        e.target.value = v;
    });
}

mascaraTelefone('telefone');
mascaraTelefone('celular');
mascaraTelefone('responsavel_telefone');

// Busca CEP (ViaCEP)
on('cep', 'blur', function() {
    const cep = this.value.replace(/\D/g, '');
    if (cep.length !== 8) return;
    fetch(`https://viacep.com.br/ws/${cep}/json/`)
        .then(r => r.json())
        .then(d => {
            if (d.erro) return;
            const setVal = (id, val) => {
                const el = document.getElementById(id);
                if (el && !el.value) el.value = val || '';
            };
            setVal('logradouro', d.logradouro);
            setVal('bairro', d.bairro);
            setVal('cidade', d.localidade);
            setVal('estado', d.uf);
        })
        .catch(() => {});
});
</script>

<?php include 'includes/footer.php'; ?>

