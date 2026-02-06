export const dynamic = 'force-dynamic';

import React from 'react';
import { FileText, AlertCircle, CheckCircle2, DollarSign } from 'lucide-react';

export default function FiscalPage() {
    return (
        <div className="p-8">
            <div className="mb-8">
                <h1 className="text-3xl font-bold text-white mb-2">Departamento Fiscal</h1>
                <p className="text-slate-400">Gestão de tributos, notas fiscais e obrigações acessórias.</p>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl">
                    <div className="flex items-center gap-4 mb-4">
                        <div className="p-3 bg-blue-500/10 rounded-xl">
                            <FileText className="w-6 h-6 text-blue-500" />
                        </div>
                        <div>
                            <div className="text-2xl font-bold text-white">142</div>
                            <div className="text-xs text-slate-500 font-bold uppercase">Notas Importadas</div>
                        </div>
                    </div>
                </div>

                <div className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl">
                    <div className="flex items-center gap-4 mb-4">
                        <div className="p-3 bg-emerald-500/10 rounded-xl">
                            <DollarSign className="w-6 h-6 text-emerald-500" />
                        </div>
                        <div>
                            <div className="text-2xl font-bold text-white">R$ 54.200</div>
                            <div className="text-xs text-slate-500 font-bold uppercase">Impostos Gerados</div>
                        </div>
                    </div>
                </div>

                <div className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl">
                    <div className="flex items-center gap-4 mb-4">
                        <div className="p-3 bg-rose-500/10 rounded-xl">
                            <AlertCircle className="w-6 h-6 text-rose-500" />
                        </div>
                        <div>
                            <div className="text-2xl font-bold text-white">3</div>
                            <div className="text-xs text-slate-500 font-bold uppercase">Pendências Fiscais</div>
                        </div>
                    </div>
                </div>
            </div>

            <div className="bg-slate-900/40 border border-slate-800 rounded-3xl p-8">
                <h2 className="text-xl font-bold text-white mb-6">Próximos Vencimentos</h2>
                <div className="space-y-4">
                    {[
                        { tributo: 'DAS - Simples Nacional', vencimento: '20/02/2026', valor: 'R$ 1.250,00', status: 'Aberto' },
                        { tributo: 'ICMS', vencimento: '12/02/2026', valor: 'R$ 890,50', status: 'Aberto' },
                        { tributo: 'ISS', vencimento: '15/02/2026', valor: 'R$ 320,00', status: 'Pago' },
                    ].map((item, i) => (
                        <div key={i} className="flex items-center justify-between p-4 bg-slate-950 rounded-xl border border-slate-900">
                            <div>
                                <div className="font-bold text-white">{item.tributo}</div>
                                <div className="text-xs text-slate-500">Vence em: {item.vencimento}</div>
                            </div>
                            <div className="text-right">
                                <div className="font-bold text-white">{item.valor}</div>
                                <div className={`text-[10px] font-bold uppercase px-2 py-0.5 rounded-full inline-block mt-1 ${item.status === 'Pago' ? 'bg-emerald-500/20 text-emerald-500' : 'bg-yellow-500/20 text-yellow-500'
                                    }`}>
                                    {item.status}
                                </div>
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </div>
    );
}
