export const dynamic = 'force-dynamic';
import type { Metadata } from 'next';

import { auth } from '@/auth';
import { redirect } from 'next/navigation';
import AdminLayoutContent from '@/components/admin/AdminLayoutContent';

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
