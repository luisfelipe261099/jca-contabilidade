export const dynamic = 'force-dynamic';

import prisma from '@/lib/prisma';
import FiscalActions from '@/components/admin/FiscalActions';

export default async function FiscalPage() {
    const [taxes, clients] = await Promise.all([
        prisma.tax.findMany({
            where: { client: { services: { contains: 'FISCAL' } } },
            include: { client: true },
            orderBy: { dueDate: 'asc' }
        }),
        prisma.client.findMany({
            where: { services: { contains: 'FISCAL' } },
            select: { id: true, name: true },
            orderBy: { name: 'asc' }
        })
    ]);

    const totalGuias = taxes.length;
    const totalAmount = taxes.reduce((acc: number, t: any) => acc + t.amount, 0);
    const pendingGuias = taxes.filter((t: any) => t.status === 'PENDING').length;

    return (
        <div className="p-4 sm:p-6 lg:p-8">
            <div className="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                <div>
                    <h1 className="text-2xl sm:text-3xl font-bold text-white mb-2">Gestão Fiscal</h1>
                    <p className="text-slate-400">Guias, impostos e obrigações fiscais dos clientes.</p>
                </div>
            </div>

            {/* Stats */}
            <div className="grid grid-cols-1 md:grid-cols-3 gap-3 sm:gap-6 mb-8 sm:mb-10">
                <div className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl">
                    <div className="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-1">Total de Guias</div>
                    <div className="text-3xl font-bold text-white">{totalGuias}</div>
                </div>
                <div className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl">
                    <div className="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-1">Valor Total</div>
                    <div className="text-3xl font-bold text-white">{new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(totalAmount)}</div>
                </div>
                <div className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl">
                    <div className="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-1">Pendentes</div>
                    <div className="text-3xl font-bold text-amber-500">{pendingGuias}</div>
                </div>
            </div>

            <FiscalActions taxes={JSON.parse(JSON.stringify(taxes))} clients={clients} />
        </div>
    );
}
