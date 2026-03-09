'use client';

import React from 'react';
import Sidebar from '@/components/admin/Sidebar';
import { usePathname } from 'next/navigation';

export default function AdminLayoutContent({
    children,
}: {
    children: React.ReactNode;
}) {
    const pathname = usePathname();
    const isLoginPage = pathname === '/admin/login';

    return (
        <div className="min-h-screen bg-[#020617] print:bg-white">
            {!isLoginPage && <Sidebar />}
            <main className={`min-h-screen ${!isLoginPage ? 'pt-16 lg:pt-0 lg:pl-64 print:pl-0 print:pt-0' : ''}`}>
                <div className="w-full">
                    {children}
                </div>
            </main>
        </div>
    );
}
