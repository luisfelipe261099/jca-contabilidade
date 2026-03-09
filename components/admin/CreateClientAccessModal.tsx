'use client';

import React, { useState } from 'react';
import { UserPlus, X, Key, Shield, Loader2, Eye, EyeOff } from 'lucide-react';
import { createUser } from '@/app/actions/admin';

interface CreateClientAccessModalProps {
    client: any;
    isOpen: boolean;
    onClose: () => void;
}

export default function CreateClientAccessModal({ client, isOpen, onClose }: CreateClientAccessModalProps) {
    const [loading, setLoading] = useState(false);
    const [message, setMessage] = useState({ type: '', text: '' });
    const [showPassword, setShowPassword] = useState(false);

    if (!isOpen) return null;

    async function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
        e.preventDefault();
        setLoading(true);
        setMessage({ type: '', text: '' });

        const formData = new FormData(e.currentTarget);
        formData.append('role', 'CLIENT');
        formData.append('clientId', client.id);

        const result = await createUser(formData);

        if (result.success) {
            setMessage({ type: 'success', text: 'Acesso criado com sucesso! O cliente já pode logar.' });
            setTimeout(() => {
                onClose();
                setMessage({ type: '', text: '' });
            }, 2000);
        } else {
            setMessage({ type: 'error', text: result.error || 'Erro ao criar acesso.' });
        }
        setLoading(false);
    }

    return (
        <div className="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm animate-in fade-in duration-200">
            <div className="bg-slate-900 border border-slate-800 w-full max-w-lg rounded-[32px] overflow-hidden shadow-2xl animate-in zoom-in-95 duration-200">
                <div className="p-8 border-b border-slate-800 flex items-center justify-between bg-slate-900/50">
                    <div className="flex items-center gap-3">
                        <div className="p-2 bg-blue-500/10 rounded-xl">
                            <Key className="w-5 h-5 text-blue-500" />
                        </div>
                        <div>
                            <h2 className="text-xl font-bold text-white uppercase italic">Criar Acesso ao Sistema</h2>
                            <p className="text-[10px] text-slate-500 font-bold uppercase tracking-widest leading-none mt-1">{client.name}</p>
                        </div>
                    </div>
                    <button
                        onClick={onClose}
                        className="p-2 hover:bg-slate-800 rounded-full text-slate-400 hover:text-white transition-colors"
                    >
                        <X className="w-5 h-5" />
                    </button>
                </div>

                <form onSubmit={handleSubmit} className="p-8 space-y-6">
                    {message.text && (
                        <div className={`p-4 rounded-2xl text-sm font-bold flex items-center gap-3 border ${message.type === 'success'
                                ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400'
                                : 'bg-red-500/10 border-red-500/20 text-red-400'
                            }`}>
                            <Shield className="w-5 h-5 shrink-0" />
                            {message.text}
                        </div>
                    )}

                    <div className="space-y-4">
                        <div>
                            <label className="block text-[10px] font-bold text-slate-500 uppercase mb-1.5 ml-1">E-mail de Acesso</label>
                            <input
                                name="email"
                                type="email"
                                placeholder="vendas@empresa.com.br"
                                required
                                className="w-full bg-slate-950 border border-slate-800 rounded-xl py-3.5 px-5 text-white text-sm focus:outline-none focus:border-blue-500 transition-all placeholder:text-slate-700"
                            />
                        </div>

                        <div>
                            <label className="block text-[10px] font-bold text-slate-500 uppercase mb-1.5 ml-1">Senha Inicial</label>
                            <div className="relative">
                                <input
                                    name="password"
                                    type={showPassword ? "text" : "password"}
                                    placeholder="Mínimo 6 caracteres"
                                    required
                                    minLength={6}
                                    className="w-full bg-slate-950 border border-slate-800 rounded-xl py-3.5 px-5 pr-12 text-white text-sm focus:outline-none focus:border-blue-500 transition-all placeholder:text-slate-700"
                                />
                                <button
                                    type="button"
                                    onClick={() => setShowPassword(!showPassword)}
                                    className="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-white transition-colors"
                                >
                                    {showPassword ? <EyeOff className="w-4 h-4" /> : <Eye className="w-4 h-4" />}
                                </button>
                            </div>
                        </div>

                        <div>
                            <label className="block text-[10px] font-bold text-slate-500 uppercase mb-1.5 ml-1">Nome do Responsável</label>
                            <input
                                name="name"
                                type="text"
                                placeholder="Ex: Financeiro / Direção"
                                required
                                className="w-full bg-slate-950 border border-slate-800 rounded-xl py-3.5 px-5 text-white text-sm focus:outline-none focus:border-blue-500 transition-all placeholder:text-slate-700"
                            />
                        </div>
                    </div>

                    <div className="pt-4 flex gap-4">
                        <button
                            type="button"
                            onClick={onClose}
                            className="flex-1 py-4 bg-slate-800 hover:bg-slate-750 text-slate-400 rounded-2xl font-bold transition-all text-sm"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            disabled={loading}
                            className="flex-[2] bg-blue-600 hover:bg-blue-500 disabled:bg-slate-800 text-white rounded-2xl font-bold transition-all flex items-center justify-center gap-2 shadow-lg shadow-blue-900/20"
                        >
                            {loading ? <Loader2 className="w-5 h-5 animate-spin" /> : <UserPlus className="w-5 h-5" />}
                            Habilitar Acesso
                        </button>
                    </div>

                    <div className="text-[10px] text-center text-slate-600 font-bold uppercase tracking-widest mt-4">
                        Acesso restrito ao Dashboard do Cliente
                    </div>
                </form>
            </div>
        </div>
    );
}
