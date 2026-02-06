'use client';

import React, { useState } from 'react';
import { toggleTaskStatus } from '@/app/actions/clients';
import { checkFederalCND, checkStateCND, checkMunicipalCND } from '@/app/actions/scrapers';
import { CheckCircle2, Circle, Loader2, Bot, Sparkles } from 'lucide-react';

interface TaskToggleProps {
    taskId: string;
    clientId: string;
    initialCompleted: boolean;
    title: string;
}

export default function TaskToggle({ taskId, clientId, initialCompleted, title }: TaskToggleProps) {
    const [completed, setCompleted] = useState(initialCompleted);
    const [loading, setLoading] = useState(false);
    const [scraping, setScraping] = useState(false);

    const isAutomated = title.toLowerCase().includes('fiscal') ||
        title.toLowerCase().includes('cnd') ||
        title.toLowerCase().includes('receita');

    async function handleToggle() {
        setLoading(true);
        const result = await toggleTaskStatus(taskId, !completed);
        setLoading(false);

        if (result.success) {
            setCompleted(!completed);
        } else {
            alert(result.error);
        }
    }

    async function handleScrape(e: React.MouseEvent) {
        e.stopPropagation();
        setScraping(true);

        let result;
        if (title.toLowerCase().includes('federal') || title.toLowerCase().includes('receita')) {
            result = await checkFederalCND(clientId);
        } else if (title.toLowerCase().includes('estadual')) {
            result = await checkStateCND(clientId);
        } else if (title.toLowerCase().includes('municipal')) {
            result = await checkMunicipalCND(clientId);
        } else {
            result = await checkFederalCND(clientId); // Default
        }

        setScraping(false);

        if (result.success) {
            setCompleted(true);
            alert(result.message);
        } else {
            alert(result.error);
        }
    }

    return (
        <div className="flex items-center gap-1 group/item">
            <button
                onClick={handleToggle}
                disabled={loading || scraping}
                className={`flex items-center gap-2 px-2 py-1.5 rounded-lg transition-all text-left flex-1 group ${completed ? 'bg-emerald-500/10 text-emerald-400' : 'hover:bg-slate-800 text-slate-400'
                    }`}
            >
                {loading ? (
                    <Loader2 className="w-3 h-3 animate-spin" />
                ) : completed ? (
                    <CheckCircle2 className="w-3 h-3 text-emerald-500" />
                ) : (
                    <Circle className="w-3 h-3 text-slate-600 group-hover:text-amber-500" />
                )}
                <span className="text-[10px] font-bold truncate tracking-tight">{title}</span>
            </button>

            {!completed && isAutomated && (
                <button
                    onClick={handleScrape}
                    disabled={scraping}
                    title="Acionar Robô de Busca"
                    className={`p-1.5 rounded-lg transition-all ${scraping
                        ? 'bg-blue-500/20 text-blue-400 animate-pulse'
                        : 'bg-slate-800/50 text-slate-500 hover:bg-blue-500/20 hover:text-blue-400 opacity-0 group-hover/item:opacity-100'
                        }`}
                >
                    {scraping ? <Loader2 className="w-3 h-3 animate-spin" /> : <Bot className="w-3 h-3" />}
                </button>
            )}
        </div>
    );
}
