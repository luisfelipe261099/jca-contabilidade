'use client';

import React, { useState } from 'react';
import EditClientModal from './EditClientModal';
import { Edit2 } from 'lucide-react';

export default function EditClientTrigger({ client }: { client: any }) {
    const [isOpen, setIsOpen] = useState(false);

    return (
        <>
            <button
                onClick={() => setIsOpen(true)}
                className="p-2 hover:bg-slate-800 rounded-lg transition-colors group"
                title="Editar Cliente"
            >
                <Edit2 className="w-4 h-4 text-slate-500 group-hover:text-blue-400" />
            </button>

            <EditClientModal
                client={client}
                isOpen={isOpen}
                onClose={() => setIsOpen(false)}
            />
        </>
    );
}
