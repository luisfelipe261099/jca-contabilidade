export const dynamic = 'force-dynamic';
import React from 'react';
import OCRReader from '@/components/admin/OCRReader';
import { Files, Clock, History } from 'lucide-react';
import prisma from '@/lib/prisma';
import { formatDistanceToNow } from 'date-fns';
import { ptBR } from 'date-fns/locale';

export default async function DocumentsPage() {
    const [clients, documents] = await Promise.all([
        prisma.client.findMany({ select: { id: true, name: true } }),
        prisma.document.findMany({
            take: 5,
            orderBy: { createdAt: 'desc' },
            include: { client: true }
        })
    ]);

    return (
        <div className="p-8">
            <div className="flex items-center justify-between mb-10">
                <div>
                    <h1 className="text-3xl font-bold text-white mb-2">Central de Documentos</h1>
                    <p className="text-slate-400">Processamento inteligente de guias, notas e certidões.</p>
                </div>
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div className="lg:col-span-2 space-y-8">
                    <OCRReader clients={clients} />

                    <div className="bg-slate-900/40 border border-slate-800 rounded-3xl p-8">
                        <h2 className="text-xl font-bold text-white mb-6 flex items-center gap-2">
                            <History className="w-5 h-5 text-slate-500" />
                            Últimos Processamentos
                        </h2>
                        <div className="space-y-4">
                            {documents.map((doc: any) => (
                                <a
                                    key={doc.id}
                                    href={`/api/documents/${doc.id}`}
                                    target="_blank"
                                    className="flex items-center justify-between p-4 bg-slate-950/50 rounded-2xl border border-slate-800/50 hover:bg-slate-900/50 transition-all cursor-pointer group"
                                >
                                    <div className="flex items-center gap-4">
                                        <div className="w-12 h-12 bg-blue-500/10 rounded-xl flex items-center justify-center group-hover:bg-blue-500/20 transition-colors">
                                            <Files className="w-6 h-6 text-blue-500" />
                                        </div>
                                        <div>
                                            <div className="text-white font-bold text-sm tracking-tight">{doc.name}</div>
                                            <div className="text-slate-500 text-[11px] font-medium">{doc.client.name}</div>
                                            <div className="text-slate-600 text-[10px]">{formatDistanceToNow(new Date(doc.createdAt), { addSuffix: true, locale: ptBR })}</div>
                                        </div>
                                    </div>
                                    <div className="text-right">
                                        <div className="text-white font-bold text-sm tracking-widest uppercase text-[10px]">{doc.type}</div>
                                        <div className="text-emerald-500 text-[10px] font-bold uppercase tracking-widest mt-1">Pronto</div>
                                    </div>
                                </a>
                            ))}
                            {documents.length === 0 && (
                                <div className="py-20 text-center text-slate-500 italic text-sm">Nenhum documento processado ainda.</div>
                            )}
                        </div>
                    </div>
                </div>

                <div className="space-y-8">
                    <div className="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-3xl p-8 text-white relative overflow-hidden group">
                        <div className="absolute -right-4 -bottom-4 opacity-10 transform group-hover:scale-110 transition-transform duration-700">
                            <Clock className="w-40 h-40" />
                        </div>
                        <h3 className="text-xl font-bold mb-4 relative z-10">Dica Prática</h3>
                        <p className="text-blue-100 text-sm leading-relaxed mb-6 relative z-10">
                            O leitor OCR identifica automaticamente CNPJs e valores, economizando 80% do tempo de digitação manual.
                        </p>
                        <button className="bg-white/20 hover:bg-white/30 text-white rounded-xl py-2 px-6 text-sm font-bold transition-all relative z-10">
                            Ver Tutorial
                        </button>
                    </div>

                    <div className="bg-slate-900/40 border border-slate-800 rounded-3xl p-8">
                        <h3 className="text-white font-bold mb-4">Estatísticas do Mês</h3>
                        <div className="space-y-4">
                            <div className="flex justify-between items-center">
                                <span className="text-slate-400 text-sm font-medium">Lidos por OCR</span>
                                <span className="text-white font-bold">142</span>
                            </div>
                            <div className="flex justify-between items-center">
                                <span className="text-slate-400 text-sm font-medium">Economia Estimada</span>
                                <span className="text-emerald-400 font-bold">~12h/mês</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
