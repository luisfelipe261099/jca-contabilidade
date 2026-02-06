import React from 'react';

export default function About() {
    return (
        <section className="py-20 px-6 border-y border-slate-900 bg-slate-950/50">
            <div className="max-w-7xl mx-auto">
                <div className="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8">
                    {[
                        { label: 'Clientes Ativos', value: '100+' },
                        { label: 'Anos de Mercado', value: '10+' },
                        { label: 'Satisfação', value: '99%' },
                        { label: 'Serviços Ofertados', value: '15+' },
                    ].map((stat, i) => (
                        <div key={i} className="text-center p-4 md:p-6 rounded-3xl bg-slate-900/40 border border-slate-800/50 hover:bg-slate-900/80 transition-colors">
                            <div className="text-2xl md:text-4xl font-bold text-white mb-2">{stat.value}</div>
                            <div className="text-slate-500 text-[10px] md:text-sm font-medium uppercase tracking-wider">{stat.label}</div>
                        </div>
                    ))}
                </div>
            </div>
        </section>
    );
}
