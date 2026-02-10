'use client';

import React, { useState } from 'react';
import { Plus, Loader2, Trash2, DollarSign, Wallet } from 'lucide-react';
import { createFinancialRecord, deleteFinancialRecord, updateFinancialRecordStatus } from '@/app/actions/admin';

interface FinancialRecord {
    id: string;
    description: string;
    amount: number;
    type: string;
    dueDate: string;
    status: string;
    client: { id: string; name: string } | null;
}

interface Client {
    id: string;
    name: string;
}

export default function FinanceiroActions({ records, clients }: { records: FinancialRecord[]; clients: Client[] }) {
    const [loading, setLoading] = useState(false);
    const [message, setMessage] = useState({ type: '', text: '' });
    const [showForm, setShowForm] = useState(false);

    async function handleCreate(formData: FormData) {
        setLoading(true);
        setMessage({ type: '', text: '' });
        const result = await createFinancialRecord(formData);
        if (result.success) {
            setMessage({ type: 'success', text: 'Registro financeiro criado com sucesso!' });
            setShowForm(false);
        } else {
            setMessage({ type: 'error', text: result.error || 'Erro.' });
        }
        setLoading(false);
    }

    async function handleDelete(id: string) {
        if (!confirm('Excluir este registro?')) return;
        setLoading(true);
        const result = await deleteFinancialRecord(id);
        if (result.error) setMessage({ type: 'error', text: result.error });
        else setMessage({ type: 'success', text: 'Excluído!' });
        setLoading(false);
    }

    async function handleStatusChange(id: string, status: string) {
        setLoading(true);
        await updateFinancialRecordStatus(id, status);
        setLoading(false);
    }

    const getStatusBadge = (status: string) => {
        switch (status) {
            case 'PAID': return 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20';
            case 'PENDING': return 'bg-amber-500/10 text-amber-400 border-amber-500/20';
            case 'LATE': return 'bg-red-500/10 text-red-400 border-red-500/20';
            default: return 'bg-slate-800 text-slate-400';
        }
    };

    const getTypeBadge = (type: string) => {
        return type === 'INCOME'
            ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20'
            : 'bg-red-500/10 text-red-400 border-red-500/20';
    };

    return (
        <div>
            {message.text && (
                <div className={`mb-6 p-4 rounded-xl text-sm font-bold text-center border ${message.type === 'success'
                    ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400'
                    : 'bg-red-500/10 border-red-500/20 text-red-400'
                    }`}>
                    {message.text}
                </div>
            )}

            <button
                onClick={() => setShowForm(!showForm)}
                className="flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-bold transition-all mb-6"
            >
                <Plus className="w-5 h-5" />
                {showForm ? 'Fechar' : 'Novo Registro Financeiro'}
            </button>

            {showForm && (
                <form action={handleCreate} className="bg-slate-900/40 border border-slate-800 rounded-3xl p-8 mb-8">
                    <h3 className="text-lg font-bold text-white mb-6 flex items-center gap-2">
                        <DollarSign className="w-5 h-5 text-blue-500" />
                        Novo Registro Financeiro
                    </h3>
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label className="block text-[10px] font-bold text-slate-500 uppercase mb-1.5 ml-1">Descrição</label>
                            <input name="description" type="text" placeholder="Ex: Honorários, Folha..."
                                className="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-white text-sm focus:outline-none focus:border-blue-500 transition-all" required />
                        </div>
                        <div>
                            <label className="block text-[10px] font-bold text-slate-500 uppercase mb-1.5 ml-1">Valor (R$)</label>
                            <input name="amount" type="number" step="0.01" placeholder="0.00"
                                className="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-white text-sm focus:outline-none focus:border-blue-500 transition-all" required />
                        </div>
                        <div>
                            <label className="block text-[10px] font-bold text-slate-500 uppercase mb-1.5 ml-1">Vencimento</label>
                            <input name="dueDate" type="date"
                                className="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-white text-sm focus:outline-none focus:border-blue-500 transition-all" required />
                        </div>
                        <div>
                            <label className="block text-[10px] font-bold text-slate-500 uppercase mb-1.5 ml-1">Tipo</label>
                            <select name="type" className="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-white text-sm focus:outline-none focus:border-blue-500 transition-all appearance-none">
                                <option value="INCOME">Receita</option>
                                <option value="EXPENSE">Despesa</option>
                            </select>
                        </div>
                        <div>
                            <label className="block text-[10px] font-bold text-slate-500 uppercase mb-1.5 ml-1">Status</label>
                            <select name="status" className="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-white text-sm focus:outline-none focus:border-blue-500 transition-all appearance-none">
                                <option value="PENDING">Pendente</option>
                                <option value="PAID">Pago</option>
                                <option value="LATE">Atrasado</option>
                            </select>
                        </div>
                        <div>
                            <label className="block text-[10px] font-bold text-slate-500 uppercase mb-1.5 ml-1">Cliente (Opcional)</label>
                            <select name="clientId" className="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-white text-sm focus:outline-none focus:border-blue-500 transition-all appearance-none">
                                <option value="">Nenhum (Interno)</option>
                                {clients.map(c => <option key={c.id} value={c.id}>{c.name}</option>)}
                            </select>
                        </div>
                    </div>
                    <button type="submit" disabled={loading}
                        className="bg-blue-600 hover:bg-blue-500 disabled:bg-slate-800 text-white px-8 py-3 rounded-xl font-bold transition-all flex items-center gap-2">
                        {loading ? <Loader2 className="w-4 h-4 animate-spin" /> : <Plus className="w-4 h-4" />}
                        Cadastrar
                    </button>
                </form>
            )}

            {/* Records Table */}
            <div className="bg-slate-900/40 border border-slate-800 rounded-3xl overflow-hidden">
                <div className="overflow-x-auto">
                    <table className="w-full text-left border-collapse">
                        <thead>
                            <tr className="border-b border-slate-800">
                                <th className="p-6 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Descrição</th>
                                <th className="p-6 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Cliente</th>
                                <th className="p-6 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Tipo</th>
                                <th className="p-6 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Valor</th>
                                <th className="p-6 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Vencimento</th>
                                <th className="p-6 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Status</th>
                                <th className="p-6 text-[10px] font-bold text-slate-500 uppercase tracking-widest text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-800/50">
                            {records.map(rec => (
                                <tr key={rec.id} className="hover:bg-slate-800/20 transition-colors">
                                    <td className="p-6 font-bold text-white">{rec.description}</td>
                                    <td className="p-6 text-slate-400 text-sm">{rec.client?.name || 'Interno'}</td>
                                    <td className="p-6">
                                        <span className={`px-3 py-1 rounded-full text-[10px] font-bold uppercase border ${getTypeBadge(rec.type)}`}>
                                            {rec.type === 'INCOME' ? 'Receita' : 'Despesa'}
                                        </span>
                                    </td>
                                    <td className="p-6 text-white font-bold">
                                        {new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(rec.amount)}
                                    </td>
                                    <td className="p-6 text-slate-400 text-sm">{new Date(rec.dueDate).toLocaleDateString('pt-BR')}</td>
                                    <td className="p-6">
                                        <select
                                            value={rec.status}
                                            onChange={(e) => handleStatusChange(rec.id, e.target.value)}
                                            className={`px-3 py-1.5 rounded-full text-[10px] font-bold uppercase border bg-transparent cursor-pointer focus:outline-none ${getStatusBadge(rec.status)}`}
                                        >
                                            <option value="PENDING">Pendente</option>
                                            <option value="PAID">Pago</option>
                                            <option value="LATE">Atrasado</option>
                                        </select>
                                    </td>
                                    <td className="p-6 text-right">
                                        <button onClick={() => handleDelete(rec.id)} disabled={loading}
                                            className="p-2 hover:bg-red-500/10 rounded-lg text-slate-500 hover:text-red-500 transition-all" title="Excluir">
                                            <Trash2 className="w-4 h-4" />
                                        </button>
                                    </td>
                                </tr>
                            ))}
                            {records.length === 0 && (
                                <tr><td colSpan={7} className="p-10 text-center text-slate-500 italic">Nenhum registro financeiro.</td></tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    );
}
