'use server';

import { getBrowser } from '@/lib/scraper';
import prisma from '@/lib/prisma';
import { revalidatePath } from 'next/cache';

interface ScraperResult {
    success: boolean;
    message: string;
    error?: string;
}

async function saveDocument(clientId: string, name: string, type: string, pdfBuffer: Buffer) {
    return await prisma.document.create({
        data: {
            name,
            url: `#`, // URL will be managed by the document viewer API
            type,
            content: pdfBuffer,
            clientId,
        },
    });
}

export async function checkFederalCND(clientId: string): Promise<ScraperResult> {
    try {
        const client = await prisma.client.findUnique({ where: { id: clientId } });
        if (!client) return { success: false, message: '', error: 'Cliente não encontrado.' };

        const browser = await getBrowser();
        const page = await browser.newPage();
        const url = 'https://solucoes.receita.fazenda.gov.br/Servicos/certidaointernet/PJ/Emitir';

        await page.goto(url, { waitUntil: 'networkidle2' });
        await page.type('#NI', client.cnpj);
        await page.click('#validar');

        await page.waitForNavigation({ waitUntil: 'networkidle2', timeout: 5000 }).catch(() => { });

        const content = await page.content();

        if (content.includes('Certidão emitida') || content.includes('Situação Regular')) {
            const pdfBuffer = await page.pdf({ format: 'A4' });
            await saveDocument(clientId, 'Certidão Federal', 'Guia', Buffer.from(pdfBuffer));

            await prisma.task.updateMany({
                where: { clientId, title: { contains: 'Federal' } },
                data: { completed: true }
            });

            await browser.close();
            revalidatePath('/admin/dashboard');
            return { success: true, message: 'Certidão Federal emitida e salva!' };
        }

        await browser.close();
        return { success: false, message: '', error: 'Não foi possível emitir a certidão automaticamente.' };
    } catch (error: any) {
        return { success: false, message: '', error: 'Erro no robô Federal: ' + error.message };
    }
}

export async function checkStateCND(clientId: string): Promise<ScraperResult> {
    try {
        const client = await prisma.client.findUnique({ where: { id: clientId } });
        if (!client) return { success: false, message: '', error: 'Cliente não encontrado.' };

        const browser = await getBrowser();
        const page = await browser.newPage();

        const url = 'https://www.fazenda.pr.gov.br';
        await page.goto(url, { waitUntil: 'networkidle2' });

        await browser.close();
        return { success: false, message: '', error: 'Robô Estadual (SEFAZ) requer configuração específica por estado.' };
    } catch (error: any) {
        return { success: false, message: '', error: 'Erro no robô Estadual: ' + error.message };
    }
}

export async function checkMunicipalCND(clientId: string): Promise<ScraperResult> {
    try {
        const client = await prisma.client.findUnique({ where: { id: clientId } });
        if (!client) return { success: false, message: '', error: 'Cliente não encontrado.' };

        const browser = await getBrowser();
        const page = await browser.newPage();

        const url = 'https://prefeitura.exemplo.gov.br';
        await page.goto(url, { waitUntil: 'networkidle2' });

        await browser.close();
        return { success: false, message: '', error: 'Robô Municipal requer configuração específica por prefeitura.' };
    } catch (error: any) {
        return { success: false, error: 'Erro no robô Municipal: ' + error.message, message: '' };
    }
}
