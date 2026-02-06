import { getClienteDetalhes } from "../_actions/getClienteDetalhes";
import {
    Building2,
    Users,
    CheckSquare,
    ShieldCheck,
    Phone,
    Mail,
    MapPin,
    FileText,
    Zap,
    ArrowLeft,
    Edit,
    LayoutDashboard
} from "lucide-react";
import Link from "next/link";
import { notFound } from "next/navigation";

export default async function ClienteDetalhesPage({
    params,
}: {
    params: { id: string };
}) {
    const data = await getClienteDetalhes(parseInt(params.id));

    if (!data) {
        notFound();
    }

    const { cliente, stats } = data;

    return (
        <div className="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
            {/* Header Premium */}
            <div className="relative overflow-hidden bg-slate-900/50 backdrop-blur-2xl border border-slate-800 rounded-[2.5rem] p-8 shadow-2xl">
                <div className="absolute top-0 right-0 w-64 h-64 bg-blue-600/10 blur-[100px] -z-10 rounded-full" />

                <div className="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                    <div className="flex-1">
                        <div className="flex items-center gap-4 mb-4">
                            <Link href="/dashboard/clientes" className="p-2 bg-slate-800/50 hover:bg-slate-800 rounded-xl text-slate-400 hover:text-white transition-all">
                                <ArrowLeft size={20} />
                            </Link>
                            <h1 className="text-4xl font-bold text-white tracking-tight">{cliente.razao_social}</h1>
                        </div>

                        <div className="flex flex-wrap gap-3">
                            <Badge text={cliente.cnpj} icon={FileText} />
                            <Badge text={cliente.regime_tributario} color="blue" />
                            <StatusBadge status={cliente.status_contrato || "Ativo"} />
                        </div>
                    </div>

                    <div className="flex flex-wrap gap-3">
                        <Link
                            href={`/dashboard/clientes/${cliente.id}/controle`}
                            className="px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white rounded-2xl transition-all shadow-lg shadow-blue-500/25 flex items-center gap-2 font-bold"
                        >
                            <LayoutDashboard size={20} />
                            Centro de Controle
                        </Link>
                        <Link
                            href={`/dashboard/clientes/${cliente.id}/editar`}
                            className="px-6 py-3 bg-slate-800 hover:bg-slate-700 text-white rounded-2xl transition-all border border-slate-700 flex items-center gap-2 font-bold"
                        >
                            <Edit size={20} />
                            Editar
                        </Link>
                    </div>
                </div>
            </div>

            {/* Stats Cards Modern */}
            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                <DetailStatCard title="Funcionários" value={stats.totalFuncionarios} icon={Users} color="blue" href={`/dashboard/clientes/${cliente.id}/funcionarios`} />
                <DetailStatCard title="Tarefas Pendentes" value={stats.tarefasPendentes} icon={CheckSquare} color="amber" href={`/dashboard/tarefas?cliente_id=${cliente.id}`} />
                <DetailStatCard title="Certificados Ativos" value={stats.totalCertificados} icon={ShieldCheck} color="emerald" href={`/dashboard/clientes/${cliente.id}/certificados`} />
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {/* Dados Cadastrais */}
                <Section title="Dados Cadastrais" icon={Building2}>
                    <div className="grid grid-cols-2 gap-x-8 gap-y-6">
                        <InfoItem label="CNPJ" value={cliente.cnpj} />
                        <InfoItem label="Inscrição Estadual" value={cliente.inscricao_est} />
                        <InfoItem label="Inscrição Municipal" value={cliente.inscricao_mun} />
                        <InfoItem label="Cliente desde" value={cliente.data_criacao?.toLocaleDateString('pt-BR')} />
                        <div className="col-span-2 pt-4 border-t border-slate-800/50">
                            <InfoItem
                                label="Endereço Completo"
                                value={cliente.logradouro ? `${cliente.logradouro}, ${cliente.numero || 'S/N'} ${cliente.complemento ? `- ${cliente.complemento}` : ''}` : null}
                            />
                            <p className="text-slate-400 mt-1">
                                {cliente.bairro} - {cliente.cidade}/{cliente.estado}
                            </p>
                            <p className="text-slate-500 text-sm">{cliente.cep}</p>
                        </div>
                    </div>
                </Section>

                {/* Contato & Responsável */}
                <Section title="Contato & Responsável" icon={Phone}>
                    <div className="space-y-6">
                        <div className="grid grid-cols-2 gap-6">
                            <ContactItem icon={Phone} label="Telefone" value={cliente.telefone} />
                            <ContactItem icon={Phone} label="Celular" value={cliente.celular} />
                            <div className="col-span-2">
                                <ContactItem icon={Mail} label="E-mail Principal" value={cliente.email} />
                            </div>
                        </div>

                        <div className="pt-6 border-t border-slate-800/50">
                            <p className="text-xs font-bold uppercase tracking-widest text-slate-500 mb-4">Responsável Legal</p>
                            <div className="bg-slate-950/30 rounded-2xl p-4 border border-slate-800/50">
                                <p className="font-bold text-white text-lg">{cliente.responsavel_nome || "-"}</p>
                                <div className="mt-2 space-y-1">
                                    {cliente.responsavel_email && <p className="text-slate-400 flex items-center gap-2 text-sm"><Mail size={14} /> {cliente.responsavel_email}</p>}
                                    {cliente.responsavel_tel && <p className="text-slate-400 flex items-center gap-2 text-sm"><Phone size={14} /> {cliente.responsavel_tel}</p>}
                                </div>
                            </div>
                        </div>
                    </div>
                </Section>

                {/* Informações Fiscais */}
                <Section title="Estratégia Fiscal" icon={FileText}>
                    <div className="grid grid-cols-2 gap-8">
                        <InfoItem label="Regime Tributário" value={cliente.regime_tributario} />
                        <InfoItem label="Porte da Empresa" value={cliente.porte_empresa} />
                        <InfoItem label="CNAE Principal" value={cliente.cnae_principal} />
                        <InfoItem label="Contador Responsável" value={cliente.contador?.nome} />
                    </div>
                </Section>

                {/* Ações Rápidas Custom */}
                <Section title="Ações Rápidas" icon={Zap}>
                    <div className="grid grid-cols-2 gap-4">
                        <QuickActionButton
                            title="Nova Tarefa"
                            desc="Abrir chamado"
                            icon={CheckSquare}
                            color="blue"
                            href={`/dashboard/tarefas/nova?cliente_id=${cliente.id}`}
                        />
                        <QuickActionButton
                            title="Funcionário"
                            desc="Admissão/Férias"
                            icon={Users}
                            color="emerald"
                            href={`/dashboard/clientes/${cliente.id}/funcionarios/novo`}
                        />
                        <QuickActionButton
                            title="Certificado"
                            desc="Renovar/Instalar"
                            icon={ShieldCheck}
                            color="amber"
                            href={`/dashboard/clientes/${cliente.id}/certificados/novo`}
                        />
                        <QuickActionButton
                            title="Relatórios"
                            desc="Extrair PDF/Excel"
                            icon={FileText}
                            color="slate"
                            href="#"
                        />
                    </div>
                </Section>
            </div>
        </div>
    );
}

function Section({ title, icon: Icon, children }: any) {
    return (
        <div className="bg-slate-900/50 backdrop-blur-xl border border-slate-800 rounded-[2rem] overflow-hidden group">
            <div className="px-8 py-6 border-b border-slate-800/50 bg-slate-900/20 flex items-center gap-3">
                <Icon size={20} className="text-blue-500" />
                <h2 className="text-xl font-bold text-white">{title}</h2>
            </div>
            <div className="p-8">
                {children}
            </div>
        </div>
    );
}

function InfoItem({ label, value }: { label: string; value: any }) {
    return (
        <div>
            <p className="text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">{label}</p>
            <p className="text-white font-medium">{value || "-"}</p>
        </div>
    );
}

function ContactItem({ icon: Icon, label, value }: any) {
    return (
        <div>
            <p className="text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">{label}</p>
            <div className="flex items-center gap-2 text-slate-200">
                <Icon size={16} className="text-slate-500" />
                <span className="font-medium">{value || "-"}</span>
            </div>
        </div>
    );
}

function DetailStatCard({ title, value, icon: Icon, color, href }: any) {
    const colors: any = {
        blue: "text-blue-400 border-blue-500/20 bg-blue-500/5 hover:bg-blue-500/10",
        amber: "text-amber-400 border-amber-500/20 bg-amber-500/5 hover:bg-amber-500/10",
        emerald: "text-emerald-400 border-emerald-500/20 bg-emerald-500/5 hover:bg-emerald-500/10",
    };

    return (
        <Link
            href={href}
            className={`p-6 rounded-3xl border transition-all group flex items-center justify-between ${colors[color]}`}
        >
            <div>
                <p className="text-sm font-medium uppercase tracking-wider opacity-60 mb-1">{title}</p>
                <p className="text-4xl font-bold group-hover:scale-105 transition-transform origin-left">{value}</p>
            </div>
            <div className="w-12 h-12 rounded-2xl flex items-center justify-center bg-white/5 border border-white/10">
                <Icon size={24} />
            </div>
        </Link>
    );
}

function QuickActionButton({ title, desc, icon: Icon, color, href }: any) {
    const colors: any = {
        blue: "hover:border-blue-500/50 hover:bg-blue-500/5 text-blue-400",
        emerald: "hover:border-emerald-500/50 hover:bg-emerald-500/5 text-emerald-400",
        amber: "hover:border-amber-500/50 hover:bg-amber-500/5 text-amber-400",
        slate: "hover:border-slate-500/50 hover:bg-slate-500/5 text-slate-400",
    };

    return (
        <Link
            href={href}
            className={`flex items-center gap-3 p-4 border border-slate-800 bg-slate-950/30 rounded-2xl transition-all ${colors[color]}`}
        >
            <div className="p-2 rounded-xl bg-white/5">
                <Icon size={20} />
            </div>
            <div>
                <p className="font-bold text-white text-sm">{title}</p>
                <p className="text-[10px] text-slate-500 uppercase font-bold tracking-tight">{desc}</p>
            </div>
        </Link>
    );
}

function Badge({ text, icon: Icon, color = "slate" }: any) {
    const colors: any = {
        slate: "bg-slate-800 text-slate-400 border-slate-700",
        blue: "bg-blue-500/10 text-blue-400 border-blue-500/20",
    };
    return (
        <div className={`px-3 py-1 rounded-full text-xs font-bold border flex items-center gap-1.5 ${colors[color]}`}>
            {Icon && <Icon size={12} />}
            {text}
        </div>
    );
}

function StatusBadge({ status }: { status: string }) {
    const styles: any = {
        Ativo: "bg-emerald-500 text-white shadow-lg shadow-emerald-500/25",
        Suspenso: "bg-amber-500 text-white shadow-lg shadow-amber-500/25",
        Inadimplente: "bg-rose-500 text-white shadow-lg shadow-rose-500/25",
        Cancelado: "bg-slate-600 text-white shadow-lg shadow-slate-600/25",
    };

    return (
        <span className={`px-3 py-1 rounded-full text-xs font-bold ${styles[status]}`}>
            {status}
        </span>
    );
}
