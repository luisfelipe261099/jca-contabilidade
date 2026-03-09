'use client';

import React, { useState } from 'react';
import { createTicket } from '@/app/actions/tickets';
import { Loader2, Plus, X, MessageSquare, AlertCircle } from 'lucide-react';

export default function NewTicketModal({ clientId }: { clientId: string }) {
    const [isOpen, setIsOpen] = useState(false);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState<string | null>(null);

    async function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
        e.preventDefault();
        setLoading(true);
        setError(null);

        const formData = new FormData(e.currentTarget);
        formData.append('clientId', clientId);

        const result = await createTicket(formData);

        if (result.success) {
            setIsOpen(false);
            e.currentTarget.reset();
        } else {
            setError(result.error || 'Erro ao criar solicitação');
        }
        setLoading(false);
    }

    return (
        <>
            <button
                onClick={() => setIsOpen(true)}
                className="bg-blue-600 hover:bg-blue-500 text-white px-8 py-3 rounded-2xl font-bold transition-all shadow-lg shadow-blue-900/20 flex items-center gap-2"
            >
                <Plus className="w-5 h-5" />
                Nova Solicitação
            </button>

            {isOpen && (
                <div className="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm animate-in fade-in duration-200">
                    <div className="bg-slate-900 border border-slate-800 w-full max-w-lg rounded-[32px] overflow-hidden shadow-2xl animate-in zoom-in-95 duration-200">
                        <div className="p-8 border-b border-slate-800 flex items-center justify-between bg-slate-900/50">
                            <div className="flex items-center gap-3">
                                <div className="p-2 bg-blue-500/10 rounded-xl">
                                    <MessageSquare className="w-5 h-5 text-blue-500" />
                                </div>
                                <h2 className="text-xl font-bold text-white uppercase italic">Nova Solicitação</h2>
                            </div>
                            <button
                                onClick={() => setIsOpen(false)}
                                className="p-2 hover:bg-slate-800 rounded-full text-slate-400 hover:text-white transition-colors"
                            >
                                <X className="w-5 h-5" />
                            </button>
                        </div>

                        <form onSubmit={handleSubmit} className="p-8 space-y-6">
                            {error && (
                                <div className="p-4 bg-rose-500/10 border border-rose-500/20 rounded-2xl flex items-center gap-3 text-rose-400 text-sm font-bold">
                                    <AlertCircle className="w-5 h-5 shrink-0" />
                                    {error}
                                </div>
                            )}

                            <div className="space-y-2">
                                <label className="text-[10px] font-bold text-slate-500 uppercase tracking-widest ml-4">Assunto</label>
                                <input
                                    name="title"
                                    placeholder="Ex: Dúvida sobre guia de impostos"
                                    required
                                    className="w-full bg-slate-950 border border-slate-800 rounded-2xl py-4 px-6 text-white focus:outline-none focus:border-blue-500 transition-all font-medium placeholder:text-slate-600"
                                />
                            </div>

                            <div className="space-y-2">
                                <label className="text-[10px] font-bold text-slate-500 uppercase tracking-widest ml-4">Prioridade</label>
                                <select
                                    name="priority"
                                    required
                                    defaultValue="NORMAL"
                                    className="w-full bg-slate-950 border border-slate-800 rounded-2xl py-4 px-6 text-white focus:outline-none focus:border-blue-500 transition-all font-medium appearance-none cursor-pointer"
                                >
                                    <option value="LOW">Baixa Prioridade</option>
                                    <option value="NORMAL">Normal</option>
                                    <option value="HIGH">Alta Prioridade / Urgente</option>
                                </select>
                            </div>

                            <div className="space-y-2">
                                <label className="text-[10px] font-bold text-slate-500 uppercase tracking-widest ml-4">Descrição Detalhada</label>
                                <textarea
                                    name="description"
                                    placeholder="Conte-nos como podemos ajudar..."
                                    required
                                    rows={4}
                                    className="w-full bg-slate-950 border border-slate-800 rounded-2xl py-4 px-6 text-white focus:outline-none focus:border-blue-500 transition-all font-medium placeholder:text-slate-600 resize-none"
                                />
                            </div>

                            <div className="pt-4 flex gap-4">
                                <button
                                    type="button"
                                    onClick={() => setIsOpen(false)}
                                    className="flex-1 py-4 bg-slate-800 hover:bg-slate-750 text-slate-300 rounded-2xl font-bold transition-all"
                                >
                                    Cancelar
                                </button>
                                <button
                                    type="submit"
                                    disabled={loading}
                                    className="flex-[2] bg-blue-600 hover:bg-blue-500 disabled:bg-slate-800 text-white rounded-2xl font-bold transition-all flex items-center justify-center gap-2 shadow-lg shadow-blue-900/20"
                                >
                                    {loading ? <Loader2 className="w-5 h-5 animate-spin" /> : 'Enviar Solicitação'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </>
    );
}
