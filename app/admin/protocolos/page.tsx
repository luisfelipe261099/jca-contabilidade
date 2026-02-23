export const dynamic = 'force-dynamic';

import prisma from '@/lib/prisma';
import { ShieldCheck } from 'lucide-react';
import ProtocoloActions from '@/components/admin/ProtocoloActions';

export default async function ProtocolosPage() {
    const [protocols, clients] = await Promise.all([
        prisma.protocol.findMany({
            include: { client: true },
            orderBy: { createdAt: 'desc' }
        }),
        prisma.client.findMany({
            select: { id: true, name: true },
            orderBy: { name: 'asc' }
        })
    ]);

    const sent = protocols.length;
    const pending = protocols.filter((p: any) => !p.receivedAt).length;
    const confirmed = protocols.filter((p: any) => p.receivedAt).length;

    return (
        <div className="p-8">
            <div className="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                <div>
                    <h1 className="text-3xl font-bold text-white mb-2">Protocolos Digitais</h1>
                    <p className="text-slate-400">Rastreabilidade e prova de entrega de documentos aos clientes.</p>
                </div>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <div className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl">
                    <div className="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-1">Enviados</div>
                    <div className="text-3xl font-bold text-white">{sent}</div>
                </div>
                <div className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl">
                    <div className="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-1">Aguardando Leitura</div>
                    <div className="text-3xl font-bold text-amber-500">{pending}</div>
                </div>
                <div className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl">
                    <div className="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-1">Confirmados</div>
                    <div className="text-3xl font-bold text-emerald-500">{confirmed}</div>
                </div>
            </div>

            <ProtocoloActions protocols={JSON.parse(JSON.stringify(protocols))} clients={clients} />

            <div className="mt-8 flex items-center gap-3">
                <ShieldCheck className="w-5 h-5 text-emerald-500" />
                <p className="text-xs text-slate-500 leading-relaxed italic">
                    Todos os protocolos são hash-protected e possuem validade técnica para auditorias fiscais e contábeis.
                </p>
            </div>
        </div>
    );
}
