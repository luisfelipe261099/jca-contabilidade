import React from 'react';
import { Search, Building2, AlertCircle, CheckCircle2, Clock, Users, ArrowUpRight } from 'lucide-react';

export default function DashboardPage() {
    const stats = [
        { label: 'Total Clientes', value: '124', icon: <Building2 className="w-5 h-5" />, color: 'bg-blue-500' },
        { label: 'Pendências Hoje', value: '8', icon: <AlertCircle className="w-5 h-5" />, color: 'bg-amber-500' },
        { label: 'Concluídos', value: '45', icon: <CheckCircle2 className="w-5 h-5" />, color: 'bg-emerald-500' },
        { label: 'Funcionários', value: '6', icon: <Users className="w-5 h-5" />, color: 'bg-indigo-500' },
    ];

    const clients = [
        { name: 'Metalúrgica Silva', cnpj: '12.345.678/0001-90', regime: 'Lucro Presumido', fiscal: 'green', rh: 'yellow', contabil: 'red' },
        { name: 'Padaria Central', cnpj: '98.765.432/0001-21', regime: 'Simples Nacional', fiscal: 'green', rh: 'green', contabil: 'green' },
        { name: 'Tech Solutions LTDA', cnpj: '45.678.912/0001-33', regime: 'Lucro Real', fiscal: 'red', rh: 'green', contabil: 'yellow' },
        { name: 'Mercado do Bairro', cnpj: '33.222.111/0001-00', regime: 'MEI', fiscal: 'green', rh: 'green', contabil: 'green' },
    ];

    const getStatusColor = (status: string) => {
        switch (status) {
            case 'green': return 'bg-emerald-500';
            case 'yellow': return 'bg-amber-500';
            case 'red': return 'bg-red-500';
            default: return 'bg-slate-700';
        }
    };

    return (
        <div className="min-h-screen bg-[#020617] text-slate-200 p-8">
            {/* Header */}
            <div className="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                <div>
                    <h1 className="text-3xl font-bold text-white mb-2">Torre de Controle</h1>
                    <p className="text-slate-400">Gerencie todos os clientes JCA em tempo real.</p>
                </div>

                <div className="relative w-full md:w-96">
                    <Search className="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-500" />
                    <input
                        type="text"
                        placeholder="Buscar empresa ou CNPJ..."
                        className="w-full bg-slate-900/50 border border-slate-800 rounded-xl py-3 pl-12 pr-4 text-white focus:outline-none focus:border-blue-500 transition-all font-medium"
                    />
                </div>
            </div>

            {/* Stats Grid */}
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                {stats.map((stat, i) => (
                    <div key={i} className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl hover:bg-slate-900/60 transition-all group">
                        <div className="flex items-center justify-between mb-4">
                            <div className={`p-3 rounded-xl ${stat.color} bg-opacity-10 text-${stat.color.replace('bg-', '')}`}>
                                {stat.icon}
                            </div>
                            <ArrowUpRight className="w-4 h-4 text-slate-600 transition-colors" />
                        </div>
                        <div className="text-2xl font-bold text-white mb-1">{stat.value}</div>
                        <div className="text-slate-500 text-sm font-medium">{stat.label}</div>
                    </div>
                ))}
            </div>

            {/* Client List (Torre de Controle) */}
            <div className="bg-slate-900/40 border border-slate-800 rounded-3xl overflow-hidden shadow-2xl">
                <div className="px-8 py-6 border-b border-slate-800 flex items-center justify-between bg-slate-900/20">
                    <h2 className="text-xl font-bold text-white">Status dos Clientes</h2>
                    <div className="flex gap-4 text-xs font-bold uppercase tracking-widest">
                        <div className="flex items-center gap-2"><div className="w-2 h-2 rounded-full bg-emerald-500"></div> Em Dia</div>
                        <div className="flex items-center gap-2"><div className="w-2 h-2 rounded-full bg-amber-500"></div> Pendente</div>
                        <div className="flex items-center gap-2"><div className="w-2 h-2 rounded-full bg-red-500"></div> Crítico</div>
                    </div>
                </div>

                <div className="overflow-x-auto">
                    <table className="w-full text-left border-collapse">
                        <thead>
                            <tr className="bg-slate-950/50">
                                <th className="px-8 py-5 text-slate-500 text-xs font-bold uppercase tracking-wider">Empresa</th>
                                <th className="px-8 py-5 text-slate-500 text-xs font-bold uppercase tracking-wider">Regime</th>
                                <th className="px-8 py-5 text-center text-slate-500 text-xs font-bold uppercase tracking-wider">Fiscal</th>
                                <th className="px-8 py-5 text-center text-slate-500 text-xs font-bold uppercase tracking-wider">RH</th>
                                <th className="px-8 py-5 text-center text-slate-500 text-xs font-bold uppercase tracking-wider">Contábil</th>
                                <th className="px-8 py-5"></th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-800/50">
                            {clients.map((client, idx) => (
                                <tr key={idx} className="hover:bg-slate-800/20 transition-colors">
                                    <td className="px-8 py-6">
                                        <div className="font-bold text-white mb-1 group-hover:text-blue-400 transition-colors">{client.name}</div>
                                        <div className="text-slate-500 text-xs">{client.cnpj}</div>
                                    </td>
                                    <td className="px-8 py-6">
                                        <span className="px-3 py-1 bg-slate-800 text-slate-300 rounded-full text-[10px] font-bold border border-slate-700">
                                            {client.regime}
                                        </span>
                                    </td>
                                    <td className="px-8 py-6">
                                        <div className="flex justify-center">
                                            <div className={`w-3 h-3 rounded-full ${getStatusColor(client.fiscal)} shadow-lg shadow-${getStatusColor(client.fiscal).replace('bg-', '')}/40 anim-pulse-slow`}></div>
                                        </div>
                                    </td>
                                    <td className="px-8 py-6">
                                        <div className="flex justify-center">
                                            <div className={`w-3 h-3 rounded-full ${getStatusColor(client.rh)} shadow-lg shadow-${getStatusColor(client.rh).replace('bg-', '')}/40`}></div>
                                        </div>
                                    </td>
                                    <td className="px-8 py-6">
                                        <div className="flex justify-center">
                                            <div className={`w-3 h-3 rounded-full ${getStatusColor(client.contabil)} shadow-lg shadow-${getStatusColor(client.contabil).replace('bg-', '')}/40 shadow-pulse`}></div>
                                        </div>
                                    </td>
                                    <td className="px-8 py-6 text-right">
                                        <button className="text-slate-600 hover:text-white transition-colors">
                                            <ArrowUpRight className="w-5 h-5" />
                                        </button>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    );
}
