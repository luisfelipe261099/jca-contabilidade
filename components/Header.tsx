'use client';

import React, { useState } from 'react';
import { Menu, X } from 'lucide-react';

export default function Header() {
    const [isOpen, setIsOpen] = useState(false);

    return (
        <nav className="fixed w-full top-0 z-50 backdrop-blur-md border-b border-slate-800/50 bg-[#020617]/80 supports-[backdrop-filter]:bg-[#020617]/50">
            <div className="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
                <div className="flex items-center gap-3">
                    <div className="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/20">
                        <span className="font-bold text-white text-xl">J</span>
                    </div>
                    <span className="text-xl font-bold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-white to-slate-400">
                        JCA Contabilidade
                    </span>
                </div>

                {/* Desktop Nav */}
                <div className="hidden md:flex items-center gap-8 text-sm font-medium text-slate-400">
                    <a href="/#servicos" className="hover:text-white transition-colors">Serviços</a>
                    <a href="/apoio" className="hover:text-white transition-colors">Apoio</a>
                    <a href="/#contato" className="hover:text-white transition-colors">Contato</a>
                    <a
                        href="#"
                        className="px-5 py-2.5 bg-slate-800 text-slate-500 cursor-not-allowed rounded-full transition-all flex items-center gap-2 border border-slate-700/50"
                    >
                        ERP em Breve
                    </a>
                    <a
                        href="#contato"
                        className="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-full transition-all shadow-lg shadow-emerald-500/25 flex items-center gap-2"
                    >
                        WhatsApp
                    </a>
                </div>

                {/* Mobile Toggle */}
                <button
                    className="md:hidden text-white p-2"
                    onClick={() => setIsOpen(!isOpen)}
                >
                    {isOpen ? <X className="w-6 h-6" /> : <Menu className="w-6 h-6" />}
                </button>
            </div>

            {/* Mobile Menu */}
            {isOpen && (
                <div className="md:hidden bg-[#020617] border-b border-slate-800 px-6 py-8 flex flex-col gap-6 animate-in slide-in-from-top duration-300">
                    <a href="/#servicos" onClick={() => setIsOpen(false)} className="text-lg font-medium text-slate-300">Serviços</a>
                    <a href="/apoio" onClick={() => setIsOpen(false)} className="text-lg font-medium text-slate-300">Apoio</a>
                    <a href="/#contato" onClick={() => setIsOpen(false)} className="text-lg font-medium text-slate-300">Contato</a>
                    <hr className="border-slate-800" />
                    <a
                        href="#contato"
                        onClick={() => setIsOpen(false)}
                        className="w-full py-4 bg-emerald-600 text-white rounded-xl text-center font-bold"
                    >
                        WhatsApp
                    </a>
                </div>
            )}
        </nav>
    );
}
