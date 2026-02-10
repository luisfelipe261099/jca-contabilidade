'use client';

import React, { useState } from 'react';
import { UserPlus, Shield, Trash2, Key, Loader2, Users, Eye, EyeOff } from 'lucide-react';
import { createUser, deleteUser, resetUserPassword } from '@/app/actions/admin';

interface User {
    id: string;
    email: string;
    name: string | null;
    role: string;
    clientId: string | null;
    createdAt: Date;
}

interface Client {
    id: string;
    name: string;
}

export default function UserActions({ users, clients }: { users: User[]; clients: Client[] }) {
    const [loading, setLoading] = useState(false);
    const [message, setMessage] = useState({ type: '', text: '' });
    const [showForm, setShowForm] = useState(false);
    const [resetId, setResetId] = useState<string | null>(null);
    const [newPassword, setNewPassword] = useState('');
    const [showPassword, setShowPassword] = useState(false);
    const [selectedRole, setSelectedRole] = useState('CLIENT');

    async function handleCreateUser(formData: FormData) {
        setLoading(true);
        setMessage({ type: '', text: '' });

        const result = await createUser(formData);

        if (result.success) {
            setMessage({ type: 'success', text: 'Usuário criado com sucesso!' });
            setShowForm(false);
        } else {
            setMessage({ type: 'error', text: result.error || 'Erro ao criar usuário.' });
        }
        setLoading(false);
    }

    async function handleDelete(id: string) {
        if (!confirm('Tem certeza que deseja excluir este usuário?')) return;
        setLoading(true);
        const result = await deleteUser(id);
        if (result.error) {
            setMessage({ type: 'error', text: result.error });
        } else {
            setMessage({ type: 'success', text: 'Usuário excluído!' });
        }
        setLoading(false);
    }

    async function handleResetPassword(id: string) {
        if (!newPassword || newPassword.length < 6) {
            setMessage({ type: 'error', text: 'Senha deve ter no mínimo 6 caracteres.' });
            return;
        }
        setLoading(true);
        const result = await resetUserPassword(id, newPassword);
        if (result.success) {
            setMessage({ type: 'success', text: 'Senha resetada com sucesso!' });
            setResetId(null);
            setNewPassword('');
        } else {
            setMessage({ type: 'error', text: result.error || 'Erro.' });
        }
        setLoading(false);
    }

    const getRoleBadge = (role: string) => {
        switch (role) {
            case 'ADMIN': return 'bg-red-500/10 text-red-400 border-red-500/20';
            case 'EMPLOYEE': return 'bg-blue-500/10 text-blue-400 border-blue-500/20';
            case 'CLIENT': return 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20';
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

            {/* Create User Form */}
            <div className="mb-8">
                <button
                    onClick={() => setShowForm(!showForm)}
                    className="flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-bold transition-all"
                >
                    <UserPlus className="w-5 h-5" />
                    {showForm ? 'Fechar Formulário' : 'Novo Usuário'}
                </button>

                {showForm && (
                    <form action={handleCreateUser} className="mt-6 bg-slate-900/40 border border-slate-800 rounded-3xl p-8">
                        <h3 className="text-lg font-bold text-white mb-6 flex items-center gap-2">
                            <UserPlus className="w-5 h-5 text-blue-500" />
                            Criar Novo Acesso
                        </h3>
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label className="block text-[10px] font-bold text-slate-500 uppercase mb-1.5 ml-1">Nome Completo</label>
                                <input
                                    name="name"
                                    type="text"
                                    placeholder="Ex: João da Silva"
                                    className="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-white text-sm focus:outline-none focus:border-blue-500 transition-all"
                                    required
                                />
                            </div>
                            <div>
                                <label className="block text-[10px] font-bold text-slate-500 uppercase mb-1.5 ml-1">E-mail</label>
                                <input
                                    name="email"
                                    type="email"
                                    placeholder="email@exemplo.com"
                                    className="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-white text-sm focus:outline-none focus:border-blue-500 transition-all"
                                    required
                                />
                            </div>
                            <div>
                                <label className="block text-[10px] font-bold text-slate-500 uppercase mb-1.5 ml-1">Senha</label>
                                <div className="relative">
                                    <input
                                        name="password"
                                        type={showPassword ? "text" : "password"}
                                        placeholder="Mínimo 6 caracteres"
                                        className="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 pr-12 text-white text-sm focus:outline-none focus:border-blue-500 transition-all"
                                        required
                                        minLength={6}
                                    />
                                    <button type="button" onClick={() => setShowPassword(!showPassword)} className="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-white">
                                        {showPassword ? <EyeOff className="w-4 h-4" /> : <Eye className="w-4 h-4" />}
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label className="block text-[10px] font-bold text-slate-500 uppercase mb-1.5 ml-1">Tipo de Acesso</label>
                                <select
                                    name="role"
                                    value={selectedRole}
                                    onChange={(e) => setSelectedRole(e.target.value)}
                                    className="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-white text-sm focus:outline-none focus:border-blue-500 transition-all appearance-none"
                                    required
                                >
                                    <option value="ADMIN">Administrador</option>
                                    <option value="EMPLOYEE">Funcionário</option>
                                    <option value="CLIENT">Cliente</option>
                                </select>
                            </div>
                            {selectedRole === 'CLIENT' && (
                                <div className="md:col-span-2">
                                    <label className="block text-[10px] font-bold text-slate-500 uppercase mb-1.5 ml-1">Vincular à Empresa</label>
                                    <select
                                        name="clientId"
                                        className="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-white text-sm focus:outline-none focus:border-blue-500 transition-all appearance-none"
                                        required
                                    >
                                        <option value="">Selecione uma empresa...</option>
                                        {clients.map(c => (
                                            <option key={c.id} value={c.id}>{c.name}</option>
                                        ))}
                                    </select>
                                </div>
                            )}
                        </div>
                        <button
                            type="submit"
                            disabled={loading}
                            className="bg-blue-600 hover:bg-blue-500 disabled:bg-slate-800 text-white px-8 py-3 rounded-xl font-bold transition-all flex items-center gap-2"
                        >
                            {loading ? <Loader2 className="w-4 h-4 animate-spin" /> : <UserPlus className="w-4 h-4" />}
                            Criar Usuário
                        </button>
                    </form>
                )}
            </div>

            {/* Users Table */}
            <div className="bg-slate-900/40 border border-slate-800 rounded-3xl overflow-hidden">
                <div className="p-6 border-b border-slate-800 flex items-center justify-between">
                    <h2 className="text-xl font-bold text-white flex items-center gap-2">
                        <Users className="w-5 h-5 text-blue-500" />
                        Todos os Usuários ({users.length})
                    </h2>
                </div>
                <div className="overflow-x-auto">
                    <table className="w-full text-left border-collapse">
                        <thead>
                            <tr className="border-b border-slate-800">
                                <th className="p-6 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Nome</th>
                                <th className="p-6 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Email</th>
                                <th className="p-6 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Tipo</th>
                                <th className="p-6 text-[10px] font-bold text-slate-500 uppercase tracking-widest text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-800/50">
                            {users.map(user => (
                                <tr key={user.id} className="hover:bg-slate-800/20 transition-colors">
                                    <td className="p-6 font-bold text-white">{user.name || '—'}</td>
                                    <td className="p-6 text-slate-400 text-sm font-mono">{user.email}</td>
                                    <td className="p-6">
                                        <span className={`px-3 py-1 rounded-full text-[10px] font-bold uppercase border ${getRoleBadge(user.role)}`}>
                                            {user.role === 'ADMIN' ? 'Admin' : user.role === 'EMPLOYEE' ? 'Funcionário' : 'Cliente'}
                                        </span>
                                    </td>
                                    <td className="p-6 text-right">
                                        <div className="flex items-center justify-end gap-2">
                                            {resetId === user.id ? (
                                                <div className="flex items-center gap-2">
                                                    <input
                                                        type="password"
                                                        placeholder="Nova senha"
                                                        value={newPassword}
                                                        onChange={e => setNewPassword(e.target.value)}
                                                        className="bg-slate-950 border border-slate-700 rounded-lg py-1.5 px-3 text-sm text-white w-36 focus:outline-none focus:border-blue-500"
                                                    />
                                                    <button
                                                        onClick={() => handleResetPassword(user.id)}
                                                        disabled={loading}
                                                        className="text-emerald-500 hover:text-emerald-400 text-xs font-bold"
                                                    >
                                                        Salvar
                                                    </button>
                                                    <button
                                                        onClick={() => { setResetId(null); setNewPassword(''); }}
                                                        className="text-slate-500 hover:text-white text-xs"
                                                    >
                                                        ✕
                                                    </button>
                                                </div>
                                            ) : (
                                                <>
                                                    <button
                                                        onClick={() => setResetId(user.id)}
                                                        className="p-2 hover:bg-slate-800 rounded-lg text-slate-500 hover:text-amber-500 transition-all"
                                                        title="Resetar Senha"
                                                    >
                                                        <Key className="w-4 h-4" />
                                                    </button>
                                                    <button
                                                        onClick={() => handleDelete(user.id)}
                                                        disabled={loading}
                                                        className="p-2 hover:bg-red-500/10 rounded-lg text-slate-500 hover:text-red-500 transition-all"
                                                        title="Excluir"
                                                    >
                                                        <Trash2 className="w-4 h-4" />
                                                    </button>
                                                </>
                                            )}
                                        </div>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    );
}
