export const dynamic = 'force-dynamic';
import React from 'react';
import { Plus, Search, Building2, MoreHorizontal } from 'lucide-react';
import prisma from '@/lib/prisma';
import ClientForm from '@/components/admin/ClientForm';
import { auth } from '@/auth';
import EditClientTrigger from '@/components/admin/EditClientTrigger';
import CredentialsTrigger from '@/components/admin/CredentialsTrigger';

export default async function ClientsPage() {
    const session = await auth();
    const role = (session?.user as any)?.role || 'EMPLOYEE';
    const email = session?.user?.email;

    const loggedUser = email ? await prisma.user.findUnique({ where: { email } }) : null;
    const userId = loggedUser?.id;
    const department = loggedUser?.department || 'GERAL';

    let clientWhere: any = {};
    if (role === 'CLIENT' && loggedUser?.clientId) {
        clientWhere.id = loggedUser.clientId;
    } else if (role !== 'ADMIN' && department !== 'GERAL') {
        clientWhere.OR = [
            { responsibleId: userId },
            { services: { contains: department } } // DP / RH needs exact substring, if department string doesn't match we might need adjusting but usually it works fine
        ];
    }

    const clients = await prisma.client.findMany({
        where: clientWhere,
        orderBy: { name: 'asc' },
    });

    return (
        <div className="p-8">
            <div className="flex items-center justify-between mb-10">
                <div>
                    <h1 className="text-3xl font-bold text-white mb-2">Gestão de Clientes</h1>
                    <p className="text-slate-400">Cadastre e gerencie a carteira da JCA.</p>
                </div>
                <button className="flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-bold transition-all">
                    <Plus className="w-5 h-5" />
                    Novo Cliente
                </button>
            </div>

            {/* Quick Add Form */}
            <ClientForm />

            <div className="bg-slate-900/40 border border-slate-800 rounded-3xl overflow-hidden shadow-2xl">
                <div className="overflow-x-auto">
                    <table className="w-full text-left border-collapse">
                        <thead>
                            <tr className="bg-slate-950/50 border-b border-slate-800">
                                <th className="px-8 py-5 text-slate-500 text-xs font-bold uppercase tracking-wider">Empresa</th>
                                <th className="px-8 py-5 text-slate-500 text-xs font-bold uppercase tracking-wider">CNPJ</th>
                                <th className="px-8 py-5 text-slate-500 text-xs font-bold uppercase tracking-wider">Regime</th>
                                <th className="px-8 py-5 text-slate-500 text-xs font-bold uppercase tracking-wider">Serviços Contratados</th>
                                <th className="px-8 py-5 text-slate-500 text-xs font-bold uppercase tracking-wider">Status</th>
                                <th className="px-8 py-5 text-right"></th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-800/50">
                            {clients.map((client: any) => (
                                <tr key={client.id} className="hover:bg-slate-800/20 transition-colors">
                                    <td className="px-8 py-6">
                                        <div className="flex items-center gap-3">
                                            <div className="w-10 h-10 bg-slate-800 rounded-lg flex items-center justify-center">
                                                <Building2 className="w-5 h-5 text-slate-400" />
                                            </div>
                                            <span className="font-bold text-white">{client.name}</span>
                                        </div>
                                    </td>
                                    <td className="px-8 py-6 text-slate-400 font-mono text-sm">{client.cnpj}</td>
                                    <td className="px-8 py-6">
                                        <span className="px-3 py-1 bg-slate-800 text-slate-300 rounded-full text-[10px] font-bold border border-slate-700 uppercase">
                                            {client.regime.replace('_', ' ')}
                                        </span>
                                    </td>
                                    <td className="px-8 py-6">
                                        <div className="flex flex-wrap gap-1 min-w-[150px] max-w-[200px]">
                                            {client.services ? client.services.split(',').map((s: string) => (
                                                <span key={s} className="px-2 py-0.5 bg-blue-500/10 text-blue-400 rounded text-[9px] font-bold uppercase border border-blue-500/20">{s}</span>
                                            )) : <span className="text-slate-500 text-[10px] italic">Sem serviços</span>}
                                        </div>
                                    </td>
                                    <td className="px-8 py-6">
                                        <div className="flex items-center gap-2">
                                            <div className="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]"></div>
                                            <span className="text-xs text-slate-300 font-medium">Ativo</span>
                                        </div>
                                    </td>
                                    <td className="px-8 py-6 text-right">
                                        <div className="flex items-center justify-end gap-1">
                                            <CredentialsTrigger client={client} />
                                            <EditClientTrigger client={client} />
                                        </div>
                                    </td>
                                </tr>
                            ))}
                            {clients.length === 0 && (
                                <tr>
                                    <td colSpan={5} className="px-8 py-20 text-center text-slate-500 italic">
                                        Nenhum cliente cadastrado ainda. Use o formulário acima para começar.
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    );
}
