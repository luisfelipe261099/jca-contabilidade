export const dynamic = 'force-dynamic';

import prisma from '@/lib/prisma';
import { Wallet, TrendingUp, AlertTriangle, ArrowUpRight, ArrowDownRight, DollarSign, Calendar } from 'lucide-react';

export default async function FinanceiroPage() {
    const records = await prisma.financialRecord.findMany({
        where: { type: 'INCOME' },
        orderBy: { dueDate: 'asc' }
    });

    const totalRevenue = records.reduce((acc: number, r: any) => acc + r.amount, 0);
    const paidRevenue = records.filter((r: any) => r.status === 'PAID').reduce((acc: number, r: any) => acc + r.amount, 0);
    const pendingRevenue = records.filter((r: any) => r.status === 'PENDING').reduce((acc: number, r: any) => acc + r.amount, 0);
    const overdueRevenue = records.filter((r: any) => r.status === 'LATE').reduce((acc: number, r: any) => acc + r.amount, 0);

    const upcomingPayments = records.filter((r: any) => r.status === 'PENDING' || r.status === 'LATE').slice(0, 5);

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
                <div className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl">
                    <div className="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-2">Receita Total (Mês)</div>
                    <div className="text-2xl font-bold text-white flex items-end gap-3 truncate">
                        {new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(totalRevenue)}
                        <span className="text-[10px] pb-1 flex items-center gap-0.5 text-emerald-500">
                            <ArrowUpRight className="w-3 h-3" />
                            +5.2%
                        </span>
                    </div>
                </div>
                <div className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl">
                    <div className="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-2">Recebido</div>
                    <div className="text-2xl font-bold text-white flex items-end gap-3 truncate">
                        {new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(paidRevenue)}
                        <span className="text-[10px] pb-1 flex items-center gap-0.5 text-emerald-500">
                            <ArrowUpRight className="w-3 h-3" />
                            +12%
                        </span>
                    </div>
                </div>
                <div className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl">
                    <div className="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-2">Pendente</div>
                    <div className="text-2xl font-bold text-white flex items-end gap-3 truncate">
                        {new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(pendingRevenue)}
                    </div>
                </div>
                <div className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl">
                    <div className="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-2">Inadimplência</div>
                    <div className="text-2xl font-bold text-white flex items-end gap-3 truncate">
                        {new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(overdueRevenue)}
                        <span className="text-[10px] pb-1 flex items-center gap-0.5 text-rose-500">
                            <ArrowDownRight className="w-3 h-3" />
                            -2.1%
                        </span>
                    </div>
                </div>
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {/* Main Table Area */}
                <div className="lg:col-span-2 bg-slate-900/40 border border-slate-800 rounded-3xl p-8">
                    <h2 className="text-xl font-bold text-white mb-6 flex items-center gap-2">
                        <Calendar className="w-5 h-5 text-blue-500" />
                        Próximas Receitas
                    </h2>
                    <div className="space-y-4">
                        {upcomingPayments.map((p: any) => (
                            <div key={p.id} className="flex items-center justify-between p-4 bg-slate-950 rounded-2xl border border-slate-900 hover:border-blue-500/30 transition-all group">
                                <div className="flex items-center gap-4">
                                    <div className={`p-3 rounded-xl ${p.status === 'LATE' ? 'bg-rose-500/10' : 'bg-slate-900'}`}>
                                        <Wallet className={`w-5 h-5 ${p.status === 'LATE' ? 'text-rose-500' : 'text-slate-400'}`} />
                                    </div>
                                    <div>
                                        <div className="font-bold text-white group-hover:text-blue-400 transition-colors">{p.description}</div>
                                        <div className="text-xs text-slate-500">Vencimento: {new Date(p.dueDate).toLocaleDateString()}</div>
                                    </div>
                                </div>
                                <div className="text-right">
                                    <div className="font-bold text-white">
                                        {new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(p.amount)}
                                    </div>
                                    <div className={`text-[10px] font-bold uppercase ${p.status === 'LATE' ? 'text-rose-500' : 'text-slate-500'}`}>
                                        {p.status === 'LATE' ? 'Atrasado' : 'Aguardando'}
                                    </div>
                                </div>
                            </div>
                        ))}
                        {upcomingPayments.length === 0 && (
                            <div className="text-center py-10 text-slate-500 italic">Nenhum pagamento pendente.</div>
                        )}
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
                            {overdueRevenue > 0 && (
                                <button className="w-full text-left text-xs bg-slate-950 p-3 rounded-xl border border-slate-800 hover:border-slate-600 transition-all text-slate-300">
                                    Enviar lembrete de atraso (Inadimplentes)
                                </button>
                            )}
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
