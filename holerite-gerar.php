<?php
require_once 'config/config.php';
if (!isLoggedIn()) redirect('index.php');

$usuario = getUsuarioLogado();
$db = getDB();

// Buscar funcionário se ID fornecido
$funcionarioId = isset($_GET['funcionario_id']) ? (int)$_GET['funcionario_id'] : 0;
$funcionario = null;

if ($funcionarioId > 0) {
    $stmt = $db->prepare("
        SELECT fc.*, c.razao_social, c.nome_fantasia
        FROM funcionarios_clientes fc
        INNER JOIN clientes c ON fc.cliente_id = c.id
        WHERE fc.id = ?
    ");
    $stmt->execute([$funcionarioId]);
    $funcionario = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Processar formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $funcionarioId = (int)$_POST['funcionario_id'];
    $mesReferencia = (int)$_POST['mes_referencia'];
    $anoReferencia = (int)$_POST['ano_referencia'];

    // Buscar dados do funcionário
    $stmt = $db->prepare("SELECT * FROM funcionarios_clientes WHERE id = ?");
    $stmt->execute([$funcionarioId]);
    $func = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$func) {
        $erro = "Funcionário não encontrado!";
    } else {
        // Verificar se já existe holerite para este período
        $stmt = $db->prepare("SELECT id FROM holerites WHERE funcionario_id = ? AND mes_referencia = ? AND ano_referencia = ?");
        $stmt->execute([$funcionarioId, $mesReferencia, $anoReferencia]);

        if ($stmt->rowCount() > 0) {
            $erro = "Já existe um holerite para este funcionário neste período!";
        } else {
            // Dados do formulário
            $diasTrabalhados = (int)$_POST['dias_trabalhados'];
            $horasTrabalhadas = (float)$_POST['horas_trabalhadas'];
            $faltas = (int)$_POST['faltas'];
            $horasExtras = (float)($_POST['horas_extras'] ?? 0);

            // Salário base
            $salarioBase = $func['salario_base'];

            // Calcular horas extras (50% a mais)
            $valorHoraExtra = ($salarioBase / 220) * 1.5;
            $horasExtrasValor = $horasExtras * $valorHoraExtra;

            // Adicionais
            $adicionalNoturno = (float)str_replace(['.', ','], ['', '.'], $_POST['adicional_noturno'] ?? '0');
            $adicionalInsalubridade = (float)str_replace(['.', ','], ['', '.'], $_POST['adicional_insalubridade'] ?? '0');
            $adicionalPericulosidade = (float)str_replace(['.', ','], ['', '.'], $_POST['adicional_periculosidade'] ?? '0');
            $comissoes = (float)str_replace(['.', ','], ['', '.'], $_POST['comissoes'] ?? '0');
            $bonus = (float)str_replace(['.', ','], ['', '.'], $_POST['bonus'] ?? '0');
            $dsr = (float)str_replace(['.', ','], ['', '.'], $_POST['dsr'] ?? '0');
            $outrosProventos = (float)str_replace(['.', ','], ['', '.'], $_POST['outros_proventos'] ?? '0');

            // Total de proventos
            $totalProventos = $salarioBase + $horasExtrasValor + $adicionalNoturno + $adicionalInsalubridade +
                            $adicionalPericulosidade + $comissoes + $bonus + $dsr + $outrosProventos;

            // Calcular INSS
            $inssBase = $totalProventos;
            $inssValor = 0;
            $inssAliquota = 0;

            if ($inssBase <= 1320.00) {
                $inssAliquota = 7.5;
                $inssValor = $inssBase * 0.075;
            } elseif ($inssBase <= 2571.29) {
                $inssAliquota = 9.0;
                $inssValor = $inssBase * 0.09;
            } elseif ($inssBase <= 3856.94) {
                $inssAliquota = 12.0;
                $inssValor = $inssBase * 0.12;
            } elseif ($inssBase <= 7507.49) {
                $inssAliquota = 14.0;
                $inssValor = $inssBase * 0.14;
            } else {
                $inssAliquota = 14.0;
                $inssValor = 7507.49 * 0.14; // Teto do INSS
            }

            // Calcular IRRF
            $numeroDependentes = $func['numero_dependentes'];
            $valorDependente = 189.59;
            $deducaoDependentes = $numeroDependentes * $valorDependente;

            $irrfBase = $totalProventos - $inssValor - $deducaoDependentes;
            $irrfValor = 0;
            $irrfAliquota = 0;
            $irrfDeducao = 0;

            if ($irrfBase <= 2112.00) {
                $irrfAliquota = 0;
                $irrfValor = 0;
            } elseif ($irrfBase <= 2826.65) {
                $irrfAliquota = 7.5;
                $irrfDeducao = 158.40;
                $irrfValor = ($irrfBase * 0.075) - $irrfDeducao;
            } elseif ($irrfBase <= 3751.05) {
                $irrfAliquota = 15.0;
                $irrfDeducao = 370.40;
                $irrfValor = ($irrfBase * 0.15) - $irrfDeducao;
            } elseif ($irrfBase <= 4664.68) {
                $irrfAliquota = 22.5;
                $irrfDeducao = 651.73;
                $irrfValor = ($irrfBase * 0.225) - $irrfDeducao;
            } else {
                $irrfAliquota = 27.5;
                $irrfDeducao = 884.96;
                $irrfValor = ($irrfBase * 0.275) - $irrfDeducao;
            }

            if ($irrfValor < 0) $irrfValor = 0;

            // FGTS (8% sobre o salário)
            $fgtsBase = $totalProventos;
            $fgtsValor = $fgtsBase * 0.08;

            // Outros descontos
            $valeTransporte = $func['vale_transporte'];
            $valeRefeicao = $func['vale_refeicao'];
            $planoSaude = (float)str_replace(['.', ','], ['', '.'], $_POST['plano_saude'] ?? '0');
            $planoOdontologico = (float)str_replace(['.', ','], ['', '.'], $_POST['plano_odontologico'] ?? '0');
            $adiantamento = (float)str_replace(['.', ','], ['', '.'], $_POST['adiantamento'] ?? '0');
            $pensaoAlimenticia = (float)str_replace(['.', ','], ['', '.'], $_POST['pensao_alimenticia'] ?? '0');
            $emprestimoConsignado = (float)str_replace(['.', ','], ['', '.'], $_POST['emprestimo_consignado'] ?? '0');

            // Calcular valor das faltas
            $valorDia = $salarioBase / 30;
            $faltasValor = $faltas * $valorDia;

            $outrosDescontos = (float)str_replace(['.', ','], ['', '.'], $_POST['outros_descontos'] ?? '0');

            // Total de descontos
            $totalDescontos = $inssValor + $irrfValor + $valeTransporte + $valeRefeicao + $planoSaude +
                            $planoOdontologico + $adiantamento + $pensaoAlimenticia + $emprestimoConsignado +
                            $faltasValor + $outrosDescontos;

            // Salário líquido
            $salarioLiquido = $totalProventos - $totalDescontos;

            // Inserir holerite
            $stmt = $db->prepare("
                INSERT INTO holerites (
                    funcionario_id, cliente_id, mes_referencia, ano_referencia,
                    cargo, departamento, data_admissao,
                    dias_trabalhados, horas_trabalhadas, faltas, horas_extras, horas_extras_valor,
                    salario_base, adicional_noturno, adicional_insalubridade, adicional_periculosidade,
                    comissoes, bonus, dsr, outros_proventos, total_proventos,
                    inss_base, inss_aliquota, inss_valor,
                    irrf_base, irrf_aliquota, irrf_deducao, irrf_valor,
                    fgts_base, fgts_valor,
                    vale_transporte, vale_refeicao, plano_saude, plano_odontologico,
                    adiantamento, pensao_alimenticia, emprestimo_consignado,
                    faltas_valor, outros_descontos, total_descontos,
                    salario_liquido,
                    base_calculo_fgts, fgts_mes, base_calculo_13,
                    numero_dependentes, valor_dependente,
                    status, observacoes, gerado_por_id
                ) VALUES (
                    ?, ?, ?, ?,
                    ?, ?, ?,
                    ?, ?, ?, ?, ?,
                    ?, ?, ?, ?,
                    ?, ?, ?, ?, ?,
                    ?, ?, ?,
                    ?, ?, ?, ?,
                    ?, ?,
                    ?, ?, ?, ?,
                    ?, ?, ?,
                    ?, ?, ?,
                    ?,
                    ?, ?, ?,
                    ?, ?,
                    ?, ?, ?
                )
            ");

            $stmt->execute([
                $funcionarioId, $func['cliente_id'], $mesReferencia, $anoReferencia,
                $func['cargo'], $func['departamento'], $func['data_admissao'],
                $diasTrabalhados, $horasTrabalhadas, $faltas, $horasExtras, $horasExtrasValor,
                $salarioBase, $adicionalNoturno, $adicionalInsalubridade, $adicionalPericulosidade,
                $comissoes, $bonus, $dsr, $outrosProventos, $totalProventos,
                $inssBase, $inssAliquota, $inssValor,
                $irrfBase, $irrfAliquota, $irrfDeducao, $irrfValor,
                $fgtsBase, $fgtsValor,
                $valeTransporte, $valeRefeicao, $planoSaude, $planoOdontologico,
                $adiantamento, $pensaoAlimenticia, $emprestimoConsignado,
                $faltasValor, $outrosDescontos, $totalDescontos,
                $salarioLiquido,
                $fgtsBase, $fgtsValor, $totalProventos,
                $numeroDependentes, $valorDependente,
                'Processado', $_POST['observacoes'] ?? null, $usuario['id']
            ]);

            $holeriteId = $db->lastInsertId();

            setMessage('success', 'Holerite gerado com sucesso!');
            redirect("holerite-visualizar.php?id=$holeriteId");
        }
    }
}

// Buscar todos os funcionários ativos
$stmtFuncionarios = $db->query("
    SELECT fc.id, fc.nome_completo, fc.cpf, fc.cargo, c.nome_fantasia as empresa
    FROM funcionarios_clientes fc
    INNER JOIN clientes c ON fc.cliente_id = c.id
    WHERE fc.status = 'Ativo'
    ORDER BY fc.nome_completo
");
$funcionarios = $stmtFuncionarios->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = 'Gerar Holerite';
include 'includes/header.php';
?>

<div class="min-h-screen bg-gray-50 pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <a href="holerites.php" class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="arrow-left" class="w-5 h-5"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Gerar Holerite</h1>
            </div>
            <p class="text-gray-600">Preencha os dados para gerar o holerite do funcionário</p>
        </div>

        <?php if (isset($erro)): ?>
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                <?php echo htmlspecialchars($erro); ?>
            </div>
        <?php endif; ?>

        <!-- Formulário -->
        <form method="POST" class="space-y-6">
            <!-- Seleção de Funcionário e Período -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Funcionário e Período</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Funcionário *</label>
                        <select name="funcionario_id" id="funcionario_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                onchange="carregarDadosFuncionario(this.value)">
                            <option value="">Selecione...</option>
                            <?php foreach ($funcionarios as $f): ?>
                                <option value="<?php echo $f['id']; ?>"
                                        <?php echo ($funcionarioId == $f['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($f['nome_completo']); ?> - <?php echo htmlspecialchars($f['empresa']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Mês de Referência *</label>
                        <select name="mes_referencia" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <?php
                            $meses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
                                     'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
                            $mesAtual = (int)date('n');
                            for ($i = 1; $i <= 12; $i++):
                            ?>
                                <option value="<?php echo $i; ?>" <?php echo ($i == $mesAtual) ? 'selected' : ''; ?>>
                                    <?php echo $meses[$i-1]; ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ano de Referência *</label>
                        <select name="ano_referencia" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <?php
                            $anoAtual = (int)date('Y');
                            for ($i = $anoAtual - 1; $i <= $anoAtual + 1; $i++):
                            ?>
                                <option value="<?php echo $i; ?>" <?php echo ($i == $anoAtual) ? 'selected' : ''; ?>>
                                    <?php echo $i; ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
            </div>

            <?php if ($funcionario): ?>
            <!-- Dados do Funcionário -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                <h3 class="text-sm font-semibold text-blue-900 mb-3">Dados do Funcionário</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div>
                        <span class="text-blue-700 font-medium">Cargo:</span>
                        <span class="text-blue-900 ml-2"><?php echo htmlspecialchars($funcionario['cargo']); ?></span>
                    </div>
                    <div>
                        <span class="text-blue-700 font-medium">Salário Base:</span>
                        <span class="text-blue-900 ml-2">R$ <?php echo number_format($funcionario['salario_base'], 2, ',', '.'); ?></span>
                    </div>
                    <div>
                        <span class="text-blue-700 font-medium">Admissão:</span>
                        <span class="text-blue-900 ml-2"><?php echo date('d/m/Y', strtotime($funcionario['data_admissao'])); ?></span>
                    </div>
                    <div>
                        <span class="text-blue-700 font-medium">Dependentes:</span>
                        <span class="text-blue-900 ml-2"><?php echo $funcionario['numero_dependentes']; ?></span>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Jornada de Trabalho -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Jornada de Trabalho</h2>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Dias Trabalhados</label>
                        <input type="number" name="dias_trabalhados" value="30" min="0" max="31" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Horas Trabalhadas</label>
                        <input type="number" name="horas_trabalhadas" value="220.00" step="0.01" min="0" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Faltas</label>
                        <input type="number" name="faltas" value="0" min="0" max="31"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Horas Extras</label>
                        <input type="number" name="horas_extras" value="0" step="0.01" min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Proventos (Ganhos) -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                    Proventos (Ganhos)
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Adicional Noturno</label>
                        <input type="text" name="adicional_noturno" value="0,00" class="money w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Adicional Insalubridade</label>
                        <input type="text" name="adicional_insalubridade" value="0,00" class="money w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Adicional Periculosidade</label>
                        <input type="text" name="adicional_periculosidade" value="0,00" class="money w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Comissões</label>
                        <input type="text" name="comissoes" value="0,00" class="money w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bônus</label>
                        <input type="text" name="bonus" value="0,00" class="money w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">DSR (Descanso Semanal)</label>
                        <input type="text" name="dsr" value="0,00" class="money w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Outros Proventos</label>
                        <input type="text" name="outros_proventos" value="0,00" class="money w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>
                </div>
            </div>

            <!-- Descontos -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                    Descontos
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Plano de Saúde</label>
                        <input type="text" name="plano_saude" value="0,00" class="money w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Plano Odontológico</label>
                        <input type="text" name="plano_odontologico" value="0,00" class="money w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Adiantamento</label>
                        <input type="text" name="adiantamento" value="0,00" class="money w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pensão Alimentícia</label>
                        <input type="text" name="pensao_alimenticia" value="0,00" class="money w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Empréstimo Consignado</label>
                        <input type="text" name="emprestimo_consignado" value="0,00" class="money w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Outros Descontos</label>
                        <input type="text" name="outros_descontos" value="0,00" class="money w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>
                </div>
            </div>

            <!-- Observações -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Observações</h2>
                <textarea name="observacoes" rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                          placeholder="Observações adicionais sobre este holerite..."></textarea>
            </div>

            <!-- Botões -->
            <div class="flex items-center justify-end gap-4">
                <a href="holerites.php" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors flex items-center gap-2">
                    <i data-lucide="check" class="w-4 h-4"></i>
                    Gerar Holerite
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Máscara de dinheiro
document.querySelectorAll('.money').forEach(input => {
    input.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        value = (value / 100).toFixed(2);
        value = value.replace('.', ',');
        value = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
        e.target.value = value;
    });
});

// Carregar dados do funcionário via AJAX
function carregarDadosFuncionario(id) {
    if (id) {
        window.location.href = 'holerite-gerar.php?funcionario_id=' + id;
    }
}

// Inicializar Lucide icons
lucide.createIcons();
</script>

<?php include 'includes/footer.php'; ?>
