export const dynamic = 'force-dynamic';

import React from 'react';
import { PieChart, TrendingUp, Scale, Building2 } from 'lucide-react';

export default function ContabilPage() {
    return (
        <div className="p-8">
            <div className="mb-8">
                <h1 className="text-3xl font-bold text-white mb-2">Contábil</h1>
                <p className="text-slate-400">Balancetes, DRE e controle patrimonial.</p>
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <div className="bg-slate-900/40 border border-slate-800 p-8 rounded-3xl">
                    <h2 className="text-lg font-bold text-white mb-6 flex items-center gap-2">
                        <Scale className="w-5 h-5 text-blue-500" />
                        Saúde Financeira Geral
                    </h2>
                    <div className="space-y-4">
                        <div className="flex justify-between items-center p-4 bg-slate-950 rounded-xl border border-slate-900">
                            <span className="text-slate-400 font-medium">Ativo Total</span>
                            <span className="text-white font-bold">R$ 12.540.000</span>
                        </div>
                        <div className="flex justify-between items-center p-4 bg-slate-950 rounded-xl border border-slate-900">
                            <span className="text-slate-400 font-medium">Passivo Circulante</span>
                            <span className="text-white font-bold">R$ 3.200.000</span>
                        </div>
                        <div className="flex justify-between items-center p-4 bg-slate-950 rounded-xl border border-slate-900">
                            <span className="text-slate-400 font-medium pt-1">Patrimônio Líquido</span>
                            <span className="text-emerald-500 font-bold text-lg">R$ 9.340.000</span>
                        </div>
                    </div>
                </div>

                <div className="bg-slate-900/40 border border-slate-800 p-8 rounded-3xl">
                    <h2 className="text-lg font-bold text-white mb-6 flex items-center gap-2">
                        <Building2 className="w-5 h-5 text-purple-500" />
                        Regimes Tributários (Clientes)
                    </h2>
                    <div className="space-y-6">
                        <div className="space-y-2">
                            <div className="flex justify-between text-sm">
                                <span className="text-white font-medium">Simples Nacional</span>
                                <span className="text-slate-400">65%</span>
                            </div>
                            <div className="h-2 w-full bg-slate-800 rounded-full overflow-hidden">
                                <div className="h-full bg-blue-500" style={{ width: '65%' }} />
                            </div>
                        </div>
                        <div className="space-y-2">
                            <div className="flex justify-between text-sm">
                                <span className="text-white font-medium">Lucro Presumido</span>
                                <span className="text-slate-400">25%</span>
                            </div>
                            <div className="h-2 w-full bg-slate-800 rounded-full overflow-hidden">
                                <div className="h-full bg-purple-500" style={{ width: '25%' }} />
                            </div>
                        </div>
                        <div className="space-y-2">
                            <div className="flex justify-between text-sm">
                                <span className="text-white font-medium">Lucro Real</span>
                                <span className="text-slate-400">10%</span>
                            </div>
                            <div className="h-2 w-full bg-slate-800 rounded-full overflow-hidden">
                                <div className="h-full bg-amber-500" style={{ width: '10%' }} />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
