export const dynamic = 'force-dynamic';
import prisma from '@/lib/prisma';
import TaskToggle from '@/components/admin/TaskToggle';
import EditClientTrigger from '@/components/admin/EditClientTrigger';
import {
    Users,
    FileText,
    CheckCircle,
    Clock,
    TrendingUp,
    ChevronRight,
    Search,
    Filter,
    Building2,
    AlertCircle,
    CheckCircle2,
    ArrowUpRight
} from 'lucide-react';

export default async function DashboardPage() {
    const clientCount = await prisma.client.count();
    const taskCount = await prisma.task.count({ where: { completed: false } });
    const completedTaskCount = await prisma.task.count({ where: { completed: true } });
    const userCount = await prisma.user.count();

    const clients = await prisma.client.findMany({
        take: 5,
        orderBy: { createdAt: 'desc' },
        include: { tasks: true }
    });

    const stats = [
        { label: 'Total Clientes', value: clientCount.toString(), icon: <Building2 className="w-5 h-5" />, color: 'bg-blue-500' },
        { label: 'Tarefas Pendentes', value: taskCount.toString(), icon: <AlertCircle className="w-5 h-5" />, color: 'bg-amber-500' },
        { label: 'Tarefas Concluídas', value: completedTaskCount.toString(), icon: <CheckCircle2 className="w-5 h-5" />, color: 'bg-emerald-500' },
        { label: 'Equipe JCA', value: userCount.toString(), icon: <Users className="w-5 h-5" />, color: 'bg-indigo-500' },
    ];

    const getStatusColor = (status: string) => {
        switch (status) {
            case 'CONCLUIDO': return 'bg-emerald-500';
            case 'PENDENTE': return 'bg-amber-500';
            case 'ATENDIMENTO': return 'bg-blue-500';
            default: return 'bg-slate-700';
        }
    };

    return (
        <div className="p-8">
            {/* Header */}
            <div className="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                <div>
                    <h1 className="text-3xl font-bold text-white mb-2">Torre de Controle</h1>
                    <p className="text-slate-400">Gerencie todos os clientes JCA em tempo real.</p>
                </div>

                <div className="relative w-full md:w-96">
                    <Search className="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-500" />
                    <input
                        type="text"
                        placeholder="Buscar empresa ou CNPJ..."
                        className="w-full bg-slate-900/50 border border-slate-800 rounded-xl py-3 pl-12 pr-4 text-white focus:outline-none focus:border-blue-500 transition-all font-medium"
                    />
                </div>
            </div>

            {/* Stats Grid */}
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                {stats.map((stat, i) => (
                    <div key={i} className="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl hover:bg-slate-900/60 transition-all group">
                        <div className="flex items-center justify-between mb-4">
                            <div className={`p-3 rounded-xl ${stat.color} bg-opacity-10 text-white`}>
                                {stat.icon}
                            </div>
                            <ArrowUpRight className="w-4 h-4 text-slate-600 transition-colors" />
                        </div>
                        <div className="text-2xl font-bold text-white mb-1">{stat.value}</div>
                        <div className="text-slate-500 text-sm font-medium">{stat.label}</div>
                    </div>
                ))}
            </div>

            {/* Client List (Torre de Controle) */}
            <div className="bg-slate-900/40 border border-slate-800 rounded-3xl overflow-hidden shadow-2xl">
                <div className="px-8 py-6 border-b border-slate-800 flex items-center justify-between bg-slate-900/20">
                    <h2 className="text-xl font-bold text-white">Últimas Atualizações</h2>
                    <div className="flex gap-4 text-xs font-bold uppercase tracking-widest">
                        <div className="flex items-center gap-2"><div className="w-2 h-2 rounded-full bg-emerald-500"></div> Em Dia</div>
                        <div className="flex items-center gap-2"><div className="w-2 h-2 rounded-full bg-amber-500"></div> Pendente</div>
                    </div>
                </div>

                <div className="overflow-x-auto">
                    <table className="w-full text-left border-collapse">
                        <thead>
                            <tr className="bg-slate-950/50">
                                <th className="px-8 py-5 text-slate-500 text-xs font-bold uppercase tracking-wider">Empresa</th>
                                <th className="px-8 py-5 text-slate-500 text-xs font-bold uppercase tracking-wider">Pendências</th>
                                <th className="px-8 py-5 text-center text-slate-500 text-xs font-bold uppercase tracking-wider">Status Geral</th>
                                <th className="px-8 py-5"></th>
                            </tr>
                        </thead>
                        <tbody>
                            {clients.map((client: any, idx: number) => (
                                <tr key={idx} className="hover:bg-slate-800/20 transition-colors">
                                    <td className="px-8 py-6">
                                        <div className="font-bold text-white mb-1 group-hover:text-blue-400 transition-colors uppercase tracking-tight">{client.name}</div>
                                        <div className="text-slate-500 text-[10px] font-mono">{client.cnpj}</div>
                                    </td>
                                    <td className="px-8 py-6">
                                        <div className="flex flex-col gap-1 max-h-32 overflow-y-auto pr-2 custom-scrollbar">
                                            {client.tasks.map((task: any) => (
                                                <TaskToggle
                                                    key={task.id}
                                                    taskId={task.id}
                                                    clientId={client.id}
                                                    initialCompleted={task.completed}
                                                    title={task.title}
                                                />
                                            ))}
                                            {client.tasks.length === 0 && (
                                                <div className="text-[10px] italic text-slate-600">Nenhuma tarefa.</div>
                                            )}
                                        </div>
                                    </td>
                                    <td className="px-8 py-6">
                                        <div className="flex justify-center items-center gap-2">
                                            <div className={`w-3 h-3 rounded-full ${getStatusColor(client.status)} shadow-lg shadow-blue-500/20 animate-pulse`}></div>
                                            <span className="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{client.status}</span>
                                        </div>
                                    </td>
                                    <td className="px-8 py-6 text-right">
                                        <EditClientTrigger client={client} />
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
