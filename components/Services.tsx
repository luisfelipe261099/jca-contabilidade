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
        <section id="servicos" className="py-24 px-6">
            <div className="max-w-7xl mx-auto">
                <div className="text-center mb-14 reveal-up">
                    <h2 className="text-3xl md:text-5xl font-black text-[#0B2F47] mb-6">
                        Tudo que sua empresa precisa
                        <span className="block text-transparent bg-clip-text bg-gradient-to-r from-[#007CC3] to-[#33B8AE]">em um so ecossistema.</span>
                    </h2>
                    <p className="text-[#4A687D] max-w-2xl mx-auto font-semibold">
                        Da burocracia a estrategia. Nosso time cuida de cada etapa para voce focar em decisao, crescimento e caixa saudavel.
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
