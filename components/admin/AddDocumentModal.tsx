'use client';

import React, { useState } from 'react';
import { Upload, X, Loader2, CheckCircle2 } from 'lucide-react';
import { launchDocument } from '@/app/actions/clients';

interface AddDocumentModalProps {
    clients: { id: string, name: string }[];
    isOpen: boolean;
    onClose: () => void;
}

export default function AddDocumentModal({ clients, isOpen, onClose }: AddDocumentModalProps) {
    const [loading, setLoading] = useState(false);
    const [fileName, setFileName] = useState('');
    const [selectedFile, setSelectedFile] = useState<File | null>(null);
    const [selectedClientId, setSelectedClientId] = useState('');
    const [docType, setDocType] = useState('OUTROS');

    if (!isOpen) return null;

    async function handleSubmit(e: React.FormEvent) {
        e.preventDefault();
        if (!selectedClientId) return alert('Selecione um cliente.');
        if (!selectedFile) return alert('Selecione um arquivo.');

        setLoading(true);
        const formData = new FormData();
        formData.append('clientId', selectedClientId);
        formData.append('file', selectedFile);
        formData.append('fileName', fileName || selectedFile.name);
        formData.append('type', docType);

        const result = await launchDocument(formData);
        setLoading(false);

        if (result.success) {
            alert('Documento adicionado com sucesso!');
            onClose();
            // Reset state
            setSelectedFile(null);
            setFileName('');
            setSelectedClientId('');
        } else {
            alert(result.error);
        }
    }

    return (
        <div className="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm animate-in fade-in duration-200">
            <div className="bg-slate-900 border border-slate-800 w-full max-w-lg rounded-[32px] overflow-hidden shadow-2xl animate-in zoom-in-95 duration-200">
                <div className="p-8 border-b border-slate-800 flex items-center justify-between bg-slate-900/50">
                    <div className="flex items-center gap-3">
                        <div className="p-2 bg-blue-500/10 rounded-xl">
                            <Upload className="w-5 h-5 text-blue-500" />
                        </div>
                        <div>
                            <h2 className="text-xl font-bold text-white uppercase italic tracking-tight">Adicionar Documento</h2>
                            <p className="text-[10px] text-slate-500 font-bold uppercase tracking-widest leading-none mt-1">Disponibilizar para o cliente</p>
                        </div>
                    </div>
                    <button onClick={onClose} className="p-2 hover:bg-slate-800 rounded-full text-slate-400 hover:text-white transition-colors">
                        <X className="w-5 h-5" />
                    </button>
                </div>

                <form onSubmit={handleSubmit} className="p-8 space-y-6">
                    <div className="space-y-4">
                        <div>
                            <label className="block text-[10px] font-bold text-slate-500 uppercase mb-1.5 ml-1">Vincular ao Cliente</label>
                            <select
                                value={selectedClientId}
                                onChange={(e) => setSelectedClientId(e.target.value)}
                                className="w-full bg-slate-950 border border-slate-800 rounded-xl py-3.5 px-5 text-white text-sm focus:outline-none focus:border-blue-500 transition-all appearance-none"
                                required
                            >
                                <option value="">Selecione um cliente...</option>
                                {clients.map(c => (
                                    <option key={c.id} value={c.id}>{c.name}</option>
                                ))}
                            </select>
                        </div>

                        <div>
                            <label className="block text-[10px] font-bold text-slate-500 uppercase mb-1.5 ml-1">Tipo de Documento</label>
                            <select
                                value={docType}
                                onChange={(e) => setDocType(e.target.value)}
                                className="w-full bg-slate-950 border border-slate-800 rounded-xl py-3.5 px-5 text-white text-sm focus:outline-none focus:border-blue-500 transition-all appearance-none"
                            >
                                <option value="CONTRATO">Contrato</option>
                                <option value="CERTIDAO">Certidão</option>
                                <option value="RELATORIO">Relatório / Balancete</option>
                                <option value="IMPOSTO">Guia de Imposto (DAS/BOLETO)</option>
                                <option value="OUTROS">Outros</option>
                            </select>
                        </div>

                        <div>
                            <label className="block text-[10px] font-bold text-slate-500 uppercase mb-1.5 ml-1">Nome do Arquivo (Opcional)</label>
                            <input
                                type="text"
                                placeholder="Ex: Contrato Social Atualizado"
                                value={fileName}
                                onChange={(e) => setFileName(e.target.value)}
                                className="w-full bg-slate-950 border border-slate-800 rounded-xl py-3.5 px-5 text-white text-sm focus:outline-none focus:border-blue-500 transition-all placeholder:text-slate-700"
                            />
                        </div>

                        <div className="relative group">
                            <input
                                type="file"
                                onChange={(e) => setSelectedFile(e.target.files?.[0] || null)}
                                className="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                required
                            />
                            <div className={`border-2 border-dashed rounded-2xl p-8 flex flex-col items-center justify-center transition-all ${selectedFile ? 'border-emerald-500/50 bg-emerald-500/5' : 'border-slate-800 group-hover:border-blue-500/50 group-hover:bg-slate-800/50'
                                }`}>
                                {selectedFile ? (
                                    <div className="flex flex-col items-center text-center">
                                        <CheckCircle2 className="w-10 h-10 text-emerald-500 mb-2" />
                                        <div className="text-white font-bold text-sm tracking-tight">{selectedFile.name}</div>
                                        <div className="text-slate-500 text-[10px] mt-1">Clique para trocar o arquivo</div>
                                    </div>
                                ) : (
                                    <div className="text-center">
                                        <Upload className="w-10 h-10 text-slate-600 mb-2 mx-auto group-hover:text-blue-500 transition-colors" />
                                        <div className="text-white font-bold text-sm">Selecionar Arquivo</div>
                                        <div className="text-slate-600 text-[10px] mt-1">PDF, Imagem ou Documento</div>
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>

                    <div className="pt-4 flex gap-4">
                        <button type="button" onClick={onClose} className="flex-1 py-4 bg-slate-800 hover:bg-slate-750 text-slate-400 rounded-2xl font-bold transition-all text-sm">
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            disabled={loading}
                            className="flex-[2] bg-blue-600 hover:bg-blue-500 disabled:bg-slate-800 text-white rounded-2xl font-bold transition-all flex items-center justify-center gap-2 shadow-lg shadow-blue-900/20"
                        >
                            {loading ? <Loader2 className="w-5 h-5 animate-spin" /> : <Upload className="w-5 h-5" />}
                            Adicionar Documento
                        </button>
                    </div>
                </form>
            </div>
        </div>
    );
}
