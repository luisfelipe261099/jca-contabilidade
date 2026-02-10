import React from 'react';

export default function Footer() {
    return (
        <footer className="py-12 px-6 bg-[#020617] border-t border-slate-900">
            <div className="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-6">
                <div className="flex flex-col gap-2">
                    <div className="flex items-center gap-3">
                        <div className="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center shadow-lg shadow-blue-500/20">
                            <span className="font-bold text-white">J</span>
                        </div>
                        <span className="text-lg font-bold text-slate-200">
                            JCA Soluções Contábeis
                        </span>
                    </div>
                    <p className="text-xs text-slate-500 max-w-xs leading-relaxed pl-11">
                        CRC-SP 12345/O-0 • CNPJ 00.000.000/0001-00<br />
                        Av. Paulista, 1000 - São Paulo, SP
                    </p>
                </div>

                <div className="flex flex-col items-center md:items-end gap-1">
                    <div className="text-slate-500 text-sm">
                        © {new Date().getFullYear()} JCA Soluções Contábeis.
                    </div>
                    <div className="flex gap-6 text-xs font-medium text-slate-400">
                        <a href="#" className="hover:text-white transition-colors">Privacidade</a>
                        <a href="#" className="hover:text-white transition-colors">Termos</a>
                    </div>
                </div>
            </div>
        </footer>
    );
}
