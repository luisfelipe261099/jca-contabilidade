import React from 'react';
import Logo from './Logo';

export default function Footer() {
    return (
        <footer className="py-12 px-6 mt-8 border-t border-sky-100 bg-white/80 backdrop-blur-sm">
            <div className="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-6">
                <div className="flex flex-col gap-2">
                    <Logo size="sm" />
                    <p className="text-xs text-[#5A7385] font-semibold max-w-xs leading-relaxed pl-11">
                        CRC-SP 12345/O-0 • CNPJ 59.224.736/0001-96<br />
                        Curitiba - PR
                    </p>
                </div>

                <div className="flex flex-col items-center md:items-end gap-1">
                    <div className="text-[#587184] text-sm font-semibold">
                        © {new Date().getFullYear()} JCC Jade Cristina Contabilidade.
                    </div>
                    <div className="flex gap-6 text-xs font-extrabold text-[#406074] uppercase tracking-[0.12em]">
                        <a href="https://www.instagram.com/jc_contabilidade_cwb/" target="_blank" rel="noreferrer" className="hover:text-[#007CC3] transition-colors">Instagram</a>
                        <a href="#contato" className="hover:text-[#007CC3] transition-colors">Contato</a>
                    </div>
                </div>
            </div>
        </footer>
    );
}
