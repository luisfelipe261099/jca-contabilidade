'use client';

import React, { useEffect, useState } from 'react';
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
    ShieldCheck,
    BookOpen,
    MessageSquare
} from 'lucide-react';
import { signOut, getSession } from 'next-auth/react';
import { cn } from '@/lib/utils';

export default function Sidebar() {
    const pathname = usePathname();
    const [session, setSession] = useState<any>(null);

    useEffect(() => {
        getSession().then((sess) => {
            setSession(sess);
        });
    }, []);

    const role = session?.user?.role || 'EMPLOYEE';
    const department = session?.user?.department || 'GERAL';

    const allMenuItems = [
        { name: 'Dashboard', href: '/admin/dashboard', icon: LayoutDashboard, depts: ['ALL'] },
        { name: 'Manual do Sistema', href: '/admin/manual', icon: BookOpen, depts: ['ALL'] },
        { name: 'Clientes', href: '/admin/clients', icon: Users, depts: ['ALL'] },
        { name: 'Documentos', href: '/admin/documents', icon: FileText, depts: ['ALL'] },
        { name: 'Folha / DP', href: '/admin/dp', icon: Users, depts: ['DP'] },
        { name: 'Fiscal', href: '/admin/fiscal', icon: Briefcase, depts: ['FISCAL'] },
        { name: 'Contábil', href: '/admin/contabil', icon: FileText, depts: ['CONTABIL'] },
        { name: 'Societário', href: '/admin/societario', icon: Landmark, depts: ['SOCIETARIO'] },
        { name: 'Financeiro', href: '/admin/financeiro', icon: DollarSign, depts: ['FINANCEIRO'] },
        { name: 'Atendimento (Chamados)', href: '/admin/tickets', icon: MessageSquare, depts: ['ALL'] },
        { name: 'Protocolos', href: '/admin/protocolos', icon: ShieldCheck, depts: ['ALL'] },
        { name: 'Estratégico (BI)', href: '/admin/bi', icon: TrendingUp, adminOnly: true },
        { name: 'Usuários', href: '/admin/users', icon: UserPlus, adminOnly: true },
        { name: 'Configurações', href: '/admin/settings', icon: Settings, adminOnly: true },
    ];

    const menuItems = allMenuItems.filter(item => {
        // ADMIN vê tudo
        if (role === 'ADMIN') return true;
        // Itens restritos a Admin não aparecem para Employee
        if (item.adminOnly) return false;
        // ALL aparece para todos os departments
        if (item.depts?.includes('ALL')) return true;
        // GERAL vê todas as áreas de serviço
        if (department === 'GERAL') return true;
        // Outros departamentos veem apenas as suas áreas
        if (item.depts?.includes(department)) return true;
        return false;
    });

    return (
        <aside className="fixed left-0 top-0 h-full w-64 bg-slate-950 border-r border-slate-900 flex flex-col z-50">
            <div className="p-8">
                <div className="flex items-center gap-3">
                    <div className="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center font-bold text-white text-xl">
                        J
                    </div>
                    <div>
                        <h1 className="text-white font-bold leading-none">JCA ERP</h1>
                        <span className="text-[10px] text-slate-500 font-bold tracking-widest uppercase">
                            {role === 'ADMIN' ? 'Admin Panel' : `Panel - ${department}`}
                        </span>
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
