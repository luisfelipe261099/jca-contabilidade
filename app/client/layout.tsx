export const dynamic = 'force-dynamic';
import type { Metadata } from 'next';

import { auth } from '@/auth';
import prisma from '@/lib/prisma';
import { redirect } from 'next/navigation';
import ClientNavbar from '@/components/client/ClientNavbar';

export const metadata: Metadata = {
    robots: {
        index: false,
        follow: false,
        googleBot: {
            index: false,
            follow: false,
            noimageindex: true,
        },
    },
};

export default async function ClientLayout({
    children,
}: {
    children: React.ReactNode;
}) {
    const session = await auth();

    if (!session || (session.user as any).role !== 'CLIENT') {
        redirect('/admin/login');
    }

    const clientId = (session.user as any).clientId;
    if (!clientId) {
        return (
            <div className="min-h-screen bg-[#020617] flex items-center justify-center p-6 text-center">
                <div className="max-w-md">
                    <h1 className="text-2xl font-bold text-white mb-2">Acesso Restrito</h1>
                    <p className="text-slate-400">Usuário não vinculado.</p>
                </div>
            </div>
        );
    }

    const client = await prisma.client.findUnique({
        where: { id: clientId },
        select: { name: true, cnpj: true }
    });

    if (!client) {
        redirect('/admin/login');
    }

    return (
        <div className="min-h-screen bg-[#020617]">
            <ClientNavbar clientName={client.name} cnpj={client.cnpj} />
            <main>
                {children}
            </main>
        </div>
    );
}
