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
    ArrowRight
} from 'lucide-react';

export default function ManualPage() {
    const modules = [
        {
            title: "Dashboard (Visão Geral)",
            icon: LayoutDashboard,
            color: "text-blue-500",
            bg: "bg-blue-500/10",
            description: "O painel de controle da sua nave. Aqui você vê tudo o que está acontecendo agora.",
            steps: [
                "Veja o total de clientes ativos.",
                "Acompanhe as tarefas pendentes do dia.",
                "Visualize os últimos documentos enviados.",
                "Monitore a saúde financeira do escritório."
            ]
        },
        {
            title: "Clientes (Empresas)",
            icon: Users,
            color: "text-emerald-500",
            bg: "bg-emerald-500/10",
            description: "Onde ficam as empresas que você atende. É o coração do sistema.",
            steps: [
                "Clique em 'Novo Cliente' para cadastrar uma empresa.",
                "Preencha CNPJ, Nome e Regime Tributário.",
                "O sistema cria automaticamente as obrigações mensais baseadas no regime.",
                "Use a busca para encontrar qualquer empresa rapidamente."
            ]
        },
        {
            title: "Usuários (Acessos)",
            icon: UserPlus,
            color: "text-purple-500",
            bg: "bg-purple-500/10",
            description: "Aqui você cria as chaves de entrada para o sistema, tanto para sua equipe quanto para seus clientes.",
            steps: [
                "Crie usuários do tipo 'ADMIN' para sua equipe interna.",
                "Crie usuários do tipo 'CLIENTE' para os donos das empresas.",
                "Importante: Ao criar um usuário Cliente, vincule-o à Empresa correspondente.",
                "Você pode resetar senhas aqui se alguém esquecer."
            ]
        },
        {
            title: "Central de Documentos (OCR)",
            icon: FileText,
            color: "text-pink-500",
            bg: "bg-pink-500/10",
            description: "Seu assistente robô. Ele lê guias e notas fiscais para você não precisar digitar.",
            steps: [
                "Arraste um PDF ou imagem para a área de upload.",
                "O robô vai tentar ler o CNPJ e o Valor.",
                "Se ele não conseguir ler tudo, você pode completar manualmente.",
                "Selecione o Cliente e clique em 'Lançar Documento'.",
                "Pronto! O documento já aparece na hora para o cliente baixar."
            ]
        },
        {
            title: "Fiscal (Impostos)",
            icon: Briefcase,
            color: "text-orange-500",
            bg: "bg-orange-500/10",
            description: "Controle de DAS, ICMS, ISS e todas as guias de pagamento.",
            steps: [
                "Cadastre uma nova guia clicando em 'Nova Guia'.",
                "Defina o valor e o vencimento.",
                "O cliente recebe um aviso de 'Pendente'.",
                "Quando o cliente pagar, você ou ele podem mudar o status para 'Pago'."
            ]
        },
        {
            title: "Folha / DP (Funcionários)",
            icon: Users,
            color: "text-cyan-500",
            bg: "bg-cyan-500/10",
            description: "Gestão completa dos funcionários das empresas dos seus clientes.",
            steps: [
                "Cadastre os funcionários de cada cliente.",
                "Controle salários, cargos e datas de admissão.",
                "Gerencie status como 'Férias', 'Afastado' ou 'Ativo'.",
                "Use essa base para gerar a folha de pagamento mensal."
            ]
        },
        {
            title: "Societário (Legal)",
            icon: Landmark,
            color: "text-amber-500",
            bg: "bg-amber-500/10",
            description: "Abertura, alteração e encerramento de empresas.",
            steps: [
                "Acompanhe processos de abertura de CNPJ.",
                "Atualize em qual etapa o processo está (Ex: 'Na Junta Comercial').",
                "O cliente consegue acompanhar o progresso sem te ligar toda hora.",
                "Mantenha o histórico de alterações contratuais."
            ]
        },
        {
            title: "Financeiro Interno",
            icon: DollarSign,
            color: "text-green-500",
            bg: "bg-green-500/10",
            description: "O bolso da JCA Contabilidade. Controle seus honorários.",
            steps: [
                "Lance seus honorários mensais para cada cliente.",
                "Controle quem já pagou e quem está inadimplente.",
                "Registre despesas internas do escritório.",
                "Veja gráficos de receita vs. despesa."
            ]
        },
        {
            title: "Protocolos (Auditoria)",
            icon: ShieldCheck,
            color: "text-indigo-500",
            bg: "bg-indigo-500/10",
            description: "Sua segurança jurídica. A prova de que você entregou o que precisava.",
            steps: [
                "Tudo o que é enviado gera um protocolo automático.",
                "Você pode criar protocolos manuais para documentos físicos.",
                "O sistema registra a data e hora exata da visualização pelo cliente.",
                "Use isso para provar que enviou a guia antes do vencimento."
            ]
        },
        {
            title: "Estratégico (BI)",
            icon: TrendingUp,
            color: "text-rose-500",
            bg: "bg-rose-500/10",
            description: "Inteligência de negócios. Dados para tomada de decisão.",
            steps: [
                "Veja quais clientes dão mais lucro.",
                "Identifique qual regime tributário é mais comum na sua carteira.",
                "Analise o crescimento do escritório mês a mês."
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
                <h1 className="text-4xl font-bold text-white mb-4">Manual Completo JCA ERP</h1>
                <p className="text-lg text-slate-400 max-w-2xl mx-auto">
                    Domine cada módulo do sistema. Clique em um tópico abaixo para entender como ele funciona.
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
                <h3 className="text-xl font-bold text-white mb-4">Ainda com dúvidas?</h3>
                <p className="text-slate-400 mb-6">
                    Se precisar de suporte técnico avançado, entre em contato com o desenvolvedor responsável.
                </p>
                <div className="inline-flex items-center gap-2 px-4 py-2 bg-slate-800 rounded-lg text-sm text-slate-300">
                    <Settings className="w-4 h-4" />
                    <span>Versão do Sistema: 1.2.0 (Stable)</span>
                </div>
            </div>
        </div>
    );
}
