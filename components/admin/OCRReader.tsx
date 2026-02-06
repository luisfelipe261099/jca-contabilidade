'use client';

import React, { useState } from 'react';
import { createWorker } from 'tesseract.js';
import { FileSearch, Loader2, CheckCircle2, Send } from 'lucide-react';
import { launchDocument } from '@/app/actions/clients';

export default function OCRReader({ clients }: { clients: any[] }) {
    const [loading, setLoading] = useState(false);
    const [progress, setProgress] = useState(0);
    const [ocrText, setOcrText] = useState('');
    const [extractedData, setExtractedData] = useState<{ cnpj?: string, valor?: string }>({});
    const [selectedClientId, setSelectedClientId] = useState('');
    const [launching, setLaunching] = useState(false);
    const [fileName, setFileName] = useState('');

    async function handleOCR(e: React.ChangeEvent<HTMLInputElement>) {
        const file = e.target.files?.[0];
        if (!file) return;

        setFileName(file.name);
        setLoading(true);
        setOcrText('');
        setExtractedData({});

        try {
            const worker = await createWorker('por', 1, {
                logger: m => {
                    if (m.status === 'recognizing text') setProgress(Math.round(m.progress * 100));
                }
            });

            const { data: { text } } = await worker.recognize(file);
            await worker.terminate();

            setOcrText(text);

            // Simple regex pattern matching for "Revolutionary" automation
            const cnpjMatch = text.match(/\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2}/);
            const valorMatch = text.match(/R\$\s?(\d+\.?\d*,\d{2})/);

            setExtractedData({
                cnpj: cnpjMatch ? cnpjMatch[0] : undefined,
                valor: valorMatch ? valorMatch[1] : undefined
            });

        } catch (error) {
            console.error('OCR Error:', error);
        } finally {
            setLoading(false);
            setProgress(0);
        }
    }

    async function handleLaunch() {
        if (!selectedClientId) return alert('Selecione um cliente para lançar.');

        setLaunching(true);
        const formData = new FormData();
        formData.append('clientId', selectedClientId);
        formData.append('fileName', fileName);
        formData.append('cnpj', extractedData.cnpj || '');
        formData.append('value', extractedData.valor || '');

        const result = await launchDocument(formData);
        setLaunching(false);

        if (result.success) {
            alert('Documento lançado com sucesso!');
            setOcrText('');
            setExtractedData({});
            setFileName('');
            setSelectedClientId('');
        } else {
            alert(result.error);
        }
    }

    return (
        <div className="bg-slate-900/40 border border-slate-800 rounded-3xl p-8 shadow-2xl">
            <div className="flex items-center justify-between mb-8">
                <div>
                    <h2 className="text-xl font-bold text-white flex items-center gap-2">
                        <FileSearch className="w-5 h-5 text-blue-500" />
                        Scanner Inteligente (OCR)
                    </h2>
                    <p className="text-sm text-slate-400">Arraste a guia ou nota fiscal para leitura automática.</p>
                </div>
            </div>

            <div className="relative group mb-8">
                <input
                    type="file"
                    accept="image/*,application/pdf"
                    onChange={handleOCR}
                    className="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                    disabled={loading || launching}
                />
                <div className={`border-2 border-dashed rounded-2xl p-10 flex flex-col items-center justify-center transition-all ${loading ? 'border-blue-500/50 bg-blue-500/5' : 'border-slate-800 group-hover:border-blue-500/50 group-hover:bg-slate-800/50'
                    }`}>
                    {loading ? (
                        <div className="flex flex-col items-center text-center">
                            <Loader2 className="w-10 h-10 text-blue-500 animate-spin mb-4" />
                            <div className="text-white font-bold mb-1">Lendo Documento... {progress}%</div>
                            <div className="w-64 h-2 bg-slate-800 rounded-full mt-4 overflow-hidden">
                                <div
                                    className="h-full bg-blue-500 transition-all duration-300"
                                    style={{ width: `${progress}%` }}
                                ></div>
                            </div>
                        </div>
                    ) : (
                        <div className="text-center">
                            <div className="w-16 h-16 bg-slate-800 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                                <FileSearch className="w-8 h-8 text-slate-400 group-hover:text-blue-500" />
                            </div>
                            <div className="text-white font-bold mb-1">{fileName || 'Clique ou Arraste o Arquivo'}</div>
                            <div className="text-slate-500 text-xs text-balance px-10">Processamento local, 100% privado e gratuito.</div>
                        </div>
                    )}
                </div>
            </div>

            {(extractedData.cnpj || extractedData.valor) && (
                <div className="space-y-6 animate-in fade-in slide-in-from-bottom-4 duration-500">
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div className="bg-slate-950/50 border border-slate-800 p-4 rounded-xl">
                            <div className="text-slate-500 text-xs font-bold uppercase mb-1">CNPJ Identificado</div>
                            <div className="text-white font-mono flex items-center gap-2">
                                {extractedData.cnpj || 'Não encontrado'}
                                {extractedData.cnpj && <CheckCircle2 className="w-4 h-4 text-emerald-500" />}
                            </div>
                        </div>
                        <div className="bg-slate-950/50 border border-slate-800 p-4 rounded-xl">
                            <div className="text-slate-500 text-xs font-bold uppercase mb-1">Valor da Guia</div>
                            <div className="text-white font-bold flex items-center gap-2">
                                R$ {extractedData.valor || '---'}
                                {extractedData.valor && <CheckCircle2 className="w-4 h-4 text-emerald-500" />}
                            </div>
                        </div>
                    </div>

                    <div className="bg-slate-800/30 border border-slate-700 p-6 rounded-2xl flex flex-col md:flex-row items-end gap-4">
                        <div className="flex-1 w-full">
                            <label className="block text-xs font-bold text-slate-400 uppercase mb-2">Vincular ao Cliente</label>
                            <select
                                value={selectedClientId}
                                onChange={(e) => setSelectedClientId(e.target.value)}
                                className="w-full bg-slate-900 border border-slate-700 rounded-lg py-2 px-3 text-white text-sm focus:outline-none focus:border-blue-500"
                            >
                                <option value="">Selecione um cliente...</option>
                                {clients.map(c => (
                                    <option key={c.id} value={c.id}>{c.name}</option>
                                ))}
                            </select>
                        </div>
                        <button
                            onClick={handleLaunch}
                            disabled={launching || !selectedClientId}
                            className="px-6 py-2 bg-emerald-600 hover:bg-emerald-500 disabled:bg-slate-800 disabled:text-slate-600 text-white rounded-lg font-bold text-sm transition-all flex items-center gap-2 whitespace-nowrap"
                        >
                            {launching ? <Loader2 className="w-4 h-4 animate-spin" /> : <Send className="w-4 h-4" />}
                            Lançar no Sistema
                        </button>
                    </div>
                </div>
            )}

            {ocrText && (
                <div className="mt-8">
                    <button
                        onClick={() => setOcrText('')}
                        className="text-xs text-slate-500 hover:text-white mb-2 underline underline-offset-4"
                    >
                        Limpar resultado
                    </button>
                    <div className="max-h-40 overflow-y-auto bg-slate-950/30 p-4 rounded-xl border border-slate-800/50">
                        <pre className="text-[10px] text-slate-500 whitespace-pre-wrap leading-relaxed font-mono">
                            {ocrText}
                        </pre>
                    </div>
                </div>
            )}
        </div>
    );
}
