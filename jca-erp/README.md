# 🚀 JCA ERP - Sistema de Gestão Contábil

Sistema ERP completo desenvolvido para **JCA Soluções Contábeis** gerenciar 500+ empresas clientes, 5 funcionários e todas as operações contábeis, fiscais e de RH.

## 📋 Funcionalidades Principais

### ✅ Módulos Implementados

#### 1. **Dashboard Executivo**
- KPIs em tempo real
- Visão geral de clientes, obrigações e tarefas
- Alertas e notificações
- Gráficos e estatísticas

#### 2. **Gestão de Clientes (500+)**
- CRUD completo de clientes
- Dados cadastrais e fiscais
- Regime tributário (Simples, Presumido, Real, MEI)
- Controle de contratos e mensalidades
- Histórico completo

#### 3. **Sistema de Obrigações Fiscais**
- Calendário de obrigações
- Alertas automáticos de vencimento
- Controle por cliente
- Status de cumprimento
- Obrigações: DAS, DCTF, SPED, eSocial, DIRF, RAIS, etc.

#### 4. **Gestão de Tarefas e Workflow**
- Sistema de tarefas por cliente
- Atribuição a funcionários
- Prioridades e prazos
- Controle de tempo
- Status de andamento

#### 5. **Gestão de Documentos**
- Upload e organização de documentos
- Categorização por tipo
- Busca avançada
- Controle de versões
- Armazenamento seguro

#### 6. **Sistema de Alertas**
- Notificações automáticas
- Alertas de vencimento
- Pendências e atrasos
- Notificações por usuário

#### 7. **Módulo Financeiro**
- Controle de mensalidades
- Receitas e despesas
- Inadimplência
- Relatórios financeiros

#### 8. **Gestão de Funcionários**
- Cadastro de funcionários
- Setores e permissões
- Controle de acesso
- Atribuições por área

#### 9. **Relatórios Gerenciais**
- Relatórios customizáveis
- Exportação (PDF, Excel)
- Dashboards analíticos
- Indicadores de performance

#### 10. **Auditoria e Logs**
- Registro de todas as ações
- Rastreabilidade completa
- Segurança e compliance

## 🛠️ Tecnologias Utilizadas

- **Backend**: PHP 7.4+
- **Banco de Dados**: MySQL 5.7+
- **Frontend**: Bootstrap 5, JavaScript ES6+
- **Ícones**: FontAwesome 6
- **Gráficos**: Chart.js
- **Arquitetura**: MVC simplificado
- **Segurança**: PDO, Prepared Statements, Password Hashing

## 📦 Instalação

### Pré-requisitos
- XAMPP, WAMP ou servidor com PHP 7.4+ e MySQL 5.7+
- Navegador moderno (Chrome, Firefox, Edge)

### Passo a Passo

#### 1. **Clone ou copie os arquivos**
```bash
# Copie a pasta jca-erp para htdocs (XAMPP) ou www (WAMP)
C:\xampp\htdocs\jd\jca-erp\
```

#### 2. **Crie o Banco de Dados**
```bash
# Acesse phpMyAdmin: http://localhost/phpmyadmin
# Execute o arquivo: database/schema.sql
```

Ou via linha de comando:
```bash
mysql -u root -p < database/schema.sql
```

#### 3. **Configure a Conexão**
Edite o arquivo `config/database.php`:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'jca_erp');
define('DB_USER', 'root');
define('DB_PASS', ''); // Sua senha do MySQL
```

#### 4. **Configure a URL Base**
Edite o arquivo `config/config.php`:
```php
define('SITE_URL', 'http://localhost/jd/jca-erp');
```

#### 5. **Crie a Pasta de Uploads**
```bash
# Crie a pasta e dê permissões
mkdir uploads
chmod 755 uploads
```

#### 6. **Acesse o Sistema**
```
http://localhost/jd/jca-erp
```

### 🔐 Credenciais Padrão

**Usuário Admin:**
- Email: `admin@jcacontabilidade.com.br`
- Senha: `admin123`

⚠️ **IMPORTANTE**: Altere a senha após o primeiro acesso!

## 📊 Estrutura do Banco de Dados

### Tabelas Principais:
- `usuarios` - Usuários do sistema (admin, funcionários, clientes)
- `setores` - Departamentos da empresa
- `clientes` - Empresas clientes (500+)
- `servicos` - Serviços oferecidos
- `cliente_servicos` - Serviços contratados
- `obrigacoes_fiscais` - Obrigações fiscais cadastradas
- `cliente_obrigacoes` - Obrigações por cliente
- `tarefas` - Sistema de tarefas
- `documentos` - Gestão de documentos
- `alertas` - Sistema de notificações
- `financeiro` - Controle financeiro
- `logs_sistema` - Auditoria
- `configuracoes` - Configurações do sistema

## 🎯 Áreas de Atuação Cobertas

1. ✅ **Assessoria Contábil** - Escrituração, balancetes, DRE
2. ✅ **Assessoria em RH** - Folha de pagamento, admissões, demissões
3. ✅ **Abertura/Fechamento de Empresas** - Processos completos
4. ✅ **Contabilidade MEI** - Gestão específica para MEI
5. ✅ **Folha de Pagamento** - Processamento e encargos
6. ✅ **Imposto de Renda** - Declarações e acompanhamento
7. ✅ **Malha Fiscal** - Regularização e compliance
8. ✅ **Planejamento Tributário** - Otimização fiscal

## 🔒 Segurança

- ✅ Senhas criptografadas (password_hash)
- ✅ Prepared Statements (proteção SQL Injection)
- ✅ Sanitização de inputs
- ✅ Controle de sessão
- ✅ Níveis de acesso (Admin, Funcionário, Cliente)
- ✅ Logs de auditoria
- ✅ HTTPS recomendado em produção

## 📱 Responsividade

- ✅ Design responsivo (Bootstrap 5)
- ✅ Mobile-first approach
- ✅ Compatível com tablets e smartphones
- ✅ Interface adaptativa

## 🚀 Próximas Funcionalidades

- [ ] API REST para integrações
- [ ] App Mobile (React Native)
- [ ] Integração com e-CAC
- [ ] Assinatura digital de documentos
- [ ] Chat interno entre funcionários
- [ ] Backup automático
- [ ] Relatórios avançados com BI
- [ ] Integração com bancos (boletos)

## 📞 Suporte

**JCA Soluções Contábeis**
- Email: contato@jcacontabilidadecwb.com.br
- Telefone: (41) 98858-4456
- Endereço: Curitiba - PR

## 📄 Licença

© 2024 JCA Soluções Contábeis. Todos os direitos reservados.

---

**Desenvolvido com ❤️ para JCA Soluções Contábeis**

