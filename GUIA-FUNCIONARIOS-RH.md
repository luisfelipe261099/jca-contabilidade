# 📋 Guia: Gestão de Funcionários e RH

## 🎯 O que mudou?

### 1. Menu "Funcionários" → "Usuários"
- O menu lateral agora mostra **"Usuários"** em vez de "Funcionários"
- Isso evita confusão entre:
  - **Usuários do sistema** (admin, funcionários da JCA, clientes)
  - **Funcionários das empresas clientes** (gerenciados no módulo RH)

---

## 👥 Como Gerenciar Funcionários das Empresas Clientes

### Passo 1: Acessar a Empresa Cliente
1. Vá em **Clientes** no menu
2. Clique em **Ver** na empresa desejada
3. Na página de detalhes, clique no botão **"Funcionários"** (azul)

### Passo 2: Cadastrar Funcionários
1. Na página de funcionários, clique em **"Novo Funcionário"**
2. Preencha os dados:
   - **Dados Pessoais**: Nome, CPF, RG, Data de Nascimento, etc.
   - **Dados Trabalhistas**: Cargo, Salário, Data de Admissão, Tipo de Contrato
   - **Contato**: Telefone, Celular, E-mail
   - **Endereço**: CEP, Logradouro, Número, Bairro, Cidade, Estado
   - **Dados Bancários**: Banco, Agência, Conta, PIX (para pagamento)
   - **Documentos**: CTPS, PIS/PASEP
3. Clique em **"Salvar"**

### Passo 3: Visualizar Funcionários
Na página de funcionários você verá:
- **Total de Funcionários**
- **Funcionários Ativos**
- **Folha Mensal** (soma dos salários base)
- **Tabela** com todos os funcionários (Nome, CPF, Cargo, Admissão, Salário, Status)

### Passo 4: Editar ou Ver Holerites
- Clique no ícone **✏️ (Editar)** para alterar dados do funcionário
- Clique no ícone **📄 (Holerites)** para ver/gerar holerites

---

## 💰 Como Gerenciar Holerites (Contracheques)

### O que são Holerites?
Holerites são os contracheques mensais dos funcionários, contendo:
- **Vencimentos**: Salário base, horas extras, comissões, bônus
- **Descontos**: INSS, IRRF, FGTS, Vale Transporte, Plano de Saúde
- **Salário Líquido**: Valor final a receber

### Como Gerar Holerites
1. Acesse a página de **Funcionários** da empresa
2. Clique no ícone **📄** ao lado do funcionário
3. Clique em **"Gerar Novo Holerite"**
4. Preencha:
   - Mês e Ano de referência
   - Vencimentos (salário base já vem preenchido)
   - Descontos (INSS, IRRF, etc.)
5. O sistema calcula automaticamente o **Salário Líquido**
6. Salve e, se necessário, faça upload do PDF do holerite

---

## 📊 Módulo RH (Recursos Humanos)

### O que o Módulo RH faz?
O módulo RH mostra:
- **Tarefas de RH** pendentes (ex: "Processar folha de Janeiro/2026")
- **Próximas tarefas** relacionadas a RH

### Como Usar
1. Vá em **RH e Folha** no menu
2. Veja as tarefas pendentes
3. Clique em **"Nova"** para criar uma tarefa de RH
4. Exemplos de tarefas:
   - "Processar folha de pagamento - Janeiro/2026"
   - "Admitir novo funcionário - João Silva"
   - "Calcular férias - Maria Santos"
   - "Gerar SEFIP - Fevereiro/2026"

---

## 📝 Módulo Fiscal - Como Adicionar Obrigações por Empresa

### O que são Obrigações Fiscais?
São entregas obrigatórias que cada empresa deve fazer (SPED, DCTF, EFD, etc.)

### Como Adicionar Obrigações
1. Vá em **Obrigações** no menu
2. Clique em **"Nova Obrigação"** (se disponível)
3. Ou crie uma **Tarefa** do tipo "Fiscal" vinculada ao cliente:
   - Vá em **Tarefas** → **Nova Tarefa**
   - Selecione o **Cliente**
   - Escolha **Tipo: Fiscal**
   - Título: "SPED Fiscal - Janeiro/2026"
   - Data de Vencimento
   - Responsável

### Exemplo de Obrigações Fiscais Comuns
- **SPED Fiscal** (mensal)
- **DCTF** (mensal)
- **EFD Contribuições** (mensal)
- **DEFIS** (anual - Simples Nacional)
- **DIRF** (anual)

---

## 📚 Módulo Contábil - Como Adicionar Tarefas por Empresa

### Como Funciona
O módulo contábil gerencia tarefas contábeis das empresas clientes.

### Como Adicionar Tarefas Contábeis
1. Vá em **Tarefas** → **Nova Tarefa**
2. Preencha:
   - **Cliente**: Selecione a empresa
   - **Tipo**: Contábil
   - **Título**: "Escrituração Contábil - Janeiro/2026"
   - **Prioridade**: Alta/Média/Baixa
   - **Data de Vencimento**
   - **Responsável**: Contador responsável
3. Clique em **"Salvar"**

### Exemplos de Tarefas Contábeis
- "Escrituração Contábil - Janeiro/2026"
- "Conciliação Bancária - Fevereiro/2026"
- "Balanço Patrimonial - 2025"
- "DRE (Demonstração do Resultado) - 4º Trimestre/2025"

---

## 🔐 Controle de Acesso

### Quem pode fazer o quê?

| Ação | Admin | Funcionário | Cliente |
|------|-------|-------------|---------|
| Ver usuários do sistema | ✅ | ✅ | ❌ |
| Cadastrar usuários | ✅ | ❌ | ❌ |
| Ver funcionários das empresas | ✅ | ✅ | ❌ |
| Cadastrar funcionários | ✅ | ✅ | ❌ |
| Gerar holerites | ✅ | ✅ | ❌ |
| Ver tarefas | ✅ | ✅ (suas) | ✅ (suas) |
| Criar tarefas | ✅ | ✅ | ❌ |

---

## 🗂️ Estrutura do Banco de Dados

### Novas Tabelas Criadas

#### `funcionarios_clientes`
Armazena os funcionários das empresas clientes com:
- Dados pessoais (nome, CPF, RG, data de nascimento)
- Endereço completo
- Dados trabalhistas (cargo, salário, data de admissão)
- Dados bancários (para pagamento)
- Documentos (CTPS, PIS/PASEP)

#### `holerites`
Armazena os holerites mensais com:
- Vencimentos (salário, horas extras, comissões)
- Descontos (INSS, IRRF, FGTS)
- Salário líquido
- Status (Pendente, Processado, Pago)

---

## 📞 Dúvidas Frequentes

**P: Qual a diferença entre "Usuários" e "Funcionários"?**
R: "Usuários" são pessoas que acessam o sistema JCA ERP (você e sua equipe). "Funcionários" são os funcionários das empresas clientes que você gerencia.

**P: Como adiciono mais usuários ao sistema?**
R: Vá em "Usuários" no menu e clique em "Novo Usuário". Apenas administradores podem fazer isso.

**P: Posso gerar holerites em PDF?**
R: Sim! Após criar o holerite, você pode fazer upload do PDF gerado ou usar um sistema externo e anexar o arquivo.

**P: Como sei quais obrigações fiscais cada empresa tem?**
R: Depende do regime tributário (Simples Nacional, Lucro Presumido, Lucro Real). Crie tarefas do tipo "Fiscal" para cada obrigação.

---

## ✅ Checklist Mensal (Exemplo)

### Para cada empresa cliente:
- [ ] Cadastrar/atualizar funcionários (se houver mudanças)
- [ ] Gerar holerites do mês
- [ ] Processar folha de pagamento
- [ ] Enviar obrigações fiscais (SPED, DCTF, etc.)
- [ ] Fazer escrituração contábil
- [ ] Conciliação bancária
- [ ] Atualizar documentos

---

**Sistema JCA ERP** - Gestão Completa para Escritórios Contábeis 🚀

