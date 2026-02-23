'use client';

import Sidebar from '@/components/admin/Sidebar';
import { usePathname } from 'next/navigation';

export default function AdminLayout({
    children,
}: {
    children: React.ReactNode;
}) {
    const pathname = usePathname();
    const isLoginPage = pathname === '/admin/login';

    return (
        <div className="min-h-screen bg-[#020617] print:bg-white">
            {!isLoginPage && <Sidebar />}
            <main className={`min-h-screen ${!isLoginPage ? 'pl-64 print:pl-0' : ''}`}>
                <div className="w-full">
                    {children}
                </div>
            </main>
        </div>
    );
}
