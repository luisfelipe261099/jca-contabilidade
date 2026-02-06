<?php
require_once 'config/config.php';

if (!isLoggedIn()) {
    redirect('index.php');
}

$usuario = getUsuarioLogado();
$db = getDB();
$clienteId = $_GET['id'] ?? 0;

// Busca cliente com contador
$stmt = $db->prepare("SELECT c.*, u.nome as contador_nome 
                      FROM clientes c
                      LEFT JOIN usuarios u ON c.contador_responsavel_id = u.id
                      WHERE c.id = ?");
$stmt->execute([$clienteId]);
$cliente = $stmt->fetch();

if (!$cliente) {
    setError('Cliente não encontrado!');
    redirect('clientes.php');
}

// Busca serviços contratados
$stmt = $db->prepare("SELECT s.* FROM servicos s
                      INNER JOIN cliente_servicos cs ON s.id = cs.servico_id
                      WHERE cs.cliente_id = ? AND cs.ativo = 1");
$stmt->execute([$clienteId]);
$servicos = $stmt->fetchAll();

// Busca obrigações pendentes
$stmt = $db->prepare("SELECT co.*, of.nome as obrigacao_nome, of.periodicidade
                      FROM cliente_obrigacoes co
                      INNER JOIN obrigacoes_fiscais of ON co.obrigacao_id = of.id
                      WHERE co.cliente_id = ? AND co.status != 'Concluída'
                      ORDER BY co.data_vencimento ASC
                      LIMIT 5");
$stmt->execute([$clienteId]);
$obrigacoes = $stmt->fetchAll();

// Busca tarefas recentes
$stmt = $db->prepare("SELECT t.*, u.nome as responsavel_nome
                      FROM tarefas t
                      LEFT JOIN usuarios u ON t.responsavel_id = u.id
                      WHERE t.cliente_id = ?
                      ORDER BY t.data_criacao DESC
                      LIMIT 5");
$stmt->execute([$clienteId]);
$tarefas = $stmt->fetchAll();

// Busca documentos recentes
$stmt = $db->prepare("SELECT * FROM documentos
                      WHERE cliente_id = ?
                      ORDER BY data_upload DESC
                      LIMIT 5");
$stmt->execute([$clienteId]);
$documentos = $stmt->fetchAll();

$pageTitle = $cliente['razao_social'];
$breadcrumb = '<a href="dashboard.php">Dashboard</a> / <a href="clientes.php">Clientes</a> / Detalhes';
include 'includes/header.php';
?>

<div class="p-6 space-y-6">
    <!-- Cabeçalho do Cliente -->
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <div class="flex items-start justify-between">
            <div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2"><?php echo htmlspecialchars($cliente['razao_social']); ?></h3>
                <?php if ($cliente['nome_fantasia']): ?>
                    <p class="text-gray-600 mb-3"><?php echo htmlspecialchars($cliente['nome_fantasia']); ?></p>
                <?php endif; ?>
                <div class="flex flex-wrap gap-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                        <?php echo $cliente['regime_tributario']; ?>
                    </span>
                    <?php
                    $statusConfig = [
                        'Ativo' => 'bg-green-100 text-green-700',
                        'Suspenso' => 'bg-amber-100 text-amber-700',
                        'Inadimplente' => 'bg-red-100 text-red-700',
                        'Cancelado' => 'bg-gray-100 text-gray-700'
                    ];
                    ?>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $statusConfig[$cliente['status_contrato']] ?? 'bg-gray-100 text-gray-700'; ?>">
                        <?php echo $cliente['status_contrato']; ?>
                    </span>
                    <?php if ($cliente['porte_empresa']): ?>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                            <?php echo $cliente['porte_empresa']; ?>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="cliente-centro-controle.php?id=<?php echo $clienteId; ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-lg hover:from-primary-700 hover:to-primary-800 transition-all shadow-md hover:shadow-lg font-medium">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    Centro de Controle
                </a>
                <a href="cliente-funcionarios.php?id=<?php echo $clienteId; ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i data-lucide="users" class="w-4 h-4"></i>
                    Funcionários
                </a>
                <a href="cliente-editar.php?id=<?php echo $clienteId; ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors">
                    <i data-lucide="edit" class="w-4 h-4"></i>
                    Editar
                </a>
                <a href="clientes.php" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Voltar
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Coluna Esquerda -->
        <div class="space-y-6">
            <!-- Dados Cadastrais -->
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center gap-2">
                    <i data-lucide="building-2" class="w-5 h-5 text-gray-600"></i>
                    <h6 class="text-lg font-semibold text-gray-900">Dados Cadastrais</h6>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">CNPJ</p>
                        <p class="font-semibold text-gray-900"><?php echo formatarCNPJ($cliente['cnpj']); ?></p>
                    </div>
                    <?php if ($cliente['inscricao_estadual']): ?>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Inscrição Estadual</p>
                        <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($cliente['inscricao_estadual']); ?></p>
                    </div>
                    <?php endif; ?>
                    <?php if ($cliente['inscricao_municipal']): ?>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Inscrição Municipal</p>
                        <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($cliente['inscricao_municipal']); ?></p>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($cliente['cnae_principal'])): ?>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">CNAE Principal</p>
                        <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($cliente['cnae_principal']); ?></p>
                    </div>
                    <?php endif; ?>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Cliente desde</p>
                        <p class="font-semibold text-gray-900"><?php echo formatarData($cliente['data_inicio_contrato']); ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Endereço -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0"><i class="fas fa-map-marker-alt"></i> Endereço</h6>
                </div>
                <div class="card-body">
                    <?php if ($cliente['logradouro']): ?>
                        <p class="mb-1"><?php echo htmlspecialchars($cliente['logradouro']); ?>, <?php echo htmlspecialchars($cliente['numero']); ?></p>
                        <?php if ($cliente['complemento']): ?>
                            <p class="mb-1"><?php echo htmlspecialchars($cliente['complemento']); ?></p>
                        <?php endif; ?>
                        <p class="mb-1"><?php echo htmlspecialchars($cliente['bairro']); ?></p>
                        <p class="mb-1"><?php echo htmlspecialchars($cliente['cidade']); ?> - <?php echo htmlspecialchars($cliente['estado']); ?></p>
                        <?php if ($cliente['cep']): ?>
                            <p class="mb-0">CEP: <?php echo htmlspecialchars($cliente['cep']); ?></p>
                        <?php endif; ?>
                    <?php else: ?>
                        <p class="text-muted mb-0">Endereço não cadastrado</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Contato -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0"><i class="fas fa-phone"></i> Contato</h6>
                </div>
                <div class="card-body">
                    <?php if ($cliente['telefone']): ?>
                    <div class="mb-2">
                        <small class="text-muted d-block">Telefone</small>
                        <strong><?php echo formatarTelefone($cliente['telefone']); ?></strong>
                    </div>
                    <?php endif; ?>
                    <?php if ($cliente['celular']): ?>
                    <div class="mb-2">
                        <small class="text-muted d-block">Celular</small>
                        <strong><?php echo formatarTelefone($cliente['celular']); ?></strong>
                    </div>
                    <?php endif; ?>
                    <?php if ($cliente['email']): ?>
                    <div class="mb-0">
                        <small class="text-muted d-block">Email</small>
                        <strong><a href="mailto:<?php echo $cliente['email']; ?>"><?php echo htmlspecialchars($cliente['email']); ?></a></strong>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Responsável Legal -->
            <?php if ($cliente['responsavel_nome']): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0"><i class="fas fa-user-tie"></i> Responsável Legal</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <small class="text-muted d-block">Nome</small>
                        <strong><?php echo htmlspecialchars($cliente['responsavel_nome']); ?></strong>
                    </div>
                    <?php if ($cliente['responsavel_cpf']): ?>
                    <div class="mb-2">
                        <small class="text-muted d-block">CPF</small>
                        <strong><?php echo formatarCPF($cliente['responsavel_cpf']); ?></strong>
                    </div>
                    <?php endif; ?>
                    <?php if ($cliente['responsavel_email']): ?>
                    <div class="mb-2">
                        <small class="text-muted d-block">Email</small>
                        <strong><a href="mailto:<?php echo $cliente['responsavel_email']; ?>"><?php echo htmlspecialchars($cliente['responsavel_email']); ?></a></strong>
                    </div>
                    <?php endif; ?>
                    <?php if ($cliente['responsavel_telefone']): ?>
                    <div class="mb-0">
                        <small class="text-muted d-block">Telefone</small>
                        <strong><?php echo formatarTelefone($cliente['responsavel_telefone']); ?></strong>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Dados do Contrato -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0"><i class="fas fa-file-contract"></i> Contrato</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <small class="text-muted d-block">Contador Responsável</small>
                        <strong><?php echo $cliente['contador_nome'] ? htmlspecialchars($cliente['contador_nome']) : 'Não atribuído'; ?></strong>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted d-block">Valor Mensalidade</small>
                        <strong><?php echo formatarMoeda($cliente['valor_mensalidade']); ?></strong>
                    </div>
                    <div class="mb-0">
                        <small class="text-muted d-block">Vencimento</small>
                        <strong>Dia <?php echo $cliente['dia_vencimento']; ?></strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Coluna Direita -->
        <div class="col-md-8">
            <!-- Serviços Contratados -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0"><i class="fas fa-briefcase"></i> Serviços Contratados</h6>
                    <a href="cliente-servicos.php?cliente_id=<?php echo $clienteId; ?>" class="btn btn-sm btn-primary"><i class="fas fa-cog"></i> Gerenciar</a>
                </div>
                <div class="card-body">
                    <?php if (!empty($servicos)): ?>
                        <div class="row">
                            <?php foreach ($servicos as $servico): ?>
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="fas <?php echo $servico['icone']; ?> fa-2x" style="color: <?php echo $servico['cor']; ?>"></i>
                                        </div>
                                        <div>
                                            <strong><?php echo htmlspecialchars($servico['nome']); ?></strong>
                                            <br><small class="text-muted"><?php echo htmlspecialchars($servico['categoria']); ?></small>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0">Nenhum serviço contratado</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Obrigações Pendentes -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0"><i class="fas fa-calendar-check"></i> Obrigações Pendentes</h6>
                    <a href="obrigacoes.php?cliente_id=<?php echo $clienteId; ?>" class="btn btn-sm btn-primary">Ver Todas</a>
                </div>
                <div class="card-body p-0">
                    <?php if (!empty($obrigacoes)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Obrigação</th>
                                        <th>Vencimento</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($obrigacoes as $obrigacao): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo htmlspecialchars($obrigacao['obrigacao_nome']); ?></strong>
                                                <br><small class="text-muted"><?php echo $obrigacao['periodicidade']; ?></small>
                                            </td>
                                            <td><?php echo formatarData($obrigacao['data_vencimento']); ?></td>
                                            <td>
                                                <?php
                                                $statusClass = [
                                                    'Pendente' => 'warning',
                                                    'Em Andamento' => 'info',
                                                    'Atrasada' => 'danger'
                                                ];
                                                ?>
                                                <span class="badge bg-<?php echo $statusClass[$obrigacao['status']] ?? 'secondary'; ?>">
                                                    <?php echo $obrigacao['status']; ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted p-3 mb-0">Nenhuma obrigação pendente</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Tarefas Recentes -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0"><i class="fas fa-tasks"></i> Tarefas Recentes</h6>
                    <a href="tarefa-nova.php?cliente_id=<?php echo $clienteId; ?>" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Nova Tarefa</a>
                </div>
                <div class="card-body p-0">
                    <?php if (!empty($tarefas)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tarefa</th>
                                        <th>Responsável</th>
                                        <th>Prioridade</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tarefas as $tarefa): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo htmlspecialchars($tarefa['titulo']); ?></strong>
                                                <br><small class="text-muted"><?php echo $tarefa['tipo']; ?></small>
                                            </td>
                                            <td><?php echo $tarefa['responsavel_nome'] ? htmlspecialchars($tarefa['responsavel_nome']) : '-'; ?></td>
                                            <td>
                                                <?php
                                                $prioridadeClass = [
                                                    'Baixa' => 'secondary',
                                                    'Média' => 'info',
                                                    'Alta' => 'warning',
                                                    'Urgente' => 'danger'
                                                ];
                                                ?>
                                                <span class="badge bg-<?php echo $prioridadeClass[$tarefa['prioridade']] ?? 'secondary'; ?>">
                                                    <?php echo $tarefa['prioridade']; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php
                                                $statusClass = [
                                                    'Pendente' => 'warning',
                                                    'Em Andamento' => 'info',
                                                    'Concluída' => 'success'
                                                ];
                                                ?>
                                                <span class="badge bg-<?php echo $statusClass[$tarefa['status']] ?? 'secondary'; ?>">
                                                    <?php echo $tarefa['status']; ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted p-3 mb-0">Nenhuma tarefa cadastrada</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Documentos Recentes -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0"><i class="fas fa-file-alt"></i> Documentos Recentes</h6>
                    <a href="documento-upload.php?cliente_id=<?php echo $clienteId; ?>" class="btn btn-sm btn-primary"><i class="fas fa-upload"></i> Upload</a>
                </div>
                <div class="card-body p-0">
                    <?php if (!empty($documentos)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Documento</th>
                                        <th>Tipo</th>
                                        <th>Data</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($documentos as $doc): ?>
                                        <tr>
                                            <td>
                                                <i class="fas fa-file-pdf text-danger me-2"></i>
                                                <strong><?php echo htmlspecialchars($doc['nome_arquivo']); ?></strong>
                                            </td>
                                            <td><?php echo htmlspecialchars($doc['tipo_documento']); ?></td>
                                            <td><?php echo formatarData($doc['data_upload']); ?></td>
                                            <td>
                                                <a href="documento-download.php?id=<?php echo $doc['id']; ?>" class="btn btn-sm btn-info" title="Download">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted p-3 mb-0">Nenhum documento enviado</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Observações -->
            <?php if ($cliente['observacoes']): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0"><i class="fas fa-sticky-note"></i> Observações</h6>
                </div>
                <div class="card-body">
                    <p class="mb-0"><?php echo nl2br(htmlspecialchars($cliente['observacoes'])); ?></p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

