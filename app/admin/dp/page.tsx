export const dynamic = 'force-dynamic';

import prisma from '@/lib/prisma';
import DPActions from '@/components/admin/DPActions';

export default async function DPPage() {
    const [employees, clients] = await Promise.all([
        prisma.employee.findMany({
            where: { client: { services: { contains: 'DP' } } },
            include: { client: true },
            orderBy: { name: 'asc' }
        }),
        prisma.client.findMany({
            where: { services: { contains: 'DP' } },
            select: { id: true, name: true },
            orderBy: { name: 'asc' }
        })
    ]);

    const totalEmployees = employees.length;
    const activeEmployees = employees.filter((e: any) => e.status === 'ACTIVE').length;
    const vacationEmployees = employees.filter((e: any) => e.status === 'VACATION').length;
    const totalPayroll = employees.filter((e: any) => e.status === 'ACTIVE').reduce((acc: number, e: any) => acc + e.salary, 0);

    return (
        <div className="p-4 sm:p-6 lg:p-8">
            <div className="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                <div>
                    <h1 className="text-2xl sm:text-3xl font-bold text-white mb-2">Departamento Pessoal</h1>
                    <p className="text-slate-400">Gestão de funcionários, folha de pagamento e admissões.</p>
                </div>
            </div>

            {/* Stats */}
            <div className="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 mb-8 sm:mb-10">
                <div className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl">
                    <div className="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-1">Total Funcionários</div>
                    <div className="text-3xl font-bold text-white">{totalEmployees}</div>
                </div>
                <div className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl">
                    <div className="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-1">Ativos</div>
                    <div className="text-3xl font-bold text-emerald-500">{activeEmployees}</div>
                </div>
                <div className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl">
                    <div className="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-1">Férias</div>
                    <div className="text-3xl font-bold text-amber-500">{vacationEmployees}</div>
                </div>
                <div className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl">
                    <div className="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-1">Folha Bruta</div>
                    <div className="text-2xl font-bold text-white">{new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(totalPayroll)}</div>
                </div>
            </div>

            <DPActions employees={JSON.parse(JSON.stringify(employees))} clients={clients} />
        </div>
    );
}
