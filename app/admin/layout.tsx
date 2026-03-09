export const dynamic = 'force-dynamic';

import { auth } from '@/auth';
import { redirect } from 'next/navigation';
import AdminLayoutContent from '@/components/admin/AdminLayoutContent';

export default async function AdminLayout({
    children,
}: {
    children: React.ReactNode;
}) {
    const session = await auth();
    const user = session?.user as any;

    if (user && user.role === 'CLIENT') {
        redirect('/client/dashboard');
    }

    return (
        <AdminLayoutContent>
            {children}
        </AdminLayoutContent>
    );
}
