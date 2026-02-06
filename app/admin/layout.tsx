import Sidebar from '@/components/admin/Sidebar';

export default function AdminLayout({
    children,
}: {
    children: React.ReactNode;
}) {
    return (
        <div className="min-h-screen bg-[#020617]">
            <Sidebar />
            <main className="pl-64 min-h-screen">
                <div className="w-full">
                    {children}
                </div>
            </main>
        </div>
    );
}
