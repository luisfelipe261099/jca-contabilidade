import React from 'react';
import { ArrowRight, CheckCircle2 } from 'lucide-react';

export default function Hero() {
    return (
        <section className="relative pt-32 pb-20 px-6 overflow-hidden">
            {/* Background Effects */}
            <div className="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full max-w-7xl pointer-events-none">
                <div className="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-blue-600/20 blur-[120px] rounded-full animate-pulse" />
                <div className="absolute bottom-[-10%] right-[-10%] w-[500px] h-[500px] bg-emerald-600/10 blur-[120px] rounded-full" />
            </div>

            <div className="max-w-7xl mx-auto text-center relative z-10">
                <div className="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs font-bold uppercase tracking-widest mb-8">
                    <span className="relative flex h-2 w-2">
                        <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                        <span className="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                    </span>
                    JCA Soluções Contábeis
                </div>

                <h1 className="text-5xl md:text-7xl font-bold leading-tight mb-8 text-white text-balance">
                    Soluções contábeis sob medida para o <span className="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-emerald-400">crescimento da sua empresa.</span>
                </h1>

                <p className="text-lg md:text-xl text-slate-400 mb-10 max-w-3xl mx-auto leading-relaxed">
                    Do MEI às grandes empresas. Atendemos mais de 100 clientes com pacotes flexíveis:
                    Contábil, RH, Fiscal ou Full-Service. Focamos na burocracia para você focar no negócio.
                </p>

                <div className="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <a
                        href="#contato"
                        className="px-8 py-4 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-full transition-all shadow-lg shadow-blue-500/25 flex items-center gap-2 group"
                    >
                        Falar com Especialista
                        <ArrowRight className="w-5 h-5 group-hover:translate-x-1 transition-transform" />
                    </a>
                    <a
                        href="#servicos"
                        className="px-8 py-4 bg-slate-900 border border-slate-800 text-slate-300 hover:text-white font-bold rounded-full hover:bg-slate-800 transition-all"
                    >
                        Conhecer Soluções
                    </a>
                </div>

                <div className="mt-12 flex flex-wrap justify-center gap-x-8 gap-y-4 text-sm text-slate-500">
                    <div className="flex items-center gap-2">
                        <CheckCircle2 className="w-4 h-4 text-emerald-500" />
                        <span>Atendimento Personalizado</span>
                    </div>
                    <div className="flex items-center gap-2">
                        <CheckCircle2 className="w-4 h-4 text-emerald-500" />
                        <span>Tecnologia de Ponta</span>
                    </div>
                    <div className="flex items-center gap-2">
                        <CheckCircle2 className="w-4 h-4 text-emerald-500" />
                        <span>+10 Anos de Experiência</span>
                    </div>
                </div>
            </div>
        </section>
    );
}
