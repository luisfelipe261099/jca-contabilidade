export const dynamic = 'force-dynamic';
import React from 'react';
import prisma from '@/lib/prisma';
import AdminDocumentsActions from '@/components/admin/AdminDocumentsActions';

export default async function DocumentsPage() {
    const [clients, documents] = await Promise.all([
        prisma.client.findMany({
            select: { id: true, name: true },
            orderBy: { name: 'asc' }
        }),
        prisma.document.findMany({
            orderBy: { createdAt: 'desc' },
            include: { client: true }
        })
    ]);

    return (
        <div className="p-4 sm:p-6 lg:p-8">
            <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 sm:mb-10">
                <div>
                    <h1 className="text-2xl sm:text-3xl font-bold text-white mb-2 italic uppercase">Central de Documentos</h1>
                    <p className="text-slate-400 text-sm sm:text-base">Gerencie, filtre e disponibilize arquivos para seus clientes.</p>
                </div>
            </div>

            <AdminDocumentsActions
                clients={clients}
                initialDocuments={JSON.parse(JSON.stringify(documents))}
            />
        </div>
    );
}
