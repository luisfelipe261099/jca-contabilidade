"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { signOut } from "next-auth/react";
import {
    LayoutDashboard,
    Users,
    FileText,
    Settings,
    LogOut,
    ChevronRight,
    ClipboardList
} from "lucide-react";

export default function Sidebar({ user }: { user: any }) {
    const pathname = usePathname();

    const menuItems = [
        { name: "Dashboard", href: "/dashboard", icon: LayoutDashboard },
        { name: "Clientes", href: "/dashboard/clientes", icon: Users },
        { name: "Tarefas", href: "/dashboard/tarefas", icon: ClipboardList },
        { name: "Relatórios", href: "/dashboard/relatorios", icon: FileText },
        { name: "Configurações", href: "/dashboard/configuracoes", icon: Settings },
    ];

    return (
        <aside className="w-72 border-r border-slate-800 bg-[#020617] h-screen sticky top-0 flex flex-col p-6 overflow-hidden">
            <div className="flex items-center gap-3 mb-12 px-2">
                <div className="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                    <span className="text-white font-bold">J</span>
                </div>
                <span className="text-xl font-bold text-white tracking-tight">JCA ERP</span>
            </div>

            <nav className="flex-1 space-y-2">
                {menuItems.map((item) => {
                    const isActive = pathname === item.href;
                    return (
                        <Link
                            key={item.href}
                            href={item.href}
                            className={`flex items-center justify-between group px-4 py-3 rounded-xl transition-all ${isActive
                                    ? "bg-blue-600/10 text-blue-400 border border-blue-500/20"
                                    : "text-slate-400 hover:text-slate-200 hover:bg-slate-900/50"
                                }`}
                        >
                            <div className="flex items-center gap-3">
                                <item.icon size={20} className={isActive ? "text-blue-400" : "text-slate-500 group-hover:text-slate-300"} />
                                <span className="font-medium">{item.name}</span>
                            </div>
                            <ChevronRight size={14} className={`opacity-0 group-hover:opacity-100 transition-opacity ${isActive ? 'text-blue-400 opacity-100' : ''}`} />
                        </Link>
                    );
                })}
            </nav>

            <div className="mt-auto space-y-6 pt-6">
                <div className="bg-slate-900/40 rounded-2xl p-4 border border-slate-800/50">
                    <div className="flex items-center gap-3 mb-1">
                        <div className="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-sm font-bold text-white shadow-lg">
                            {user.name?.charAt(0) || 'U'}
                        </div>
                        <div className="flex-1 overflow-hidden">
                            <p className="text-sm font-semibold text-white truncate">{user.name}</p>
                            <p className="text-[10px] uppercase tracking-wider text-slate-500 font-bold">{user.role}</p>
                        </div>
                    </div>
                </div>

                <button
                    onClick={() => signOut()}
                    className="w-full flex items-center gap-3 px-4 py-3 text-slate-400 hover:text-red-400 hover:bg-red-400/5 rounded-xl transition-all"
                >
                    <LogOut size={20} />
                    <span className="font-medium">Sair do Sistema</span>
                </button>
            </div>
        </aside>
    );
}
