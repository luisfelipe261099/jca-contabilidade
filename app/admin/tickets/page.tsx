export const dynamic = 'force-dynamic';

import prisma from '@/lib/prisma';
import TicketActions from '@/components/admin/TicketActions';
import { MessageSquare, Clock, CheckCircle2, AlertCircle } from 'lucide-react';

export default async function TicketsPage() {
    const [tickets, clients] = await Promise.all([
        prisma.ticket.findMany({
            include: { client: true },
            orderBy: [{ status: 'asc' }, { createdAt: 'desc' }]
        }),
        prisma.client.findMany({
            select: { id: true, name: true },
            orderBy: { name: 'asc' }
        })
    ]);

    const open = tickets.filter((t: any) => t.status === 'OPEN').length;
    const inProgress = tickets.filter((t: any) => t.status === 'IN_PROGRESS').length;
    const closed = tickets.filter((t: any) => t.status === 'CLOSED').length;

    return (
        <div className="p-4 sm:p-6 lg:p-8">
            <div className="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                <div>
                    <h1 className="text-2xl sm:text-3xl font-bold text-white mb-2">Central de Atendimento</h1>
                    <p className="text-slate-400">Demandas e chamados (Helpdesk) abertos pelos seus clientes.</p>
                </div>
            </div>

            {/* Metrics Grid */}
            <div className="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-6 mb-8 sm:mb-10">
                <div className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl flex items-center justify-between">
                    <div>
                        <div className="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-2">Na Fila (Abertos)</div>
                        <div className="text-3xl font-bold text-amber-500">{open}</div>
                    </div>
                    <AlertCircle className="w-10 h-10 text-amber-500/20" />
                </div>
                <div className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl flex items-center justify-between">
                    <div>
                        <div className="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-2">Em Tratativa</div>
                        <div className="text-3xl font-bold text-blue-500">{inProgress}</div>
                    </div>
                    <Clock className="w-10 h-10 text-blue-500/20" />
                </div>
                <div className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl flex items-center justify-between">
                    <div>
                        <div className="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-2">Resolvidos</div>
                        <div className="text-3xl font-bold text-emerald-500">{closed}</div>
                    </div>
                    <CheckCircle2 className="w-10 h-10 text-emerald-500/20" />
                </div>
            </div>

            <TicketActions tickets={JSON.parse(JSON.stringify(tickets))} clients={clients} />
        </div>
    );
}
