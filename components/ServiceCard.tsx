import React, { ReactNode } from 'react';

interface ServiceCardProps {
    icon: ReactNode;
    title: string;
    description: string;
}

export default function ServiceCard({ icon, title, description }: ServiceCardProps) {
    return (
        <div className="group p-7 rounded-3xl border border-sky-100 bg-white shadow-[0_14px_35px_rgba(9,65,102,0.1)] hover:border-[#48BFB0]/40 transition-all duration-300 hover:-translate-y-1.5">
            <div className="w-12 h-12 bg-gradient-to-br from-[#E7F5FF] to-[#E8FBF7] rounded-2xl flex items-center justify-center text-[#007CC3] mb-6 group-hover:scale-110 transition-transform duration-300">
                {icon}
            </div>
            <h3 className="text-xl font-black text-[#0B2F47] mb-3 group-hover:text-[#007CC3] transition-colors">
                {title}
            </h3>
            <p className="text-[#547085] leading-relaxed text-sm font-semibold">
                {description}
            </p>
        </div>
    );
}
