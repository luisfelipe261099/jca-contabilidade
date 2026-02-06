export const dynamic = 'force-dynamic';

import React from 'react';
import { Wallet, TrendingUp, AlertTriangle, ArrowUpRight, ArrowDownRight, DollarSign, Calendar } from 'lucide-react';

export default function FinanceiroPage() {
    const metrics = [
        { label: 'Receita Prevista (Fev)', value: 'R$ 84.200', change: '+5.2%', isUp: true },
        { label: 'Honorários Recebidos', value: 'R$ 62.150', change: '+12%', isUp: true },
        { label: 'Inadimplência', value: '4.2%', change: '+0.5%', isUp: false },
        { label: 'Ticket Médio', value: 'R$ 1.150', change: '-2.1%', isUp: false },
    ];

    const upcomingPayments = [
        { client: 'Mercado Silveira LTDA', amount: 'R$ 1.500,00', due: '10/02/2026', status: 'Pending' },
        { client: 'Logística Express', amount: 'R$ 2.850,00', due: '12/02/2026', status: 'Late' },
        { client: 'Consultoria Tech', amount: 'R$ 1.200,00', due: '15/02/2026', status: 'Pending' },
        { client: 'Padaria Central', amount: 'R$ 850,00', due: '15/02/2026', status: 'Pending' },
    ];

    return (
        <div className="p-8">
            <div className="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                <div>
                    <h1 className="text-3xl font-bold text-white mb-2">Financeiro Interno</h1>
                    <p className="text-slate-400">Gestão de honorários, faturamento e saúde financeira da JCA.</p>
                </div>
                <button className="bg-blue-600 hover:bg-blue-500 text-white px-6 py-2 rounded-xl font-bold transition-all flex items-center gap-2">
                    <DollarSign className="w-4 h-4" />
                    Gerar Faturamento
                </button>
            </div>

            {/* Metrics Grid */}
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                {metrics.map((m, i) => (
                    <div key={i} className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl">
                        <div className="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-2">{m.label}</div>
                        <div className="text-2xl font-bold text-white flex items-end gap-3 truncate">
                            {m.value}
                            <span className={`text-[10px] pb-1 flex items-center gap-0.5 ${m.isUp ? 'text-emerald-500' : 'text-rose-500'}`}>
                                {m.isUp ? <ArrowUpRight className="w-3 h-3" /> : <ArrowDownRight className="w-3 h-3" />}
                                {m.change}
                            </span>
                        </div>
                    </div>
                ))}
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {/* Main Table Area */}
                <div className="lg:col-span-2 bg-slate-900/40 border border-slate-800 rounded-3xl p-8">
                    <h2 className="text-xl font-bold text-white mb-6 flex items-center gap-2">
                        <Calendar className="w-5 h-5 text-blue-500" />
                        Próximas Receitas
                    </h2>
                    <div className="space-y-4">
                        {upcomingPayments.map((p, i) => (
                            <div key={i} className="flex items-center justify-between p-4 bg-slate-950 rounded-2xl border border-slate-900 hover:border-blue-500/30 transition-all group">
                                <div className="flex items-center gap-4">
                                    <div className={`p-3 rounded-xl ${p.status === 'Late' ? 'bg-rose-500/10' : 'bg-slate-900'}`}>
                                        <Wallet className={`w-5 h-5 ${p.status === 'Late' ? 'text-rose-500' : 'text-slate-400'}`} />
                                    </div>
                                    <div>
                                        <div className="font-bold text-white group-hover:text-blue-400 transition-colors">{p.client}</div>
                                        <div className="text-xs text-slate-500">Vencimento: {p.due}</div>
                                    </div>
                                </div>
                                <div className="text-right">
                                    <div className="font-bold text-white">{p.amount}</div>
                                    <div className={`text-[10px] font-bold uppercase ${p.status === 'Late' ? 'text-rose-500' : 'text-slate-500'}`}>
                                        {p.status === 'Late' ? 'Atrasado' : 'Aguardando'}
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>

                {/* Quick Insights / Actions */}
                <div className="space-y-6">
                    <div className="bg-gradient-to-br from-blue-600 to-blue-800 rounded-3xl p-8 text-white shadow-xl shadow-blue-900/10">
                        <h3 className="text-sm font-bold uppercase tracking-widest opacity-80 mb-2">Meta de Honorários</h3>
                        <div className="text-3xl font-bold mb-4">74%</div>
                        <div className="w-full bg-white/20 h-2 rounded-full mb-4">
                            <div className="bg-white h-full rounded-full" style={{ width: '74%' }} />
                        </div>
                        <p className="text-xs opacity-80 leading-relaxed">
                            Faltam R$ 22.050 para atingir a meta mensal de crescimento de honorários.
                        </p>
                    </div>

                    <div className="bg-slate-900/40 border border-slate-800 rounded-3xl p-8">
                        <h3 className="text-sm font-bold text-white mb-4 flex items-center gap-2">
                            <AlertTriangle className="w-4 h-4 text-amber-500" />
                            Ações Requeridas
                        </h3>
                        <div className="space-y-4">
                            <button className="w-full text-left text-xs bg-slate-950 p-3 rounded-xl border border-slate-800 hover:border-slate-600 transition-all text-slate-300">
                                Enviar lembrete de atraso (3 clientes)
                            </button>
                            <button className="w-full text-left text-xs bg-slate-950 p-3 rounded-xl border border-slate-800 hover:border-slate-600 transition-all text-slate-300">
                                Revisar contratos sem reajuste
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
