import React from 'react';
import { Calculator, Users, Building2, TrendingUp, BadgeDollarSign, FileSearch, ShieldCheck, PieChart } from 'lucide-react';
import ServiceCard from './ServiceCard';

export default function Services() {
    const services = [
        {
            icon: <Calculator className="w-6 h-6" />,
            title: "Assessoria Contábil",
            description: "Escrituração completa, balancetes e relatórios gerenciais para tomadas de decisão assertivas."
        },
        {
            icon: <Users className="w-6 h-6" />,
            title: "RH & Folha de Pagamento",
            description: "Gestão completa de funcionários, admissões, rescisões, eSocial e benefícios."
        },
        {
            icon: <Building2 className="w-6 h-6" />,
            title: "Abertura de Empresas",
            description: "Cuidamos de toda burocracia para você abrir seu negócio com rapidez e segurança jurídica."
        },
        {
            icon: <BadgeDollarSign className="w-6 h-6" />,
            title: "MEI Especializado",
            description: "Suporte completo para Microempreendedores Individuais: declarações, guias e regularização."
        },
        {
            icon: <TrendingUp className="w-6 h-6" />,
            title: "Planejamento Tributário",
            description: "Análise profunda para enquadrar sua empresa no regime que paga menos impostos legalmente."
        },
        {
            icon: <FileSearch className="w-6 h-6" />,
            title: "Imposto de Renda",
            description: "Declaração de IRPF e IRPJ com cruzamento de dados para evitar malha fina."
        },
        {
            icon: <ShieldCheck className="w-6 h-6" />,
            title: "Malha Fiscal",
            description: "Diagnóstico e resolução de pendências junto à Receita Federal e órgãos públicos."
        },
        {
            icon: <PieChart className="w-6 h-6" />,
            title: "BPO Financeiro",
            description: "Terceirização do seu financeiro: contas a pagar, receber e fluxo de caixa."
        }
    ];

    return (
        <section id="servicos" className="py-24 px-6 bg-[#020617]">
            <div className="max-w-7xl mx-auto">
                <div className="text-center mb-16">
                    <h2 className="text-3xl md:text-5xl font-bold text-white mb-6">
                        Tudo o que sua empresa precisa <br />
                        <span className="text-blue-500">em um só lugar.</span>
                    </h2>
                    <p className="text-slate-400 max-w-2xl mx-auto">
                        Da burocracia à estratégia. Nossos especialistas cuidam de cada detalhe para que você tenha tranquilidade e foco no crescimento.
                    </p>
                </div>

                <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                    {services.map((service, index) => (
                        <ServiceCard
                            key={index}
                            icon={service.icon}
                            title={service.title}
                            description={service.description}
                        />
                    ))}
                </div>
            </div>
        </section>
    );
}
