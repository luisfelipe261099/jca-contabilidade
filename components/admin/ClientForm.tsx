'use client';

import React, { useState } from 'react';
import { Plus, Loader2 } from 'lucide-react';
import { createClient } from '@/app/actions/clients';

export default function ClientForm() {
    const [loading, setLoading] = useState(false);
    const [message, setMessage] = useState({ type: '', text: '' });

    async function handleSubmit(formData: FormData) {
        setLoading(true);
        setMessage({ type: '', text: '' });

        const result = await createClient(formData);

        if (result.success) {
            setMessage({ type: 'success', text: 'Cliente cadastrado com sucesso!' });
            (document.getElementById('add-client-form') as HTMLFormElement).reset();
        } else {
            setMessage({ type: 'error', text: result.error || 'Ocorreu um erro.' });
        }

        setLoading(false);
    }

    return (
        <div className="bg-slate-900/40 border border-slate-800 rounded-3xl p-8 mb-10">
            <h2 className="text-lg font-bold text-white mb-6 flex items-center gap-2">
                <Plus className="w-5 h-5 text-blue-500" />
                Cadastramento Rápido
            </h2>
            <form id="add-client-form" action={handleSubmit} className="grid grid-cols-1 md:grid-cols-4 gap-4">
                <input
                    name="name"
                    type="text"
                    placeholder="Nome da Empresa / Razão Social"
                    className="bg-slate-950/50 border border-slate-800 rounded-xl py-3 px-4 text-white focus:outline-none focus:border-blue-500 transition-all font-medium"
                    required
                />
                <input
                    name="cnpj"
                    type="text"
                    placeholder="CNPJ"
                    className="bg-slate-950/50 border border-slate-800 rounded-xl py-3 px-4 text-white focus:outline-none focus:border-blue-500 transition-all font-medium"
                    required
                />
                <select
                    name="regime"
                    className="bg-slate-950/50 border border-slate-800 rounded-xl py-3 px-4 text-white focus:outline-none focus:border-blue-500 transition-all appearance-none font-medium"
                    required
                >
                    <option value="SIMPLES_NACIONAL">Simples Nacional</option>
                    <option value="LUCRO_PRESUMIDO">Lucro Presumido</option>
                    <option value="LUCRO_REAL">Lucro Real</option>
                    <option value="MEI">MEI</option>
                    <option value="ESOCIAL_DOMESTICO">eSocial Doméstico</option>
                </select>
                <div className="md:col-span-4 flex flex-wrap gap-4 mt-2 mb-2">
                    <span className="w-full text-xs font-bold text-slate-500 uppercase">Serviços Contratados:</span>
                    {['FISCAL', 'CONTABIL', 'DP / RH', 'SOCIETARIO', 'FINANCEIRO', 'IMPOSTO DE RENDA', 'BPO FINANCEIRO', 'ALVARÁ', 'CERTIFICADO DIGITAL', 'ESOCIAL DOMÉSTICO'].map((s) => (
                        <label key={s} className="flex items-center gap-2 text-white bg-slate-950/50 border border-slate-800 px-4 py-2 rounded-xl cursor-pointer hover:border-blue-500 transition-all select-none">
                            <input type="checkbox" name="services" value={s} defaultChecked={['FISCAL', 'CONTABIL', 'DP / RH', 'SOCIETARIO', 'FINANCEIRO'].includes(s)} className="w-4 h-4 accent-blue-600 cursor-pointer" />
                            <span className="text-sm font-bold uppercase tracking-wide">{s}</span>
                        </label>
                    ))}
                </div>
                <div className="md:col-span-4">
                    <button
                        type="submit"
                        disabled={loading}
                        className="w-full bg-blue-600 hover:bg-blue-500 disabled:bg-slate-800 text-white py-3 rounded-xl font-bold transition-all flex items-center justify-center gap-2"
                    >
                        {loading ? <Loader2 className="w-5 h-5 animate-spin" /> : 'Salvar Cliente'}
                    </button>
                </div>
            </form>

            {message.text && (
                <div className={`mt-4 p-4 rounded-xl text-sm font-bold text-center border ${message.type === 'success'
                    ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400'
                    : 'bg-red-500/10 border-red-500/20 text-red-400'
                    }`}>
                    {message.text}
                </div>
            )}
        </div>
    );
}
