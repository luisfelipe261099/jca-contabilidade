'use client';

import React, { useState } from 'react';
import { Plus, Loader2, CheckCircle, ShieldCheck, UserCheck, History, Clock } from 'lucide-react';
import { createProtocol, confirmProtocol } from '@/app/actions/admin';
import { format } from 'date-fns';

interface Protocol {
    id: string;
    code: string;
    type: string;
    description: string;
    receivedAt: string | null;
    createdAt: string;
    client: { id: string; name: string };
}

interface Client {
    id: string;
    name: string;
}

export default function ProtocoloActions({ protocols, clients }: { protocols: Protocol[]; clients: Client[] }) {
    const [loading, setLoading] = useState(false);
    const [message, setMessage] = useState({ type: '', text: '' });
    const [showForm, setShowForm] = useState(false);

    async function handleCreate(formData: FormData) {
        setLoading(true);
        setMessage({ type: '', text: '' });
        const result = await createProtocol(formData);
        if (result.success) {
            setMessage({ type: 'success', text: 'Protocolo criado com sucesso!' });
            setShowForm(false);
        } else {
            setMessage({ type: 'error', text: result.error || 'Erro.' });
        }
        setLoading(false);
    }

    async function handleConfirm(id: string) {
        if (!confirm('Confirmar recebimento deste protocolo?')) return;
        setLoading(true);
        const result = await confirmProtocol(id);
        if (result.success) {
            setMessage({ type: 'success', text: 'Protocolo confirmado!' });
        } else {
            setMessage({ type: 'error', text: result.error || 'Erro.' });
        }
        setLoading(false);
    }

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
                {showForm ? 'Fechar' : 'Novo Protocolo'}
            </button>

            {showForm && (
                <form action={handleCreate} className="bg-slate-900/40 border border-slate-800 rounded-3xl p-8 mb-8">
                    <h3 className="text-lg font-bold text-white mb-6 flex items-center gap-2">
                        <ShieldCheck className="w-5 h-5 text-blue-500" />
                        Novo Protocolo de Entrega
                    </h3>
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label className="block text-[10px] font-bold text-slate-500 uppercase mb-1.5 ml-1">Cliente</label>
                            <select name="clientId" className="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-white text-sm focus:outline-none focus:border-blue-500 transition-all appearance-none" required>
                                <option value="">Selecione...</option>
                                {clients.map(c => <option key={c.id} value={c.id}>{c.name}</option>)}
                            </select>
                        </div>
                        <div>
                            <label className="block text-[10px] font-bold text-slate-500 uppercase mb-1.5 ml-1">Tipo</label>
                            <select name="type" className="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-white text-sm focus:outline-none focus:border-blue-500 transition-all appearance-none" required>
                                <option value="Guia">Guia</option>
                                <option value="Certidão">Certidão</option>
                                <option value="Contrato">Contrato</option>
                                <option value="Balancete">Balancete</option>
                                <option value="Folha">Folha de Pagamento</option>
                                <option value="Outros">Outros</option>
                            </select>
                        </div>
                        <div>
                            <label className="block text-[10px] font-bold text-slate-500 uppercase mb-1.5 ml-1">Descrição</label>
                            <input name="description" type="text" placeholder="Ex: Guia DAS 01/2026"
                                className="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-white text-sm focus:outline-none focus:border-blue-500 transition-all" required />
                        </div>
                    </div>
                    <button type="submit" disabled={loading}
                        className="bg-blue-600 hover:bg-blue-500 disabled:bg-slate-800 text-white px-8 py-3 rounded-xl font-bold transition-all flex items-center gap-2">
                        {loading ? <Loader2 className="w-4 h-4 animate-spin" /> : <Plus className="w-4 h-4" />}
                        Criar Protocolo
                    </button>
                </form>
            )}

            {/* Protocols Table */}
            <div className="bg-slate-900/40 border border-slate-800 rounded-3xl overflow-hidden">
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
                            {protocols.map(p => (
                                <tr key={p.id} className="hover:bg-slate-800/20 transition-all group">
                                    <td className="p-6 text-sm font-mono text-blue-400">{p.code}</td>
                                    <td className="p-6 text-sm text-white font-medium">{p.client.name}</td>
                                    <td className="p-6">
                                        <div className="text-sm text-white font-medium">{p.description}</div>
                                        <div className="text-[10px] text-slate-500 font-bold uppercase">{p.type}</div>
                                    </td>
                                    <td className="p-6 text-xs text-slate-400">
                                        {format(new Date(p.createdAt), "dd/MM/yyyy HH:mm")}
                                    </td>
                                    <td className="p-6">
                                        {p.receivedAt ? (
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
                                        {!p.receivedAt && (
                                            <button
                                                onClick={() => handleConfirm(p.id)}
                                                disabled={loading}
                                                className="px-3 py-1.5 bg-emerald-600/10 hover:bg-emerald-600 text-emerald-500 hover:text-white rounded-lg text-[10px] font-bold uppercase transition-all"
                                            >
                                                Confirmar
                                            </button>
                                        )}
                                    </td>
                                </tr>
                            ))}
                            {protocols.length === 0 && (
                                <tr>
                                    <td colSpan={6} className="p-10 text-center text-slate-500 italic">Nenhum protocolo encontrado.</td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    );
}
