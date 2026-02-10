'use client';

import React, { useState } from 'react';
import { Plus, Loader2, Trash2, Landmark } from 'lucide-react';
import { createLegalProcess, deleteLegalProcess, updateLegalProcessStatus } from '@/app/actions/admin';

interface LegalProcess {
    id: string;
    title: string;
    description: string | null;
    status: string;
    stage: string | null;
    createdAt: string;
    client: { id: string; name: string };
}

interface Client {
    id: string;
    name: string;
}

export default function SocietarioActions({ processes, clients }: { processes: LegalProcess[]; clients: Client[] }) {
    const [loading, setLoading] = useState(false);
    const [message, setMessage] = useState({ type: '', text: '' });
    const [showForm, setShowForm] = useState(false);

    async function handleCreate(formData: FormData) {
        setLoading(true);
        setMessage({ type: '', text: '' });
        const result = await createLegalProcess(formData);
        if (result.success) {
            setMessage({ type: 'success', text: 'Processo cadastrado com sucesso!' });
            setShowForm(false);
        } else {
            setMessage({ type: 'error', text: result.error || 'Erro.' });
        }
        setLoading(false);
    }

    async function handleDelete(id: string) {
        if (!confirm('Excluir este processo?')) return;
        setLoading(true);
        const result = await deleteLegalProcess(id);
        if (result.error) setMessage({ type: 'error', text: result.error });
        else setMessage({ type: 'success', text: 'Excluído!' });
        setLoading(false);
    }

    async function handleStatusChange(id: string, status: string) {
        setLoading(true);
        await updateLegalProcessStatus(id, status);
        setLoading(false);
    }

    const getStatusBadge = (status: string) => {
        switch (status) {
            case 'OPEN': return 'bg-amber-500/10 text-amber-400 border-amber-500/20';
            case 'IN_PROGRESS': return 'bg-blue-500/10 text-blue-400 border-blue-500/20';
            case 'DONE': return 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20';
            default: return 'bg-slate-800 text-slate-400';
        }
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
                {showForm ? 'Fechar' : 'Novo Processo'}
            </button>

            {showForm && (
                <form action={handleCreate} className="bg-slate-900/40 border border-slate-800 rounded-3xl p-8 mb-8">
                    <h3 className="text-lg font-bold text-white mb-6 flex items-center gap-2">
                        <Landmark className="w-5 h-5 text-blue-500" />
                        Novo Processo Societário
                    </h3>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label className="block text-[10px] font-bold text-slate-500 uppercase mb-1.5 ml-1">Título do Processo</label>
                            <input name="title" type="text" placeholder="Ex: Abertura de empresa, Alteração contratual..."
                                className="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-white text-sm focus:outline-none focus:border-blue-500 transition-all" required />
                        </div>
                        <div>
                            <label className="block text-[10px] font-bold text-slate-500 uppercase mb-1.5 ml-1">Empresa (Cliente)</label>
                            <select name="clientId" className="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-white text-sm focus:outline-none focus:border-blue-500 transition-all appearance-none" required>
                                <option value="">Selecione...</option>
                                {clients.map(c => <option key={c.id} value={c.id}>{c.name}</option>)}
                            </select>
                        </div>
                        <div>
                            <label className="block text-[10px] font-bold text-slate-500 uppercase mb-1.5 ml-1">Etapa Atual</label>
                            <input name="stage" type="text" placeholder="Ex: Protocolo na Junta, Análise..."
                                className="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-white text-sm focus:outline-none focus:border-blue-500 transition-all" />
                        </div>
                        <div>
                            <label className="block text-[10px] font-bold text-slate-500 uppercase mb-1.5 ml-1">Status</label>
                            <select name="status" className="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-white text-sm focus:outline-none focus:border-blue-500 transition-all appearance-none">
                                <option value="OPEN">Em Aberto</option>
                                <option value="IN_PROGRESS">Em Andamento</option>
                                <option value="DONE">Concluído</option>
                            </select>
                        </div>
                        <div className="md:col-span-2">
                            <label className="block text-[10px] font-bold text-slate-500 uppercase mb-1.5 ml-1">Descrição</label>
                            <textarea name="description" rows={3} placeholder="Detalhes do processo..."
                                className="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-white text-sm focus:outline-none focus:border-blue-500 transition-all resize-none" />
                        </div>
                    </div>
                    <button type="submit" disabled={loading}
                        className="bg-blue-600 hover:bg-blue-500 disabled:bg-slate-800 text-white px-8 py-3 rounded-xl font-bold transition-all flex items-center gap-2">
                        {loading ? <Loader2 className="w-4 h-4 animate-spin" /> : <Plus className="w-4 h-4" />}
                        Cadastrar
                    </button>
                </form>
            )}

            {/* Processes Table */}
            <div className="bg-slate-900/40 border border-slate-800 rounded-3xl overflow-hidden">
                <div className="overflow-x-auto">
                    <table className="w-full text-left border-collapse">
                        <thead>
                            <tr className="border-b border-slate-800">
                                <th className="p-6 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Processo</th>
                                <th className="p-6 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Cliente</th>
                                <th className="p-6 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Etapa</th>
                                <th className="p-6 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Status</th>
                                <th className="p-6 text-[10px] font-bold text-slate-500 uppercase tracking-widest text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-800/50">
                            {processes.map(proc => (
                                <tr key={proc.id} className="hover:bg-slate-800/20 transition-colors">
                                    <td className="p-6">
                                        <div className="font-bold text-white">{proc.title}</div>
                                        {proc.description && <div className="text-xs text-slate-500 mt-1 max-w-xs truncate">{proc.description}</div>}
                                    </td>
                                    <td className="p-6 text-slate-400 text-sm">{proc.client?.name || '—'}</td>
                                    <td className="p-6 text-slate-300 text-sm">{proc.stage || '—'}</td>
                                    <td className="p-6">
                                        <select
                                            value={proc.status}
                                            onChange={(e) => handleStatusChange(proc.id, e.target.value)}
                                            className={`px-3 py-1.5 rounded-full text-[10px] font-bold uppercase border bg-transparent cursor-pointer focus:outline-none ${getStatusBadge(proc.status)}`}
                                        >
                                            <option value="OPEN">Em Aberto</option>
                                            <option value="IN_PROGRESS">Em Andamento</option>
                                            <option value="DONE">Concluído</option>
                                        </select>
                                    </td>
                                    <td className="p-6 text-right">
                                        <button onClick={() => handleDelete(proc.id)} disabled={loading}
                                            className="p-2 hover:bg-red-500/10 rounded-lg text-slate-500 hover:text-red-500 transition-all" title="Excluir">
                                            <Trash2 className="w-4 h-4" />
                                        </button>
                                    </td>
                                </tr>
                            ))}
                            {processes.length === 0 && (
                                <tr><td colSpan={5} className="p-10 text-center text-slate-500 italic">Nenhum processo cadastrado.</td></tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    );
}
