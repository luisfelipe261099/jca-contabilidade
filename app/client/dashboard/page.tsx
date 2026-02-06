import prisma from '@/lib/prisma';
import { auth } from '@/auth';
import {
    Building2,
    FileText,
    Download,
    ShieldCheck,
    AlertCircle,
    CheckCircle2,
    Clock,
    User
} from 'lucide-react';
import { formatDistanceToNow } from 'date-fns';
import { ptBR } from 'date-fns/locale';
import Link from 'next/link';

export default async function ClientDashboard() {
    const session = await auth();
    const clientId = (session?.user as any)?.clientId;

    if (!clientId) {
        return (
            <div className="min-h-screen bg-[#020617] flex items-center justify-center p-6 text-center">
                <div className="max-w-md">
                    <AlertCircle className="w-16 h-16 text-rose-500 mx-auto mb-6" />
                    <h1 className="text-2xl font-bold text-white mb-2">Acesso Restrito</h1>
                    <p className="text-slate-400">Este usuário não está vinculado a nenhuma empresa. Por favor, entre em contato com a JCA Contabilidade.</p>
                </div>
            </div>
        );
    }

    const client = await prisma.client.findUnique({
        where: { id: clientId },
        include: {
            documents: {
                orderBy: { createdAt: 'desc' },
                take: 20
            },
            tasks: {
                orderBy: { dueDate: 'asc' }
            }
        }
    });

    if (!client) {
        return <div className="p-8 text-white">Empresa não encontrada.</div>;
    }

    const pendingTasks = client.tasks.filter((t: any) => !t.completed);
    const completedTasks = client.tasks.filter((t: any) => t.completed);

    return (
        <div className="min-h-screen bg-[#020617] text-slate-200">
            {/* Top Navigation / Header */}
            <header className="border-b border-slate-900 bg-slate-950/50 backdrop-blur-md sticky top-0 z-50">
                <div className="max-w-6xl mx-auto px-6 h-20 flex items-center justify-between">
                    <div className="flex items-center gap-3">
                        <div className="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center font-bold text-white">
                            J
                        </div>
                        <div>
                            <h1 className="text-white font-bold leading-none uppercase tracking-tighter">Portal JCA</h1>
                            <span className="text-[10px] text-slate-500 font-bold tracking-widest uppercase">Área do Cliente</span>
                        </div>
                    </div>

                    <div className="flex items-center gap-4">
                        <div className="hidden md:block text-right">
                            <div className="text-xs font-bold text-white uppercase">{client.name}</div>
                            <div className="text-[10px] text-slate-500 font-mono">{client.cnpj}</div>
                        </div>
                        <div className="w-10 h-10 bg-slate-900 rounded-full border border-slate-800 flex items-center justify-center">
                            <User className="w-5 h-5 text-slate-400" />
                        </div>
                    </div>
                </div>
            </header>

            <main className="max-w-6xl mx-auto px-6 py-10 space-y-10">
                {/* Welcome & Quick Stats */}
                <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div className="md:col-span-2 bg-gradient-to-br from-blue-600/20 to-indigo-900/20 border border-blue-500/20 rounded-3xl p-8 flex flex-col justify-between">
                        <div>
                            <h2 className="text-3xl font-bold text-white mb-2">Olá, {client.name.split(' ')[0]}</h2>
                            <p className="text-slate-400 text-sm max-w-md leading-relaxed">
                                Bem-vindo ao seu portal de autoatendimento. Aqui você encontra todas as suas certidões, guias e o status das suas obrigações em tempo real.
                            </p>
                        </div>
                        <div className="mt-8 flex gap-4">
                            <div className="bg-slate-950/50 border border-slate-800 px-4 py-3 rounded-2xl flex items-center gap-3">
                                <ShieldCheck className="w-5 h-5 text-emerald-500" />
                                <div>
                                    <div className="text-[10px] text-slate-500 uppercase font-bold tracking-widest">Status Geral</div>
                                    <div className="text-xs font-bold text-white">{client.status}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div className="bg-slate-900/40 border border-slate-800 rounded-3xl p-8 flex flex-col justify-center gap-6">
                        <div className="flex items-center justify-between">
                            <span className="text-slate-400 text-sm font-medium">Contas Pendentes</span>
                            <span className="px-2 py-1 bg-amber-500/10 text-amber-500 text-[10px] font-bold rounded-lg uppercase tracking-wider">{pendingTasks.length} Atrasadas</span>
                        </div>
                        <div className="flex items-center justify-between">
                            <span className="text-slate-400 text-sm font-medium">Documentos Novos</span>
                            <span className="px-2 py-1 bg-blue-500/10 text-blue-500 text-[10px] font-bold rounded-lg uppercase tracking-wider">04 Este Mês</span>
                        </div>
                        <div className="h-px bg-slate-800 w-full" />
                        <button className="w-full py-3 bg-slate-950 border border-slate-800 text-slate-300 rounded-xl text-xs font-bold hover:bg-slate-900 transition-colors">
                            Precisa de Suporte?
                        </button>
                    </div>
                </div>

                <div className="grid grid-cols-1 lg:grid-cols-3 gap-10">
                    {/* Document List */}
                    <div className="lg:col-span-2 space-y-6">
                        <div className="flex items-center justify-between">
                            <h3 className="text-xl font-bold text-white flex items-center gap-2">
                                <FileText className="w-6 h-6 text-blue-500" />
                                Meus Documentos
                            </h3>
                            <span className="text-slate-500 text-xs font-bold uppercase tracking-widest">Últimos 20</span>
                        </div>

                        <div className="grid grid-cols-1 gap-4">
                            {client.documents.map((doc: any) => (
                                <div key={doc.id} className="group bg-slate-900/20 border border-slate-800 rounded-2xl p-5 flex items-center justify-between hover:bg-slate-900/40 transition-all border-l-4 border-l-blue-500/50">
                                    <div className="flex items-center gap-4">
                                        <div className="w-12 h-12 bg-slate-950 rounded-xl flex items-center justify-center border border-slate-800 group-hover:bg-blue-600/10 group-hover:border-blue-500/30 transition-all">
                                            <FileText className="w-6 h-6 text-slate-500 group-hover:text-blue-500" />
                                        </div>
                                        <div>
                                            <div className="text-white font-bold text-sm tracking-tight">{doc.name}</div>
                                            <div className="flex items-center gap-3 text-[10px] text-slate-500">
                                                <span className="px-1.5 py-0.5 bg-slate-800 rounded uppercase font-bold text-slate-400">{doc.type}</span>
                                                <span className="flex items-center gap-1">
                                                    <Clock className="w-3 h-3" />
                                                    {formatDistanceToNow(new Date(doc.createdAt), { addSuffix: true, locale: ptBR })}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <a
                                        href={`/api/documents/${doc.id}`}
                                        target="_blank"
                                        className="p-3 bg-blue-600/10 text-blue-500 hover:bg-blue-600 hover:text-white rounded-xl transition-all shadow-sm"
                                    >
                                        <Download className="w-5 h-5" />
                                    </a>
                                </div>
                            ))}
                            {client.documents.length === 0 && (
                                <div className="py-20 text-center border-2 border-dashed border-slate-800 rounded-3xl">
                                    <div className="text-slate-500 italic text-sm">Nenhum documento disponível para download.</div>
                                </div>
                            )}
                        </div>
                    </div>

                    {/* Task Timeline / Status */}
                    <div className="space-y-6">
                        <h3 className="text-xl font-bold text-white flex items-center gap-2">
                            <Clock className="w-6 h-6 text-amber-500" />
                            Minhas Obrigações
                        </h3>

                        <div className="space-y-4">
                            {client.tasks.slice(0, 5).map((task: any) => (
                                <div key={task.id} className="relative pl-8 pb-6 border-l border-slate-800 last:pb-0">
                                    <div className={`absolute left-[-5px] top-1 w-[9px] h-[9px] rounded-full ${task.completed ? 'bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]' : 'bg-amber-500 shadow-[0_0_8px_rgba(245,158,11,0.5)]'}`} />
                                    <div>
                                        <div className={`text-xs font-bold uppercase tracking-wider ${task.completed ? 'text-emerald-500' : 'text-slate-400'}`}>
                                            {task.title}
                                        </div>
                                        <div className="text-slate-500 text-[10px] mb-1">
                                            Vencimento: {new Date(task.dueDate).toLocaleDateString('pt-BR')}
                                        </div>
                                        {task.completed ? (
                                            <div className="flex items-center gap-1 text-emerald-500/80 text-[10px] font-bold">
                                                <CheckCircle2 className="w-3 h-3" /> CONCLUÍDO
                                            </div>
                                        ) : (
                                            <div className="flex items-center gap-1 text-amber-500/80 text-[10px] font-bold uppercase">
                                                <Clock className="w-3 h-3" /> Em Processamento
                                            </div>
                                        )}
                                    </div>
                                </div>
                            ))}
                        </div>

                        <div className="bg-slate-900/40 border border-slate-800 rounded-2xl p-6 mt-8">
                            <div className="flex items-center gap-3 mb-4">
                                <div className="p-2 bg-blue-500/10 rounded-lg">
                                    <Building2 className="w-4 h-4 text-blue-500" />
                                </div>
                                <h4 className="text-xs font-bold text-white uppercase tracking-widest">Sua Assessoria</h4>
                            </div>
                            <div className="space-y-3">
                                <div className="text-[11px] text-slate-400">Entre em contato direto com seu contador via WhatsApp:</div>
                                <button className="w-full py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-bold transition-all">
                                    Iniciar Chat JCA
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    );
}
