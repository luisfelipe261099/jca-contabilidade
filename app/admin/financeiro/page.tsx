export const dynamic = 'force-dynamic';

import prisma from '@/lib/prisma';
import FinanceiroActions from '@/components/admin/FinanceiroActions';
import { Wallet, ArrowUpRight, ArrowDownRight, AlertTriangle } from 'lucide-react';

export default async function FinanceiroPage() {
    const [records, clients] = await Promise.all([
        prisma.financialRecord.findMany({
            include: { client: true },
            orderBy: { dueDate: 'asc' }
        }),
        prisma.client.findMany({
            select: { id: true, name: true },
            orderBy: { name: 'asc' }
        })
    ]);

    const incomeRecords = records.filter((r: any) => r.type === 'INCOME');
    const expenseRecords = records.filter((r: any) => r.type === 'EXPENSE');
    const totalIncome = incomeRecords.reduce((acc: number, r: any) => acc + r.amount, 0);
    const totalExpense = expenseRecords.reduce((acc: number, r: any) => acc + r.amount, 0);
    const paidRevenue = incomeRecords.filter((r: any) => r.status === 'PAID').reduce((acc: number, r: any) => acc + r.amount, 0);
    const pendingRevenue = incomeRecords.filter((r: any) => r.status === 'PENDING').reduce((acc: number, r: any) => acc + r.amount, 0);
    const overdueRevenue = records.filter((r: any) => r.status === 'LATE').reduce((acc: number, r: any) => acc + r.amount, 0);

    return (
        <div className="p-4 sm:p-6 lg:p-8">
            <div className="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                <div>
                    <h1 className="text-2xl sm:text-3xl font-bold text-white mb-2">Financeiro Interno</h1>
                    <p className="text-slate-400">Gestão de honorários, faturamento e saúde financeira da JCA.</p>
                </div>
            </div>

            {/* Metrics Grid */}
            <div className="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 mb-8 sm:mb-10">
                <div className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl">
                    <div className="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-2">Receita Total</div>
                    <div className="text-2xl font-bold text-white truncate">
                        {new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(totalIncome)}
                    </div>
                </div>
                <div className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl">
                    <div className="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-2">Recebido</div>
                    <div className="text-2xl font-bold text-emerald-400 truncate">
                        {new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(paidRevenue)}
                    </div>
                </div>
                <div className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl">
                    <div className="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-2">Pendente</div>
                    <div className="text-2xl font-bold text-amber-400 truncate">
                        {new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(pendingRevenue)}
                    </div>
                </div>
                <div className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl">
                    <div className="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-2">Inadimplência</div>
                    <div className="text-2xl font-bold text-red-400 truncate">
                        {new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(overdueRevenue)}
                    </div>
                </div>
            </div>

            <FinanceiroActions records={JSON.parse(JSON.stringify(records))} clients={clients} />
        </div>
    );
}
