import React from 'react';

interface LogoProps {
    className?: string;
    showText?: boolean;
    size?: 'sm' | 'md' | 'lg' | 'xl';
}

export default function Logo({ className = "", showText = true, size = 'md' }: LogoProps) {
    const sizes = {
        sm: { icon: 'h-6 w-6', text: 'text-sm', gap: 'gap-2' },
        md: { icon: 'h-9 w-9', text: 'text-lg', gap: 'gap-3' },
        lg: { icon: 'h-12 w-12', text: 'text-2xl', gap: 'gap-4' },
        xl: { icon: 'h-16 w-16', text: 'text-3xl', gap: 'gap-5' }
    };

    const currentSize = sizes[size];

    return (
        <div className={`flex items-center ${currentSize.gap} ${className}`}>
            {/* Logo Icon */}
            <div className={`relative ${currentSize.icon} group`}>
                {/* Background Glow */}
                <div className="absolute inset-0 bg-blue-600/30 blur-lg rounded-xl group-hover:bg-blue-600/50 transition-all duration-500" />

                {/* Main Shape */}
                <div className="relative h-full w-full bg-gradient-to-br from-blue-500 via-indigo-600 to-violet-700 rounded-xl flex items-center justify-center shadow-xl border border-white/20 overflow-hidden transform group-hover:scale-105 transition-all duration-300">
                    {/* SVG Graphic Component */}
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        className="w-2/3 h-2/3 text-white drop-shadow-md"
                        stroke="currentColor"
                        strokeWidth="2.5"
                        strokeLinecap="round"
                        strokeLinejoin="round"
                    >
                        <path d="M12 3v18M3 12h18" className="opacity-20" /> {/* Subtle grid */}
                        <path d="M16 8v8a4 4 0 0 1-8 0V8" /> {/* Main Curve */}
                        <path d="M12 3v12" /> {/* Supporting vertical */}
                    </svg>

                    {/* Shine effect */}
                    <div className="absolute top-0 left-0 w-full h-1/2 bg-gradient-to-b from-white/20 to-transparent" />
                </div>
            </div>

            {/* Logo Text */}
            {showText && (
                <div className="flex flex-col">
                    <span className={`${currentSize.text} font-black tracking-tighter bg-clip-text text-transparent bg-gradient-to-r from-white via-slate-200 to-slate-400 leading-none`}>
                        JCA
                    </span>
                    <span className="text-[10px] font-bold text-blue-500 uppercase tracking-[0.2em] mt-1 leading-none">
                        Contabilidade
                    </span>
                </div>
            )}
        </div>
    );
}
