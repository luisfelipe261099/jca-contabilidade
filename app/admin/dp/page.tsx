export const dynamic = 'force-dynamic';

import React from 'react';
import { Users, UserCheck, CalendarDays, Wallet } from 'lucide-react';

export default function DPPage() {
    return (
        <div className="p-8">
            <div className="mb-8">
                <h1 className="text-3xl font-bold text-white mb-2">Folha & DP</h1>
                <p className="text-slate-400">Gestão de pessoal, folha de pagamento e benefícios.</p>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl">
                    <div className="flex items-center gap-3">
                        <Users className="w-5 h-5 text-blue-500" />
                        <span className="text-slate-400 text-xs font-bold uppercase">Total Funcionários</span>
                    </div>
                    <div className="text-3xl font-bold text-white mt-2">1.205</div>
                </div>

                <div className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl">
                    <div className="flex items-center gap-3">
                        <UserCheck className="w-5 h-5 text-emerald-500" />
                        <span className="text-slate-400 text-xs font-bold uppercase">Admissões (Mês)</span>
                    </div>
                    <div className="text-3xl font-bold text-white mt-2">12</div>
                </div>

                <div className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl">
                    <div className="flex items-center gap-3">
                        <CalendarDays className="w-5 h-5 text-purple-500" />
                        <span className="text-slate-400 text-xs font-bold uppercase">Férias Ativas</span>
                    </div>
                    <div className="text-3xl font-bold text-white mt-2">45</div>
                </div>

                <div className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl">
                    <div className="flex items-center gap-3">
                        <Wallet className="w-5 h-5 text-yellow-500" />
                        <span className="text-slate-400 text-xs font-bold uppercase">Folha Líquida</span>
                    </div>
                    <div className="text-2xl font-bold text-white mt-2">R$ 2.4M</div>
                </div>
            </div>

            <div className="bg-slate-900/40 border border-slate-800 rounded-3xl p-8">
                <h2 className="text-xl font-bold text-white mb-6">Processamento de Folha (Fev/2026)</h2>
                <div className="relative pt-6">
                    <div className="overflow-hidden h-2 mb-4 text-xs flex rounded bg-slate-800">
                        <div style={{ width: "65%" }} className="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-blue-500"></div>
                    </div>
                    <div className="flex justify-between text-xs text-slate-400 font-bold uppercase tracking-widest">
                        <span>Cálculo</span>
                        <span>Conferência</span>
                        <span>Envio eSocial</span>
                        <span>Fechamento</span>
                    </div>
                    <div className="mt-4 text-center">
                        <span className="text-2xl font-bold text-white">65%</span>
                        <span className="text-slate-500 ml-2">Concluído</span>
                    </div>
                </div>
            </div>
        </div>
    );
}
