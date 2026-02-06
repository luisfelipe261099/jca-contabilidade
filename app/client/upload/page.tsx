export const dynamic = 'force-dynamic';

import React from 'react';
import { Upload, FileUp, ShieldCheck, AlertCircle, ArrowLeft, Loader2, CheckCircle2 } from 'lucide-react';
import Link from 'next/link';

export default function ClientUploadPage() {
    return (
        <div className="min-h-screen bg-[#020617] text-white p-6 md:p-12">
            <div className="max-w-4xl mx-auto">
                <div className="mb-12 text-center md:text-left">
                    <Link href="/client/dashboard" className="text-blue-500 inline-flex items-center gap-2 text-sm font-bold mb-6 hover:underline">
                        <ArrowLeft className="w-4 h-4" />
                        Voltar ao Dashboard
                    </Link>
                    <h1 className="text-4xl font-bold tracking-tighter uppercase italic mb-2">Envio de Documentos</h1>
                    <p className="text-slate-500">Envie arquivos de forma segura diretamente para o seu contador.</p>
                </div>

                <div className="bg-slate-900/40 border-2 border-dashed border-slate-800 rounded-[40px] p-12 text-center hover:border-blue-600/50 transition-all group">
                    <div className="bg-blue-600/10 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-8 group-hover:scale-110 transition-transform">
                        <FileUp className="w-10 h-10 text-blue-500" />
                    </div>
                    <h3 className="text-2xl font-bold mb-4">Arraste ou clique para selecionar</h3>
                    <p className="text-slate-500 mb-8 max-w-sm mx-auto">Suporta PDF, XML, JPG e PNG. Tamanho máximo de 20MB por arquivo.</p>
                    <input type="file" className="hidden" id="file-upload" />
                    <label
                        htmlFor="file-upload"
                        className="bg-blue-600 hover:bg-blue-500 text-white px-10 py-4 rounded-2xl font-bold cursor-pointer transition-all inline-block shadow-xl shadow-blue-900/20"
                    >
                        Selecionar Arquivos
                    </label>
                </div>

                <div className="mt-12 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div className="bg-slate-900/20 border border-slate-800 p-8 rounded-3xl">
                        <div className="flex items-center gap-4 mb-4">
                            <div className="p-3 bg-emerald-500/10 rounded-2xl">
                                <ShieldCheck className="w-6 h-6 text-emerald-500" />
                            </div>
                            <h4 className="font-bold text-white uppercase italic">Protocolo Automático</h4>
                        </div>
                        <p className="text-sm text-slate-500 leading-relaxed">
                            Cada arquivo enviado gera um protocolo digital que serve como comprovante de entrega para fins fiscais.
                        </p>
                    </div>

                    <div className="bg-slate-900/20 border border-slate-800 p-8 rounded-3xl">
                        <div className="flex items-center gap-4 mb-4">
                            <div className="p-3 bg-amber-500/10 rounded-2xl">
                                <AlertCircle className="w-6 h-6 text-amber-500" />
                            </div>
                            <h4 className="font-bold text-white uppercase italic">O que enviar aqui?</h4>
                        </div>
                        <p className="text-sm text-slate-500 leading-relaxed">
                            Notas de compra, extratos bancários, certidões, avisos de férias e outros documentos contábeis mensais.
                        </p>
                    </div>
                </div>

                <div className="mt-12 text-center text-[10px] font-bold text-slate-600 uppercase tracking-[0.2em]">
                    Criptografia de Ponta-a-Ponta • JCA Soluções Contábeis
                </div>
            </div>
        </div>
    );
}
