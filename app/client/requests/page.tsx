export const dynamic = 'force-dynamic';

import prisma from '@/lib/prisma';
import { auth } from '@/auth';
import NewTicketModal from '@/components/client/NewTicketModal';
import { Clock, CheckCircle, MessageCircle, ArrowLeft, AlertCircle } from 'lucide-react';
import Link from 'next/link';
import { format } from 'date-fns';

export default async function ClientRequestsPage() {
    const session = await auth();
    const user = session?.user as { clientId?: string } | undefined;
    const clientId = user?.clientId;

    if (!clientId) {
        return (
            <div className="min-h-screen bg-[#020617] flex items-center justify-center p-6 text-center">
                <div className="max-w-md">
                    <AlertCircle className="w-16 h-16 text-rose-500 mx-auto mb-6" />
                    <h1 className="text-2xl font-bold text-white mb-2">Acesso Restrito</h1>
                    <p className="text-slate-400">Este usuário não está vinculado a nenhuma empresa.</p>
                </div>
            </div>
        );
    }

    const tickets = await prisma.ticket.findMany({
        where: { clientId },
        orderBy: { createdAt: 'desc' }
    });

    return (
        <div className="min-h-screen bg-[#020617] text-white p-6 md:p-12">
            <div className="max-w-6xl mx-auto">
                <div className="mb-10 flex items-center justify-between">
                    <div>
                        <Link href="/client/dashboard" className="text-blue-500 flex items-center gap-2 text-sm font-bold mb-4 hover:underline">
                            <ArrowLeft className="w-4 h-4" />
                            Voltar ao Dashboard
                        </Link>
                        <h1 className="text-4xl font-bold tracking-tighter uppercase italic">Minhas Solicitações</h1>
                        <p className="text-slate-500 mt-2">Abra chamados e tire dúvidas com a equipe JCA.</p>
                    </div>
                    <NewTicketModal clientId={clientId} />
                </div>

                <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div className="lg:col-span-2 space-y-6">
                        {tickets.map((t: any) => (
                            <div key={t.id} className="bg-slate-900/40 border border-slate-800 p-8 rounded-3xl hover:border-blue-500/30 transition-all group">
                                <div className="flex justify-between items-start mb-4">
                                    <div>
                                        <div className="text-[10px] font-bold text-blue-500 uppercase tracking-widest mb-1">{t.id.slice(-8)} • {t.priority}</div>
                                        <h3 className="text-xl font-bold group-hover:text-blue-400 transition-colors uppercase italic">{t.title}</h3>
                                        <p className="text-slate-400 text-sm mt-2">{t.description}</p>
                                    </div>
                                    <div className={`px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wider ${t.status === 'CLOSED' ? 'bg-emerald-500/10 text-emerald-500 border border-emerald-500/20' : 'bg-blue-500/10 text-blue-400 border border-blue-500/20'
                                        }`}>
                                        {t.status === 'CLOSED' ? 'Concluída' : 'Em Análise'}
                                    </div>
                                </div>
                                <div className="flex items-center justify-between text-slate-500 text-sm">
                                    <div className="flex items-center gap-2">
                                        <Clock className="w-4 h-4" />
                                        <span>Iniciada em: {format(t.createdAt, 'dd/MM/yyyy')}</span>
                                    </div>
                                    <button className="flex items-center gap-2 text-blue-500 font-bold hover:text-white transition-colors">
                                        <MessageCircle className="w-4 h-4" />
                                        Ver Conversa
                                    </button>
                                </div>
                            </div>
                        ))}
                        {tickets.length === 0 && (
                            <div className="text-center py-20 bg-slate-900/40 border border-slate-800 rounded-3xl">
                                <p className="text-slate-500 italic">Nenhuma solicitação encontrada.</p>
                            </div>
                        )}
                    </div>

                    <div className="space-y-8">
                        <div className="bg-blue-600/10 border border-blue-500/20 p-8 rounded-3xl">
                            <h4 className="text-white font-bold mb-4 flex items-center gap-2">
                                <CheckCircle className="w-5 h-5 text-blue-500" />
                                Tempo de Resposta
                            </h4>
                            <p className="text-sm text-slate-400 leading-relaxed">
                                Nossa equipe interna responde em média em até **4 horas úteis**. Casos urgentes, utilize o canal direto do WhatsApp.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
