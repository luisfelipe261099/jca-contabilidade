export const dynamic = 'force-dynamic';

import React from 'react';
import {
    TrendingUp,
    ArrowUpRight,
    ArrowDownRight,
    BarChart3,
    PieChart,
    Activity,
    Calendar,
    Target
} from 'lucide-react';

export default async function BIPage() {
    // Dados fictícios para demonstração de BI
    const summary = [
        { label: 'Faturamento Mensal', value: 'R$ 142.500', growth: '+12.5%', isUp: true },
        { label: 'Novos Clientes', value: '08', growth: '+2', isUp: true },
        { label: 'Eficiência Operacional', value: '94%', growth: '-1.4%', isUp: false },
        { label: 'Economia OCR', value: 'R$ 4.200', growth: '+8%', isUp: true },
    ];

    const topClients = [
        { name: 'Indústria Metalúrgica JCA', revenue: 'R$ 25.400', share: '18%' },
        { name: 'Transportes Rápidos', revenue: 'R$ 18.200', share: '13%' },
        { name: 'Comércio de Grãos LTDA', revenue: 'R$ 15.600', share: '11%' },
        { name: 'Serviços de Tecnologia', revenue: 'R$ 12.100', share: '8%' },
    ];

    return (
        <div className="p-8">
            <div className="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                <div>
                    <h1 className="text-3xl font-bold text-white mb-2">Estratégico & BI</h1>
                    <p className="text-slate-400">Visão analítica e indicadores de performance da JCA.</p>
                </div>
                <div className="flex gap-4">
                    <div className="bg-slate-900/50 border border-slate-800 rounded-xl px-4 py-2 flex items-center gap-2 text-slate-300 text-sm">
                        <Calendar className="w-4 h-4" />
                        <span>Fev 2026</span>
                    </div>
                </div>
            </div>

            {/* Main Stats */}
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                {summary.map((stat, i) => (
                    <div key={i} className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl relative overflow-hidden group">
                        <div className="absolute -right-4 -bottom-4 w-24 h-24 bg-blue-500/5 rounded-full group-hover:bg-blue-500/10 transition-colors" />
                        <div className="text-slate-500 text-xs font-bold uppercase tracking-widest mb-2">{stat.label}</div>
                        <div className="text-2xl font-bold text-white flex items-end gap-3">
                            {stat.value}
                            <span className={`text-[10px] pb-1 flex items-center gap-0.5 ${stat.isUp ? 'text-emerald-500' : 'text-rose-500'}`}>
                                {stat.isUp ? <ArrowUpRight className="w-3 h-3" /> : <ArrowDownRight className="w-3 h-3" />}
                                {stat.growth}
                            </span>
                        </div>
                    </div>
                ))}
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {/* Revenue Chart Placeholder */}
                <div className="lg:col-span-2 bg-slate-900/40 border border-slate-800 rounded-3xl p-8">
                    <div className="flex items-center justify-between mb-8">
                        <div>
                            <h2 className="text-xl font-bold text-white mb-1 flex items-center gap-2">
                                <BarChart3 className="w-5 h-5 text-blue-500" />
                                Crescimento de Faturamento
                            </h2>
                            <p className="text-slate-500 text-xs">Comparativo entre os últimos 6 meses.</p>
                        </div>
                        <div className="flex gap-2">
                            <span className="w-3 h-3 rounded-full bg-blue-600"></span>
                            <span className="w-3 h-3 rounded-full bg-blue-900"></span>
                        </div>
                    </div>

                    {/* Simulated Chart Bars */}
                    <div className="h-64 flex items-end justify-between gap-4 px-4 border-b border-slate-800 mb-4">
                        {[40, 65, 45, 90, 75, 85].map((height, i) => (
                            <div key={i} className="flex-1 space-y-2 group">
                                <div
                                    className="w-full bg-gradient-to-t from-blue-600/20 to-blue-500 rounded-t-lg transition-all duration-500 group-hover:from-blue-600 group-hover:to-blue-400 group-hover:shadow-[0_0_20px_rgba(59,130,246,0.5)]"
                                    style={{ height: `${height}%` }}
                                />
                                <div className="text-[10px] font-bold text-slate-600 text-center uppercase">Mês {i + 1}</div>
                            </div>
                        ))}
                    </div>
                </div>

                {/* Top Clients Performance */}
                <div className="bg-slate-900/40 border border-slate-800 rounded-3xl p-8">
                    <h2 className="text-xl font-bold text-white mb-6 flex items-center gap-2">
                        <Target className="w-5 h-5 text-emerald-500" />
                        Top Clientes
                    </h2>
                    <div className="space-y-6">
                        {topClients.map((client, i) => (
                            <div key={i} className="space-y-2">
                                <div className="flex justify-between text-sm">
                                    <span className="text-slate-300 font-medium truncate max-w-[150px]">{client.name}</span>
                                    <span className="text-white font-bold">{client.share}</span>
                                </div>
                                <div className="h-1.5 w-full bg-slate-800 rounded-full overflow-hidden">
                                    <div
                                        className="h-full bg-emerald-500 transition-all duration-1000"
                                        style={{ width: client.share }}
                                    />
                                </div>
                            </div>
                        ))}
                    </div>

                    <div className="mt-10 p-4 bg-blue-600/10 border border-blue-500/20 rounded-2xl">
                        <div className="flex items-center gap-3 mb-2">
                            <Activity className="w-4 h-4 text-blue-500" />
                            <span className="text-xs font-bold text-white uppercase tracking-wider">Insight IA</span>
                        </div>
                        <p className="text-[11px] text-slate-400 leading-relaxed">
                            O faturamento com clientes de **Simples Nacional** cresceu 15% este mês. Recomendamos foco em automação para estes casos.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    );
}
