import React from 'react';

export default function Footer() {
    return (
        <footer className="py-12 px-6 bg-[#020617] border-t border-slate-900">
            <div className="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-6">
                <div className="flex items-center gap-3">
                    <div className="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center shadow-lg shadow-blue-500/20">
                        <span className="font-bold text-white">J</span>
                    </div>
                    <span className="text-lg font-bold text-slate-200">
                        JCA Soluções Contábeis
                    </span>
                </div>

                <div className="text-slate-500 text-sm">
                    © {new Date().getFullYear()} JCA Soluções Contábeis. Todos os direitos reservados.
                </div>

                <div className="flex gap-6 text-sm font-medium text-slate-400">
                    <a href="#" className="hover:text-white transition-colors">Privacidade</a>
                    <a href="#" className="hover:text-white transition-colors">Termos</a>
                </div>
            </div>
        </footer>
    );
}
