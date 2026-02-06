import { getClientes, getClientStats } from "./_actions/getClientes";
import {
    Building2,
    CheckCircle,
    AlertTriangle,
    Search,
    Filter,
    Plus,
    Eye,
    Edit,
    MoreHorizontal
} from "lucide-react";
import Link from "next/link";

export default async function ClientesPage({
    searchParams,
}: {
    searchParams: { [key: string]: string | string[] | undefined };
}) {
    const search = typeof searchParams.search === "string" ? searchParams.search : "";
    const regime = typeof searchParams.regime === "string" ? searchParams.regime : "";
    const status = typeof searchParams.status === "string" ? searchParams.status : "";
    const page = typeof searchParams.page === "string" ? parseInt(searchParams.page) : 1;

    const { clientes, total, totalPages } = await getClientes({
        search,
        regime,
        status,
        page,
    });

    const stats = await getClientStats();

    return (
        <div className="space-y-8 animate-in fade-in duration-700">
            <div className="flex items-center justify-between">
                <div>
                    <h1 className="text-4xl font-bold text-white tracking-tight">Gestão de Clientes</h1>
                    <p className="text-slate-400 mt-1">Visualize e gerencie todos os clientes JCA</p>
                </div>
                <Link
                    href="/dashboard/clientes/novo"
                    className="px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white rounded-xl transition-all shadow-lg shadow-blue-500/25 flex items-center gap-2 font-semibold"
                >
                    <Plus size={20} />
                    Novo Cliente
                </Link>
            </div>

            {/* Stats Cards */}
            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                <StatCard title="Total de Clientes" value={stats.total} icon={Building2} color="blue" />
                <StatCard title="Contratos Ativos" value={stats.ativos} icon={CheckCircle} color="green" />
                <StatCard title="Inadimplentes" value={stats.inadimplentes} icon={AlertTriangle} color="red" />
            </div>

            {/* Filters */}
            <div className="bg-slate-900/50 backdrop-blur-xl border border-slate-800 p-6 rounded-3xl">
                <form className="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div className="relative">
                        <Search className="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500" size={18} />
                        <input
                            name="search"
                            defaultValue={search}
                            placeholder="Razão social, CNPJ..."
                            className="w-full bg-slate-950/50 border border-slate-800 rounded-xl pl-10 pr-4 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500/40 transition-all"
                        />
                    </div>
                    <select
                        name="regime"
                        defaultValue={regime}
                        className="bg-slate-950/50 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500/40 transition-all"
                    >
                        <option value="">Todos os Regimes</option>
                        <option value="Simples Nacional">Simples Nacional</option>
                        <option value="Lucro Presumido">Lucro Presumido</option>
                        <option value="Lucro Real">Lucro Real</option>
                        <option value="MEI">MEI</option>
                    </select>
                    <select
                        name="status"
                        defaultValue={status}
                        className="bg-slate-950/50 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500/40 transition-all"
                    >
                        <option value="">Todos os Status</option>
                        <option value="Ativo">Ativo</option>
                        <option value="Suspenso">Suspenso</option>
                        <option value="Inadimplente">Inadimplente</option>
                        <option value="Cancelado">Cancelado</option>
                    </select>
                    <button
                        type="submit"
                        className="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-white rounded-xl transition-all flex items-center justify-center gap-2 font-medium"
                    >
                        <Filter size={18} />
                        Aplicar Filtros
                    </button>
                </form>
            </div>

            {/* Table */}
            <div className="bg-slate-900/50 backdrop-blur-xl border border-slate-800 rounded-3xl overflow-hidden">
                <div className="overflow-x-auto">
                    <table className="w-full text-left">
                        <thead>
                            <tr className="border-b border-slate-800 bg-slate-900/20">
                                <th className="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Razão Social</th>
                                <th className="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">CNPJ</th>
                                <th className="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Regime</th>
                                <th className="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Responsável</th>
                                <th className="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Status</th>
                                <th className="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Ações</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-800/50">
                            {clientes.length === 0 ? (
                                <tr>
                                    <td colSpan={6} className="px-6 py-12 text-center text-slate-500">
                                        Nenhum cliente encontrado com os filtros aplicados.
                                    </td>
                                </tr>
                            ) : (
                                clientes.map((cliente) => (
                                    <tr key={cliente.id} className="hover:bg-white/5 transition-colors group">
                                        <td className="px-6 py-4">
                                            <div className="font-semibold text-white group-hover:text-blue-400 transition-colors">
                                                {cliente.razao_social}
                                            </div>
                                            {cliente.nome_fantasia && (
                                                <div className="text-xs text-slate-500 mt-0.5">{cliente.nome_fantasia}</div>
                                            )}
                                        </td>
                                        <td className="px-6 py-4 text-sm text-slate-400 font-mono">{cliente.cnpj}</td>
                                        <td className="px-6 py-4">
                                            <span className="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider bg-blue-500/10 text-blue-400 border border-blue-500/20">
                                                {cliente.regime_tributario}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4 text-sm text-slate-400">
                                            {cliente.contador?.nome || "-"}
                                        </td>
                                        <td className="px-6 py-4">
                                            <StatusBadge status={cliente.status_contrato || "Ativo"} />
                                        </td>
                                        <td className="px-6 py-4">
                                            <div className="flex items-center gap-2">
                                                <Link
                                                    href={`/dashboard/clientes/${cliente.id}`}
                                                    className="p-2 bg-slate-800 hover:bg-slate-700 rounded-lg text-slate-300 hover:text-white transition-all border border-slate-700"
                                                >
                                                    <Eye size={16} />
                                                </Link>
                                                <Link
                                                    href={`/dashboard/clientes/${cliente.id}/editar`}
                                                    className="p-2 bg-slate-800 hover:bg-slate-700 rounded-lg text-slate-300 hover:text-white transition-all border border-slate-700"
                                                >
                                                    <Edit size={16} />
                                                </Link>
                                            </div>
                                        </td>
                                    </tr>
                                ))
                            )}
                        </tbody>
                    </table>
                </div>

                {totalPages > 1 && (
                    <div className="px-6 py-4 border-t border-slate-800 flex items-center justify-between bg-slate-900/20">
                        <span className="text-sm text-slate-500">
                            Página {page} de {totalPages}
                        </span>
                        <div className="flex gap-2">
                            {/* Simplified pagination for now */}
                            {Array.from({ length: totalPages }).map((_, i) => (
                                <Link
                                    key={i}
                                    href={`?page=${i + 1}&search=${search}&regime=${regime}&status=${status}`}
                                    className={`w-8 h-8 rounded-lg flex items-center justify-center text-sm font-medium transition-all ${page === i + 1 ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/25' : 'bg-slate-800 text-slate-400 hover:bg-slate-700'
                                        }`}
                                >
                                    {i + 1}
                                </Link>
                            ))}
                        </div>
                    </div>
                )}
            </div>
        </div>
    );
}

function StatCard({ title, value, icon: Icon, color }: any) {
    const colors: any = {
        blue: "text-blue-400 bg-blue-400/10 border-blue-400/20",
        green: "text-emerald-400 bg-emerald-400/10 border-emerald-400/20",
        red: "text-rose-400 bg-rose-400/10 border-rose-400/20",
    };

    return (
        <div className="bg-slate-900/50 backdrop-blur-xl border border-slate-800 p-6 rounded-3xl hover:border-slate-700 transition-all group">
            <div className="flex items-center justify-between mb-4">
                <div className={`w-12 h-12 rounded-2xl flex items-center justify-center border ${colors[color]}`}>
                    <Icon size={24} />
                </div>
            </div>
            <h3 className="text-slate-500 text-sm font-medium uppercase tracking-wider">{title}</h3>
            <p className="text-3xl font-bold text-white mt-1 group-hover:scale-105 transition-transform origin-left">{value}</p>
        </div>
    );
}

function StatusBadge({ status }: { status: string }) {
    const styles: any = {
        Ativo: "bg-emerald-500/10 text-emerald-400 border-emerald-500/20",
        Suspenso: "bg-amber-500/10 text-amber-400 border-amber-500/20",
        Inadimplente: "bg-rose-500/10 text-rose-400 border-rose-500/20",
        Cancelado: "bg-slate-500/10 text-slate-400 border-slate-500/20",
    };

    return (
        <span className={`px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider border ${styles[status]}`}>
            {status}
        </span>
    );
}
