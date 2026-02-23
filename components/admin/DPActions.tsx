'use client';

import React, { useState } from 'react';
import { Plus, Loader2, Trash2, Users, FileText, FileDown } from 'lucide-react';
import { createEmployee, deleteEmployee, updateEmployeeStatus } from '@/app/actions/admin';
import HoleriteModal from './HoleriteModal';

interface Employee {
    id: string;
    name: string;
    role: string;
    admissionDate: string;
    salary: number;
    status: string;
    client: { id: string; name: string };
}

interface Client {
    id: string;
    name: string;
}

export default function DPActions({ employees, clients }: { employees: Employee[]; clients: Client[] }) {
    const [loading, setLoading] = useState(false);
    const [message, setMessage] = useState({ type: '', text: '' });
    const [showForm, setShowForm] = useState(false);
    const [selectedEmployee, setSelectedEmployee] = useState<Employee | null>(null);

    async function handleCreate(formData: FormData) {
        setLoading(true);
        setMessage({ type: '', text: '' });
        const result = await createEmployee(formData);
        if (result.success) {
            setMessage({ type: 'success', text: 'Funcionário cadastrado com sucesso!' });
            setShowForm(false);
        } else {
            setMessage({ type: 'error', text: result.error || 'Erro.' });
        }
        setLoading(false);
    }

    async function handleDelete(id: string) {
        if (!confirm('Excluir este funcionário?')) return;
        setLoading(true);
        const result = await deleteEmployee(id);
        if (result.error) setMessage({ type: 'error', text: result.error });
        else setMessage({ type: 'success', text: 'Excluído!' });
        setLoading(false);
    }

    async function handleStatusChange(id: string, status: string) {
        setLoading(true);
        await updateEmployeeStatus(id, status);
        setLoading(false);
    }

    const getStatusBadge = (status: string) => {
        switch (status) {
            case 'ACTIVE': return 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20';
            case 'VACATION': return 'bg-amber-500/10 text-amber-400 border-amber-500/20';
            case 'DISMISSED': return 'bg-red-500/10 text-red-400 border-red-500/20';
            case 'LEAVE': return 'bg-blue-500/10 text-blue-400 border-blue-500/20';
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

            <div className="flex justify-between items-center mb-6">
                <button
                    onClick={() => setShowForm(!showForm)}
                    className="flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-bold transition-all no-print"
                >
                    <Plus className="w-5 h-5" />
                    {showForm ? 'Fechar' : 'Novo Funcionário'}
                </button>
                <button
                    onClick={() => window.print()}
                    className="flex items-center gap-2 px-4 py-3 border border-slate-700 hover:border-blue-500 hover:text-blue-400 text-slate-300 rounded-xl font-bold transition-all text-sm no-print"
                >
                    <FileDown className="w-4 h-4" /> Exportar Listagem em PDF
                </button>
            </div>

            {showForm && (
                <form action={handleCreate} className="bg-slate-900/40 border border-slate-800 rounded-3xl p-8 mb-8">
                    <h3 className="text-lg font-bold text-white mb-6 flex items-center gap-2">
                        <Users className="w-5 h-5 text-blue-500" />
                        Cadastrar Funcionário
                    </h3>
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label className="block text-[10px] font-bold text-slate-500 uppercase mb-1.5 ml-1">Nome Completo</label>
                            <input name="name" type="text" placeholder="Nome do funcionário"
                                className="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-white text-sm focus:outline-none focus:border-blue-500 transition-all" required />
                        </div>
                        <div>
                            <label className="block text-[10px] font-bold text-slate-500 uppercase mb-1.5 ml-1">Cargo / Função</label>
                            <input name="role" type="text" placeholder="Ex: Analista, Auxiliar..."
                                className="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-white text-sm focus:outline-none focus:border-blue-500 transition-all" required />
                        </div>
                        <div>
                            <label className="block text-[10px] font-bold text-slate-500 uppercase mb-1.5 ml-1">Salário (R$)</label>
                            <input name="salary" type="number" step="0.01" placeholder="0.00"
                                className="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-white text-sm focus:outline-none focus:border-blue-500 transition-all" required />
                        </div>
                        <div>
                            <label className="block text-[10px] font-bold text-slate-500 uppercase mb-1.5 ml-1">Data de Admissão</label>
                            <input name="admissionDate" type="date"
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
                            <label className="block text-[10px] font-bold text-slate-500 uppercase mb-1.5 ml-1">Situação</label>
                            <select name="status" className="w-full bg-slate-950 border border-slate-800 rounded-xl py-3 px-4 text-white text-sm focus:outline-none focus:border-blue-500 transition-all appearance-none">
                                <option value="ACTIVE">Ativo</option>
                                <option value="VACATION">Férias</option>
                                <option value="LEAVE">Afastado</option>
                                <option value="DISMISSED">Desligado</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" disabled={loading}
                        className="bg-blue-600 hover:bg-blue-500 disabled:bg-slate-800 text-white px-8 py-3 rounded-xl font-bold transition-all flex items-center gap-2">
                        {loading ? <Loader2 className="w-4 h-4 animate-spin" /> : <Plus className="w-4 h-4" />}
                        Cadastrar
                    </button>
                </form>
            )}

            {/* Employees Table */}
            <div className="bg-slate-900/40 border border-slate-800 rounded-3xl overflow-hidden">
                <div className="overflow-x-auto">
                    <table className="w-full text-left border-collapse">
                        <thead>
                            <tr className="border-b border-slate-800">
                                <th className="p-6 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Funcionário</th>
                                <th className="p-6 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Empresa</th>
                                <th className="p-6 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Cargo</th>
                                <th className="p-6 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Salário</th>
                                <th className="p-6 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Admissão</th>
                                <th className="p-6 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Status</th>
                                <th className="p-6 text-[10px] font-bold text-slate-500 uppercase tracking-widest text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-800/50">
                            {employees.map(emp => (
                                <tr key={emp.id} className="hover:bg-slate-800/20 transition-colors">
                                    <td className="p-6 font-bold text-white">{emp.name}</td>
                                    <td className="p-6 text-slate-400 text-sm">{emp.client?.name || '—'}</td>
                                    <td className="p-6 text-slate-300 text-sm">{emp.role}</td>
                                    <td className="p-6 text-white font-bold">
                                        {new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(emp.salary)}
                                    </td>
                                    <td className="p-6 text-slate-400 text-sm">{new Date(emp.admissionDate).toLocaleDateString('pt-BR')}</td>
                                    <td className="p-6">
                                        <select
                                            value={emp.status}
                                            onChange={(e) => handleStatusChange(emp.id, e.target.value)}
                                            className={`px-3 py-1.5 rounded-full text-[10px] font-bold uppercase border bg-transparent cursor-pointer focus:outline-none ${getStatusBadge(emp.status)}`}
                                        >
                                            <option value="ACTIVE">Ativo</option>
                                            <option value="VACATION">Férias</option>
                                            <option value="LEAVE">Afastado</option>
                                            <option value="DISMISSED">Desligado</option>
                                        </select>
                                    </td>
                                    <td className="p-6 text-right relative no-print">
                                        <div className="flex items-center justify-end gap-1">
                                            <button
                                                onClick={() => setSelectedEmployee(emp)}
                                                className="p-2 hover:bg-blue-500/10 rounded-lg text-slate-500 hover:text-blue-500 transition-all font-bold flex items-center gap-2 text-[10px] uppercase tracking-wider"
                                                title="Gerar Holerite / PDF"
                                            >
                                                <FileText className="w-4 h-4" /> PDF
                                            </button>
                                            <button onClick={() => handleDelete(emp.id)} disabled={loading}
                                                className="p-2 hover:bg-red-500/10 rounded-lg text-slate-500 hover:text-red-500 transition-all" title="Excluir">
                                                <Trash2 className="w-4 h-4" />
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            ))}
                            {employees.length === 0 && (
                                <tr><td colSpan={7} className="p-10 text-center text-slate-500 italic">Nenhum funcionário cadastrado.</td></tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>

            <HoleriteModal
                employee={selectedEmployee}
                isOpen={!!selectedEmployee}
                onClose={() => setSelectedEmployee(null)}
            />
        </div>
    );
}
