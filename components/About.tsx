import React from 'react';

export default function About() {
    return (
        <section className="py-20 px-6">
            <div className="max-w-7xl mx-auto">
                <div className="mb-10 text-center reveal-up">
                    <h2 className="text-3xl md:text-5xl font-black tracking-tight text-[#0B2F47]">Resultados que sustentam crescimento</h2>
                    <p className="text-[#4C6A7E] font-semibold mt-3 max-w-2xl mx-auto">Cada numero reflete processos bem executados, proximidade no atendimento e compromisso com a evolucao dos clientes.</p>
                </div>

                <div className="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                    {[
                        { label: 'Clientes ativos', value: '100+' },
                        { label: 'Anos de mercado', value: '10+' },
                        { label: 'Satisfacao', value: '99%' },
                        { label: 'Servicos ofertados', value: '15+' },
                    ].map((stat, i) => (
                        <div key={i} className="text-center p-5 md:p-7 rounded-3xl bg-white border border-sky-100 shadow-[0_14px_35px_rgba(9,65,102,0.11)] hover:-translate-y-1 transition-transform duration-300">
                            <div className="text-3xl md:text-4xl font-black text-[#007CC3] mb-2">{stat.value}</div>
                            <div className="text-[#547084] text-[10px] md:text-sm font-extrabold uppercase tracking-[0.13em]">{stat.label}</div>
                        </div>
                    ))}
                </div>
            </div>
        </section>
    );
}
