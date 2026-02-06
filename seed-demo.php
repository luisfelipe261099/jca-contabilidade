<?php
require_once 'config/config.php';

$isCli = (php_sapi_name() === 'cli');
if (!$isCli) {
    if (!isLoggedIn()) redirect('index.php');
    if (!isAdmin()) { setError('Acesso negado.'); redirect('dashboard.php'); }
}

$db = getDB();

function upsertUsuario(PDO $db, $nome, $email, $tipo, $setorId, $senhaPlano) {
    $stmt = $db->prepare("SELECT id FROM usuarios WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $id = $stmt->fetchColumn();
    if ($id) return (int)$id;
    $hash = password_hash($senhaPlano, PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT INTO usuarios (nome,email,senha,tipo_usuario,setor_id,ativo) VALUES (?,?,?,?,?,1)");
    $stmt->execute([$nome,$email,$hash,$tipo,$setorId]);
    return (int)$db->lastInsertId();
}

function upsertCliente(PDO $db, array $c) {
    $stmt = $db->prepare("SELECT id FROM clientes WHERE cnpj = ? LIMIT 1");
    $stmt->execute([$c['cnpj']]);
    $id = $stmt->fetchColumn();
    if ($id) return (int)$id;
    $cols = array_keys($c);
    $sql = "INSERT INTO clientes (".implode(',',$cols).") VALUES (".implode(',', array_fill(0,count($cols),'?')).")";
    $stmt = $db->prepare($sql);
    $stmt->execute(array_values($c));
    return (int)$db->lastInsertId();
}

function idByNome(PDO $db, $table, $nomeField, $nome) {
    $stmt = $db->prepare("SELECT id FROM {$table} WHERE {$nomeField} = ? LIMIT 1");
    $stmt->execute([$nome]);
    return (int)$stmt->fetchColumn();
}

if ($isCli || (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST')) {
    $db->beginTransaction();
    try {
        $adminId = idByNome($db,'usuarios','email','admin@jcacontabilidade.com.br');
        if (!$adminId) throw new Exception('Usuário admin não encontrado. Execute install.php');

        $anaId   = upsertUsuario($db,'Ana Silva','ana@jcacontabilidade.com.br','funcionario',1,'123456');
        $brunoId = upsertUsuario($db,'Bruno Pereira','bruno@jcacontabilidade.com.br','funcionario',2,'123456');
        $carlaId = upsertUsuario($db,'Carla Souza','carla@jcacontabilidade.com.br','funcionario',3,'123456');
        $diegoId = upsertUsuario($db,'Diego Lima','diego@jcacontabilidade.com.br','funcionario',5,'123456');

        $c1 = upsertCliente($db,[
            'razao_social'=>'Alfa Comércio e Serviços LTDA','nome_fantasia'=>'AlfaTech','cnpj'=>'11.222.333/0001-44',
            'regime_tributario'=>'Simples Nacional','porte_empresa'=>'EPP','cnae_principal'=>'6201501','data_abertura'=>'2018-03-12',
            'cep'=>'80010-000','logradouro'=>'Rua XV de Novembro','numero'=>'100','bairro'=>'Centro','cidade'=>'Curitiba','estado'=>'PR',
            'telefone'=>'(41) 3000-1000','celular'=>'(41) 98888-1000','email'=>'financeiro@alfatech.com.br','site'=>'https://alfatech.exemplo',
            'responsavel_nome'=>'Marcos Almeida','responsavel_cpf'=>'123.456.789-09','responsavel_email'=>'marcos@alfatech.com.br','responsavel_telefone'=>'(41) 99999-1111',
            'data_inicio_contrato'=>date('Y-m-01'),'valor_mensalidade'=>850.00,'dia_vencimento'=>10,'status_contrato'=>'Ativo',
            'contador_responsavel_id'=>$anaId,'observacoes'=>'Cliente DEMO (fictício).'
        ]);

        $c2 = upsertCliente($db,[
            'razao_social'=>'Beatriz Doces MEI','nome_fantasia'=>'Doces da Bia','cnpj'=>'55.666.777/0001-88',
            'regime_tributario'=>'MEI','porte_empresa'=>'MEI','cnae_principal'=>'4721102','data_abertura'=>'2023-06-01',
            'cep'=>'81000-000','logradouro'=>'Av. Brasil','numero'=>'500','bairro'=>'Água Verde','cidade'=>'Curitiba','estado'=>'PR',
            'telefone'=>'(41) 3200-5000','celular'=>'(41) 97777-5000','email'=>'bia@docesdabia.com.br','site'=>NULL,
            'responsavel_nome'=>'Beatriz Fernandes','responsavel_cpf'=>'987.654.321-00','responsavel_email'=>'bia@docesdabia.com.br','responsavel_telefone'=>'(41) 97777-5000',
            'data_inicio_contrato'=>date('Y-m-01'),'valor_mensalidade'=>180.00,'dia_vencimento'=>5,'status_contrato'=>'Ativo',
            'contador_responsavel_id'=>$diegoId,'observacoes'=>'Cliente DEMO (fictício).'
        ]);

        $servC1 = ['Assessoria Contábil','Folha de Pagamento','Planejamento Tributário','Malha Fiscal','Abertura de Empresa'];
        $servC2 = ['Contabilidade MEI','Imposto de Renda','Assessoria em RH','Fechamento de Empresa'];
        foreach ([[$c1,$servC1,850],[$c2,$servC2,180]] as $pack) {
            [$cid,$lista,$base] = $pack;
            foreach ($lista as $nm) {
                $sid = idByNome($db,'servicos','nome',$nm);
                $stmt = $db->prepare("SELECT id FROM cliente_servicos WHERE cliente_id=? AND servico_id=? AND ativo=1");
                $stmt->execute([$cid,$sid]);
                if (!$stmt->fetchColumn()) {
                    $stmt = $db->prepare("INSERT INTO cliente_servicos (cliente_id,servico_id,valor_cobrado,data_inicio,ativo) VALUES (?,?,?,?,1)");
                    $stmt->execute([$cid,$sid,$base,date('Y-m-01')]);
                }
            }
        }

        $mes = (int)date('n'); $ano = (int)date('Y');
        $obNomes = ['DAS - Simples Nacional','DCTF','eSocial','SPED Contábil','DEFIS'];
        foreach ([$c1,$c2] as $cid) {
            foreach ($obNomes as $i=>$nm) {
                $oid = idByNome($db,'obrigacoes_fiscais','nome',$nm);
                $stmt = $db->prepare("SELECT id FROM cliente_obrigacoes WHERE cliente_id=? AND obrigacao_id=? AND mes_referencia=? AND ano_referencia=?");
                $stmt->execute([$cid,$oid,$mes,$ano]);
                if ($stmt->fetchColumn()) continue;
                $venc = date('Y-m-d', strtotime('+' . (5+$i) . ' days'));
                $status = ($i===0 ? 'Pendente' : ($i===1 ? 'Em Andamento' : 'Atrasada'));
                $resp = ($cid===$c1 ? $brunoId : $carlaId);
                $stmt = $db->prepare("INSERT INTO cliente_obrigacoes (cliente_id,obrigacao_id,mes_referencia,ano_referencia,data_vencimento,status,responsavel_id,observacoes) VALUES (?,?,?,?,?,?,?,?)");
                $stmt->execute([$cid,$oid,$mes,$ano,$venc,$status,$resp,'Demo - obrigação automática']);
            }
        }

        $tarefas = [
            [$c1,'Conferir balancete','Contábil','Alta',$anaId,'+3 days'],
            [$c1,'Revisar apuração DAS','Fiscal','Urgente',$brunoId,'+2 days'],
            [$c2,'Folha / Pró-labore (MEI)','RH','Média',$carlaId,'+7 days'],
            [$c2,'Atualizar cadastro e contrato','Administrativo','Baixa',$diegoId,'+10 days'],
        ];
        foreach ($tarefas as $t) {
            [$cid,$titulo,$tipo,$prio,$resp,$when] = $t;
            $stmt = $db->prepare("SELECT id FROM tarefas WHERE cliente_id=? AND titulo=? LIMIT 1");
            $stmt->execute([$cid,$titulo]);
            if ($stmt->fetchColumn()) continue;
            $stmt = $db->prepare("INSERT INTO tarefas (titulo,descricao,cliente_id,tipo,prioridade,status,criado_por_id,responsavel_id,data_vencimento,tags) VALUES (?,?,?,?,?, 'Pendente',?,?,?,?)");
            $stmt->execute([$titulo,'Tarefa DEMO (fictícia).',$cid,$tipo,$prio,$adminId,$resp,date('Y-m-d',strtotime($when)),'demo,erp']);
        }

        foreach ([$c1,$c2] as $cid) {
            $docs = ['Contrato.pdf'=>'uploads/documentos/demo/contrato.pdf','CNPJ.pdf'=>'uploads/documentos/demo/cnpj.pdf'];
            foreach ($docs as $nome=>$path) {
                $stmt = $db->prepare("SELECT id FROM documentos WHERE cliente_id=? AND nome_arquivo=? LIMIT 1");
                $stmt->execute([$cid,$nome]);
                if ($stmt->fetchColumn()) continue;
                $stmt = $db->prepare("INSERT INTO documentos (cliente_id,tipo_documento,nome_arquivo,caminho_arquivo,mes_referencia,ano_referencia,enviado_por_id,descricao,tags) VALUES (?,?,?,?,?,?,?,?,?)");
                $stmt->execute([$cid,'Outros',$nome,$path,$mes,$ano,$adminId,'Documento DEMO','demo']);
            }
        }

        $alerts = [
            ['Vencimento','DAS próximo do vencimento','Verifique os vencimentos do mês.','Aviso',$c1,$adminId,'obrigacoes.php'],
            ['Atraso','Obrigações em atraso','Existem obrigações com status Atrasada.','Urgente',$c2,$adminId,'obrigacoes.php'],
        ];
        foreach ($alerts as $a) {
            [$tipo,$titulo,$msg,$nivel,$cid,$uid,$link] = $a;
            $stmt = $db->prepare("SELECT id FROM alertas WHERE usuario_id=? AND titulo=? LIMIT 1");
            $stmt->execute([$uid,$titulo]);
            if ($stmt->fetchColumn()) continue;
            $stmt = $db->prepare("INSERT INTO alertas (tipo,titulo,mensagem,nivel,cliente_id,usuario_id,link_relacionado,lido) VALUES (?,?,?,?,?,?,?,0)");
            $stmt->execute([$tipo,$titulo,$msg,$nivel,$cid,$uid,$link]);
        }

        foreach ([$c1=>850.00,$c2=>180.00] as $cid=>$valor) {
            $stmt = $db->prepare("SELECT id FROM financeiro WHERE cliente_id=? AND mes_referencia=? AND ano_referencia=? AND descricao='Mensalidade' LIMIT 1");
            $stmt->execute([$cid,$mes,$ano]);
            if (!$stmt->fetchColumn()) {
                $venc = date('Y-m-d', strtotime('+5 days'));
                $stmt = $db->prepare("INSERT INTO financeiro (cliente_id,tipo,descricao,valor,mes_referencia,ano_referencia,data_vencimento,status) VALUES (?,?,?,?,?,?,?,?)");
                $stmt->execute([$cid,'Receita','Mensalidade',$valor,$mes,$ano,$venc,'Pendente']);
            }
        }

        $db->commit();
        if ($isCli) { echo "Seed DEMO concluído.\n"; exit(0); }
        setSuccess('Dados DEMO inseridos/atualizados com sucesso.');
        redirect('dashboard.php');
    } catch (Throwable $e) {
        $db->rollBack();
        if ($isCli) { fwrite(STDERR, $e->getMessage()."\n"); exit(1); }
        setError('Erro ao inserir DEMO: ' . $e->getMessage());
    }
}

$pageTitle = 'Inserir Dados DEMO';
$breadcrumb = '<a href="dashboard.php">Dashboard</a> / Dados DEMO';
$usuario = getUsuarioLogado();
include 'includes/header.php';
?>

<div class="content-area">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Criar 2 clientes fictícios + dados em todas as áreas</h5>
      <p class="text-muted mb-3">Isso vai inserir: clientes, serviços contratados, obrigações, tarefas, documentos, alertas e financeiro.</p>
      <form method="post">
        <button class="btn btn-primary" type="submit"><i class="fas fa-database"></i> Inserir/Atualizar Dados DEMO</button>
      </form>
      <hr>
      <small class="text-muted">Usuários demo criados (senha: <strong>123456</strong>): ana@, bruno@, carla@, diego@</small>
    </div>
  </div>
</div>

<?php include 'includes/footer.php';

