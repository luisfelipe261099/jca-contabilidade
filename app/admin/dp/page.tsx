export const dynamic = 'force-dynamic';

import prisma from '@/lib/prisma';
import { Users, UserCheck, CalendarDays, Wallet, User } from 'lucide-react';

export default async function DPPage() {
    const employees = await prisma.employee.findMany({
        include: { client: true },
        orderBy: { name: 'asc' }
    });

    const totalEmployees = employees.length;
    const activeEmployees = employees.filter((e: any) => e.status === 'ACTIVE').length;
    const vacationEmployees = employees.filter((e: any) => e.status === 'VACATION').length;
    const totalPayroll = employees.reduce((acc: number, emp: any) => acc + emp.salary, 0);

    return (
        <div className="p-8">
            <div className="mb-8">
                <h1 className="text-3xl font-bold text-white mb-2">Folha & DP</h1>
                <p className="text-slate-400">Gestão de pessoal, folha de pagamento e benefícios.</p>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl">
                    <div className="flex items-center gap-3">
                        <Users className="w-5 h-5 text-blue-500" />
                        <span className="text-slate-400 text-xs font-bold uppercase">Total Funcionários</span>
                    </div>
                    <div className="text-3xl font-bold text-white mt-2">{totalEmployees}</div>
                </div>

                <div className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl">
                    <div className="flex items-center gap-3">
                        <UserCheck className="w-5 h-5 text-emerald-500" />
                        <span className="text-slate-400 text-xs font-bold uppercase">Ativos</span>
                    </div>
                    <div className="text-3xl font-bold text-white mt-2">{activeEmployees}</div>
                </div>

                <div className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl">
                    <div className="flex items-center gap-3">
                        <CalendarDays className="w-5 h-5 text-purple-500" />
                        <span className="text-slate-400 text-xs font-bold uppercase">Em Férias</span>
                    </div>
                    <div className="text-3xl font-bold text-white mt-2">{vacationEmployees}</div>
                </div>

                <div className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl">
                    <div className="flex items-center gap-3">
                        <Wallet className="w-5 h-5 text-yellow-500" />
                        <span className="text-slate-400 text-xs font-bold uppercase">Folha Bruta</span>
                    </div>
                    <div className="text-2xl font-bold text-white mt-2">
                        {new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL', maximumFractionDigits: 0 }).format(totalPayroll)}
                    </div>
                </div>
            </div>

            <div className="bg-slate-900/40 border border-slate-800 rounded-3xl p-8">
                <h2 className="text-xl font-bold text-white mb-6 flex items-center gap-2">
                    <User className="w-5 h-5 text-blue-500" />
                    Quadro de Funcionários
                </h2>
                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {employees.map((emp: any) => (
                        <div key={emp.id} className="flex items-center justify-between p-4 bg-slate-950 rounded-xl border border-slate-900">
                            <div>
                                <div className="font-bold text-white">{emp.name}</div>
                                <div className="text-xs text-slate-500">{emp.role} • {emp.client.name}</div>
                            </div>
                            <div className="text-right">
                                <div className="font-bold text-white">
                                    {new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(emp.salary)}
                                </div>
                                <div className={`text-[10px] font-bold uppercase px-2 py-0.5 rounded-full inline-block mt-1 ${emp.status === 'ACTIVE' ? 'bg-emerald-500/20 text-emerald-500' : 'bg-purple-500/20 text-purple-500'
                                    }`}>
                                    {emp.status === 'ACTIVE' ? 'ATIVO' : 'FÉRIAS'}
                                </div>
                            </div>
                        </div>
                    ))}
                    {employees.length === 0 && (
                        <div className="text-center py-10 text-slate-500 italic col-span-2">Nenhum funcionário cadastrado.</div>
                    )}
                </div>
            </div>
        </div>
    );
}
