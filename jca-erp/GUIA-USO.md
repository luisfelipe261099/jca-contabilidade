# 📖 GUIA DE USO - JCA ERP

## 🚀 Primeiros Passos

### 1. Instalação

#### Opção A: Instalador Automático (Recomendado)
1. Acesse: `http://localhost/jd/jca-erp/install.php`
2. Clique em "Iniciar Instalação"
3. Aguarde a conclusão
4. Acesse o sistema com as credenciais fornecidas

#### Opção B: Manual
1. Importe o arquivo `database/schema.sql` no phpMyAdmin
2. Configure `config/database.php` com suas credenciais
3. Crie a pasta `uploads/` com permissões 755
4. Acesse `http://localhost/jd/jca-erp`

### 2. Primeiro Acesso

**Credenciais Padrão:**
- Email: `admin@jcacontabilidade.com.br`
- Senha: `admin123`

⚠️ **IMPORTANTE**: Altere a senha imediatamente após o primeiro acesso!

---

## 📊 Dashboard

O Dashboard é a tela inicial do sistema e apresenta:

### KPIs Principais:
- **Total de Clientes**: Quantidade total de empresas cadastradas
- **Obrigações Este Mês**: Obrigações fiscais pendentes no mês atual
- **Tarefas Abertas**: Tarefas em andamento
- **Alertas Novos**: Notificações não lidas

### Seções:
- **Obrigações Próximas**: Lista de obrigações com vencimento nos próximos 7 dias
- **Tarefas Recentes**: Últimas tarefas criadas ou atualizadas

---

## 👥 Gestão de Clientes

### Cadastrar Novo Cliente

1. Acesse **Clientes** no menu lateral
2. Clique em **"+ Novo Cliente"**
3. Preencha os dados:
   - **Dados da Empresa**: Razão social, CNPJ, regime tributário
   - **Endereço**: CEP, logradouro, cidade, estado
   - **Contato**: Telefone, email, responsável
   - **Contrato**: Data início, valor mensalidade, dia vencimento
4. Clique em **"Salvar Cliente"**

### Buscar e Filtrar Clientes

**Filtros Disponíveis:**
- **Busca**: Por razão social, nome fantasia ou CNPJ
- **Regime Tributário**: Simples Nacional, Lucro Presumido, Lucro Real, MEI
- **Status do Contrato**: Ativo, Suspenso, Inadimplente, Cancelado

**Como usar:**
1. Digite o termo de busca
2. Selecione os filtros desejados
3. Clique em **"Filtrar"**

### Visualizar Detalhes do Cliente

1. Na lista de clientes, clique no ícone **👁️ (olho)**
2. Você verá:
   - Dados cadastrais completos
   - Serviços contratados
   - Obrigações fiscais
   - Documentos enviados
   - Histórico de tarefas
   - Situação financeira

### Editar Cliente

1. Na lista, clique no ícone **✏️ (lápis)**
2. Altere os dados necessários
3. Clique em **"Salvar Alterações"**

---

## 📋 Sistema de Obrigações Fiscais

### Obrigações Cadastradas Automaticamente:

- **DAS** - Simples Nacional (dia 20)
- **DCTF** - Declaração de Débitos (dia 15)
- **EFD-Contribuições** - Escrituração Fiscal (dia 10)
- **SPED Contábil** - Anual (31/05)
- **SPED Fiscal** - Mensal (dia 20)
- **eSocial** - Mensal (dia 7)
- **DIRF** - Anual (28/02)
- **RAIS** - Anual (31/03)
- **DEFIS** - Anual (31/03)

### Gerenciar Obrigações

1. Acesse **Obrigações** no menu
2. Visualize o calendário mensal
3. Filtre por:
   - Cliente
   - Tipo de obrigação
   - Status (Pendente, Em Andamento, Concluída, Atrasada)
   - Período

### Marcar Obrigação como Concluída

1. Clique na obrigação
2. Altere o status para **"Concluída"**
3. Adicione observações se necessário
4. Clique em **"Salvar"**

---

## ✅ Sistema de Tarefas

### Criar Nova Tarefa

1. Acesse **Tarefas** no menu
2. Clique em **"+ Nova Tarefa"**
3. Preencha:
   - **Título**: Descrição curta
   - **Descrição**: Detalhes da tarefa
   - **Cliente**: Selecione o cliente (opcional)
   - **Tipo**: Contábil, Fiscal, RH, etc.
   - **Prioridade**: Baixa, Média, Alta, Urgente
   - **Responsável**: Atribua a um funcionário
   - **Data de Vencimento**: Prazo para conclusão
4. Clique em **"Criar Tarefa"**

### Acompanhar Tarefas

**Visualizações:**
- **Minhas Tarefas**: Tarefas atribuídas a você
- **Todas as Tarefas**: Visão geral (apenas admin/funcionários)
- **Por Cliente**: Tarefas de um cliente específico
- **Por Status**: Pendente, Em Andamento, Concluída

**Filtros:**
- Prioridade
- Data de vencimento
- Responsável
- Cliente

---

## 📁 Gestão de Documentos

### Upload de Documentos

1. Acesse **Documentos** no menu
2. Clique em **"+ Enviar Documento"**
3. Selecione:
   - **Cliente**: Empresa relacionada
   - **Tipo**: Contrato, Certidão, Balanço, DRE, Folha, Guia, etc.
   - **Arquivo**: Selecione o arquivo (PDF, DOC, XLS, JPG, PNG, ZIP)
   - **Mês/Ano de Referência**: Para organização
   - **Descrição**: Informações adicionais
4. Clique em **"Enviar"**

### Buscar Documentos

**Filtros:**
- Cliente
- Tipo de documento
- Período (mês/ano)
- Palavra-chave

### Baixar Documento

1. Localize o documento na lista
2. Clique no ícone **⬇️ (download)**

---

## 🔔 Sistema de Alertas

### Tipos de Alertas Automáticos:

1. **Vencimento**: Obrigações próximas do vencimento (7 dias)
2. **Atraso**: Obrigações ou tarefas atrasadas
3. **Pendência**: Documentos ou informações faltantes
4. **Financeiro**: Mensalidades vencidas
5. **Sistema**: Atualizações e manutenções

### Gerenciar Alertas

1. Acesse **Alertas** no menu ou clique no ícone 🔔 no topo
2. Visualize alertas não lidos (destacados)
3. Clique em um alerta para:
   - Ver detalhes
   - Marcar como lido
   - Acessar o item relacionado

---

## 💰 Módulo Financeiro

### Controle de Mensalidades

**Geração Automática:**
- O sistema gera automaticamente as mensalidades com base no:
  - Valor do contrato
  - Dia de vencimento configurado
  - Status do cliente

### Registrar Pagamento

1. Acesse **Financeiro** no menu
2. Localize a mensalidade
3. Clique em **"Registrar Pagamento"**
4. Informe:
   - Data do pagamento
   - Forma de pagamento
   - Observações
5. Clique em **"Salvar"**

### Relatórios Financeiros

- **Receitas do Mês**: Total recebido
- **Inadimplência**: Clientes em atraso
- **Previsão de Receita**: Mensalidades a receber
- **Histórico**: Movimentações anteriores

---

## 👨‍💼 Gestão de Funcionários

### Cadastrar Funcionário

1. Acesse **Funcionários** (apenas admin)
2. Clique em **"+ Novo Funcionário"**
3. Preencha:
   - Nome completo
   - Email (será o login)
   - Senha inicial
   - Setor
   - Telefone
4. Clique em **"Cadastrar"**

### Setores Disponíveis:

- **Contábil**: Escrituração, balancetes
- **Fiscal**: Obrigações, SPED
- **RH**: Folha de pagamento
- **Administrativo**: Gestão geral
- **Atendimento**: Suporte ao cliente

---

## 📊 Relatórios

### Relatórios Disponíveis:

1. **Clientes**:
   - Lista completa
   - Por regime tributário
   - Por status de contrato
   - Inadimplentes

2. **Obrigações**:
   - Calendário mensal
   - Pendentes por cliente
   - Histórico de cumprimento

3. **Tarefas**:
   - Por responsável
   - Por cliente
   - Por status
   - Tempo gasto

4. **Financeiro**:
   - Receitas e despesas
   - Inadimplência
   - Previsões

5. **Documentos**:
   - Por cliente
   - Por tipo
   - Por período

### Exportar Relatórios:

- **PDF**: Para impressão
- **Excel**: Para análise
- **CSV**: Para importação

---

## ⚙️ Configurações (Admin)

### Configurações Disponíveis:

1. **Empresa**:
   - Nome, email, telefone
   - Endereço
   - Logo

2. **Sistema**:
   - Dias de alerta antes do vencimento
   - Limite de upload (MB)
   - Itens por página

3. **Usuários**:
   - Gerenciar permissões
   - Resetar senhas
   - Ativar/desativar

4. **Backup**:
   - Backup automático
   - Restauração

---

## 🔒 Segurança

### Boas Práticas:

1. ✅ Altere a senha padrão imediatamente
2. ✅ Use senhas fortes (mínimo 8 caracteres)
3. ✅ Não compartilhe credenciais
4. ✅ Faça logout ao sair
5. ✅ Mantenha o sistema atualizado
6. ✅ Faça backups regulares

### Níveis de Acesso:

- **Admin**: Acesso total ao sistema
- **Funcionário**: Acesso aos módulos operacionais
- **Cliente**: Acesso limitado (portal do cliente)

---

## 📞 Suporte

**Em caso de dúvidas:**
- Email: contato@jcacontabilidadecwb.com.br
- Telefone: (41) 98858-4456
- WhatsApp: (41) 98858-4456

---

**© 2024 JCA Soluções Contábeis - Sistema ERP v1.0.0**

