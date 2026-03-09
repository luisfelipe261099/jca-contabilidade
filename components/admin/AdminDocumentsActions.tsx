'use client';

import React, { useState } from 'react';
import { Search, Filter, Plus, FileText, Download, Trash2, Loader2 } from 'lucide-react';
import AddDocumentModal from './AddDocumentModal';
import { format } from 'date-fns';
import { ptBR } from 'date-fns/locale';

interface Document {
    id: string;
    name: string;
    type: string;
    createdAt: Date;
    client: { id: string; name: string };
}

interface AdminDocumentsActionsProps {
    clients: { id: string, name: string }[];
    initialDocuments: any[];
}

export default function AdminDocumentsActions({ clients, initialDocuments }: AdminDocumentsActionsProps) {
    const [isAddModalOpen, setIsAddModalOpen] = useState(false);
    const [filterName, setFilterName] = useState('');
    const [filterClient, setFilterClient] = useState('');
    const [filterType, setFilterType] = useState('');

    // Client-side filtering for simplicity, though pagination would ideally be server-side
    // For now, I'll implement a clean UI with client-side filtering as a starting point
    // consistent with the "simpler" request. If needed, I can move to server actions for full pagination.

    const filteredDocs = initialDocuments.filter(doc => {
        const matchesName = doc.name.toLowerCase().includes(filterName.toLowerCase());
        const matchesClient = filterClient === '' || doc.clientId === filterClient;
        const matchesType = filterType === '' || doc.type === filterType;
        return matchesName && matchesClient && matchesType;
    });

    return (
        <div className="space-y-8">
            <div className="flex flex-col md:flex-row gap-4 justify-between items-start md:items-center bg-slate-900/40 border border-slate-800 p-6 rounded-3xl">
                <div className="flex flex-col md:flex-row gap-4 w-full md:w-auto flex-1">
                    <div className="relative flex-1 max-w-sm">
                        <Search className="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" />
                        <input
                            type="text"
                            placeholder="Buscar documento..."
                            value={filterName}
                            onChange={(e) => setFilterName(e.target.value)}
                            className="w-full bg-slate-950 border border-slate-800 rounded-xl py-2.5 pl-11 pr-4 text-white text-sm focus:outline-none focus:border-blue-500 transition-all"
                        />
                    </div>

                    <select
                        value={filterClient}
                        onChange={(e) => setFilterClient(e.target.value)}
                        className="bg-slate-950 border border-slate-800 rounded-xl py-2.5 px-4 text-white text-sm focus:outline-none focus:border-blue-500 transition-all appearance-none min-w-[200px]"
                    >
                        <option value="">Todos os Clientes</option>
                        {clients.map(c => (
                            <option key={c.id} value={c.id}>{c.name}</option>
                        ))}
                    </select>

                    <select
                        value={filterType}
                        onChange={(e) => setFilterType(e.target.value)}
                        className="bg-slate-950 border border-slate-800 rounded-xl py-2.5 px-4 text-white text-sm focus:outline-none focus:border-blue-500 transition-all appearance-none min-w-[150px]"
                    >
                        <option value="">Todos os Tipos</option>
                        <option value="CONTRATO">Contrato</option>
                        <option value="CERTIDAO">Certidão</option>
                        <option value="RELATORIO">Relatório / Balancete</option>
                        <option value="IMPOSTO">Guia de Imposto</option>
                        <option value="OUTROS">Outros</option>
                    </select>
                </div>

                <button
                    onClick={() => setIsAddModalOpen(true)}
                    className="flex items-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-bold transition-all shadow-lg shadow-blue-900/20 whitespace-nowrap self-stretch md:self-auto"
                >
                    <Plus className="w-4 h-4" />
                    Novo Documento
                </button>
            </div>

            <div className="bg-slate-900/40 border border-slate-800 rounded-3xl overflow-hidden shadow-2xl">
                <div className="overflow-x-auto">
                    <table className="w-full text-left border-collapse">
                        <thead>
                            <tr className="bg-slate-950/50 border-b border-slate-800 text-slate-500 text-[10px] font-bold uppercase tracking-widest">
                                <th className="px-8 py-5">Documento</th>
                                <th className="px-8 py-5">Cliente</th>
                                <th className="px-8 py-5">Tipo</th>
                                <th className="px-8 py-5">Data</th>
                                <th className="px-8 py-5 text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-800/50 text-sm">
                            {filteredDocs.map((doc) => (
                                <tr key={doc.id} className="hover:bg-slate-800/20 transition-colors group">
                                    <td className="px-8 py-4">
                                        <div className="flex items-center gap-3">
                                            <div className="p-2 bg-blue-500/10 rounded-lg">
                                                <FileText className="w-4 h-4 text-blue-500" />
                                            </div>
                                            <span className="font-bold text-white tracking-tight leading-tight">{doc.name}</span>
                                        </div>
                                    </td>
                                    <td className="px-8 py-4 text-slate-400 font-medium">{doc.client.name}</td>
                                    <td className="px-8 py-4">
                                        <span className="px-2 py-0.5 bg-slate-800 text-slate-400 rounded-full text-[9px] font-bold uppercase border border-slate-700">
                                            {doc.type}
                                        </span>
                                    </td>
                                    <td className="px-8 py-4 text-slate-500 text-[11px] font-mono uppercase">
                                        {format(new Date(doc.createdAt), 'dd MMM yyyy', { locale: ptBR })}
                                    </td>
                                    <td className="px-8 py-4 text-right">
                                        <div className="flex items-center justify-end gap-2 outline-none">
                                            <a
                                                href={`/api/documents/${doc.id}`}
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                className="p-2 hover:bg-slate-800 rounded-lg text-slate-500 hover:text-blue-400 transition-all"
                                                title="Visualizar / Baixar"
                                            >
                                                <Download className="w-4 h-4" />
                                            </a>
                                            {/* Delete button could be added here if needed */}
                                        </div>
                                    </td>
                                </tr>
                            ))}
                            {filteredDocs.length === 0 && (
                                <tr>
                                    <td colSpan={5} className="py-20 text-center text-slate-600 italic">
                                        Nenhum documento encontrado com os filtros aplicados.
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>

            <AddDocumentModal
                clients={clients}
                isOpen={isAddModalOpen}
                onClose={() => setIsAddModalOpen(false)}
            />
        </div>
    );
}
