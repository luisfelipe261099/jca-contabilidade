import { PrismaClient } from "@prisma/client";

const prisma = new PrismaClient();

export default async function DashboardPage() {
    // Fetch some stats for the modern dashboard
    const totalClientes = await prisma.cliente.count({ where: { ativo: true } });
    const totalUsuarios = await prisma.usuario.count({ where: { ativo: true } });
    const tarefasPendentes = await prisma.tarefa.count({ where: { status: "Pendente" } });

    return (
        <div className="space-y-8">
            <div className="flex items-center justify-between">
                <div>
                    <h1 className="text-4xl font-bold text-white tracking-tight">Dashboard</h1>
                    <p className="text-slate-400 mt-1">Bem-vindo ao centro de controle JCA</p>
                </div>
                <div className="flex gap-3">
                    <button className="px-4 py-2 bg-slate-900 border border-slate-800 rounded-xl hover:bg-slate-800 transition-all font-medium">
                        Gerar Relatório
                    </button>
                </div>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <StatCard title="Clientes Ativos" value={totalClientes} icon="users" color="blue" />
                <StatCard title="Usuários do Sistema" value={totalUsuarios} icon="user-check" color="purple" />
                <StatCard title="Tarefas Pendentes" value={tarefasPendentes} icon="clock" color="orange" />
            </div>

            <div className="bg-slate-900/50 backdrop-blur-xl border border-slate-800 rounded-3xl p-8">
                <h2 className="text-xl font-bold text-white mb-6">Próximos Passos na Migração</h2>
                <div className="space-y-4">
                    <TodoItem text="Migrar lista de clientes para Next.js" done={false} />
                    <TodoItem text="Configurar envio de e-mails via API" done={false} />
                    <TodoItem text="Ativar filtros avançados com Prisma" done={false} />
                    <TodoItem text="Sincronizar anexos com Vercel Blob" done={false} />
                </div>
            </div>
        </div>
    );
}

function StatCard({ title, value, icon, color }: any) {
    const colors: any = {
        blue: "text-blue-400 bg-blue-400/10",
        purple: "text-purple-400 bg-purple-400/10",
        orange: "text-orange-400 bg-orange-400/10",
    };

    return (
        <div className="bg-slate-900/50 backdrop-blur-xl border border-slate-800 p-6 rounded-3xl hover:border-slate-700 transition-all group">
            <div className="flex items-center justify-between mb-4">
                <div className={`w-12 h-12 rounded-2xl flex items-center justify-center transition-all ${colors[color]}`}>
                    {/* Icon will be Lucide, for now just text */}
                    <span className="text-xs uppercase font-bold tracking-widest">{icon}</span>
                </div>
            </div>
            <h3 className="text-slate-400 font-medium">{title}</h3>
            <p className="text-3xl font-bold text-white mt-1 group-hover:scale-105 transition-transform origin-left">{value}</p>
        </div>
    );
}

function TodoItem({ text, done }: { text: string; done: boolean }) {
    return (
        <div className="flex items-center gap-3 p-3 hover:bg-white/5 rounded-xl transition-colors cursor-default">
            <div className={`w-5 h-5 rounded-md border flex items-center justify-center ${done ? 'bg-blue-600 border-blue-600' : 'border-slate-700'}`}>
                {done && <span className="text-white text-xs">✓</span>}
            </div>
            <span className={done ? 'text-slate-500 line-through' : 'text-slate-300'}>{text}</span>
        </div>
    );
}
