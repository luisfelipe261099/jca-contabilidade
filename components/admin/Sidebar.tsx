'use client';

import React from 'react';
import Link from 'next/link';
import { usePathname } from 'next/navigation';
import {
    LayoutDashboard,
    Users,
    UserPlus,
    FileText,
    Settings,
    LogOut,
    TrendingUp,
    Briefcase,
    DollarSign,
    Landmark,
    ShieldCheck
} from 'lucide-react';
import { signOut } from 'next-auth/react';
import { cn } from '@/lib/utils';

export default function Sidebar() {
    const pathname = usePathname();

    const menuItems = [
        { name: 'Dashboard', href: '/admin/dashboard', icon: LayoutDashboard },
        { name: 'Clientes', href: '/admin/clients', icon: Users },
        { name: 'Usuários', href: '/admin/users', icon: UserPlus },
        { name: 'Documentos', href: '/admin/documents', icon: FileText },
        { name: 'Fiscal', href: '/admin/fiscal', icon: Briefcase },
        { name: 'Folha / DP', href: '/admin/dp', icon: Users },
        { name: 'Contábil', href: '/admin/contabil', icon: FileText },
        { name: 'Societário', href: '/admin/societario', icon: Landmark },
        { name: 'Financeiro', href: '/admin/financeiro', icon: DollarSign },
        { name: 'Protocolos', href: '/admin/protocolos', icon: ShieldCheck },
        { name: 'Estratégico (BI)', href: '/admin/bi', icon: TrendingUp },
        { name: 'Configurações', href: '/admin/settings', icon: Settings },
    ];

    return (
        <aside className="fixed left-0 top-0 h-full w-64 bg-slate-950 border-r border-slate-900 flex flex-col z-50">
            <div className="p-8">
                <div className="flex items-center gap-3">
                    <div className="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center font-bold text-white text-xl">
                        J
                    </div>
                    <div>
                        <h1 className="text-white font-bold leading-none">JCA ERP</h1>
                        <span className="text-[10px] text-slate-500 font-bold tracking-widest uppercase">Admin Panel</span>
                    </div>
                </div>
            </div>

            <nav className="flex-1 px-4 space-y-1 overflow-y-auto custom-scrollbar">
                {menuItems.map((item) => {
                    const isActive = pathname === item.href;
                    return (
                        <Link
                            key={item.name}
                            href={item.href}
                            className={cn(
                                "flex items-center gap-3 px-4 py-3 rounded-xl transition-all group",
                                isActive
                                    ? "bg-blue-600/10 text-blue-500 border border-blue-600/20"
                                    : "text-slate-400 hover:text-white hover:bg-slate-900"
                            )}
                        >
                            <item.icon className={cn("w-5 h-5", isActive ? "text-blue-500" : "text-slate-500 group-hover:text-slate-300")} />
                            <span className="font-medium text-sm">{item.name}</span>
                        </Link>
                    );
                })}
            </nav>

            <div className="p-4 border-t border-slate-900">
                <button
                    onClick={() => signOut({ callbackUrl: '/admin/login' })}
                    className="flex items-center gap-3 w-full px-4 py-3 text-slate-400 hover:text-red-400 hover:bg-red-500/5 rounded-xl transition-all"
                >
                    <LogOut className="w-5 h-5" />
                    <span className="font-medium">Sair do Sistema</span>
                </button>
            </div>
        </aside>
    );
}
