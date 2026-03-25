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
    MessageSquare,
    Menu,
    X
} from 'lucide-react';
import { signOut, getSession } from 'next-auth/react';
import { cn } from '@/lib/utils';

export default function Sidebar() {
    const pathname = usePathname();
    const [session, setSession] = useState<any>(null);
    const [mobileOpen, setMobileOpen] = useState(false);

    useEffect(() => {
        getSession().then((sess) => {
            setSession(sess);
        });
    }, []);

    // Close mobile menu on route change
    useEffect(() => {
        setMobileOpen(false);
    }, [pathname]);

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
        { name: 'Atendimento', href: '/admin/tickets', icon: MessageSquare, depts: ['ALL'] },
        { name: 'Protocolos', href: '/admin/protocolos', icon: ShieldCheck, depts: ['ALL'] },
        { name: 'Estratégico (BI)', href: '/admin/bi', icon: TrendingUp, adminOnly: true },
        { name: 'Usuários', href: '/admin/users', icon: UserPlus, adminOnly: true },
        { name: 'Configurações', href: '/admin/settings', icon: Settings, adminOnly: true },
    ];

    const menuItems = allMenuItems.filter(item => {
        if (role === 'ADMIN') return true;
        if (item.adminOnly) return false;
        if (item.depts?.includes('ALL')) return true;
        if (department === 'GERAL') return true;
        if (item.depts?.includes(department)) return true;
        return false;
    });

    const sidebarContent = (
        <>
            <div className="p-6 lg:p-8">
                <div className="flex items-center gap-3">
                    <div className="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center font-bold text-white text-xl shrink-0">
                        J
                    </div>
                    <div className="min-w-0">
                        <h1 className="text-white font-bold leading-none">JCC ERP</h1>
                        <span className="text-[10px] text-slate-500 font-bold tracking-widest uppercase">
                            {role === 'ADMIN' ? 'Admin Panel' : `Panel - ${department}`}
                        </span>
                    </div>
                </div>
            </div>

            <nav className="flex-1 px-3 lg:px-4 space-y-1 overflow-y-auto custom-scrollbar">
                {menuItems.map((item) => {
                    const isActive = pathname === item.href;
                    return (
                        <Link
                            key={item.name}
                            href={item.href}
                            onClick={() => setMobileOpen(false)}
                            className={cn(
                                "flex items-center gap-3 px-4 py-3 rounded-xl transition-all group",
                                isActive
                                    ? "bg-blue-600/10 text-blue-500 border border-blue-600/20"
                                    : "text-slate-400 hover:text-white hover:bg-slate-900"
                            )}
                        >
                            <item.icon className={cn("w-5 h-5 shrink-0", isActive ? "text-blue-500" : "text-slate-500 group-hover:text-slate-300")} />
                            <span className="font-medium text-sm truncate">{item.name}</span>
                        </Link>
                    );
                })}
            </nav>

            <div className="p-4 border-t border-slate-900">
                <button
                    onClick={() => signOut({ callbackUrl: '/admin/login' })}
                    className="flex items-center gap-3 w-full px-4 py-3 text-slate-400 hover:text-red-400 hover:bg-red-500/5 rounded-xl transition-all"
                >
                    <LogOut className="w-5 h-5 shrink-0" />
                    <span className="font-medium">Sair do Sistema</span>
                </button>
            </div>
        </>
    );

    return (
        <>
            {/* Mobile Top Bar */}
            <div className="lg:hidden fixed top-0 left-0 right-0 z-50 bg-slate-950 border-b border-slate-900 h-16 flex items-center justify-between px-4">
                <div className="flex items-center gap-3">
                    <div className="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center font-bold text-white text-sm">
                        J
                    </div>
                    <span className="text-white font-bold text-sm">JCC ERP</span>
                </div>
                <button
                    onClick={() => setMobileOpen(!mobileOpen)}
                    className="p-2 text-slate-400 hover:text-white rounded-lg hover:bg-slate-800 transition-colors"
                    aria-label="Menu"
                >
                    {mobileOpen ? <X className="w-6 h-6" /> : <Menu className="w-6 h-6" />}
                </button>
            </div>

            {/* Mobile Overlay */}
            {mobileOpen && (
                <div
                    className="lg:hidden fixed inset-0 z-40 bg-black/60 backdrop-blur-sm"
                    onClick={() => setMobileOpen(false)}
                />
            )}

            {/* Mobile Drawer */}
            <aside className={cn(
                "lg:hidden fixed left-0 top-0 h-full w-72 bg-slate-950 border-r border-slate-900 flex flex-col z-50 transition-transform duration-300 ease-in-out pt-16",
                mobileOpen ? "translate-x-0" : "-translate-x-full"
            )}>
                {sidebarContent}
            </aside>

            {/* Desktop Sidebar */}
            <aside className="hidden lg:flex fixed left-0 top-0 h-full w-64 bg-slate-950 border-r border-slate-900 flex-col z-50">
                {sidebarContent}
            </aside>
        </>
    );
}
