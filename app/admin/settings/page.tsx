'use client';

import React, { useState } from 'react';
import { changePassword } from '@/app/actions/auth';
import { Lock, ShieldCheck, AlertCircle, Save, Loader2 } from 'lucide-react';

export default function SettingsPage() {
    const [loading, setLoading] = useState(false);
    const [message, setMessage] = useState<{ type: 'success' | 'error', text: string } | null>(null);

    async function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
        e.preventDefault();
        setLoading(true);
        setMessage(null);

        const formData = new FormData(e.currentTarget);
        const result = await changePassword(formData);

        setLoading(false);
        if (result.success) {
            setMessage({ type: 'success', text: result.message! });
            (e.target as HTMLFormElement).reset();
        } else {
            setMessage({ type: 'error', text: result.error! });
        }
    }

    return (
        <div className="p-4 sm:p-6 lg:p-8 max-w-2xl mx-auto">
            <div className="mb-8">
                <h1 className="text-3xl font-bold text-white uppercase tracking-tighter flex items-center gap-3">
                    <ShieldCheck className="w-8 h-8 text-blue-500" />
                    Configurações
                </h1>
                <p className="text-slate-500 text-sm mt-2">Gerencie sua conta e preferências de segurança.</p>
            </div>

            <div className="space-y-6">
                {/* Password Change Section */}
                <div className="bg-slate-900/40 border border-slate-800 rounded-3xl p-8 shadow-2xl backdrop-blur-sm">
                    <div className="flex items-center gap-3 mb-6">
                        <div className="p-2 bg-blue-500/10 rounded-xl">
                            <Lock className="w-5 h-5 text-blue-400" />
                        </div>
                        <h2 className="text-xl font-bold text-white">Segurança: Alterar Senha</h2>
                    </div>

                    <form onSubmit={handleSubmit} className="space-y-4">
                        <div className="space-y-4">
                            <div>
                                <label className="block text-[10px] font-bold text-slate-500 uppercase mb-1.5 ml-1">Senha Atual</label>
                                <input
                                    type="password"
                                    name="currentPassword"
                                    required
                                    className="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-white text-sm focus:outline-none focus:border-blue-500 transition-all font-mono"
                                    placeholder="••••••••"
                                />
                            </div>

                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label className="block text-[10px] font-bold text-slate-500 uppercase mb-1.5 ml-1">Nova Senha</label>
                                    <input
                                        type="password"
                                        name="newPassword"
                                        required
                                        className="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-white text-sm focus:outline-none focus:border-blue-500 transition-all font-mono"
                                        placeholder="Mín. 6 caracteres"
                                    />
                                </div>
                                <div>
                                    <label className="block text-[10px] font-bold text-slate-500 uppercase mb-1.5 ml-1">Confirmar Nova Senha</label>
                                    <input
                                        type="password"
                                        name="confirmPassword"
                                        required
                                        className="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-white text-sm focus:outline-none focus:border-blue-500 transition-all font-mono"
                                        placeholder="Confirme a senha"
                                    />
                                </div>
                            </div>
                        </div>

                        {message && (
                            <div className={`p-4 rounded-xl flex items-center gap-3 animate-in fade-in slide-in-from-top-2 duration-300 ${message.type === 'success' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20'
                                }`}>
                                {message.type === 'error' && <AlertCircle className="w-5 h-5 shrink-0" />}
                                <span className="text-sm font-medium">{message.text}</span>
                            </div>
                        )}

                        <button
                            type="submit"
                            disabled={loading}
                            className="w-full md:w-auto px-8 bg-blue-600 hover:bg-blue-500 disabled:bg-slate-800 text-white py-3 rounded-xl font-bold text-sm transition-all flex items-center justify-center gap-2"
                        >
                            {loading ? <Loader2 className="w-4 h-4 animate-spin" /> : <Save className="w-4 h-4" />}
                            Atualizar Senha
                        </button>
                    </form>
                </div>

                {/* Account Info Section (Read-only for now) */}
                <div className="bg-slate-900/20 border border-slate-800/50 rounded-3xl p-8">
                    <div className="flex items-center justify-between text-slate-500 text-xs font-bold uppercase tracking-widest">
                        <span>Versão do ERP</span>
                        <span>v1.2.0 - Diamond Edition</span>
                    </div>
                </div>
            </div>
        </div>
    );
}
