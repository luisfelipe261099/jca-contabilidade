<?php
require_once 'config/config.php';
if (!isLoggedIn()) redirect('index.php');

$usuario = getUsuarioLogado();

$pageTitle = 'Manual do Sistema';
$breadcrumb = '<a href="dashboard.php">Dashboard</a> / Manual';
include 'includes/header.php';
?>

<div class="p-6 space-y-6" x-data="{ activeSection: 'introducao' }">
    <!-- Header -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 rounded-lg p-8 text-white shadow-lg">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center">
                <i data-lucide="book-open" class="w-8 h-8"></i>
            </div>
            <div>
                <h1 class="text-4xl font-bold">Manual do Sistema JCA ERP</h1>
                <p class="text-primary-100 mt-2">Guia completo de uso do sistema de gestão contábil</p>
            </div>
        </div>
        <div class="flex gap-4 mt-6">
            <div class="bg-white/10 backdrop-blur-sm rounded-lg px-4 py-2">
                <p class="text-sm text-primary-100">Versão</p>
                <p class="font-semibold">1.0.0</p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-lg px-4 py-2">
                <p class="text-sm text-primary-100">Última Atualização</p>
                <p class="font-semibold"><?php echo date('d/m/Y'); ?></p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-lg px-4 py-2">
                <p class="text-sm text-primary-100">Suporte</p>
                <p class="font-semibold">(41) 98858-4456</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Menu Lateral -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg border border-gray-200 p-4 sticky top-6">
                <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i data-lucide="list" class="w-5 h-5 text-primary-600"></i>
                    Índice
                </h3>
                <nav class="space-y-1">
                    <button @click="activeSection = 'introducao'" :class="activeSection === 'introducao' ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50'" class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        1. Introdução
                    </button>
                    <button @click="activeSection = 'dashboard'" :class="activeSection === 'dashboard' ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50'" class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        2. Dashboard
                    </button>
                    <button @click="activeSection = 'clientes'" :class="activeSection === 'clientes' ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50'" class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        3. Gestão de Clientes
                    </button>
                    <button @click="activeSection = 'funcionarios'" :class="activeSection === 'funcionarios' ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50'" class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        4. Funcionários (RH)
                    </button>
                    <button @click="activeSection = 'tarefas'" :class="activeSection === 'tarefas' ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50'" class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        5. Tarefas
                    </button>
                    <button @click="activeSection = 'documentos'" :class="activeSection === 'documentos' ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50'" class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        6. Documentos
                    </button>
                    <button @click="activeSection = 'certificados'" :class="activeSection === 'certificados' ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50'" class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        7. Certificados Digitais
                    </button>
                    <button @click="activeSection = 'rh'" :class="activeSection === 'rh' ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50'" class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        8. RH e Folha
                    </button>
                    <button @click="activeSection = 'contabil'" :class="activeSection === 'contabil' ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50'" class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        9. Contábil
                    </button>
                    <button @click="activeSection = 'fiscal'" :class="activeSection === 'fiscal' ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50'" class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        10. Fiscal
                    </button>
                    <button @click="activeSection = 'obrigacoes'" :class="activeSection === 'obrigacoes' ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50'" class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        11. Obrigações
                    </button>
                    <button @click="activeSection = 'financeiro'" :class="activeSection === 'financeiro' ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50'" class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        12. Financeiro
                    </button>
                    <button @click="activeSection = 'relatorios'" :class="activeSection === 'relatorios' ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50'" class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        13. Relatórios
                    </button>
                    <button @click="activeSection = 'usuarios'" :class="activeSection === 'usuarios' ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50'" class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        14. Usuários
                    </button>
                    <button @click="activeSection = 'configuracoes'" :class="activeSection === 'configuracoes' ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50'" class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        15. Configurações
                    </button>
                    <button @click="activeSection = 'processos'" :class="activeSection === 'processos' ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50'" class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        16. Processos Mensais
                    </button>
                    <button @click="activeSection = 'faq'" :class="activeSection === 'faq' ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50'" class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        17. FAQ
                    </button>
                </nav>
            </div>
        </div>

        <!-- Conteúdo -->
        <div class="lg:col-span-3 space-y-6">
            <!-- 1. Introdução -->
            <div x-show="activeSection === 'introducao'" class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                        <span class="text-primary-700 font-bold">1</span>
                    </div>
                    Introdução ao Sistema
                </h2>
                
                <div class="prose max-w-none">
                    <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-3">O que é o JCA ERP?</h3>
                    <p class="text-gray-700 mb-4">
                        O JCA ERP é um sistema completo de gestão desenvolvido especificamente para escritórios de contabilidade. 
                        Ele permite gerenciar todos os aspectos do seu negócio em um único lugar, desde o cadastro de clientes 
                        até a emissão de holerites e notas fiscais.
                    </p>

                    <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-3">Principais Funcionalidades</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center gap-3 mb-2">
                                <i data-lucide="users" class="w-5 h-5 text-blue-600"></i>
                                <h4 class="font-semibold text-gray-900">Gestão de Clientes</h4>
                            </div>
                            <p class="text-sm text-gray-600">Cadastro completo de empresas clientes com todos os dados fiscais e contratuais</p>
                        </div>
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center gap-3 mb-2">
                                <i data-lucide="user-check" class="w-5 h-5 text-green-600"></i>
                                <h4 class="font-semibold text-gray-900">RH Completo</h4>
                            </div>
                            <p class="text-sm text-gray-600">Gestão de funcionários, holerites, férias, rescisões e FGTS</p>
                        </div>
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center gap-3 mb-2">
                                <i data-lucide="calculator" class="w-5 h-5 text-purple-600"></i>
                                <h4 class="font-semibold text-gray-900">Contabilidade</h4>
                            </div>
                            <p class="text-sm text-gray-600">Lançamentos contábeis, balancetes, DRE e balanço patrimonial</p>
                        </div>
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center gap-3 mb-2">
                                <i data-lucide="file-text" class="w-5 h-5 text-amber-600"></i>
                                <h4 class="font-semibold text-gray-900">Fiscal</h4>
                            </div>
                            <p class="text-sm text-gray-600">Emissão de notas fiscais e controle de obrigações fiscais</p>
                        </div>
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center gap-3 mb-2">
                                <i data-lucide="dollar-sign" class="w-5 h-5 text-red-600"></i>
                                <h4 class="font-semibold text-gray-900">Financeiro</h4>
                            </div>
                            <p class="text-sm text-gray-600">Controle de mensalidades, pagamentos e inadimplência</p>
                        </div>
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center gap-3 mb-2">
                                <i data-lucide="shield-check" class="w-5 h-5 text-indigo-600"></i>
                                <h4 class="font-semibold text-gray-900">Certificados Digitais</h4>
                            </div>
                            <p class="text-sm text-gray-600">Controle de validade e renovação de certificados</p>
                        </div>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-3">Como Navegar no Sistema</h3>
                    <ul class="list-disc list-inside space-y-2 text-gray-700 mb-4">
                        <li><strong>Menu Lateral:</strong> Acesse todas as funcionalidades através do menu à esquerda</li>
                        <li><strong>Dashboard:</strong> Visão geral com estatísticas e alertas importantes</li>
                        <li><strong>Busca Rápida:</strong> Use os filtros em cada página para encontrar informações rapidamente</li>
                        <li><strong>Centro de Controle:</strong> Acesse tudo relacionado a um cliente específico</li>
                    </ul>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-6">
                        <div class="flex items-start gap-3">
                            <i data-lucide="info" class="w-5 h-5 text-blue-600 mt-0.5"></i>
                            <div>
                                <h4 class="font-semibold text-blue-900 mb-1">Dica Importante</h4>
                                <p class="text-sm text-blue-800">
                                    Sempre comece cadastrando seus clientes. Depois, você poderá adicionar funcionários,
                                    documentos, tarefas e tudo mais relacionado a cada cliente.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Dashboard -->
            <div x-show="activeSection === 'dashboard'" class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                        <span class="text-primary-700 font-bold">2</span>
                    </div>
                    Dashboard
                </h2>

                <div class="prose max-w-none">
                    <p class="text-gray-700 mb-4">
                        O Dashboard é a página inicial do sistema, onde você tem uma visão geral de tudo que está acontecendo.
                    </p>

                    <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-3">Informações Disponíveis</h3>

                    <div class="space-y-4">
                        <div class="border-l-4 border-blue-500 pl-4">
                            <h4 class="font-semibold text-gray-900 mb-2">📊 Cards de Estatísticas</h4>
                            <ul class="list-disc list-inside space-y-1 text-gray-700 text-sm">
                                <li>Total de clientes ativos</li>
                                <li>Tarefas pendentes do dia</li>
                                <li>Obrigações do mês</li>
                                <li>Alertas urgentes</li>
                            </ul>
                        </div>

                        <div class="border-l-4 border-green-500 pl-4">
                            <h4 class="font-semibold text-gray-900 mb-2">📅 Calendário de Obrigações</h4>
                            <p class="text-sm text-gray-700">
                                Visualize todas as obrigações fiscais do mês atual, organizadas por data de vencimento.
                                Clique em uma obrigação para marcar como concluída.
                            </p>
                        </div>

                        <div class="border-l-4 border-amber-500 pl-4">
                            <h4 class="font-semibold text-gray-900 mb-2">🔔 Alertas Recentes</h4>
                            <p class="text-sm text-gray-700">
                                Veja os alertas mais importantes: vencimentos próximos, certificados expirando,
                                tarefas atrasadas e inadimplências.
                            </p>
                        </div>

                        <div class="border-l-4 border-purple-500 pl-4">
                            <h4 class="font-semibold text-gray-900 mb-2">✅ Tarefas Recentes</h4>
                            <p class="text-sm text-gray-700">
                                Lista das últimas tarefas criadas e seu status atual. Clique para ver detalhes ou atualizar.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Gestão de Clientes -->
            <div x-show="activeSection === 'clientes'" class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                        <span class="text-primary-700 font-bold">3</span>
                    </div>
                    Gestão de Clientes
                </h2>

                <div class="prose max-w-none">
                    <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-3">Como Cadastrar um Novo Cliente</h3>

                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <ol class="list-decimal list-inside space-y-3 text-gray-700">
                            <li>
                                <strong>Acesse o Menu:</strong> Clique em "Clientes" no menu lateral
                            </li>
                            <li>
                                <strong>Novo Cliente:</strong> Clique no botão "Novo Cliente" (canto superior direito)
                            </li>
                            <li>
                                <strong>Preencha os Dados:</strong>
                                <ul class="list-disc list-inside ml-6 mt-2 space-y-1 text-sm">
                                    <li><strong>Dados Cadastrais:</strong> Razão Social, Nome Fantasia, CNPJ, Inscrições</li>
                                    <li><strong>Regime Tributário:</strong> Simples Nacional, Lucro Presumido, Lucro Real ou MEI</li>
                                    <li><strong>Endereço:</strong> CEP, Logradouro, Número, Bairro, Cidade, Estado</li>
                                    <li><strong>Contato:</strong> Telefone, E-mail, Responsável Legal</li>
                                    <li><strong>Contrato:</strong> Valor da mensalidade, Dia de vencimento, Contador responsável</li>
                                </ul>
                            </li>
                            <li>
                                <strong>Salvar:</strong> Clique em "Cadastrar Cliente"
                            </li>
                        </ol>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-3">Visualizar Detalhes do Cliente</h3>
                    <p class="text-gray-700 mb-4">
                        Na lista de clientes, clique no botão "Ver" para acessar a página de detalhes. Lá você encontra:
                    </p>
                    <ul class="list-disc list-inside space-y-2 text-gray-700 mb-4">
                        <li><strong>Informações Completas:</strong> Todos os dados cadastrais organizados em cards</li>
                        <li><strong>Estatísticas:</strong> Número de funcionários, tarefas, documentos e certificados</li>
                        <li><strong>Centro de Controle:</strong> Botão destacado para acessar todas as funcionalidades do cliente</li>
                        <li><strong>Ações Rápidas:</strong> Criar tarefa, upload de documento, cadastrar funcionário</li>
                    </ul>

                    <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-3">Centro de Controle do Cliente</h3>
                    <p class="text-gray-700 mb-4">
                        O Centro de Controle é o hub central para gerenciar tudo relacionado a um cliente específico:
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="border border-green-200 bg-green-50 rounded-lg p-4">
                            <h4 class="font-semibold text-green-900 mb-2">🧑‍💼 Módulo RH</h4>
                            <ul class="text-sm text-green-800 space-y-1">
                                <li>• Funcionários</li>
                                <li>• Holerites</li>
                                <li>• Rescisões</li>
                                <li>• Férias</li>
                                <li>• FGTS</li>
                                <li>• 13º Salário</li>
                                <li>• eSocial</li>
                                <li>• Guias (GPS, DARF)</li>
                            </ul>
                        </div>

                        <div class="border border-blue-200 bg-blue-50 rounded-lg p-4">
                            <h4 class="font-semibold text-blue-900 mb-2">🔐 Certificados Digitais</h4>
                            <ul class="text-sm text-blue-800 space-y-1">
                                <li>• Listar certificados</li>
                                <li>• Cadastrar novo</li>
                                <li>• Alertas de vencimento</li>
                                <li>• Renovação</li>
                            </ul>
                        </div>

                        <div class="border border-purple-200 bg-purple-50 rounded-lg p-4">
                            <h4 class="font-semibold text-purple-900 mb-2">📄 Notas Fiscais</h4>
                            <ul class="text-sm text-purple-800 space-y-1">
                                <li>• Emitir NF-e, NFS-e</li>
                                <li>• Consultar status</li>
                                <li>• Cancelar notas</li>
                                <li>• Histórico</li>
                            </ul>
                        </div>

                        <div class="border border-amber-200 bg-amber-50 rounded-lg p-4">
                            <h4 class="font-semibold text-amber-900 mb-2">📊 Contábil</h4>
                            <ul class="text-sm text-amber-800 space-y-1">
                                <li>• Lançamentos</li>
                                <li>• Balancete</li>
                                <li>• DRE</li>
                                <li>• Balanço Patrimonial</li>
                                <li>• Plano de Contas</li>
                                <li>• SPED Contábil</li>
                            </ul>
                        </div>
                    </div>

                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mt-6">
                        <div class="flex items-start gap-3">
                            <i data-lucide="lightbulb" class="w-5 h-5 text-amber-600 mt-0.5"></i>
                            <div>
                                <h4 class="font-semibold text-amber-900 mb-1">Dica</h4>
                                <p class="text-sm text-amber-800">
                                    Use o Centro de Controle como ponto de partida para todas as operações de um cliente.
                                    É mais rápido e organizado do que navegar pelos menus.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. Funcionários (RH) -->
            <div x-show="activeSection === 'funcionarios'" class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                        <span class="text-primary-700 font-bold">4</span>
                    </div>
                    Funcionários dos Clientes (RH)
                </h2>

                <div class="prose max-w-none">
                    <p class="text-gray-700 mb-4">
                        Gerencie todos os funcionários das empresas clientes em um único lugar.
                    </p>

                    <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-3">Como Cadastrar um Funcionário</h3>

                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <ol class="list-decimal list-inside space-y-3 text-gray-700">
                            <li>
                                <strong>Acesse:</strong> Menu → Recursos Humanos → Funcionários
                            </li>
                            <li>
                                <strong>Novo Funcionário:</strong> Clique em "Novo Funcionário"
                            </li>
                            <li>
                                <strong>Selecione o Cliente:</strong> Escolha para qual empresa o funcionário trabalha
                            </li>
                            <li>
                                <strong>Preencha os Dados:</strong>
                                <ul class="list-disc list-inside ml-6 mt-2 space-y-1 text-sm">
                                    <li><strong>Pessoais:</strong> Nome, CPF, RG, Data Nascimento, Sexo, Estado Civil</li>
                                    <li><strong>Contato:</strong> Telefone, Celular, E-mail</li>
                                    <li><strong>Endereço:</strong> CEP, Logradouro, Número, Bairro, Cidade, Estado</li>
                                    <li><strong>Trabalhistas:</strong> Cargo, Departamento, Data Admissão, Tipo Contrato</li>
                                    <li><strong>Remuneração:</strong> Salário Base, Vale Transporte, Vale Refeição, Dependentes</li>
                                    <li><strong>Documentos:</strong> PIS/PASEP, CTPS (Número, Série, UF)</li>
                                    <li><strong>Bancários:</strong> Banco, Agência, Conta, Tipo Conta, PIX</li>
                                </ul>
                            </li>
                            <li>
                                <strong>Salvar:</strong> Clique em "Cadastrar Funcionário"
                            </li>
                        </ol>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-3">Filtros e Busca</h3>
                    <p class="text-gray-700 mb-4">
                        Na página de funcionários, você pode filtrar por:
                    </p>
                    <ul class="list-disc list-inside space-y-2 text-gray-700 mb-4">
                        <li><strong>Cliente:</strong> Ver funcionários de uma empresa específica</li>
                        <li><strong>Status:</strong> Ativo, Férias, Afastado, Demitido</li>
                        <li><strong>Busca:</strong> Por nome, CPF ou cargo</li>
                    </ul>

                    <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-3">Estatísticas</h3>
                    <p class="text-gray-700 mb-4">
                        No topo da página, veja cards com:
                    </p>
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-4">
                        <div class="text-center p-3 bg-blue-50 rounded-lg">
                            <p class="text-2xl font-bold text-blue-600">Total</p>
                            <p class="text-xs text-blue-800">Todos</p>
                        </div>
                        <div class="text-center p-3 bg-green-50 rounded-lg">
                            <p class="text-2xl font-bold text-green-600">Ativos</p>
                            <p class="text-xs text-green-800">Trabalhando</p>
                        </div>
                        <div class="text-center p-3 bg-blue-50 rounded-lg">
                            <p class="text-2xl font-bold text-blue-600">Férias</p>
                            <p class="text-xs text-blue-800">Em gozo</p>
                        </div>
                        <div class="text-center p-3 bg-amber-50 rounded-lg">
                            <p class="text-2xl font-bold text-amber-600">Afastados</p>
                            <p class="text-xs text-amber-800">Licença</p>
                        </div>
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <p class="text-2xl font-bold text-gray-600">Demitidos</p>
                            <p class="text-xs text-gray-800">Inativos</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 5. Tarefas -->
            <div x-show="activeSection === 'tarefas'" class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                        <span class="text-primary-700 font-bold">5</span>
                    </div>
                    Gestão de Tarefas
                </h2>

                <div class="prose max-w-none">
                    <p class="text-gray-700 mb-4">
                        Organize e acompanhe todas as tarefas relacionadas aos seus clientes.
                    </p>

                    <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-3">Como Criar uma Tarefa</h3>

                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <ol class="list-decimal list-inside space-y-3 text-gray-700">
                            <li><strong>Acesse:</strong> Menu → Gestão → Tarefas</li>
                            <li><strong>Nova Tarefa:</strong> Clique em "Nova Tarefa"</li>
                            <li><strong>Preencha:</strong>
                                <ul class="list-disc list-inside ml-6 mt-2 space-y-1 text-sm">
                                    <li>Cliente relacionado</li>
                                    <li>Título da tarefa</li>
                                    <li>Descrição detalhada</li>
                                    <li>Prioridade (Baixa, Média, Alta, Urgente)</li>
                                    <li>Data de vencimento</li>
                                    <li>Responsável pela tarefa</li>
                                    <li>Setor responsável</li>
                                </ul>
                            </li>
                            <li><strong>Salvar:</strong> Clique em "Criar Tarefa"</li>
                        </ol>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-3">Status das Tarefas</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                        <div class="border-l-4 border-gray-400 pl-3">
                            <p class="font-semibold text-gray-900">Pendente</p>
                            <p class="text-xs text-gray-600">Ainda não iniciada</p>
                        </div>
                        <div class="border-l-4 border-blue-400 pl-3">
                            <p class="font-semibold text-blue-900">Em Andamento</p>
                            <p class="text-xs text-blue-600">Sendo executada</p>
                        </div>
                        <div class="border-l-4 border-green-400 pl-3">
                            <p class="font-semibold text-green-900">Concluída</p>
                            <p class="text-xs text-green-600">Finalizada</p>
                        </div>
                        <div class="border-l-4 border-red-400 pl-3">
                            <p class="font-semibold text-red-900">Atrasada</p>
                            <p class="text-xs text-red-600">Passou do prazo</p>
                        </div>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-3">Prioridades</h3>
                    <ul class="space-y-2 mb-4">
                        <li class="flex items-center gap-2">
                            <span class="px-2 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded">Urgente</span>
                            <span class="text-sm text-gray-700">Precisa ser feita imediatamente</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="px-2 py-1 bg-amber-100 text-amber-700 text-xs font-semibold rounded">Alta</span>
                            <span class="text-sm text-gray-700">Importante, fazer em breve</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded">Média</span>
                            <span class="text-sm text-gray-700">Prioridade normal</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs font-semibold rounded">Baixa</span>
                            <span class="text-sm text-gray-700">Pode esperar</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- 6. Documentos -->
            <div x-show="activeSection === 'documentos'" class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                        <span class="text-primary-700 font-bold">6</span>
                    </div>
                    Gestão de Documentos
                </h2>

                <div class="prose max-w-none">
                    <p class="text-gray-700 mb-4">
                        Armazene e organize todos os documentos dos seus clientes de forma segura.
                    </p>

                    <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-3">Como Fazer Upload de Documentos</h3>

                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <ol class="list-decimal list-inside space-y-3 text-gray-700">
                            <li><strong>Acesse:</strong> Menu → Gestão → Documentos</li>
                            <li><strong>Upload:</strong> Clique em "Upload de Documento"</li>
                            <li><strong>Selecione:</strong>
                                <ul class="list-disc list-inside ml-6 mt-2 space-y-1 text-sm">
                                    <li>Cliente relacionado</li>
                                    <li>Tipo de documento (Contrato, Certidão, Balanço, DRE, Folha, Guia, Nota Fiscal, Outros)</li>
                                    <li>Mês e ano de referência (se aplicável)</li>
                                    <li>Arquivo (PDF, DOC, XLS, etc.)</li>
                                    <li>Descrição opcional</li>
                                </ul>
                            </li>
                            <li><strong>Enviar:</strong> Clique em "Fazer Upload"</li>
                        </ol>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-3">Tipos de Documentos</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                        <div class="bg-blue-50 p-3 rounded-lg">
                            <p class="font-semibold text-blue-900 text-sm">📄 Contrato</p>
                            <p class="text-xs text-blue-700">Contratos sociais</p>
                        </div>
                        <div class="bg-green-50 p-3 rounded-lg">
                            <p class="font-semibold text-green-900 text-sm">✅ Certidão</p>
                            <p class="text-xs text-green-700">Certidões diversas</p>
                        </div>
                        <div class="bg-purple-50 p-3 rounded-lg">
                            <p class="font-semibold text-purple-900 text-sm">📊 Balanço</p>
                            <p class="text-xs text-purple-700">Balanços contábeis</p>
                        </div>
                        <div class="bg-amber-50 p-3 rounded-lg">
                            <p class="font-semibold text-amber-900 text-sm">💰 DRE</p>
                            <p class="text-xs text-amber-700">Demonstrações</p>
                        </div>
                        <div class="bg-red-50 p-3 rounded-lg">
                            <p class="font-semibold text-red-900 text-sm">👥 Folha</p>
                            <p class="text-xs text-red-700">Folhas de pagamento</p>
                        </div>
                        <div class="bg-indigo-50 p-3 rounded-lg">
                            <p class="font-semibold text-indigo-900 text-sm">📋 Guia</p>
                            <p class="text-xs text-indigo-700">Guias de impostos</p>
                        </div>
                        <div class="bg-pink-50 p-3 rounded-lg">
                            <p class="font-semibold text-pink-900 text-sm">🧾 Nota Fiscal</p>
                            <p class="text-xs text-pink-700">NF-e, NFS-e</p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <p class="font-semibold text-gray-900 text-sm">📁 Outros</p>
                            <p class="text-xs text-gray-700">Diversos</p>
                        </div>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-6">
                        <div class="flex items-start gap-3">
                            <i data-lucide="shield" class="w-5 h-5 text-blue-600 mt-0.5"></i>
                            <div>
                                <h4 class="font-semibold text-blue-900 mb-1">Segurança</h4>
                                <p class="text-sm text-blue-800">
                                    Todos os documentos são armazenados de forma segura e só podem ser acessados
                                    por usuários autorizados do sistema.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 7. Certificados Digitais -->
            <div x-show="activeSection === 'certificados'" class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                        <span class="text-primary-700 font-bold">7</span>
                    </div>
                    Certificados Digitais
                </h2>

                <div class="prose max-w-none">
                    <p class="text-gray-700 mb-4">
                        Controle a validade e renovação dos certificados digitais dos seus clientes.
                    </p>

                    <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-3">Como Cadastrar um Certificado</h3>

                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <ol class="list-decimal list-inside space-y-3 text-gray-700">
                            <li><strong>Acesse:</strong> Cliente → Centro de Controle → Certificados Digitais</li>
                            <li><strong>Novo Certificado:</strong> Clique em "Novo Certificado"</li>
                            <li><strong>Preencha:</strong>
                                <ul class="list-disc list-inside ml-6 mt-2 space-y-1 text-sm">
                                    <li>Tipo (e-CNPJ A1/A3, e-CPF A1/A3, NF-e A1/A3)</li>
                                    <li>Titular do certificado</li>
                                    <li>CPF/CNPJ</li>
                                    <li>Data de emissão</li>
                                    <li>Data de validade</li>
                                    <li>Certificadora</li>
                                    <li>Senha (criptografada)</li>
                                    <li>Dias para alerta de vencimento (padrão: 30 dias)</li>
                                </ul>
                            </li>
                            <li><strong>Salvar:</strong> Clique em "Cadastrar Certificado"</li>
                        </ol>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-3">Tipos de Certificados</h3>
                    <div class="space-y-3 mb-4">
                        <div class="border-l-4 border-blue-500 pl-4">
                            <p class="font-semibold text-gray-900">e-CNPJ A1 / A3</p>
                            <p class="text-sm text-gray-600">Certificado da empresa para assinatura digital</p>
                        </div>
                        <div class="border-l-4 border-green-500 pl-4">
                            <p class="font-semibold text-gray-900">e-CPF A1 / A3</p>
                            <p class="text-sm text-gray-600">Certificado pessoal do responsável</p>
                        </div>
                        <div class="border-l-4 border-purple-500 pl-4">
                            <p class="font-semibold text-gray-900">NF-e A1 / A3</p>
                            <p class="text-sm text-gray-600">Específico para emissão de notas fiscais</p>
                        </div>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-3">Alertas de Vencimento</h3>
                    <p class="text-gray-700 mb-4">
                        O sistema envia alertas automáticos quando um certificado está próximo do vencimento:
                    </p>
                    <ul class="list-disc list-inside space-y-2 text-gray-700 mb-4">
                        <li><strong>30 dias antes:</strong> Alerta amarelo no dashboard</li>
                        <li><strong>15 dias antes:</strong> Alerta laranja com notificação</li>
                        <li><strong>7 dias antes:</strong> Alerta vermelho urgente</li>
                        <li><strong>Vencido:</strong> Status muda para "Vencido" automaticamente</li>
                    </ul>

                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mt-6">
                        <div class="flex items-start gap-3">
                            <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600 mt-0.5"></i>
                            <div>
                                <h4 class="font-semibold text-red-900 mb-1">Importante</h4>
                                <p class="text-sm text-red-800">
                                    Certificados vencidos impedem a emissão de notas fiscais e outras operações.
                                    Sempre renove com antecedência!
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 8. RH e Folha -->
            <div x-show="activeSection === 'rh'" class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                        <span class="text-primary-700 font-bold">8</span>
                    </div>
                    RH e Folha de Pagamento
                </h2>

                <div class="prose max-w-none">
                    <p class="text-gray-700 mb-4">
                        Módulo completo para gestão de RH dos clientes: holerites, férias, rescisões, FGTS e muito mais.
                    </p>

                    <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-3">Funcionalidades Disponíveis</h3>

                    <div class="space-y-4 mb-6">
                        <div class="border border-green-200 bg-green-50 rounded-lg p-4">
                            <h4 class="font-semibold text-green-900 mb-2 flex items-center gap-2">
                                <i data-lucide="file-text" class="w-5 h-5"></i>
                                Holerites (Contracheques)
                            </h4>
                            <p class="text-sm text-green-800 mb-3">
                                Gere holerites mensais para todos os funcionários com cálculos automáticos de:
                            </p>
                            <ul class="text-sm text-green-800 space-y-1 ml-4">
                                <li>• Salário base + horas extras + adicionais</li>
                                <li>• Descontos: INSS, IRRF, Vale Transporte</li>
                                <li>• Total líquido a receber</li>
                                <li>• Exportação em PDF</li>
                            </ul>
                        </div>

                        <div class="border border-blue-200 bg-blue-50 rounded-lg p-4">
                            <h4 class="font-semibold text-blue-900 mb-2 flex items-center gap-2">
                                <i data-lucide="plane" class="w-5 h-5"></i>
                                Férias
                            </h4>
                            <p class="text-sm text-blue-800 mb-3">
                                Controle completo de férias dos funcionários:
                            </p>
                            <ul class="text-sm text-blue-800 space-y-1 ml-4">
                                <li>• Período aquisitivo e concessivo</li>
                                <li>• Cálculo de férias + 1/3 constitucional</li>
                                <li>• Abono pecuniário (venda de férias)</li>
                                <li>• Programação e histórico</li>
                            </ul>
                        </div>

                        <div class="border border-red-200 bg-red-50 rounded-lg p-4">
                            <h4 class="font-semibold text-red-900 mb-2 flex items-center gap-2">
                                <i data-lucide="user-x" class="w-5 h-5"></i>
                                Rescisões
                            </h4>
                            <p class="text-sm text-red-800 mb-3">
                                Cálculo completo de rescisão trabalhista:
                            </p>
                            <ul class="text-sm text-red-800 space-y-1 ml-4">
                                <li>• Saldo de salário</li>
                                <li>• Férias vencidas e proporcionais + 1/3</li>
                                <li>• 13º salário proporcional</li>
                                <li>• Aviso prévio (trabalhado ou indenizado)</li>
                                <li>• Multa de 40% do FGTS</li>
                                <li>• Total líquido a receber</li>
                            </ul>
                        </div>

                        <div class="border border-amber-200 bg-amber-50 rounded-lg p-4">
                            <h4 class="font-semibold text-amber-900 mb-2 flex items-center gap-2">
                                <i data-lucide="piggy-bank" class="w-5 h-5"></i>
                                FGTS
                            </h4>
                            <p class="text-sm text-amber-800 mb-3">
                                Controle de depósitos mensais do FGTS:
                            </p>
                            <ul class="text-sm text-amber-800 space-y-1 ml-4">
                                <li>• Cálculo automático (8% sobre salário)</li>
                                <li>• Controle de vencimentos</li>
                                <li>• Geração de guias</li>
                                <li>• Histórico de pagamentos</li>
                            </ul>
                        </div>

                        <div class="border border-purple-200 bg-purple-50 rounded-lg p-4">
                            <h4 class="font-semibold text-purple-900 mb-2 flex items-center gap-2">
                                <i data-lucide="gift" class="w-5 h-5"></i>
                                13º Salário
                            </h4>
                            <p class="text-sm text-purple-800 mb-3">
                                Gestão do 13º salário:
                            </p>
                            <ul class="text-sm text-purple-800 space-y-1 ml-4">
                                <li>• 1ª parcela (até 30/11)</li>
                                <li>• 2ª parcela (até 20/12)</li>
                                <li>• Cálculo proporcional</li>
                                <li>• Descontos de INSS e IRRF</li>
                            </ul>
                        </div>

                        <div class="border border-indigo-200 bg-indigo-50 rounded-lg p-4">
                            <h4 class="font-semibold text-indigo-900 mb-2 flex items-center gap-2">
                                <i data-lucide="send" class="w-5 h-5"></i>
                                eSocial
                            </h4>
                            <p class="text-sm text-indigo-800 mb-3">
                                Envio de eventos para o eSocial:
                            </p>
                            <ul class="text-sm text-indigo-800 space-y-1 ml-4">
                                <li>• Admissões (S-2200)</li>
                                <li>• Desligamentos (S-2299)</li>
                                <li>• Folha de pagamento (S-1200)</li>
                                <li>• Férias (S-1200)</li>
                            </ul>
                        </div>

                        <div class="border border-gray-200 bg-gray-50 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-2 flex items-center gap-2">
                                <i data-lucide="file-check" class="w-5 h-5"></i>
                                Guias
                            </h4>
                            <p class="text-sm text-gray-800 mb-3">
                                Geração de guias de recolhimento:
                            </p>
                            <ul class="text-sm text-gray-800 space-y-1 ml-4">
                                <li>• GPS (INSS)</li>
                                <li>• DARF (IRRF)</li>
                                <li>• FGTS (GRF)</li>
                                <li>• Contribuição Sindical</li>
                            </ul>
                        </div>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-3">Processo Mensal de Folha</h3>
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <ol class="list-decimal list-inside space-y-2 text-gray-700">
                            <li>Coletar ponto dos funcionários (horas trabalhadas, faltas, horas extras)</li>
                            <li>Lançar no sistema os dados variáveis (comissões, bonificações, faltas)</li>
                            <li>Gerar holerites do mês</li>
                            <li>Revisar e aprovar</li>
                            <li>Enviar holerites para os funcionários</li>
                            <li>Gerar guias de INSS, IRRF e FGTS</li>
                            <li>Enviar eventos para o eSocial</li>
                            <li>Efetuar pagamentos</li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- 9. Contábil -->
            <div x-show="activeSection === 'contabil'" class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                        <span class="text-primary-700 font-bold">9</span>
                    </div>
                    Módulo Contábil
                </h2>

                <div class="prose max-w-none">
                    <p class="text-gray-700 mb-4">
                        Gestão completa da contabilidade dos clientes.
                    </p>

                    <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-3">Funcionalidades</h3>

                    <div class="space-y-4 mb-6">
                        <div class="border-l-4 border-blue-500 pl-4">
                            <h4 class="font-semibold text-gray-900 mb-2">📝 Lançamentos Contábeis</h4>
                            <p class="text-sm text-gray-700">
                                Registre todos os lançamentos contábeis (débito e crédito) com histórico,
                                documento de origem e competência mensal.
                            </p>
                        </div>

                        <div class="border-l-4 border-green-500 pl-4">
                            <h4 class="font-semibold text-gray-900 mb-2">📊 Balancete</h4>
                            <p class="text-sm text-gray-700">
                                Gere balancetes mensais (analítico ou sintético) com totais de ativo, passivo,
                                receitas, despesas e resultado do período.
                            </p>
                        </div>

                        <div class="border-l-4 border-purple-500 pl-4">
                            <h4 class="font-semibold text-gray-900 mb-2">💰 DRE (Demonstração do Resultado)</h4>
                            <p class="text-sm text-gray-700">
                                Demonstração completa do resultado do exercício com receitas, custos, despesas
                                e lucro/prejuízo.
                            </p>
                        </div>

                        <div class="border-l-4 border-amber-500 pl-4">
                            <h4 class="font-semibold text-gray-900 mb-2">⚖️ Balanço Patrimonial</h4>
                            <p class="text-sm text-gray-700">
                                Balanço completo com ativo, passivo e patrimônio líquido da empresa.
                            </p>
                        </div>

                        <div class="border-l-4 border-red-500 pl-4">
                            <h4 class="font-semibold text-gray-900 mb-2">🗂️ Plano de Contas</h4>
                            <p class="text-sm text-gray-700">
                                Gerencie o plano de contas contábil com hierarquia de contas (ativo, passivo,
                                receita, despesa, patrimônio líquido).
                            </p>
                        </div>

                        <div class="border-l-4 border-indigo-500 pl-4">
                            <h4 class="font-semibold text-gray-900 mb-2">🏦 Conciliação Bancária</h4>
                            <p class="text-sm text-gray-700">
                                Concilie os lançamentos contábeis com os extratos bancários.
                            </p>
                        </div>

                        <div class="border-l-4 border-pink-500 pl-4">
                            <h4 class="font-semibold text-gray-900 mb-2">📚 Livros Contábeis</h4>
                            <p class="text-sm text-gray-700">
                                Gere livros diário, razão e outros livros obrigatórios.
                            </p>
                        </div>

                        <div class="border-l-4 border-gray-500 pl-4">
                            <h4 class="font-semibold text-gray-900 mb-2">💾 SPED Contábil</h4>
                            <p class="text-sm text-gray-700">
                                Exporte arquivos no formato SPED Contábil (ECD) para envio à Receita Federal.
                            </p>
                        </div>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-3">Processo Mensal Contábil</h3>
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <ol class="list-decimal list-inside space-y-2 text-gray-700">
                            <li>Coletar documentos do cliente (notas fiscais, recibos, extratos)</li>
                            <li>Fazer lançamentos contábeis</li>
                            <li>Conciliar contas bancárias</li>
                            <li>Gerar balancete do mês</li>
                            <li>Revisar e aprovar</li>
                            <li>Enviar balancete para o cliente</li>
                            <li>Gerar DRE e Balanço (mensal ou anual)</li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- 10. Fiscal -->
            <div x-show="activeSection === 'fiscal'" class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                        <span class="text-primary-700 font-bold">10</span>
                    </div>
                    Módulo Fiscal
                </h2>

                <div class="prose max-w-none">
                    <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-3">Emissão de Notas Fiscais</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="border border-blue-200 bg-blue-50 rounded-lg p-4">
                            <h4 class="font-semibold text-blue-900 mb-2">📄 NF-e (Nota Fiscal Eletrônica)</h4>
                            <p class="text-sm text-blue-800">Para venda de produtos</p>
                        </div>
                        <div class="border border-green-200 bg-green-50 rounded-lg p-4">
                            <h4 class="font-semibold text-green-900 mb-2">🛠️ NFS-e (Nota Fiscal de Serviços)</h4>
                            <p class="text-sm text-green-800">Para prestação de serviços</p>
                        </div>
                        <div class="border border-purple-200 bg-purple-50 rounded-lg p-4">
                            <h4 class="font-semibold text-purple-900 mb-2">🛒 NFC-e (Nota Fiscal ao Consumidor)</h4>
                            <p class="text-sm text-purple-800">Para vendas no varejo</p>
                        </div>
                        <div class="border border-amber-200 bg-amber-50 rounded-lg p-4">
                            <h4 class="font-semibold text-amber-900 mb-2">🚚 CT-e (Conhecimento de Transporte)</h4>
                            <p class="text-sm text-amber-800">Para transporte de cargas</p>
                        </div>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-3">Como Emitir uma Nota Fiscal</h3>
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <ol class="list-decimal list-inside space-y-2 text-gray-700">
                            <li>Acesse: Cliente → Centro de Controle → Notas Fiscais</li>
                            <li>Clique em "Emitir Nota Fiscal"</li>
                            <li>Selecione o tipo (NF-e, NFS-e, NFC-e, CT-e)</li>
                            <li>Preencha os dados:
                                <ul class="list-disc list-inside ml-6 mt-1 text-sm">
                                    <li>Destinatário (cliente final)</li>
                                    <li>Produtos/Serviços</li>
                                    <li>Valores e impostos</li>
                                    <li>Forma de pagamento</li>
                                </ul>
                            </li>
                            <li>Revisar e transmitir para a SEFAZ</li>
                            <li>Aguardar autorização</li>
                            <li>Baixar XML e PDF</li>
                        </ol>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-3">Status das Notas</h3>
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs font-semibold rounded">Rascunho</span>
                            <span class="text-sm text-gray-700">Ainda não transmitida</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded">Autorizada</span>
                            <span class="text-sm text-gray-700">Aprovada pela SEFAZ</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded">Cancelada</span>
                            <span class="text-sm text-gray-700">Cancelada dentro do prazo</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-1 bg-amber-100 text-amber-700 text-xs font-semibold rounded">Denegada</span>
                            <span class="text-sm text-gray-700">Rejeitada pela SEFAZ</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded">Rejeitada</span>
                            <span class="text-sm text-gray-700">Erro no envio</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 11. Obrigações -->
            <div x-show="activeSection === 'obrigacoes'" class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                        <span class="text-primary-700 font-bold">11</span>
                    </div>
                    Obrigações Fiscais
                </h2>

                <div class="prose max-w-none">
                    <p class="text-gray-700 mb-4">
                        Controle todas as obrigações fiscais mensais e anuais dos seus clientes.
                    </p>

                    <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-3">Principais Obrigações</h3>

                    <div class="space-y-3 mb-6">
                        <div class="border-l-4 border-blue-500 pl-4">
                            <h4 class="font-semibold text-gray-900">DCTF (Declaração de Débitos e Créditos Tributários)</h4>
                            <p class="text-sm text-gray-600">Mensal - Vencimento: 15º dia útil do 2º mês subsequente</p>
                        </div>
                        <div class="border-l-4 border-green-500 pl-4">
                            <h4 class="font-semibold text-gray-900">SPED Fiscal (EFD ICMS/IPI)</h4>
                            <p class="text-sm text-gray-600">Mensal - Vencimento: até o 20º dia do mês seguinte</p>
                        </div>
                        <div class="border-l-4 border-purple-500 pl-4">
                            <h4 class="font-semibold text-gray-900">SPED Contribuições (EFD Contribuições)</h4>
                            <p class="text-sm text-gray-600">Mensal - Vencimento: 10º dia útil do 2º mês subsequente</p>
                        </div>
                        <div class="border-l-4 border-amber-500 pl-4">
                            <h4 class="font-semibold text-gray-900">DEFIS (Simples Nacional)</h4>
                            <p class="text-sm text-gray-600">Anual - Vencimento: 31 de março</p>
                        </div>
                        <div class="border-l-4 border-red-500 pl-4">
                            <h4 class="font-semibold text-gray-900">DIRF (Declaração do IRRF)</h4>
                            <p class="text-sm text-gray-600">Anual - Vencimento: último dia útil de fevereiro</p>
                        </div>
                        <div class="border-l-4 border-indigo-500 pl-4">
                            <h4 class="font-semibold text-gray-900">RAIS (Relação Anual de Informações Sociais)</h4>
                            <p class="text-sm text-gray-600">Anual - Vencimento: março</p>
                        </div>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-3">Como Gerenciar Obrigações</h3>
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <ol class="list-decimal list-inside space-y-2 text-gray-700">
                            <li>Acesse: Menu → Módulos → Obrigações</li>
                            <li>Visualize o calendário mensal com todas as obrigações</li>
                            <li>Filtre por cliente, tipo ou status</li>
                            <li>Clique em uma obrigação para marcar como concluída</li>
                            <li>Anexe comprovantes de envio</li>
                            <li>Acompanhe obrigações atrasadas (destacadas em vermelho)</li>
                        </ol>
                    </div>

                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mt-6">
                        <div class="flex items-start gap-3">
                            <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 mt-0.5"></i>
                            <div>
                                <h4 class="font-semibold text-red-900 mb-1">Atenção</h4>
                                <p class="text-sm text-red-800">
                                    Obrigações atrasadas podem gerar multas pesadas para seus clientes.
                                    Sempre fique atento aos prazos!
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 12. Financeiro -->
            <div x-show="activeSection === 'financeiro'" class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                        <span class="text-primary-700 font-bold">12</span>
                    </div>
                    Módulo Financeiro
                </h2>

                <div class="prose max-w-none">
                    <p class="text-gray-700 mb-4">
                        Controle financeiro completo: mensalidades, pagamentos e inadimplência.
                    </p>

                    <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-3">Funcionalidades</h3>

                    <ul class="list-disc list-inside space-y-2 text-gray-700 mb-4">
                        <li><strong>Mensalidades:</strong> Geração automática de mensalidades dos clientes</li>
                        <li><strong>Controle de Pagamentos:</strong> Registre pagamentos recebidos</li>
                        <li><strong>Inadimplência:</strong> Identifique clientes em atraso</li>
                        <li><strong>Relatórios:</strong> Receitas, despesas e fluxo de caixa</li>
                        <li><strong>Formas de Pagamento:</strong> Dinheiro, PIX, Boleto, Cartão, Transferência</li>
                    </ul>

                    <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-3">Como Registrar um Pagamento</h3>
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <ol class="list-decimal list-inside space-y-2 text-gray-700">
                            <li>Acesse: Menu → Módulos → Financeiro</li>
                            <li>Localize a mensalidade pendente</li>
                            <li>Clique em "Registrar Pagamento"</li>
                            <li>Informe a data e forma de pagamento</li>
                            <li>Salvar - o status muda para "Pago"</li>
                        </ol>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-3">Status Financeiros</h3>
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-1 bg-amber-100 text-amber-700 text-xs font-semibold rounded">Pendente</span>
                            <span class="text-sm text-gray-700">Aguardando pagamento</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded">Pago</span>
                            <span class="text-sm text-gray-700">Pagamento confirmado</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded">Atrasado</span>
                            <span class="text-sm text-gray-700">Vencimento ultrapassado</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs font-semibold rounded">Cancelado</span>
                            <span class="text-sm text-gray-700">Cobrança cancelada</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Continua nas próximas seções... -->

            <!-- 13. Relatórios -->
            <div x-show="activeSection === 'relatorios'" class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                        <span class="text-primary-700 font-bold">13</span>
                    </div>
                    Relatórios
                </h2>

                <div class="prose max-w-none">
                    <p class="text-gray-700 mb-4">
                        Gere relatórios completos para análise e tomada de decisão.
                    </p>

                    <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-3">Relatórios Disponíveis</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-2">📊 Clientes</h4>
                            <ul class="text-sm text-gray-700 space-y-1">
                                <li>• Lista completa de clientes</li>
                                <li>• Clientes por regime tributário</li>
                                <li>• Clientes ativos/inativos</li>
                            </ul>
                        </div>
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-2">💰 Financeiro</h4>
                            <ul class="text-sm text-gray-700 space-y-1">
                                <li>• Receitas do período</li>
                                <li>• Inadimplência</li>
                                <li>• Fluxo de caixa</li>
                            </ul>
                        </div>
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-2">✅ Tarefas</h4>
                            <ul class="text-sm text-gray-700 space-y-1">
                                <li>• Tarefas por status</li>
                                <li>• Tarefas por responsável</li>
                                <li>• Tarefas atrasadas</li>
                            </ul>
                        </div>
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-2">📋 Obrigações</h4>
                            <ul class="text-sm text-gray-700 space-y-1">
                                <li>• Calendário mensal</li>
                                <li>• Obrigações pendentes</li>
                                <li>• Histórico de entregas</li>
                            </ul>
                        </div>
                    </div>

                    <p class="text-sm text-gray-600">
                        Todos os relatórios podem ser exportados em PDF ou Excel.
                    </p>
                </div>
            </div>

            <!-- 14. Usuários -->
            <div x-show="activeSection === 'usuarios'" class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                        <span class="text-primary-700 font-bold">14</span>
                    </div>
                    Gestão de Usuários
                </h2>

                <div class="prose max-w-none">
                    <p class="text-gray-700 mb-4">
                        Gerencie os usuários que têm acesso ao sistema.
                    </p>

                    <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-3">Tipos de Usuários</h3>

                    <div class="space-y-3 mb-6">
                        <div class="border-l-4 border-red-500 pl-4">
                            <h4 class="font-semibold text-gray-900">👑 Administrador</h4>
                            <p class="text-sm text-gray-700">Acesso total ao sistema, pode criar usuários e alterar configurações</p>
                        </div>
                        <div class="border-l-4 border-blue-500 pl-4">
                            <h4 class="font-semibold text-gray-900">👤 Funcionário</h4>
                            <p class="text-sm text-gray-700">Acesso às funcionalidades operacionais, sem permissões administrativas</p>
                        </div>
                        <div class="border-l-4 border-gray-500 pl-4">
                            <h4 class="font-semibold text-gray-900">🏢 Cliente</h4>
                            <p class="text-sm text-gray-700">Acesso limitado apenas aos dados da própria empresa</p>
                        </div>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-3">Como Cadastrar um Usuário</h3>
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <ol class="list-decimal list-inside space-y-2 text-gray-700">
                            <li>Acesse: Menu → Gestão → Usuários</li>
                            <li>Clique em "Novo Usuário"</li>
                            <li>Preencha: Nome, E-mail, Senha, Tipo de Usuário, Setor</li>
                            <li>Salvar</li>
                            <li>O usuário receberá as credenciais por e-mail</li>
                        </ol>
                    </div>

                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mt-6">
                        <div class="flex items-start gap-3">
                            <i data-lucide="shield-alert" class="w-5 h-5 text-amber-600 mt-0.5"></i>
                            <div>
                                <h4 class="font-semibold text-amber-900 mb-1">Segurança</h4>
                                <p class="text-sm text-amber-800">
                                    Sempre use senhas fortes e nunca compartilhe credenciais entre usuários.
                                    Cada pessoa deve ter seu próprio acesso.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 15. Configurações -->
            <div x-show="activeSection === 'configuracoes'" class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                        <span class="text-primary-700 font-bold">15</span>
                    </div>
                    Configurações do Sistema
                </h2>

                <div class="prose max-w-none">
                    <p class="text-gray-700 mb-4">
                        Personalize o sistema de acordo com as necessidades do seu escritório.
                    </p>

                    <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-3">Configurações Disponíveis</h3>

                    <ul class="list-disc list-inside space-y-2 text-gray-700 mb-4">
                        <li><strong>Dados da Empresa:</strong> Nome, endereço, telefone, e-mail</li>
                        <li><strong>Alertas:</strong> Dias de antecedência para alertas de vencimento</li>
                        <li><strong>Upload:</strong> Limite de tamanho de arquivos</li>
                        <li><strong>Paginação:</strong> Número de itens por página</li>
                        <li><strong>E-mail:</strong> Configurações de servidor SMTP</li>
                        <li><strong>Backup:</strong> Configurar backups automáticos</li>
                    </ul>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-6">
                        <div class="flex items-start gap-3">
                            <i data-lucide="settings" class="w-5 h-5 text-blue-600 mt-0.5"></i>
                            <div>
                                <h4 class="font-semibold text-blue-900 mb-1">Dica</h4>
                                <p class="text-sm text-blue-800">
                                    Apenas administradores podem alterar as configurações do sistema.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 16. Processos Mensais -->
            <div x-show="activeSection === 'processos'" class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                        <span class="text-primary-700 font-bold">16</span>
                    </div>
                    Processos Mensais
                </h2>

                <div class="prose max-w-none">
                    <p class="text-gray-700 mb-4">
                        Checklist completo das atividades mensais do escritório contábil.
                    </p>

                    <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-3">📅 Semana 1 (Dias 1-7)</h3>
                    <div class="bg-blue-50 rounded-lg p-4 mb-4">
                        <ul class="space-y-2 text-gray-700">
                            <li class="flex items-start gap-2">
                                <input type="checkbox" class="mt-1">
                                <span>Coletar documentos do mês anterior (notas fiscais, recibos, extratos)</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <input type="checkbox" class="mt-1">
                                <span>Coletar ponto dos funcionários (horas, faltas, horas extras)</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <input type="checkbox" class="mt-1">
                                <span>Verificar certificados digitais próximos do vencimento</span>
                            </li>
                        </ul>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-3">📅 Semana 2 (Dias 8-15)</h3>
                    <div class="bg-green-50 rounded-lg p-4 mb-4">
                        <ul class="space-y-2 text-gray-700">
                            <li class="flex items-start gap-2">
                                <input type="checkbox" class="mt-1">
                                <span>Fazer lançamentos contábeis do mês anterior</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <input type="checkbox" class="mt-1">
                                <span>Gerar holerites do mês</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <input type="checkbox" class="mt-1">
                                <span>Enviar holerites para os funcionários</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <input type="checkbox" class="mt-1">
                                <span>Gerar guias de INSS, IRRF e FGTS</span>
                            </li>
                        </ul>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-3">📅 Semana 3 (Dias 16-23)</h3>
                    <div class="bg-purple-50 rounded-lg p-4 mb-4">
                        <ul class="space-y-2 text-gray-700">
                            <li class="flex items-start gap-2">
                                <input type="checkbox" class="mt-1">
                                <span>Conciliar contas bancárias</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <input type="checkbox" class="mt-1">
                                <span>Gerar balancetes mensais</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <input type="checkbox" class="mt-1">
                                <span>Enviar SPED Fiscal (até dia 20)</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <input type="checkbox" class="mt-1">
                                <span>Enviar eventos para o eSocial</span>
                            </li>
                        </ul>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-3">📅 Semana 4 (Dias 24-30)</h3>
                    <div class="bg-amber-50 rounded-lg p-4 mb-4">
                        <ul class="space-y-2 text-gray-700">
                            <li class="flex items-start gap-2">
                                <input type="checkbox" class="mt-1">
                                <span>Enviar balancetes para os clientes</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <input type="checkbox" class="mt-1">
                                <span>Verificar obrigações do próximo mês</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <input type="checkbox" class="mt-1">
                                <span>Gerar mensalidades dos clientes</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <input type="checkbox" class="mt-1">
                                <span>Fazer backup do sistema</span>
                            </li>
                        </ul>
                    </div>

                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mt-6">
                        <div class="flex items-start gap-3">
                            <i data-lucide="check-circle" class="w-5 h-5 text-green-600 mt-0.5"></i>
                            <div>
                                <h4 class="font-semibold text-green-900 mb-1">Dica de Organização</h4>
                                <p class="text-sm text-green-800">
                                    Use a funcionalidade de Tarefas do sistema para criar lembretes automáticos
                                    de cada etapa deste processo mensal.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 17. FAQ -->
            <div x-show="activeSection === 'faq'" class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                        <span class="text-primary-700 font-bold">17</span>
                    </div>
                    Perguntas Frequentes (FAQ)
                </h2>

                <div class="prose max-w-none space-y-6">
                    <div class="border-b border-gray-200 pb-4">
                        <h4 class="font-semibold text-gray-900 mb-2">❓ Como recuperar minha senha?</h4>
                        <p class="text-sm text-gray-700">
                            Na tela de login, clique em "Esqueci minha senha". Digite seu e-mail cadastrado
                            e você receberá um link para redefinir a senha.
                        </p>
                    </div>

                    <div class="border-b border-gray-200 pb-4">
                        <h4 class="font-semibold text-gray-900 mb-2">❓ Posso acessar o sistema de qualquer lugar?</h4>
                        <p class="text-sm text-gray-700">
                            Sim! O sistema é baseado na web e pode ser acessado de qualquer dispositivo com
                            internet (computador, tablet ou celular).
                        </p>
                    </div>

                    <div class="border-b border-gray-200 pb-4">
                        <h4 class="font-semibold text-gray-900 mb-2">❓ Como faço backup dos dados?</h4>
                        <p class="text-sm text-gray-700">
                            O sistema faz backups automáticos diários. Você também pode fazer backup manual
                            em Configurações → Backup.
                        </p>
                    </div>

                    <div class="border-b border-gray-200 pb-4">
                        <h4 class="font-semibold text-gray-900 mb-2">❓ Quantos usuários posso cadastrar?</h4>
                        <p class="text-sm text-gray-700">
                            Não há limite de usuários. Você pode cadastrar quantos funcionários precisar.
                        </p>
                    </div>

                    <div class="border-b border-gray-200 pb-4">
                        <h4 class="font-semibold text-gray-900 mb-2">❓ Os dados são seguros?</h4>
                        <p class="text-sm text-gray-700">
                            Sim! Todos os dados são criptografados e armazenados com segurança. Apenas usuários
                            autorizados têm acesso.
                        </p>
                    </div>

                    <div class="border-b border-gray-200 pb-4">
                        <h4 class="font-semibold text-gray-900 mb-2">❓ Como entro em contato com o suporte?</h4>
                        <p class="text-sm text-gray-700">
                            Telefone: (41) 98858-4456<br>
                            E-mail: contato@jcacontabilidadecwb.com.br<br>
                            Horário: Segunda a Sexta, 8h às 18h
                        </p>
                    </div>

                    <div class="border-b border-gray-200 pb-4">
                        <h4 class="font-semibold text-gray-900 mb-2">❓ Posso personalizar o sistema?</h4>
                        <p class="text-sm text-gray-700">
                            Sim! Entre em contato com o suporte para solicitar personalizações específicas
                            para o seu escritório.
                        </p>
                    </div>

                    <div class="bg-primary-50 border border-primary-200 rounded-lg p-4 mt-6">
                        <div class="flex items-start gap-3">
                            <i data-lucide="help-circle" class="w-5 h-5 text-primary-600 mt-0.5"></i>
                            <div>
                                <h4 class="font-semibold text-primary-900 mb-1">Precisa de Ajuda?</h4>
                                <p class="text-sm text-primary-800">
                                    Se sua dúvida não foi respondida aqui, entre em contato com nosso suporte.
                                    Estamos sempre prontos para ajudar!
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();
</script>

<?php include 'includes/footer.php'; ?>

