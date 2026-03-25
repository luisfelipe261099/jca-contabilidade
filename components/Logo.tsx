import React from 'react';

interface LogoProps {
    className?: string;
    showText?: boolean;
    size?: 'sm' | 'md' | 'lg' | 'xl';
}

export default function Logo({ className = "", showText = true, size = 'md' }: LogoProps) {
    const sizes = {
        sm: { icon: 'h-8 w-8', text: 'text-xs', subtitle: 'text-[9px]', gap: 'gap-2' },
        md: { icon: 'h-10 w-10', text: 'text-sm', subtitle: 'text-[10px]', gap: 'gap-3' },
        lg: { icon: 'h-14 w-14', text: 'text-xl', subtitle: 'text-xs', gap: 'gap-4' },
        xl: { icon: 'h-16 w-16', text: 'text-2xl', subtitle: 'text-sm', gap: 'gap-4' }
    };

    const currentSize = sizes[size];

    return (
        <div className={`flex items-center ${currentSize.gap} ${className}`}>
            <div className={`relative ${currentSize.icon}`}>
                <svg viewBox="0 0 64 64" className="h-full w-full drop-shadow-[0_8px_16px_rgba(0,124,195,0.35)]" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7 50L26 30L36 39L57 16" stroke="#007CC3" strokeWidth="7" strokeLinecap="round" strokeLinejoin="round" />
                    <path d="M47 16H57V26" stroke="#007CC3" strokeWidth="7" strokeLinecap="round" strokeLinejoin="round" />
                    <path d="M11 54L28 54L28 39L20 39L11 48V54Z" fill="#007CC3" />
                    <path d="M31 54H41V33H31V54Z" fill="#25A7A2" />
                    <path d="M44 54H54V24H44V54Z" fill="#7AD8C6" />
                </svg>
            </div>

            {showText && (
                <div className="leading-none">
                    <div className={`${currentSize.text} font-extrabold tracking-tight text-[#0069A8]`}>
                        JADE CRISTINA
                    </div>
                    <div className={`${currentSize.text} font-black text-[#4ABCB0]`}>
                        CONTABILIDADE
                    </div>
                    <div className={`${currentSize.subtitle} font-semibold uppercase tracking-[0.15em] text-[#2B6E98] mt-1`}>
                        Solucoes Contabeis Digitais
                    </div>
                </div>
            )}
        </div>
    );
}
