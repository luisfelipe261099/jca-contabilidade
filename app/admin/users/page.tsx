export const dynamic = 'force-dynamic';

import prisma from '@/lib/prisma';
import { Users, UserPlus, Shield, Trash2, Key } from 'lucide-react';
import UserActions from '@/components/admin/UserActions';

export default async function UsersPage() {
    const [users, clients] = await Promise.all([
        prisma.user.findMany({
            orderBy: { createdAt: 'desc' },
            select: {
                id: true,
                email: true,
                name: true,
                role: true,
                department: true,
                clientId: true,
                createdAt: true,
            }
        }),
        prisma.client.findMany({
            select: { id: true, name: true }
        })
    ]);

    return (
        <div className="p-8">
            <div className="mb-8">
                <h1 className="text-3xl font-bold text-white mb-2">Gestão de Usuários</h1>
                <p className="text-slate-400">Crie acessos para administradores e clientes.</p>
            </div>

            <UserActions users={users} clients={clients} />
        </div>
    );
}
