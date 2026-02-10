import React from 'react';
import { ArrowRight, CheckCircle2 } from 'lucide-react';

export default function Hero() {
    return (
        <section className="relative min-h-[90vh] flex items-center pt-20 overflow-hidden">
            {/* Background Image with Overlay */}
            <div className="absolute inset-0 z-0">
                <img
                    src="https://images.unsplash.com/photo-1556761175-5973dc0f32e7?ixlib=rb-4.0.3&auto=format&fit=crop&w=1932&q=80"
                    alt="Escritório de Contabilidade JCA"
                    className="w-full h-full object-cover opacity-40 mix-blend-overlay"
                />
                <div className="absolute inset-0 bg-gradient-to-b from-[#020617]/90 via-[#020617]/80 to-[#020617] z-10" />
            </div>

            {/* Background Effects */}
            <div className="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full max-w-7xl pointer-events-none z-0">
                <div className="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-blue-600/20 blur-[120px] rounded-full animate-pulse" />
                <div className="absolute bottom-[-10%] right-[-10%] w-[500px] h-[500px] bg-emerald-600/10 blur-[120px] rounded-full" />
            </div>

            <div className="max-w-7xl mx-auto px-6 relative z-20 w-full">
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div className="text-left">
                        <div className="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-[10px] md:text-xs font-bold uppercase tracking-widest mb-6 md:mb-8 backdrop-blur-sm">
                            <span className="relative flex h-2 w-2">
                                <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                <span className="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                            </span>
                            JCA Soluções Contábeis
                        </div>

                        <h1 className="text-4xl sm:text-5xl md:text-6xl font-bold leading-tight mb-6 md:mb-8 text-white text-balance tracking-tight">
                            Contabilidade Consultiva para <span className="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-emerald-400">Impulsionar seu Negócio.</span>
                        </h1>

                        <p className="text-base md:text-lg text-slate-300 mb-8 md:mb-10 max-w-xl leading-relaxed">
                            Mais que guias e impostos. Somos parceiros estratégicos para o crescimento da sua empresa, com atendimento humano e tecnologia de ponta.
                        </p>

                        <div className="flex flex-col sm:flex-row gap-4">
                            <a
                                href="#contato"
                                className="px-8 py-4 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl transition-all shadow-lg shadow-blue-500/25 flex items-center justify-center gap-2 group"
                            >
                                Falar com Contador
                                <ArrowRight className="w-5 h-5 group-hover:translate-x-1 transition-transform" />
                            </a>
                            <a
                                href="#servicos"
                                className="px-8 py-4 bg-white/5 border border-white/10 text-white hover:bg-white/10 font-bold rounded-xl transition-all backdrop-blur-sm flex items-center justify-center"
                            >
                                Nossos Serviços
                            </a>
                        </div>
                    </div>

                    <div className="hidden lg:block relative">
                        <div className="relative rounded-3xl overflow-hidden border border-slate-700/50 shadow-2xl shadow-blue-900/20 group">
                            <div className="absolute inset-0 bg-blue-600/10 mix-blend-overlay z-10 group-hover:bg-transparent transition-all duration-500"></div>
                            <img
                                src="https://images.unsplash.com/photo-1497366216548-37526070297c?ixlib=rb-4.0.3&auto=format&fit=crop&w=2301&q=80"
                                alt="Reunião Estratégica"
                                className="w-full h-auto object-cover transform group-hover:scale-105 transition-transform duration-700"
                            />
                        </div>
                        {/* Floating Cards */}
                        <div className="absolute -bottom-6 -left-6 bg-slate-900/90 backdrop-blur-xl border border-slate-700 p-4 rounded-2xl shadow-xl flex items-center gap-3 animate-bounce-slow">
                            <div className="w-10 h-10 bg-emerald-500/20 rounded-full flex items-center justify-center">
                                <CheckCircle2 className="w-6 h-6 text-emerald-500" />
                            </div>
                            <div>
                                <div className="text-xs text-slate-400 font-bold uppercase">Empresas Ativas</div>
                                <div className="text-xl font-bold text-white">+150</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div className="mt-20 pt-8 border-t border-slate-800/50 flex flex-wrap justify-center md:justify-start gap-x-12 gap-y-6 text-sm text-slate-400 font-medium">
                    <div className="flex items-center gap-2">
                        <CheckCircle2 className="w-5 h-5 text-emerald-500" />
                        <span>Abertura Grátis de Empresa</span>
                    </div>
                    <div className="flex items-center gap-2">
                        <CheckCircle2 className="w-5 h-5 text-emerald-500" />
                        <span>Planejamento Tributário</span>
                    </div>
                    <div className="flex items-center gap-2">
                        <CheckCircle2 className="w-5 h-5 text-emerald-500" />
                        <span>BPO Financeiro</span>
                    </div>
                </div>
            </div>
        </section>
    );
}
