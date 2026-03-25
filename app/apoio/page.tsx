import React from 'react';
import Header from '../../components/Header';
import Footer from '../../components/Footer';
import { ExternalLink, Building2, Globe2, Calculator } from 'lucide-react';

export default function ApoioPage() {
    const categories = [
        {
            title: "Orgaos Federais",
            icon: <Globe2 className="w-6 h-6 text-[#007CC3]" />,
            links: [
                { name: "Receita Federal (e-CAC)", url: "https://cav.receita.fazenda.gov.br/" },
                { name: "Portal do Empreendedor (MEI)", url: "https://www.gov.br/empresas-e-negocios/pt-br/empreendedor" },
                { name: "Simples Nacional", url: "https://www8.receita.fazenda.gov.br/SimplesNacional/" },
                { name: "Regularize (PGFN)", url: "https://www.regularize.pgfn.gov.br/" },
            ]
        },
        {
            title: "Estaduais (Parana)",
            icon: <Building2 className="w-6 h-6 text-[#2AA899]" />,
            links: [
                { name: "Receita Estadual PR", url: "https://www.fazenda.pr.gov.br/" },
                { name: "Junta Comercial (Jucepar)", url: "https://www.jucepar.pr.gov.br/" },
                { name: "Nota Paraná", url: "https://notaparana.pr.gov.br/" },
            ]
        },
        {
            title: "Municipais (Curitiba)",
            icon: <Building2 className="w-6 h-6 text-[#1D79AD]" />,
            links: [
                { name: "Nota Curitiba", url: "https://isscuritiba.curitiba.pr.gov.br/portalnfse/" },
                { name: "Prefeitura de Curitiba (Alvarás)", url: "https://www.curitiba.pr.gov.br/servicos/alvara-comercial-e-de-servicos/76" },
                { name: "CPM - Certidão Negativa", url: "https://cpm.curitiba.pr.gov.br/" },
            ]
        },
        {
            title: "Ferramentas e Consultas",
            icon: <Calculator className="w-6 h-6 text-[#2AA899]" />,
            links: [
                { name: "Consulta CNPJ", url: "https://solucoes.receita.fazenda.gov.br/Servicos/cnpjreva/Cnpjreva_Solicitacao.asp" },
                { name: "Certidão Negativa Federal", url: "https://solucoes.receita.fazenda.gov.br/Servicos/certidaointernet/PJ/Emitir" },
                { name: "Consulta Optantes Simples", url: "https://www8.receita.fazenda.gov.br/SimplesNacional/Aplicacoes/ATSPO/ConsultaOptantes.app/" },
                { name: "Cálculo de Rescisão (Ideal)", url: "https://www.calculadorafacil.com.br/trabalhista/calculo-de-rescisao" },
            ]
        }
    ];

    return (
        <main className="min-h-screen text-[#163244] selection:bg-cyan-200/70">
            <Header />

            <section className="pt-24 md:pt-32 pb-20 px-6">
                <div className="max-w-7xl mx-auto">
                    <div className="text-center mb-12 md:mb-16 reveal-up">
                        <h1 className="text-3xl md:text-5xl font-black text-[#0B2F47] mb-6">
                            Links e recursos
                            <span className="block text-transparent bg-clip-text bg-gradient-to-r from-[#007CC3] to-[#33B8AE]">essenciais para seu negocio</span>
                        </h1>
                        <p className="text-[#4D6B7D] max-w-2xl mx-auto text-base md:text-lg font-semibold">
                            Acesso rapido aos principais portais e ferramentas para facilitar o dia a dia da sua empresa.
                        </p>
                    </div>

                    <div className="grid md:grid-cols-2 gap-6 md:gap-8">
                        {categories.map((category, idx) => (
                            <div key={idx} className="brand-card p-8">
                                <div className="flex items-center gap-4 mb-6">
                                    <div className="w-12 h-12 bg-gradient-to-br from-[#E7F5FF] to-[#E8FBF7] rounded-xl flex items-center justify-center border border-sky-100">
                                        {category.icon}
                                    </div>
                                    <h3 className="text-xl font-black text-[#0B2F47]">{category.title}</h3>
                                </div>

                                <div className="grid gap-3">
                                    {category.links.map((link, i) => (
                                        <a
                                            key={i}
                                            href={link.url}
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            className="group flex items-center justify-between p-4 bg-white rounded-xl border border-sky-100 hover:border-[#48BFB0]/45 hover:-translate-y-0.5 transition-all"
                                        >
                                            <span className="text-[#436378] font-bold group-hover:text-[#007CC3] transition-colors">
                                                {link.name}
                                            </span>
                                            <ExternalLink className="w-4 h-4 text-[#6D8797] group-hover:text-[#007CC3] transition-colors" />
                                        </a>
                                    ))}
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            <Footer />
        </main>
    );
}
