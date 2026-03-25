'use client';

import React, { useState } from 'react';
import { Menu, X } from 'lucide-react';
import Logo from './Logo';

export default function Header() {
    const [isOpen, setIsOpen] = useState(false);

    return (
        <nav className="fixed w-full top-0 z-50 border-b border-sky-100/80 bg-white/80 backdrop-blur-xl">
            <div className="max-w-7xl mx-auto px-6 h-24 flex items-center justify-between">
                <a href="/" className="hover:opacity-90 transition-opacity">
                    <Logo size="md" />
                </a>

                <div className="hidden md:flex items-center gap-8 text-sm font-bold text-[#255171]">
                    <a href="/#servicos" className="hover:text-[#007CC3] transition-colors">Servicos</a>
                    <a href="/apoio" className="hover:text-[#007CC3] transition-colors">Apoio</a>
                    <a href="/#contato" className="hover:text-[#007CC3] transition-colors">Contato</a>
                    <a
                        href="/admin/login"
                        className="px-5 py-2.5 rounded-full border border-[#007CC3]/25 text-[#007CC3] hover:bg-[#007CC3] hover:text-white transition-all"
                    >
                        Acessar ERP
                    </a>
                    <a
                        href="#contato"
                        className="px-5 py-2.5 rounded-full bg-gradient-to-r from-[#007CC3] to-[#33B8AE] text-white shadow-[0_12px_24px_rgba(0,124,195,0.32)] hover:opacity-90 transition-opacity"
                    >
                        WhatsApp
                    </a>
                </div>

                <button
                    className="md:hidden text-[#0C3D5E] p-2"
                    onClick={() => setIsOpen(!isOpen)}
                >
                    {isOpen ? <X className="w-6 h-6" /> : <Menu className="w-6 h-6" />}
                </button>
            </div>

            {isOpen && (
                <div className="md:hidden bg-white border-b border-sky-100 px-6 py-8 flex flex-col gap-6 reveal-up">
                    <a href="/#servicos" onClick={() => setIsOpen(false)} className="text-lg font-bold text-[#255171]">Servicos</a>
                    <a href="/apoio" onClick={() => setIsOpen(false)} className="text-lg font-bold text-[#255171]">Apoio</a>
                    <a href="/#contato" onClick={() => setIsOpen(false)} className="text-lg font-bold text-[#255171]">Contato</a>
                    <a
                        href="/admin/login"
                        onClick={() => setIsOpen(false)}
                        className="w-full py-4 rounded-xl border border-[#007CC3]/20 text-center text-[#007CC3] font-extrabold"
                    >
                        Acessar ERP
                    </a>
                    <a
                        href="#contato"
                        onClick={() => setIsOpen(false)}
                        className="w-full py-4 rounded-xl bg-gradient-to-r from-[#007CC3] to-[#33B8AE] text-white text-center font-extrabold"
                    >
                        WhatsApp
                    </a>
                </div>
            )}
        </nav>
    );
}
