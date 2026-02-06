import React from 'react';

export default function Home() {
    return (
        <main className="min-h-screen bg-[#020617] text-slate-200 font-sans selection:bg-blue-500/30">
            {/* Background Glows */}
            <div className="fixed top-0 left-0 w-full h-full overflow-hidden pointer-events-none -z-10">
                <div className="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-blue-600/20 blur-[120px] rounded-full animate-pulse" />
                <div className="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-indigo-600/20 blur-[120px] rounded-full animate-pulse" style={{ animationDelay: '2s' }} />
            </div>

            {/* Navigation */}
            <nav className="sticky top-0 z-50 backdrop-blur-md border-b border-slate-800/50 bg-slate-950/50">
                <div className="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
                    <div className="flex items-center gap-3">
                        <div className="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/20">
                            <span className="font-bold text-white text-xl">J</span>
                        </div>
                        <span className="text-xl font-bold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-white to-slate-400">
                            JCA Contabilidade
                        </span>
                    </div>

                    <div className="hidden md:flex items-center gap-8 text-sm font-medium text-slate-400">
                        <a href="#" className="hover:text-white transition-colors">Serviços</a>
                        <a href="#" className="hover:text-white transition-colors">Soluções</a>
                        <a href="#" className="hover:text-white transition-colors">Contato</a>
                        <a
                            href="#"
                            className="px-5 py-2.5 bg-slate-800 text-slate-500 cursor-not-allowed rounded-full transition-all flex items-center gap-2"
                        >
                            ERP em Breve
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M5 12h14" /><path d="m12 5 7 7-7 7" /></svg>
                        </a>
                    </div>
                </div>
            </nav>

            {/* Hero Section */}
            <section className="relative pt-32 pb-20 px-6">
                <div className="max-w-7xl mx-auto grid lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <div className="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs font-bold uppercase tracking-widest mb-6">
                            <span className="relative flex h-2 w-2">
                                <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                <span className="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                            </span>
                            Tecnologia de Ponta
                        </div>
                        <h1 className="text-5xl lg:text-7xl font-bold leading-tight mb-8 text-balance">
                            Gestão inteligente para o seu <span className="text-blue-500">futuro financeiro.</span>
                        </h1>
                        <p className="text-lg text-slate-400 mb-10 max-w-xl leading-relaxed">
                            Transformamos a contabilidade tradicional em uma experiência digital, ágil e segura. Otimize seus processos com nosso novo ERP baseado em Next.js e Prisma.
                        </p>
                        <div className="flex flex-col sm:flex-row gap-4">
                            <button className="px-8 py-4 bg-white text-black font-bold rounded-2xl hover:bg-slate-200 transition-all flex items-center justify-center gap-2">
                                Começar Agora
                            </button>
                            <button className="px-8 py-4 bg-slate-900 border border-slate-800 text-white font-bold rounded-2xl hover:bg-slate-800 transition-all flex items-center justify-center gap-2">
                                Saiba Mais
                            </button>
                        </div>
                    </div>

                    <div className="relative">
                        <div className="absolute inset-0 bg-blue-500/30 blur-[100px] rounded-full animate-pulse" />
                        <div className="relative bg-slate-900/50 backdrop-blur-2xl border border-slate-800 rounded-3xl p-8 shadow-2xl overflow-hidden group">
                            <div className="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 to-indigo-600" />
                            <div className="flex items-center justify-between mb-8">
                                <div className="flex gap-1.5">
                                    <div className="w-3 h-3 rounded-full bg-red-500/50" />
                                    <div className="w-3 h-3 rounded-full bg-amber-500/50" />
                                    <div className="w-3 h-3 rounded-full bg-green-500/50" />
                                </div>
                                <div className="px-3 py-1 rounded-md bg-slate-800 text-[10px] text-slate-500 font-mono tracking-widest uppercase">
                                    erp.jca.dashboard
                                </div>
                            </div>
                            <div className="space-y-6">
                                <div className="h-4 w-3/4 bg-slate-800 rounded-full animate-pulse" />
                                <div className="grid grid-cols-3 gap-4">
                                    <div className="h-20 bg-slate-800/50 rounded-2xl border border-slate-700/50 p-4">
                                        <div className="h-2 w-1/2 bg-blue-500/30 rounded mb-4" />
                                        <div className="h-4 w-full bg-blue-500/50 rounded" />
                                    </div>
                                    <div className="h-20 bg-slate-800/50 rounded-2xl border border-slate-700/50 p-4">
                                        <div className="h-2 w-1/2 bg-indigo-500/30 rounded mb-4" />
                                        <div className="h-4 w-full bg-indigo-500/50 rounded" />
                                    </div>
                                    <div className="h-20 bg-slate-800/50 rounded-2xl border border-slate-700/50 p-4">
                                        <div className="h-2 w-1/2 bg-emerald-500/30 rounded mb-4" />
                                        <div className="h-4 w-full bg-emerald-500/50 rounded" />
                                    </div>
                                </div>
                                <div className="h-32 bg-slate-800/30 rounded-2xl border border-slate-700/50 flex items-end p-4 gap-2">
                                    <div className="h-1/2 w-full bg-blue-500/20 rounded-t-lg" />
                                    <div className="h-3/4 w-full bg-blue-500/40 rounded-t-lg" />
                                    <div className="h-2/3 w-full bg-blue-500/30 rounded-t-lg" />
                                    <div className="h-full w-full bg-blue-500/60 rounded-t-lg" />
                                    <div className="h-1/3 w-full bg-blue-500/20 rounded-t-lg" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* Stats */}
            <section className="py-20 px-6 border-t border-slate-900">
                <div className="max-w-7xl mx-auto grid md:grid-cols-4 gap-8">
                    {[
                        { label: 'Clientes Ativos', value: '500+' },
                        { label: 'Processos Mensais', value: '10k+' },
                        { label: 'Uptime Sistema', value: '99.9%' },
                        { label: 'Economia Gerada', value: 'R$ 2M+' },
                    ].map((stat, i) => (
                        <div key={i} className="text-center p-6 rounded-3xl bg-slate-900/40 border border-slate-800/50">
                            <div className="text-3xl font-bold text-white mb-2">{stat.value}</div>
                            <div className="text-slate-500 text-sm">{stat.label}</div>
                        </div>
                    ))}
                </div>
            </section>
        </main>
    );
}
