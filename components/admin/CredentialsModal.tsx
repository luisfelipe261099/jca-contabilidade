'use client';

import React, { useState, useEffect, useCallback } from 'react';
import { createCredential, deleteCredential, getClientCredentials } from '@/app/actions/credentials';
import { X, Loader2, Key, Plus, Trash2, Copy, Check } from 'lucide-react';

interface CredentialsModalProps {
    client: any;
    isOpen: boolean;
    onClose: () => void;
}

export default function CredentialsModal({ client, isOpen, onClose }: CredentialsModalProps) {
    const [loading, setLoading] = useState(false);
    const [credentials, setCredentials] = useState<any[]>([]);
    const [isAdding, setIsAdding] = useState(false);
    const [copiedId, setCopiedId] = useState<string | null>(null);

    const loadCredentials = useCallback(async () => {
        setLoading(true);
        const result = await getClientCredentials(client.id);
        if (result.success) {
            setCredentials(result.credentials || []);
        }
        setLoading(false);
    }, [client.id]);

    useEffect(() => {
        if (isOpen) {
            loadCredentials();
        }
    }, [isOpen, loadCredentials]);

    async function handleAdd(e: React.FormEvent<HTMLFormElement>) {
        e.preventDefault();
        setLoading(true);
        const formData = new FormData(e.currentTarget);
        formData.append('clientId', client.id);

        const result = await createCredential(formData);

        if (result.success) {
            setIsAdding(false);
            loadCredentials();
        } else {
            alert(result.error);
        }
        setLoading(false);
    }

    async function handleDelete(id: string) {
        if (!confirm('Excluir esta credencial?')) return;
        setLoading(true);
        const result = await deleteCredential(id);
        if (result.success) {
            loadCredentials();
        } else {
            alert(result.error);
        }
        setLoading(false);
    }

    const copyToClipboard = (text: string, id: string) => {
        navigator.clipboard.writeText(text);
        setCopiedId(id);
        setTimeout(() => setCopiedId(null), 2000);
    };

    if (!isOpen) return null;

    return (
        <div className="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4 bg-slate-950/80 backdrop-blur-sm animate-in fade-in duration-300">
            <div className="bg-slate-900 border border-slate-800 rounded-t-3xl sm:rounded-3xl w-full max-w-2xl overflow-hidden shadow-2xl flex flex-col max-h-[90vh]">
                <div className="p-6 border-b border-slate-800 flex items-center justify-between bg-slate-900/50">
                    <div className="flex items-center gap-3">
                        <div className="w-10 h-10 bg-amber-500/10 rounded-xl flex items-center justify-center">
                            <Key className="w-5 h-5 text-amber-500" />
                        </div>
                        <div>
                            <h2 className="text-xl font-bold text-white tracking-tight">Cofre de Senhas</h2>
                            <p className="text-slate-400 text-xs font-bold uppercase tracking-widest">{client.name}</p>
                        </div>
                    </div>
                    <button onClick={onClose} className="p-2 hover:bg-slate-800 rounded-xl transition-colors">
                        <X className="w-5 h-5 text-slate-500" />
                    </button>
                </div>

                <div className="p-6 overflow-y-auto custom-scrollbar flex-1">
                    {!isAdding && (
                        <button
                            onClick={() => setIsAdding(true)}
                            className="w-full mb-6 p-4 border border-dashed border-slate-700 rounded-xl text-slate-400 hover:text-white hover:border-amber-500 hover:bg-amber-500/10 transition-all flex items-center justify-center gap-2 font-bold text-sm"
                        >
                            <Plus className="w-4 h-4" />
                            Nova Credencial
                        </button>
                    )}

                    {isAdding && (
                        <form onSubmit={handleAdd} className="mb-8 bg-slate-950 p-6 rounded-2xl border border-slate-800 animate-in slide-in-from-top-4">
                            <h3 className="text-white font-bold mb-4 flex items-center gap-2 text-sm uppercase tracking-wider">
                                <Plus className="w-4 h-4 text-amber-500" /> Adicionar Acesso
                            </h3>
                            <div className="space-y-4">
                                <input
                                    name="system"
                                    placeholder="Sistema (ex: e-CAC, Gov.br, Token PMSP)"
                                    required
                                    className="w-full bg-slate-900 border border-slate-800 rounded-xl py-3 px-4 text-white text-sm focus:outline-none focus:border-amber-500 transition-all font-medium"
                                />
                                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <input
                                        name="username"
                                        placeholder="Login / CPF / CNPJ"
                                        required
                                        className="w-full bg-slate-900 border border-slate-800 rounded-xl py-3 px-4 text-white text-sm focus:outline-none focus:border-amber-500 transition-all font-medium"
                                    />
                                    <input
                                        name="password"
                                        placeholder="Senha"
                                        required
                                        className="w-full bg-slate-900 border border-slate-800 rounded-xl py-3 px-4 text-white text-sm focus:outline-none focus:border-amber-500 transition-all font-medium"
                                    />
                                </div>
                                <input
                                    name="notes"
                                    placeholder="Observações adicionais (opcional)"
                                    className="w-full bg-slate-900 border border-slate-800 rounded-xl py-3 px-4 text-white text-sm focus:outline-none focus:border-amber-500 transition-all font-medium"
                                />
                                <div className="flex justify-end gap-3 pt-2">
                                    <button
                                        type="button"
                                        onClick={() => setIsAdding(false)}
                                        className="px-6 py-2.5 rounded-xl font-bold text-sm text-slate-400 hover:text-white hover:bg-slate-800 transition-all"
                                    >
                                        Cancelar
                                    </button>
                                    <button
                                        type="submit"
                                        disabled={loading}
                                        className="bg-amber-600 hover:bg-amber-500 disabled:bg-slate-800 text-white px-6 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2"
                                    >
                                        {loading ? <Loader2 className="w-4 h-4 animate-spin" /> : 'Salvar Senha'}
                                    </button>
                                </div>
                            </div>
                        </form>
                    )}

                    <div className="space-y-3">
                        {loading && !isAdding ? (
                            <div className="flex justify-center p-8"><Loader2 className="w-6 h-6 animate-spin text-amber-500" /></div>
                        ) : credentials.length === 0 && !isAdding ? (
                            <div className="text-center p-8 text-slate-500 italic text-sm">
                                Nenhuma credencial salva para esta empresa.
                            </div>
                        ) : (
                            credentials.map((cred) => (
                                <div key={cred.id} className="bg-slate-950 p-4 rounded-xl border border-slate-800 flex items-center justify-between group hover:border-amber-500/30 transition-all">
                                    <div className="flex-1 min-w-0 pr-4">
                                        <div className="font-bold text-white text-sm mb-1">{cred.system}</div>
                                        <div className="flex flex-col gap-1 text-xs font-mono text-slate-400">
                                            <div className="flex items-center gap-2">
                                                <span className="text-slate-500 uppercase tracking-widest text-[9px] w-12">Login</span>
                                                <span className="truncate flex-1">{cred.username}</span>
                                                <button onClick={() => copyToClipboard(cred.username, cred.id + 'u')} className="p-1 hover:bg-slate-800 rounded">
                                                    {copiedId === cred.id + 'u' ? <Check className="w-3 h-3 text-emerald-500" /> : <Copy className="w-3 h-3" />}
                                                </button>
                                            </div>
                                            <div className="flex items-center gap-2">
                                                <span className="text-slate-500 uppercase tracking-widest text-[9px] w-12">Senha</span>
                                                <span className="truncate flex-1 font-sans text-[10px] tracking-[0.2em]">{cred.password.replace(/./g, '•')}</span>
                                                <button onClick={() => copyToClipboard(cred.password, cred.id + 'p')} className="p-1 hover:bg-slate-800 rounded" title="Copiar senha secreta">
                                                    {copiedId === cred.id + 'p' ? <Check className="w-3 h-3 text-emerald-500" /> : <Copy className="w-3 h-3" />}
                                                </button>
                                            </div>
                                            {cred.notes && (
                                                <div className="mt-1 text-slate-600 font-sans italic">Obs: {cred.notes}</div>
                                            )}
                                        </div>
                                    </div>
                                    <button
                                        onClick={() => handleDelete(cred.id)}
                                        disabled={loading}
                                        className="p-2 text-slate-600 hover:text-red-400 hover:bg-red-400/10 rounded-lg transition-all sm:opacity-0 sm:group-hover:opacity-100"
                                        title="Excluir"
                                    >
                                        <Trash2 className="w-4 h-4" />
                                    </button>
                                </div>
                            ))
                        )}
                    </div>
                </div>
            </div>
        </div>
    );
}
