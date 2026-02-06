export const dynamic = 'force-dynamic';

import prisma from '@/lib/prisma';
import { Gavel, FileSignature, Landmark, CheckCircle2, AlertCircle, Building, Plus, Clock } from 'lucide-react';
import { formatDistanceToNow } from 'date-fns';
import { ptBR } from 'date-fns/locale';

export default async function SocietarioPage() {
    const processes = await prisma.legalProcess.findMany({
        include: { client: true },
        orderBy: { updatedAt: 'desc' }
    });

    const pending = processes.filter(p => p.status === 'OPEN').length;
    const inProgress = processes.filter(p => p.status === 'IN_PROGRESS').length;
    const done = processes.filter(p => p.status === 'DONE').length;

    return (
        <div className="p-8">
            <div className="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                <div>
                    <h1 className="text-3xl font-bold text-white mb-2">Societário & Legalização</h1>
                    <p className="text-slate-400">Processos de abertura, alteração e encerramento de empresas.</p>
                </div>
                <button className="bg-emerald-600 hover:bg-emerald-500 text-white px-6 py-2 rounded-xl font-bold transition-all flex items-center gap-2">
                    <Plus className="w-4 h-4" />
                    Novo Processo
                </button>
            </div>

            {/* Stage Summary Cards */}
            <div className="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
                <div className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl flex items-center justify-between">
                    <div>
                        <div className="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-1">Abertos</div>
                        <div className="text-3xl font-bold text-white">{pending}</div>
                    </div>
                    <Landmark className="w-8 h-8 text-blue-500 opacity-20" />
                </div>
                <div className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl flex items-center justify-between">
                    <div>
                        <div className="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-1">Em Andamento</div>
                        <div className="text-3xl font-bold text-white">{inProgress}</div>
                    </div>
                    <FileSignature className="w-8 h-8 text-purple-500 opacity-20" />
                </div>
                <div className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl flex items-center justify-between">
                    <div>
                        <div className="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-1">Concluídos</div>
                        <div className="text-3xl font-bold text-white">{done}</div>
                    </div>
                    <CheckCircle2 className="w-8 h-8 text-emerald-500 opacity-20" />
                </div>
                <div className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl flex items-center justify-between">
                    <div>
                        <div className="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-1">Total</div>
                        <div className="text-3xl font-bold text-white">{processes.length}</div>
                    </div>
                    <Gavel className="w-8 h-8 text-amber-500 opacity-20" />
                </div>
            </div>

            <div className="bg-slate-900/40 border border-slate-800 rounded-3xl overflow-hidden">
                <div className="p-8 border-b border-slate-800 flex justify-between items-center">
                    <h2 className="text-xl font-bold text-white flex items-center gap-2">
                        <Building className="w-5 h-5 text-blue-500" />
                        Processos Recentes
                    </h2>
                    <span className="text-xs text-slate-500 font-bold uppercase tracking-widest">{processes.length} Registros</span>
                </div>

                <div className="divide-y divide-slate-800">
                    {processes.map((p) => (
                        <div key={p.id} className="p-6 flex flex-col md:flex-row md:items-center justify-between hover:bg-slate-900/20 transition-all gap-4">
                            <div className="flex items-center gap-4">
                                <div className={`w-2 h-2 rounded-full ${p.status === 'DONE' ? 'bg-emerald-500' : p.status === 'OPEN' ? 'bg-amber-500' : 'bg-blue-500'}`} />
                                <div>
                                    <div className="font-bold text-white">{p.title}</div>
                                    <div className="text-xs text-slate-500 font-medium">
                                        {p.stage || p.description} • <span className="text-slate-400">{p.client.name}</span>
                                    </div>
                                </div>
                            </div>

                            <div className="flex items-center gap-10">
                                <div className="flex items-center gap-2 text-slate-400 text-xs font-bold">
                                    <Clock className="w-3 h-3" />
                                    {formatDistanceToNow(new Date(p.updatedAt), { addSuffix: true, locale: ptBR })}
                                </div>

                                <button className="text-[10px] font-bold uppercase tracking-widest text-slate-400 hover:text-white px-4 py-2 border border-slate-800 rounded-lg hover:border-slate-600 transition-all">
                                    Detalhes
                                </button>
                            </div>
                        </div>
                    ))}
                    {processes.length === 0 && (
                        <div className="p-10 text-center text-slate-500 italic">Nenhum processo iniciado.</div>
                    )}
                </div>
            </div>

            <div className="mt-8 p-6 bg-amber-500/5 border border-amber-500/10 rounded-2xl flex items-start gap-4">
                <AlertCircle className="w-5 h-5 text-amber-500 shrink-0 mt-0.5" />
                <div>
                    <h4 className="text-sm font-bold text-amber-500 mb-1">Status do Sistema</h4>
                    <p className="text-xs text-slate-400 leading-relaxed">
                        Conexão com Junta Comercial e Receita Federal operando normalmente.
                    </p>
                </div>
            </div>
        </div>
    );
}
