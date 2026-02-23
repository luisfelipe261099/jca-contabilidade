'use client';

import React, { useState } from 'react';
import { updateClient, deleteClient } from '@/app/actions/clients';
import { X, Loader2, Save, Trash2, AlertCircle } from 'lucide-react';

interface EditClientModalProps {
    client: any;
    isOpen: boolean;
    onClose: () => void;
}

export default function EditClientModal({ client, isOpen, onClose }: EditClientModalProps) {
    const [loading, setLoading] = useState(false);
    const [showDeleteConfirm, setShowDeleteConfirm] = useState(false);

    if (!isOpen) return null;

    async function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
        e.preventDefault();
        setLoading(true);
        const formData = new FormData(e.currentTarget);
        const result = await updateClient(client.id, formData);
        setLoading(false);

        if (result.success) {
            onClose();
        } else {
            alert(result.error);
        }
    }

    async function handleDelete() {
        if (!showDeleteConfirm) {
            setShowDeleteConfirm(true);
            return;
        }

        setLoading(true);
        const result = await deleteClient(client.id);
        setLoading(false);

        if (result.success) {
            onClose();
        } else {
            alert(result.error);
        }
    }

    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm animate-in fade-in duration-300">
            <div className="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-md overflow-hidden shadow-2xl animate-in zoom-in-95 duration-300">
                <div className="p-6 border-b border-slate-800 flex items-center justify-between bg-slate-900/50">
                    <h2 className="text-xl font-bold text-white uppercase tracking-tight">Editar Cliente</h2>
                    <button onClick={onClose} className="p-2 hover:bg-slate-800 rounded-xl transition-colors">
                        <X className="w-5 h-5 text-slate-500" />
                    </button>
                </div>

                <form onSubmit={handleSubmit} className="p-6 space-y-6">
                    <div className="space-y-4">
                        <div>
                            <label className="block text-[10px] font-bold text-slate-500 uppercase mb-1.5 ml-1">Razão Social</label>
                            <input
                                name="name"
                                defaultValue={client.name}
                                required
                                className="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-white text-sm focus:outline-none focus:border-blue-500 transition-all"
                            />
                        </div>
                        <div>
                            <label className="block text-[10px] font-bold text-slate-500 uppercase mb-1.5 ml-1">CNPJ</label>
                            <input
                                name="cnpj"
                                defaultValue={client.cnpj}
                                required
                                className="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-white text-sm font-mono focus:outline-none focus:border-blue-500 transition-all"
                            />
                        </div>
                        <div>
                            <label className="block text-[10px] font-bold text-slate-500 uppercase mb-1.5 ml-1">Regime Tributário</label>
                            <select
                                name="regime"
                                defaultValue={client.regime}
                                className="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-white text-sm focus:outline-none focus:border-blue-500 transition-all"
                            >
                                <option value="SIMPLES_NACIONAL">Simples Nacional</option>
                                <option value="LUCRO_PRESUMIDO">Lucro Presumido</option>
                                <option value="LUCRO_REAL">Lucro Real</option>
                                <option value="MEI">MEI</option>
                                <option value="ESOCIAL_DOMESTICO">eSocial Doméstico</option>
                            </select>
                        </div>
                        <div>
                            <label className="block text-[10px] font-bold text-slate-500 uppercase mb-1.5 ml-1">Serviços Contratados</label>
                            <div className="flex flex-wrap gap-2">
                                {['FISCAL', 'CONTABIL', 'DP / RH', 'SOCIETARIO', 'FINANCEIRO', 'IMPOSTO DE RENDA', 'BPO FINANCEIRO', 'ALVARÁ', 'CERTIFICADO DIGITAL', 'ESOCIAL DOMÉSTICO'].map((s) => (
                                    <label key={s} className="flex items-center gap-2 text-white bg-slate-950 border border-slate-800 px-3 py-1.5 rounded-lg cursor-pointer hover:border-blue-500 transition-all">
                                        <input type="checkbox" name="services" value={s} defaultChecked={client.services?.includes(s)} className="w-3.5 h-3.5 accent-blue-600 cursor-pointer" />
                                        <span className="text-sm font-bold">{s}</span>
                                    </label>
                                ))}
                            </div>
                        </div>
                    </div>

                    <div className="flex flex-col gap-3 pt-4">
                        <button
                            type="submit"
                            disabled={loading}
                            className="w-full bg-blue-600 hover:bg-blue-500 disabled:bg-slate-800 text-white py-3 rounded-xl font-bold text-sm transition-all flex items-center justify-center gap-2"
                        >
                            {loading ? <Loader2 className="w-4 h-4 animate-spin" /> : <Save className="w-4 h-4" />}
                            Salvar Alterações
                        </button>

                        <button
                            type="button"
                            onClick={handleDelete}
                            disabled={loading}
                            className={`w-full py-3 rounded-xl font-bold text-sm transition-all flex items-center justify-center gap-2 ${showDeleteConfirm
                                ? 'bg-rose-600 hover:bg-rose-500 text-white'
                                : 'bg-slate-800/50 hover:bg-rose-500/10 text-slate-400 hover:text-rose-500'
                                }`}
                        >
                            {loading ? <Loader2 className="w-4 h-4 animate-spin" /> : <Trash2 className="w-4 h-4" />}
                            {showDeleteConfirm ? 'Confirmar Exclusão' : 'Excluir Cliente'}
                        </button>

                        {showDeleteConfirm && (
                            <div className="flex items-center gap-2 text-rose-500 text-[10px] font-bold uppercase justify-center animate-pulse">
                                <AlertCircle className="w-3 h-3" />
                                Atenção: Isso apagará tarefas e documentos!
                            </div>
                        )}
                    </div>
                </form>
            </div>
        </div>
    );
}
