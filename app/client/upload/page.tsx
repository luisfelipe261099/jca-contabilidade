'use client';

import React, { useState } from 'react';
import { Upload, FileUp, ShieldCheck, AlertCircle, ArrowLeft, Loader2, CheckCircle2 } from 'lucide-react';
import Link from 'next/link';
import { uploadClientDocument } from '@/app/actions/client-portal';
import { useRouter } from 'next/navigation';

export default function ClientUploadPage() {
    const [file, setFile] = useState<File | null>(null);
    const [loading, setLoading] = useState(false);
    const [message, setMessage] = useState<{ type: 'success' | 'error', text: string } | null>(null);
    const router = useRouter();

    const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        if (e.target.files && e.target.files[0]) {
            setFile(e.target.files[0]);
            setMessage(null);
        }
    };

    const handleUpload = async () => {
        if (!file) return;

        setLoading(true);
        const formData = new FormData();
        formData.append('file', file);

        const result = await uploadClientDocument(formData);

        if (result.error) {
            setMessage({ type: 'error', text: result.error });
        } else {
            setMessage({ type: 'success', text: `Arquivo enviado com sucesso! Protocolo: ${result.protocol}` });
            setFile(null);
            setTimeout(() => router.push('/client/dashboard'), 3000);
        }
        setLoading(false);
    };

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

                <div className="bg-slate-900/40 border-2 border-dashed border-slate-800 rounded-[40px] p-12 text-center hover:border-blue-600/50 transition-all group relative">
                    <div className="bg-blue-600/10 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-8 group-hover:scale-110 transition-transform">
                        <FileUp className="w-10 h-10 text-blue-500" />
                    </div>

                    {!file ? (
                        <>
                            <h3 className="text-2xl font-bold mb-4">Arraste ou clique para selecionar</h3>
                            <p className="text-slate-500 mb-8 max-w-sm mx-auto">Suporta PDF, XML, JPG e PNG. Tamanho máximo de 20MB por arquivo.</p>
                            <input
                                type="file"
                                className="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                id="file-upload"
                                onChange={handleFileChange}
                            />
                            <button className="bg-blue-600 hover:bg-blue-500 text-white px-10 py-4 rounded-2xl font-bold transition-all inline-block shadow-xl shadow-blue-900/20 pointer-events-none">
                                Selecionar Arquivos
                            </button>
                        </>
                    ) : (
                        <div className="animate-in fade-in zoom-in duration-300">
                            <h3 className="text-xl font-bold text-white mb-2">{file.name}</h3>
                            <p className="text-sm text-slate-500 mb-6">{(file.size / 1024 / 1024).toFixed(2)} MB</p>

                            <div className="flex justify-center gap-4">
                                <button
                                    onClick={() => setFile(null)}
                                    className="px-6 py-3 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl font-bold text-sm transition-all"
                                    disabled={loading}
                                >
                                    Cancelar
                                </button>
                                <button
                                    onClick={handleUpload}
                                    disabled={loading}
                                    className="px-8 py-3 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl font-bold text-sm transition-all shadow-lg shadow-emerald-900/20 flex items-center gap-2"
                                >
                                    {loading ? <Loader2 className="w-4 h-4 animate-spin" /> : <Upload className="w-4 h-4" />}
                                    Enviar Agora
                                </button>
                            </div>
                        </div>
                    )}
                </div>

                {message && (
                    <div className={`mt-6 p-4 rounded-xl text-center font-bold text-sm ${message.type === 'success' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20'}`}>
                        {message.text}
                    </div>
                )}

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
