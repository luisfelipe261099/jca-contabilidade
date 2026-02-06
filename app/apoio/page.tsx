import React from 'react';
import Header from '../../components/Header';
import Footer from '../../components/Footer';
import { ExternalLink, Building2, Globe2, Calculator, FileText } from 'lucide-react';

export default function ApoioPage() {
    const categories = [
        {
            title: "Órgãos Federais",
            icon: <Globe2 className="w-6 h-6 text-blue-400" />,
            links: [
                { name: "Receita Federal (e-CAC)", url: "https://cav.receita.fazenda.gov.br/" },
                { name: "Portal do Empreendedor (MEI)", url: "https://www.gov.br/empresas-e-negocios/pt-br/empreendedor" },
                { name: "Simples Nacional", url: "https://www8.receita.fazenda.gov.br/SimplesNacional/" },
                { name: "Regularize (PGFN)", url: "https://www.regularize.pgfn.gov.br/" },
            ]
        },
        {
            title: "Estaduais (Paraná)",
            icon: <Building2 className="w-6 h-6 text-emerald-400" />,
            links: [
                { name: "Receita Estadual PR", url: "https://www.fazenda.pr.gov.br/" },
                { name: "Junta Comercial (Jucepar)", url: "https://www.jucepar.pr.gov.br/" },
                { name: "Nota Paraná", url: "https://notaparana.pr.gov.br/" },
            ]
        },
        {
            title: "Municipais (Curitiba)",
            icon: <Building2 className="w-6 h-6 text-indigo-400" />,
            links: [
                { name: "Nota Curitiba", url: "https://isscuritiba.curitiba.pr.gov.br/portalnfse/" },
                { name: "Prefeitura de Curitiba (Alvarás)", url: "https://www.curitiba.pr.gov.br/servicos/alvara-comercial-e-de-servicos/76" },
                { name: "CPM - Certidão Negativa", url: "https://cpm.curitiba.pr.gov.br/" },
            ]
        },
        {
            title: "Ferramentas & Consultas",
            icon: <Calculator className="w-6 h-6 text-amber-400" />,
            links: [
                { name: "Consulta CNPJ", url: "https://solucoes.receita.fazenda.gov.br/Servicos/cnpjreva/Cnpjreva_Solicitacao.asp" },
                { name: "Certidão Negativa Federal", url: "https://solucoes.receita.fazenda.gov.br/Servicos/certidaointernet/PJ/Emitir" },
                { name: "Consulta Optantes Simples", url: "https://www8.receita.fazenda.gov.br/SimplesNacional/Aplicacoes/ATSPO/ConsultaOptantes.app/" },
                { name: "Cálculo de Rescisão (Ideal)", url: "https://www.calculadorafacil.com.br/trabalhista/calculo-de-rescisao" },
            ]
        }
    ];

    return (
        <main className="min-h-screen bg-[#020617] text-slate-200 font-sans selection:bg-blue-500/30">
            <Header />

            <section className="pt-24 md:pt-32 pb-20 px-6">
                <div className="max-w-7xl mx-auto">
                    <div className="text-center mb-12 md:mb-16">
                        <h1 className="text-3xl md:text-5xl font-bold text-white mb-6">
                            Links e Recursos <span className="text-blue-500">Úteis</span>
                        </h1>
                        <p className="text-slate-400 max-w-2xl mx-auto text-base md:text-lg">
                            Acesso rápido aos principais portais e ferramentas para facilitar o dia a dia da sua empresa.
                        </p>
                    </div>

                    <div className="grid md:grid-cols-2 gap-6 md:gap-8">
                        {categories.map((category, idx) => (
                            <div key={idx} className="bg-slate-900/50 border border-slate-800 rounded-3xl p-8 hover:bg-slate-900/80 transition-all">
                                <div className="flex items-center gap-4 mb-6">
                                    <div className="w-12 h-12 bg-slate-950 rounded-xl flex items-center justify-center border border-slate-800">
                                        {category.icon}
                                    </div>
                                    <h3 className="text-xl font-bold text-white">{category.title}</h3>
                                </div>

                                <div className="grid gap-3">
                                    {category.links.map((link, i) => (
                                        <a
                                            key={i}
                                            href={link.url}
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            className="group flex items-center justify-between p-4 bg-slate-950/50 rounded-xl border border-slate-800/50 hover:border-blue-500/30 hover:bg-blue-500/5 transition-all"
                                        >
                                            <span className="text-slate-300 font-medium group-hover:text-blue-400 transition-colors">
                                                {link.name}
                                            </span>
                                            <ExternalLink className="w-4 h-4 text-slate-600 group-hover:text-blue-500 transition-colors" />
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
