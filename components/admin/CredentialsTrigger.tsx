'use client';

import React, { useState } from 'react';
import CredentialsModal from '@/components/admin/CredentialsModal';
import { Key } from 'lucide-react';

export default function CredentialsTrigger({ client }: { client: any }) {
    const [isOpen, setIsOpen] = useState(false);

    return (
        <>
            <button
                onClick={() => setIsOpen(true)}
                className="p-2 hover:bg-amber-500/20 rounded-lg transition-colors group"
                title="Cofre de Senhas"
            >
                <Key className="w-4 h-4 text-slate-500 group-hover:text-amber-500" />
            </button>

            <CredentialsModal
                client={client}
                isOpen={isOpen}
                onClose={() => setIsOpen(false)}
            />
        </>
    );
}
