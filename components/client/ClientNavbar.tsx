'use client';

import React from 'react';
import { Home, MessageSquare, FileUp, LogOut, User, Building2 } from 'lucide-react';
import Link from 'next/link';
import { usePathname } from 'next/navigation';
import Logo from '@/components/Logo';
import { signOut } from 'next-auth/react';

export default function ClientNavbar({ clientName, cnpj }: { clientName: string, cnpj: string }) {
    const pathname = usePathname();

    const navItems = [
        { href: '/client/dashboard', label: 'Início', icon: Home },
        { href: '/client/requests', label: 'Solicitações', icon: MessageSquare },
        { href: '/client/upload', label: 'Enviar Arquivos', icon: FileUp },
    ];

    return (
        <header className="border-b border-slate-900 bg-slate-950/50 backdrop-blur-md sticky top-0 z-50">
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
                <div className="flex items-center gap-8">
                    <Link href="/client/dashboard" className="flex items-center gap-3 group">
                        <Logo size="sm" />
                        <div>
                            <h1 className="text-white font-bold leading-none uppercase tracking-tighter group-hover:text-blue-400 transition-colors">Portal JCC</h1>
                            <span className="text-[10px] text-slate-500 font-bold tracking-widest uppercase">Área do Cliente</span>
                        </div>
                    </Link>

                    <nav className="hidden lg:flex items-center gap-1">
                        {navItems.map((item) => {
                            const Icon = item.icon;
                            const isActive = pathname === item.href;
                            return (
                                <Link
                                    key={item.href}
                                    href={item.href}
                                    className={`px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider flex items-center gap-2 transition-all ${isActive
                                            ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/20'
                                            : 'text-slate-400 hover:text-white hover:bg-slate-900'
                                        }`}
                                >
                                    <Icon className="w-4 h-4" />
                                    {item.label}
                                </Link>
                            );
                        })}
                    </nav>
                </div>

                <div className="flex items-center gap-4">
                    <div className="hidden md:block text-right">
                        <div className="text-xs font-bold text-white uppercase">{clientName}</div>
                        <div className="text-[10px] text-slate-500 font-mono tracking-tighter">{cnpj}</div>
                    </div>

                    <div className="h-8 w-px bg-slate-800 mx-2 hidden sm:block"></div>

                    <div className="flex items-center gap-2">
                        <div className="w-10 h-10 bg-slate-900 rounded-full border border-slate-800 flex items-center justify-center">
                            <User className="w-5 h-5 text-slate-400" />
                        </div>
                        <button
                            onClick={() => signOut({ callbackUrl: '/admin/login' })}
                            className="p-2.5 bg-slate-900 hover:bg-rose-500/10 text-slate-500 hover:text-rose-500 rounded-xl transition-all border border-slate-800"
                            title="Sair"
                        >
                            <LogOut className="w-5 h-5" />
                        </button>
                    </div>
                </div>
            </div>

            {/* Mobile Nav */}
            <nav className="lg:hidden h-14 border-t border-slate-900/50 flex items-center justify-around bg-slate-950/20 px-2 overflow-x-auto">
                {navItems.map((item) => {
                    const Icon = item.icon;
                    const isActive = pathname === item.href;
                    return (
                        <Link
                            key={item.href}
                            href={item.href}
                            className={`flex flex-col items-center gap-1 px-3 py-1 rounded-lg transition-all ${isActive ? 'text-blue-500' : 'text-slate-500'
                                }`}
                        >
                            <Icon className="w-4 h-4" />
                            <span className="text-[9px] font-bold uppercase tracking-tighter">{item.label}</span>
                        </Link>
                    );
                })}
            </nav>
        </header>
    );
}
