'use client';

import React, { useState } from 'react';
import { Trash2, Loader2 } from 'lucide-react';
import { deleteClient } from '@/app/actions/clients';

export default function DeleteClientButton({ id }: { id: string }) {
    const [loading, setLoading] = useState(false);

    async function handleDelete() {
        if (!confirm('Tem certeza que deseja excluir este cliente? Todos os dados (documentos, tarefas, tickets, etc) vinculados a ele serão apagados permanentemente.')) {
            return;
        }

        setLoading(true);
        const result = await deleteClient(id);
        if (result.error) {
            alert(result.error);
        }
        setLoading(false);
    }

    return (
        <button
            onClick={handleDelete}
            disabled={loading}
            className="p-2 hover:bg-red-500/10 rounded-lg transition-colors group"
            title="Excluir Cliente"
        >
            {loading ? (
                <Loader2 className="w-4 h-4 text-red-400 animate-spin" />
            ) : (
                <Trash2 className="w-4 h-4 text-slate-500 group-hover:text-red-500" />
            )}
        </button>
    );
}
