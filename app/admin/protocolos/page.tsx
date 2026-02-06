export const dynamic = 'force-dynamic';

import React from 'react';
import { ShieldCheck, History, UserCheck, FileText, Search, Filter, Download } from 'lucide-react';

export default function ProtocolosPage() {
    const protocols = [
        { id: 'PRT-2026-001', client: 'Indústria JCA', doc: 'DAS Fev/2026', type: 'Guia', date: '06/02/2026 14:20', receipted: true },
        { id: 'PRT-2026-002', client: 'Mercado Silveira', doc: 'Folha Pagamento', type: 'Relatório', date: '06/02/2026 10:15', receipted: false },
        { id: 'PRT-2026-003', client: 'Transportes Rápidos', doc: 'CND Municipal', type: 'Certidão', date: '05/02/2026 16:45', receipted: true },
        { id: 'PRT-2026-004', client: 'Tech Services', doc: 'Contrato Social', type: 'Legal', date: '04/02/2026 11:30', receipted: true },
    ];

    return (
        <div className="p-8">
            <div className="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                <div>
                    <h1 className="text-3xl font-bold text-white mb-2">Protocolos Digitais</h1>
                    <p className="text-slate-400">Rastreabilidade e prova de entrega de documentos aos clientes.</p>
                </div>
                <div className="flex gap-3">
                    <button className="bg-slate-900 border border-slate-800 text-slate-300 px-4 py-2 rounded-xl font-bold flex items-center gap-2 hover:bg-slate-800 transition-all">
                        <Download className="w-4 h-4" />
                        Exportar Relatório
                    </button>
                </div>
            </div>

            {/* Quick Stats */}
            <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <div className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl">
                    <div className="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-1">Enviados (Mês)</div>
                    <div className="text-3xl font-bold text-white">452</div>
                </div>
                <div className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl">
                    <div className="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-1">Aguardando Leitura</div>
                    <div className="text-3xl font-bold text-amber-500">28</div>
                </div>
                <div className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl">
                    <div className="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-1">Vistos / Recebidos</div>
                    <div className="text-3xl font-bold text-emerald-500">424</div>
                </div>
            </div>

            {/* Table Area */}
            <div className="bg-slate-900/40 border border-slate-800 rounded-3xl overflow-hidden">
                <div className="p-6 border-b border-slate-800 flex flex-col md:flex-row gap-4 justify-between items-center bg-slate-900/20">
                    <div className="relative w-full md:w-96">
                        <Search className="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" />
                        <input
                            type="text"
                            placeholder="Buscar por cliente ou protocolo..."
                            className="w-full bg-slate-950 border border-slate-800 rounded-xl py-2 pl-12 pr-4 text-sm text-white focus:outline-none focus:border-blue-500 transition-all"
                        />
                    </div>
                    <div className="flex gap-2">
                        <button className="p-2 bg-slate-950 border border-slate-800 rounded-lg text-slate-400 hover:text-white transition-all">
                            <Filter className="w-4 h-4" />
                        </button>
                    </div>
                </div>

                <div className="overflow-x-auto">
                    <table className="w-full text-left border-collapse">
                        <thead>
                            <tr className="border-b border-slate-800">
                                <th className="p-6 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Protocolo</th>
                                <th className="p-6 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Cliente</th>
                                <th className="p-6 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Documento</th>
                                <th className="p-6 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Data/Hora</th>
                                <th className="p-6 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Status</th>
                                <th className="p-6 text-[10px] font-bold text-slate-500 uppercase tracking-widest text-right">Ação</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-800">
                            {protocols.map((p, i) => (
                                <tr key={i} className="hover:bg-slate-800/20 transition-all group">
                                    <td className="p-6 text-sm font-mono text-blue-400">{p.id}</td>
                                    <td className="p-6 text-sm text-white font-medium">{p.client}</td>
                                    <td className="p-6">
                                        <div className="text-sm text-white font-medium">{p.doc}</div>
                                        <div className="text-[10px] text-slate-500 font-bold uppercase">{p.type}</div>
                                    </td>
                                    <td className="p-6 text-xs text-slate-400">{p.date}</td>
                                    <td className="p-6">
                                        {p.receipted ? (
                                            <span className="flex items-center gap-1.5 text-emerald-500 text-xs font-bold uppercase tracking-wider">
                                                <UserCheck className="w-3.5 h-3.5" />
                                                Confirmado
                                            </span>
                                        ) : (
                                            <span className="flex items-center gap-1.5 text-amber-500 text-xs font-bold uppercase tracking-wider">
                                                <History className="w-3.5 h-3.5" />
                                                Enviado
                                            </span>
                                        )}
                                    </td>
                                    <td className="p-6 text-right">
                                        <button className="p-2 hover:bg-slate-800 rounded-lg text-slate-500 hover:text-white transition-all">
                                            <FileText className="w-4 h-4" />
                                        </button>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>

            <div className="mt-8 flex items-center gap-3">
                <ShieldCheck className="w-5 h-5 text-emerald-500" />
                <p className="text-xs text-slate-500 leading-relaxed italic">
                    Todos os protocolos são hash-protected e possuem validade técnica para auditorias fiscais e contábeis.
                </p>
            </div>
        </div>
    );
}
