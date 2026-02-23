export const dynamic = 'force-dynamic';

import prisma from '@/lib/prisma';
import SocietarioActions from '@/components/admin/SocietarioActions';

export default async function SocietarioPage() {
    const [processes, clients] = await Promise.all([
        prisma.legalProcess.findMany({
            where: { client: { services: { contains: 'SOCIETARIO' } } },
            include: { client: true },
            orderBy: { createdAt: 'desc' }
        }),
        prisma.client.findMany({
            where: { services: { contains: 'SOCIETARIO' } },
            select: { id: true, name: true },
            orderBy: { name: 'asc' }
        })
    ]);

    const open = processes.filter((p: any) => p.status === 'OPEN').length;
    const inProgress = processes.filter((p: any) => p.status === 'IN_PROGRESS').length;
    const done = processes.filter((p: any) => p.status === 'DONE').length;

    return (
        <div className="p-8">
            <div className="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                <div>
                    <h1 className="text-3xl font-bold text-white mb-2">Societário / Jurídico</h1>
                    <p className="text-slate-400">Processos societários, abertura e alteração de empresas.</p>
                </div>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <div className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl">
                    <div className="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-1">Em Aberto</div>
                    <div className="text-3xl font-bold text-amber-500">{open}</div>
                </div>
                <div className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl">
                    <div className="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-1">Em Andamento</div>
                    <div className="text-3xl font-bold text-blue-500">{inProgress}</div>
                </div>
                <div className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl">
                    <div className="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-1">Concluídos</div>
                    <div className="text-3xl font-bold text-emerald-500">{done}</div>
                </div>
            </div>

            <SocietarioActions processes={JSON.parse(JSON.stringify(processes))} clients={clients} />
        </div>
    );
}
