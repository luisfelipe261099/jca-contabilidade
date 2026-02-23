export const dynamic = 'force-dynamic';

import React from 'react';
import {
    LayoutDashboard,
    Users,
    UserPlus,
    FileText,
    Settings,
    TrendingUp,
    Briefcase,
    DollarSign,
    Landmark,
    ShieldCheck,
    BookOpen,
    HelpCircle,
    CheckCircle2,
    MessageSquare,
    Key
} from 'lucide-react';

export default function ManualPage() {
    const modules = [
        {
            title: "Dashboard (Torre de Controle)",
            icon: LayoutDashboard,
            color: "text-blue-500",
            bg: "bg-blue-500/10",
            description: "O painel de controle da sua nave. Focado em mostrar apenas o que importa para cada departamento.",
            steps: [
                "Veja o total de clientes ativos (filtrado pelo seu departamento).",
                "Acompanhe as tarefas com base de prioridade.",
                "Abra abas rápidas clicando no ícone do 'Lápis' para editar configurações do cliente.",
                "Pesquise pela barra superior para encontrar o cadastro de qualquer empresa."
            ]
        },
        {
            title: "Gestão de Clientes (O Início de Tudo)",
            icon: Users,
            color: "text-emerald-500",
            bg: "bg-emerald-500/10",
            description: "Onde ficam as empresas que você atende. É o coração do sistema. Tudo começa aqui.",
            steps: [
                "1. Clique em 'Novo Cliente' ou use o Cadastro Rápido na tela principal.",
                "2. Preencha Nome, CNPJ e Regime Tributário.",
                "3. IMPORTANTÍSSIMO: Marque todos os serviços que a empresa contratou (Ex: FISCAL, DP / RH, BPO FINANCEIRO).",
                "4. A mágica acontece: O sistema gera as tarefas mensais automaticamente (Ex: Se marcou DP, cria 'Fechamento de Folha').",
                "5. E os funcionários (DP) só verão os clientes que tiverem 'DP' marcado."
            ]
        },
        {
            title: "Cofre de Senhas (Credenciais)",
            icon: Key,
            color: "text-amber-500",
            bg: "bg-amber-500/10",
            description: "Esqueça perder senhas do Gov.br ou e-CAC em planilhas.",
            steps: [
                "Acesse a Gestão de Clientes e encontre a empresa desejada.",
                "Clique no ícone da 'Chave Dourada' ao lado do botão de editar.",
                "Adicione novas senhas de sistemas oficiais, com login, senha secreta e anotações.",
                "As senhas ficam ocultas. Use o botão de 'Dois Quadrados' (Copiar) para copiar a senha em 1 segundo e colar na Receita."
            ]
        },
        {
            title: "Atendimento (Helpdesk / Chamados)",
            icon: MessageSquare,
            color: "text-indigo-500",
            bg: "bg-indigo-500/10",
            description: "Centralize todas as solicitações e não deixe nenhum cliente se sentir esquecido.",
            steps: [
                "Acesse 'Atendimento' no menu.",
                "Crie 'Novo Chamado' (Ticket) selecionando a empresa e a Classificação (Normal, Alta Prioridade, etc).",
                "Identifique rapidamente na fila quantos chamados estão abertos.",
                "Inicie um chamado alterando o status para 'Em Tratativa' e, finalizado, marque 'Concluir'."
            ]
        },
        {
            title: "Central de Documentos (Leitura OCR)",
            icon: FileText,
            color: "text-pink-500",
            bg: "bg-pink-500/10",
            description: "Seu assistente robô que lê guias, PDFS e notas.",
            steps: [
                "Arraste um PDF ou imagem da guia para a área de upload.",
                "O robô inteligente reconhecerá os dados daquele documento automaticamente.",
                "Atribua à empresa certa.",
                "Esse módulo interage com os Protocolos. O cliente recebe e você será notificado assim que ele baixar."
            ]
        },
        {
            title: "Gestão de Permissionamento (Usuários)",
            icon: UserPlus,
            color: "text-purple-500",
            bg: "bg-purple-500/10",
            description: "Crie senhas para seus funcionários e os limites de onde podem chegar.",
            steps: [
                "Crie o login interno definindo o Cargo (EMPLOYEE/ADMIN).",
                "IMPORTANTE: Defina qual o Setor do funcionário (DP, FISCAL, SOCIETÁRIO). Isso bloqueia ele de ver empresas dos outros.",
                "O 'Admin' consegue entrar no Cofre de Senhas e ver dados Estratégicos.",
                "Se precisar criar login para o próprio Cliente baixar as coisas, vincule-o no momento da criação da senha."
            ]
        },
        {
            title: "Folha / DP e Departamentos",
            icon: Briefcase,
            color: "text-cyan-500",
            bg: "bg-cyan-500/10",
            description: "Os atalhos diretos para as obrigações específicas de cada base contábil.",
            steps: [
                "Fiscal controla as Guias, impostos e alvarás fiscais.",
                "DP/RH Cadastra funcionários das empresas pra processar folha e rescisões.",
                "Societário cuida do passo-a-passo e SLA (prazos) para abertura de um CNPJ.",
                "O foco aqui é o isolamento de informações para segurança."
            ]
        },
        {
            title: "Protocolos Rastreados (Auditoria)",
            icon: ShieldCheck,
            color: "text-rose-500",
            bg: "bg-rose-500/10",
            description: "Sua prova jurídica de que um recado civil foi enviado no prazo.",
            steps: [
                "Ao enviar um documento oficial pelo sistema ou registrar uma entrega, o sistema bloqueia edição retroativa.",
                "Quando o cliente baixa pelo login dele, o sistema assinala o minuto em que foi feito o carimbo de Data/Hora.",
                "Você pode provar perante auditoria quem confirmou o recebimento da Guia do Simples Nacional."
            ]
        }
    ];

    return (
        <div className="p-8 max-w-6xl mx-auto text-slate-200">
            <div className="mb-12 text-center">
                <div className="inline-flex items-center justify-center p-4 bg-blue-600/20 rounded-full mb-6 relative group">
                    <div className="absolute inset-0 bg-blue-600/20 blur-xl rounded-full group-hover:blur-2xl transition-all"></div>
                    <BookOpen className="w-12 h-12 text-blue-500 relative z-10" />
                </div>
                <h1 className="text-4xl font-bold text-white mb-4">Central de Integração - Manual JCA ERP</h1>
                <p className="text-lg text-slate-400 max-w-3xl mx-auto">
                    A cartilha para você e seus funcionários não ficarem perdidos. O sistema trabalha por você, desde que o cadastro inicial da empresa seja feito corretamente.
                </p>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                {modules.map((item, index) => {
                    const Icon = item.icon;
                    return (
                        <div key={index} className="bg-slate-900/40 border border-slate-800 rounded-3xl p-8 hover:border-slate-700 transition-all hover:shadow-2xl hover:shadow-blue-900/10 group">
                            <div className="flex items-start gap-4 mb-6">
                                <div className={`w-12 h-12 rounded-2xl ${item.bg} flex items-center justify-center shrink-0`}>
                                    <Icon className={`w-6 h-6 ${item.color}`} />
                                </div>
                                <div>
                                    <h2 className="text-xl font-bold text-white mb-2">{item.title}</h2>
                                    <p className="text-sm text-slate-400 leading-relaxed">
                                        {item.description}
                                    </p>
                                </div>
                            </div>

                            <div className="pl-16">
                                <h3 className="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4 flex items-center gap-2">
                                    <HelpCircle className="w-3 h-3" />
                                    Como Usar
                                </h3>
                                <ul className="space-y-3">
                                    {item.steps.map((step, idx) => (
                                        <li key={idx} className="flex items-start gap-3 text-sm text-slate-300">
                                            <CheckCircle2 className={`w-4 h-4 mt-0.5 shrink-0 ${item.color}`} />
                                            <span>{step}</span>
                                        </li>
                                    ))}
                                </ul>
                            </div>
                        </div>
                    );
                })}
            </div>

            <div className="mt-16 bg-gradient-to-r from-blue-900/20 to-indigo-900/20 rounded-3xl p-8 border border-blue-500/20 text-center">
                <h3 className="text-xl font-bold text-white mb-4">Feito para Acabar com a Desorganização</h3>
                <p className="text-slate-400 mb-6">
                    A regra de ouro: <strong>Cadastre tudo no módulo Gestão de Clientes</strong>. Se a empresa estiver vinculada a &quot;DP&quot; e &quot;Fiscal&quot;, o sistema criará os bloqueios e as tarefas daquele mês para sua equipe automaticamente.
                </p>
                <div className="inline-flex items-center gap-2 px-4 py-2 bg-slate-800 rounded-lg text-sm text-slate-300">
                    <Settings className="w-4 h-4" />
                    <span>Versão do Sistema: JCA 2.0 (Completa)</span>
                </div>
            </div>
        </div>
    );
}
