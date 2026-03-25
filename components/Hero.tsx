import React from 'react';
import { ArrowRight, CheckCircle2, Sparkles } from 'lucide-react';

export default function Hero() {
    return (
        <section className="relative pt-36 pb-20 overflow-hidden brand-grid">
            <div className="absolute inset-0 z-0 pointer-events-none">
                <div className="absolute -top-36 -left-24 w-[420px] h-[420px] rounded-full bg-[#007CC3]/20 blur-[100px] pulse-soft" />
                <div className="absolute -bottom-32 -right-28 w-[430px] h-[430px] rounded-full bg-[#43C2B2]/25 blur-[110px] pulse-soft" />
            </div>

            <div className="max-w-7xl mx-auto px-6 relative z-10 w-full">
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-14 items-center">
                    <div className="text-left reveal-up">
                        <div className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-sky-100 text-[#0F5E88] text-[11px] font-extrabold uppercase tracking-[0.14em] mb-6">
                            <span className="relative flex h-2 w-2">
                                <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#33B8AE] opacity-75"></span>
                                <span className="relative inline-flex rounded-full h-2 w-2 bg-[#007CC3]"></span>
                            </span>
                            Contabilidade consultiva digital
                        </div>

                        <h1 className="text-4xl sm:text-5xl md:text-6xl font-black leading-tight mb-6 text-balance tracking-tight text-[#0B2F47]">
                            Sua empresa cresce melhor com uma contabilidade
                            <span className="block text-transparent bg-clip-text bg-gradient-to-r from-[#007CC3] to-[#33B8AE]">
                                estrategica, humana e digital.
                            </span>
                        </h1>

                        <p className="text-base md:text-lg text-[#406173] mb-8 max-w-xl leading-relaxed font-semibold">
                            Da abertura do CNPJ ao planejamento tributario, voce tem um time que simplifica sua rotina e protege suas decisoes com dados.
                        </p>

                        <div className="flex flex-col sm:flex-row gap-4">
                            <a
                                href="#contato"
                                className="px-8 py-4 rounded-2xl bg-gradient-to-r from-[#007CC3] to-[#33B8AE] text-white font-extrabold transition-all shadow-[0_16px_36px_rgba(0,124,195,0.32)] flex items-center justify-center gap-2 group"
                            >
                                Falar com Contador
                                <ArrowRight className="w-5 h-5 group-hover:translate-x-1 transition-transform" />
                            </a>
                            <a
                                href="#servicos"
                                className="px-8 py-4 rounded-2xl border border-[#007CC3]/20 text-[#0F5E88] bg-white hover:bg-sky-50 font-extrabold transition-all flex items-center justify-center"
                            >
                                Nossos Serviços
                            </a>
                        </div>
                    </div>

                    <div className="relative reveal-up" style={{ animationDelay: '140ms' }}>
                        <div className="brand-card p-7 md:p-8">
                            <div className="flex items-center justify-between mb-6">
                                <div>
                                    <p className="text-sm font-extrabold uppercase tracking-[0.14em] text-[#1D79AD]">Painel de resultados</p>
                                    <h3 className="text-2xl font-black text-[#083452] mt-1">Visao financeira em tempo real</h3>
                                </div>
                                <Sparkles className="w-7 h-7 text-[#33B8AE]" />
                            </div>

                            <div className="grid grid-cols-3 gap-3">
                                {[
                                    { label: 'Empresas', value: '+150' },
                                    { label: 'Protocolos', value: '99.2%' },
                                    { label: 'SLA Medio', value: '<24h' },
                                ].map((card) => (
                                    <div key={card.label} className="rounded-2xl bg-[#0B3F61] px-4 py-5 text-center">
                                        <div className="text-2xl font-black text-white">{card.value}</div>
                                        <div className="text-[11px] uppercase tracking-[0.12em] text-cyan-100 mt-1">{card.label}</div>
                                    </div>
                                ))}
                            </div>

                            <div className="mt-6 rounded-2xl bg-gradient-to-r from-[#007CC3] to-[#33B8AE] px-4 py-3 text-white font-bold flex items-center justify-between">
                                <span>Atendimento digital + humano</span>
                                <CheckCircle2 className="w-5 h-5" />
                            </div>
                        </div>

                        <div className="absolute -bottom-5 -right-4 bg-white rounded-2xl border border-sky-100 p-4 shadow-xl flex items-center gap-3">
                            <div className="w-10 h-10 rounded-full bg-[#E8F9F6] flex items-center justify-center">
                                <CheckCircle2 className="w-5 h-5 text-[#2B9F90]" />
                            </div>
                            <div>
                                <div className="text-[11px] text-[#4D6B7D] font-bold uppercase">Confianca do cliente</div>
                                <div className="text-lg font-black text-[#0B2F47]">NPS 94</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div className="mt-16 pt-8 border-t border-sky-100/90 flex flex-wrap justify-center md:justify-start gap-x-10 gap-y-4 text-sm font-bold text-[#436579] reveal-up" style={{ animationDelay: '220ms' }}>
                    <div className="flex items-center gap-2">
                        <CheckCircle2 className="w-5 h-5 text-[#2B9F90]" />
                        <span>Abertura de empresa</span>
                    </div>
                    <div className="flex items-center gap-2">
                        <CheckCircle2 className="w-5 h-5 text-[#2B9F90]" />
                        <span>Planejamento tributario</span>
                    </div>
                    <div className="flex items-center gap-2">
                        <CheckCircle2 className="w-5 h-5 text-[#2B9F90]" />
                        <span>BPO Financeiro</span>
                    </div>
                </div>
            </div>
        </section>
    );
}
