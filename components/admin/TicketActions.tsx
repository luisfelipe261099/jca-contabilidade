'use client';

import React, { useState } from 'react';
import { createTicket, updateTicketStatus, deleteTicket } from '@/app/actions/tickets';
import { Loader2, Plus, MessageSquare, AlertCircle, CheckCircle2, Clock, Trash2 } from 'lucide-react';

export default function TicketActions({ tickets, clients }: { tickets: any[], clients: any[] }) {
    const [loading, setLoading] = useState(false);
    const [isAdding, setIsAdding] = useState(false);

    async function handleAdd(e: React.FormEvent<HTMLFormElement>) {
        e.preventDefault();
        setLoading(true);
        const formData = new FormData(e.currentTarget);
        const result = await createTicket(formData);

        if (result.success) {
            setIsAdding(false);
            e.currentTarget.reset();
        } else {
            alert(result.error);
        }
        setLoading(false);
    }

    async function handleStatusUpdate(id: string, status: string) {
        setLoading(true);
        await updateTicketStatus(id, status);
        setLoading(false);
    }

    async function handleDelete(id: string) {
        if (!confirm('Deseja excluir este chamado?')) return;
        setLoading(true);
        await deleteTicket(id);
        setLoading(false);
    }

    const getStatusInfo = (status: string) => {
        switch (status) {
            case 'OPEN': return { label: 'Aberto', color: 'bg-amber-500', icon: <AlertCircle className="w-4 h-4" /> };
            case 'IN_PROGRESS': return { label: 'Em Andamento', color: 'bg-blue-500', icon: <Clock className="w-4 h-4" /> };
            case 'CLOSED': return { label: 'Resolvido', color: 'bg-emerald-500', icon: <CheckCircle2 className="w-4 h-4" /> };
            default: return { label: status, color: 'bg-slate-500', icon: <MessageSquare className="w-4 h-4" /> };
        }
    };

    const getPriorityColor = (priority: string) => {
        switch (priority) {
            case 'HIGH': return 'text-red-400 border-red-500/20 bg-red-500/10';
            case 'NORMAL': return 'text-blue-400 border-blue-500/20 bg-blue-500/10';
            case 'LOW': return 'text-slate-400 border-slate-500/20 bg-slate-500/10';
            default: return 'text-slate-400 border-slate-500/20 bg-slate-500/10';
        }
    };

    return (
        <div className="bg-slate-900/40 border border-slate-800 rounded-3xl overflow-hidden shadow-2xl">
            <div className="p-6 border-b border-slate-800 flex items-center justify-between bg-slate-900/50">
                <h2 className="text-xl font-bold text-white flex items-center gap-2">
                    <MessageSquare className="w-5 h-5 text-blue-500" />
                    Lista de Chamados
                </h2>
                <button
                    onClick={() => setIsAdding(!isAdding)}
                    className="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-bold transition-all text-sm"
                >
                    <Plus className="w-4 h-4" />
                    {isAdding ? 'Cancelar' : 'Novo Chamado'}
                </button>
            </div>

            {isAdding && (
                <div className="p-6 border-b border-slate-800 bg-slate-900/80">
                    <form onSubmit={handleAdd} className="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <input
                            name="title"
                            placeholder="Assunto do chamado"
                            required
                            className="bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-white focus:outline-none focus:border-blue-500 transition-all font-medium"
                        />
                        <select
                            name="clientId"
                            required
                            defaultValue=""
                            className="bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-white focus:outline-none focus:border-blue-500 transition-all font-medium"
                        >
                            <option value="" disabled>Selecione o Cliente</option>
                            {clients.map(c => (
                                <option key={c.id} value={c.id}>{c.name}</option>
                            ))}
                        </select>
                        <textarea
                            name="description"
                            placeholder="Descreva a solicitação da empresa com detalhes..."
                            required
                            rows={3}
                            className="bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-white focus:outline-none focus:border-blue-500 transition-all font-medium md:col-span-2"
                        />
                        <select
                            name="priority"
                            required
                            defaultValue="NORMAL"
                            className="bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-white focus:outline-none focus:border-blue-500 transition-all font-medium"
                        >
                            <option value="LOW">Baixa Prioridade</option>
                            <option value="NORMAL">Normal</option>
                            <option value="HIGH">Alta Prioridade / Urgente</option>
                        </select>

                        <button
                            type="submit"
                            disabled={loading}
                            className="bg-emerald-600 hover:bg-emerald-500 h-[50px] disabled:bg-slate-800 text-white rounded-xl font-bold transition-all flex items-center justify-center gap-2"
                        >
                            {loading ? <Loader2 className="w-5 h-5 animate-spin" /> : 'Abrir Ticket'}
                        </button>
                    </form>
                </div>
            )}

            <div className="overflow-x-auto">
                <table className="w-full text-left border-collapse">
                    <thead>
                        <tr className="bg-slate-950/50">
                            <th className="px-6 py-4 text-slate-500 text-xs font-bold uppercase tracking-wider">Assunto / Cliente</th>
                            <th className="px-6 py-4 text-slate-500 text-xs font-bold uppercase tracking-wider">Prioridade</th>
                            <th className="px-6 py-4 text-slate-500 text-xs font-bold uppercase tracking-wider">Status</th>
                            <th className="px-6 py-4 text-right text-slate-500 text-xs font-bold uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody className="divide-y divide-slate-800/50">
                        {tickets.map((t: any) => {
                            const info = getStatusInfo(t.status);
                            return (
                                <tr key={t.id} className="hover:bg-slate-800/20 transition-colors">
                                    <td className="px-6 py-5">
                                        <div className="font-bold text-white mb-1">{t.title}</div>
                                        <div className="text-slate-400 text-[11px] uppercase tracking-wide flex flex-col gap-1">
                                            <span className="font-bold text-blue-400">{t.client.name}</span>
                                            <span className="truncate max-w-md normal-case text-slate-500">{t.description}</span>
                                        </div>
                                    </td>
                                    <td className="px-6 py-5">
                                        <span className={`px-3 py-1 rounded-full text-[10px] font-bold uppercase border ${getPriorityColor(t.priority)}`}>
                                            {t.priority}
                                        </span>
                                    </td>
                                    <td className="px-6 py-5">
                                        <div className="flex items-center gap-2">
                                            <div className={`w-2.5 h-2.5 rounded-full ${info.color}`}></div>
                                            <span className="text-xs text-slate-300 font-medium">{info.label}</span>
                                        </div>
                                    </td>
                                    <td className="px-6 py-5 text-right">
                                        <div className="flex items-center justify-end gap-2">
                                            {t.status === 'OPEN' && (
                                                <button
                                                    onClick={() => handleStatusUpdate(t.id, 'IN_PROGRESS')}
                                                    disabled={loading}
                                                    className="px-3 py-1.5 bg-blue-500/10 text-blue-400 hover:bg-blue-500 hover:text-white rounded-lg text-xs font-bold transition-all"
                                                >
                                                    Iniciar
                                                </button>
                                            )}
                                            {t.status !== 'CLOSED' && (
                                                <button
                                                    onClick={() => handleStatusUpdate(t.id, 'CLOSED')}
                                                    disabled={loading}
                                                    className="px-3 py-1.5 bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500 hover:text-white rounded-lg text-xs font-bold transition-all"
                                                >
                                                    Concluir
                                                </button>
                                            )}
                                            <button
                                                onClick={() => handleDelete(t.id)}
                                                disabled={loading}
                                                className="p-1.5 text-slate-500 hover:text-red-400 hover:bg-red-400/10 rounded-lg transition-all"
                                            >
                                                <Trash2 className="w-4 h-4" />
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            );
                        })}
                        {tickets.length === 0 && (
                            <tr>
                                <td colSpan={4} className="px-6 py-12 text-center text-slate-500 italic">
                                    Nenhum chamado aberto no momento. Caixa limpa!
                                </td>
                            </tr>
                        )}
                    </tbody>
                </table>
            </div>
        </div>
    );
}
