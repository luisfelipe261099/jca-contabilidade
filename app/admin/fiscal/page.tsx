export const dynamic = 'force-dynamic';

import prisma from '@/lib/prisma';
import { FileText, AlertCircle, DollarSign, Calendar } from 'lucide-react';

export default async function FiscalPage() {
    const taxes = await prisma.tax.findMany({
        include: { client: true },
        orderBy: { dueDate: 'asc' }
    });

    const totalTaxes = taxes.reduce((acc: number, tax: any) => acc + tax.amount, 0);
    const pendingTaxes = taxes.filter((t: any) => t.status === 'PENDING').length;

    return (
        <div className="p-8">
            <div className="mb-8">
                <h1 className="text-3xl font-bold text-white mb-2">Departamento Fiscal</h1>
                <p className="text-slate-400">Gestão de tributos, notas fiscais e obrigações acessórias.</p>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl">
                    <div className="flex items-center gap-4 mb-4">
                        <div className="p-3 bg-blue-500/10 rounded-xl">
                            <FileText className="w-6 h-6 text-blue-500" />
                        </div>
                        <div>
                            <div className="text-2xl font-bold text-white">{taxes.length}</div>
                            <div className="text-xs text-slate-500 font-bold uppercase">Guias Geradas</div>
                        </div>
                    </div>
                </div>

                <div className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl">
                    <div className="flex items-center gap-4 mb-4">
                        <div className="p-3 bg-emerald-500/10 rounded-xl">
                            <DollarSign className="w-6 h-6 text-emerald-500" />
                        </div>
                        <div>
                            <div className="text-2xl font-bold text-white">
                                {new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(totalTaxes)}
                            </div>
                            <div className="text-xs text-slate-500 font-bold uppercase">Total em Tributos</div>
                        </div>
                    </div>
                </div>

                <div className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl">
                    <div className="flex items-center gap-4 mb-4">
                        <div className="p-3 bg-rose-500/10 rounded-xl">
                            <AlertCircle className="w-6 h-6 text-rose-500" />
                        </div>
                        <div>
                            <div className="text-2xl font-bold text-white">{pendingTaxes}</div>
                            <div className="text-xs text-slate-500 font-bold uppercase">Guias em Aberto</div>
                        </div>
                    </div>
                </div>
            </div>

            <div className="bg-slate-900/40 border border-slate-800 rounded-3xl p-8">
                <h2 className="text-xl font-bold text-white mb-6 flex items-center gap-2">
                    <Calendar className="w-5 h-5 text-blue-500" />
                    Cronograma de Vencimentos
                </h2>
                <div className="space-y-4">
                    {taxes.map((tax: any) => (
                        <div key={tax.id} className="flex items-center justify-between p-5 bg-slate-950 rounded-xl border border-slate-900 hover:border-blue-500/30 transition-all group">
                            <div>
                                <div className="font-bold text-white group-hover:text-blue-400 transition-colors">{tax.name}</div>
                                <div className="text-xs text-slate-500 flex items-center gap-2">
                                    <span className="font-bold text-slate-400">{tax.client.name}</span>
                                    • Vence em: {tax.dueDate.toLocaleDateString('pt-BR')}
                                </div>
                            </div>
                            <div className="text-right">
                                <div className="font-bold text-white">
                                    {new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(tax.amount)}
                                </div>
                                <div className={`text-[10px] font-bold uppercase px-2 py-0.5 rounded-full inline-block mt-1 ${tax.status === 'PAID' ? 'bg-emerald-500/20 text-emerald-500' :
                                    tax.status === 'LATE' ? 'bg-rose-500/20 text-rose-500' : 'bg-amber-500/20 text-amber-500'
                                    }`}>
                                    {tax.status === 'PAID' ? 'PAGO' : tax.status === 'LATE' ? 'ATRASADO' : 'ABERTO'}
                                </div>
                            </div>
                        </div>
                    ))}
                    {taxes.length === 0 && (
                        <div className="text-center py-10 text-slate-500 italic">Nenhum imposto lançado para este mês.</div>
                    )}
                </div>
            </div>
        </div>
    );
}
