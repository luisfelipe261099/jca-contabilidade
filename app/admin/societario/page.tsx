export const dynamic = 'force-dynamic';

import React from 'react';
import { Gavel, FileSignature, Landmark, Clock, CheckCircle2, AlertCircle, Building, Plus } from 'lucide-react';

export default function SocietarioPage() {
    const processes = [
        { name: 'Abertura: Padaria Central', stage: 'Viabilidade Prefeitura', status: 'Pending', days: 4 },
        { name: 'Alteração: Tech Services', stage: 'Junta Comercial', status: 'In Progress', days: 2 },
        { name: 'Baixa: Comércio Inativo', stage: 'Receita Federal', status: 'Done', days: 12 },
        { name: 'Alvará: Mercado Novo', stage: 'Corpo de Bombeiros', status: 'Pending', days: 7 },
    ];

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
                {[
                    { label: 'Viabilidade', count: 8, icon: Landmark, color: 'text-blue-500' },
                    { label: 'Junta/Receita', count: 5, icon: FileSignature, color: 'text-purple-500' },
                    { label: 'Alvarás/Licenças', count: 12, icon: Gavel, color: 'text-amber-500' },
                    { label: 'Finalizados (Mês)', count: 4, icon: CheckCircle2, color: 'text-emerald-500' },
                ].map((s, i) => (
                    <div key={i} className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl flex items-center justify-between">
                        <div>
                            <div className="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-1">{s.label}</div>
                            <div className="text-3xl font-bold text-white">{s.count}</div>
                        </div>
                        <s.icon className={`w-8 h-8 ${s.color} opacity-20`} />
                    </div>
                ))}
            </div>

            <div className="bg-slate-900/40 border border-slate-800 rounded-3xl overflow-hidden">
                <div className="p-8 border-b border-slate-800 flex justify-between items-center">
                    <h2 className="text-xl font-bold text-white flex items-center gap-2">
                        <Building className="w-5 h-5 text-blue-500" />
                        Processos em Andamento
                    </h2>
                    <span className="text-xs text-slate-500 font-bold uppercase tracking-widest">{processes.length} Processos Ativos</span>
                </div>

                <div className="divide-y divide-slate-800">
                    {processes.map((p, i) => (
                        <div key={i} className="p-6 flex flex-col md:flex-row md:items-center justify-between hover:bg-slate-900/20 transition-all gap-4">
                            <div className="flex items-center gap-4">
                                <div className={`w-2 h-2 rounded-full ${p.status === 'Done' ? 'bg-emerald-500' : p.status === 'Pending' ? 'bg-amber-500' : 'bg-blue-500'}`} />
                                <div>
                                    <div className="font-bold text-white">{p.name}</div>
                                    <div className="text-xs text-slate-500 font-medium">Etapa: {p.stage}</div>
                                </div>
                            </div>

                            <div className="flex items-center gap-10">
                                <div className="flex items-center gap-2 text-slate-400 text-xs font-bold">
                                    <Clock className="w-3 h-3" />
                                    {p.days} dias decorridos
                                </div>

                                <button className="text-[10px] font-bold uppercase tracking-widest text-slate-400 hover:text-white px-4 py-2 border border-slate-800 rounded-lg hover:border-slate-600 transition-all">
                                    Detalhes
                                </button>
                            </div>
                        </div>
                    ))}
                </div>
            </div>

            <div className="mt-8 p-6 bg-amber-500/5 border border-amber-500/10 rounded-2xl flex items-start gap-4">
                <AlertCircle className="w-5 h-5 text-amber-500 shrink-0 mt-0.5" />
                <div>
                    <h4 className="text-sm font-bold text-amber-500 mb-1">Atenção Especial Requerida</h4>
                    <p className="text-xs text-slate-400 leading-relaxed">
                        A Viabilidade da **Padaria Central** está parada há 4 dias. Verifique se há exigências pendentes no portal Redesim.
                    </p>
                </div>
            </div>
        </div>
    );
}
