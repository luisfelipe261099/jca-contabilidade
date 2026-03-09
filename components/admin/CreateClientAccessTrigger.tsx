'use client';

import React, { useState } from 'react';
import { Key } from 'lucide-react';
import CreateClientAccessModal from './CreateClientAccessModal';

export default function CreateClientAccessTrigger({ client }: { client: any }) {
    const [isOpen, setIsOpen] = useState(false);

    return (
        <>
            <button
                onClick={() => setIsOpen(true)}
                className="p-2 hover:bg-emerald-500/10 rounded-lg transition-colors group"
                title="Criar Acesso ao Sistema"
            >
                <Key className="w-4 h-4 text-slate-500 group-hover:text-emerald-500" />
            </button>

            <CreateClientAccessModal
                client={client}
                isOpen={isOpen}
                onClose={() => setIsOpen(false)}
            />
        </>
    );
}
