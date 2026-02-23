'use server';

import prisma from '@/lib/prisma';
import { revalidatePath } from 'next/cache';
import { TaxRegime, ClientStatus } from '@prisma/client';

export async function createClient(formData: FormData) {
    const name = formData.get('name') as string;
    const cnpj = formData.get('cnpj') as string;
    const regime = formData.get('regime') as TaxRegime;
    const servicesArr = formData.getAll('services') as string[];
    const services = servicesArr.join(',');

    try {
        const client = await prisma.client.create({
            data: {
                name,
                cnpj,
                regime,
                services,
                status: 'ATENDIMENTO',
            },
        });

        // Auto-generate initial tasks based on contracted services
        const tasks = [];
        if (servicesArr.includes('FISCAL')) tasks.push({ title: 'Apuração Fiscal Mensal', category: 'Fiscal' });
        if (servicesArr.includes('CONTABIL')) tasks.push({ title: 'Conciliação Contábil', category: 'Contábil' });
        if (servicesArr.includes('DP / RH')) tasks.push({ title: 'Fechamento de Folha (RH)', category: 'DP' });
        if (servicesArr.includes('SOCIETARIO')) tasks.push({ title: 'Revisão de CNDs e Alvarás', category: 'Societário' });
        if (servicesArr.includes('FINANCEIRO') || servicesArr.includes('BPO FINANCEIRO')) tasks.push({ title: 'Fechamento Financeiro / BPO', category: 'Financeiro' });
        if (servicesArr.includes('IMPOSTO DE RENDA')) tasks.push({ title: 'Planejamento / Declaração IR', category: 'Imposto de Renda' });
        if (servicesArr.includes('ALVARÁ')) tasks.push({ title: 'Acompanhamento de Alvará', category: 'Societário' });
        if (servicesArr.includes('CERTIFICADO DIGITAL')) tasks.push({ title: 'Renovação de Certificado Digital', category: 'TI / Doc' });

        if (tasks.length > 0) {
            await prisma.task.createMany({
                data: tasks.map(t => ({
                    ...t,
                    clientId: client.id,
                    dueDate: new Date(new Date().getFullYear(), new Date().getMonth() + 1, 10), // Next month 10th
                }))
            });
        }

        revalidatePath('/admin/dashboard');
        revalidatePath('/admin/clients');
        return { success: true };
    } catch (error) {
        console.error('Failed to create client:', error);
        return { error: 'Falha ao cadastrar cliente.' };
    }
}

export async function launchDocument(formData: FormData) {
    const clientId = formData.get('clientId') as string;
    const fileName = formData.get('fileName') as string;
    const extractedValue = formData.get('value') as string;
    const extractedCnpj = formData.get('cnpj') as string;
    const file = formData.get('file') as File;

    if (!file) {
        return { error: 'Arquivo não recebido no servidor.' };
    }

    try {
        const arrayBuffer = await file.arrayBuffer();
        const buffer = Buffer.from(arrayBuffer);

        await prisma.document.create({
            data: {
                name: fileName,
                url: `/uploads/${fileName}`,
                type: 'OUTROS',
                clientId,
                content: buffer
            }
        });

        revalidatePath('/admin/documents');
        return { success: true };
    } catch (error) {
        console.error('Failed to launch document:', error);
        return { error: 'Falha ao lançar documento.' };
    }
}

export async function toggleTaskStatus(taskId: string, completed: boolean) {
    try {
        await prisma.task.update({
            where: { id: taskId },
            data: { completed }
        });

        revalidatePath('/admin/dashboard');
        return { success: true };
    } catch (error) {
        console.error('Failed to toggle task:', error);
        return { error: 'Falha ao atualizar tarefa.' };
    }
}

export async function updateClient(id: string, formData: FormData) {
    const name = formData.get('name') as string;
    const cnpj = formData.get('cnpj') as string;
    const regime = formData.get('regime') as string;
    const servicesArr = formData.getAll('services') as string[];
    const services = servicesArr.join(',');

    try {
        await prisma.client.update({
            where: { id },
            data: { name, cnpj, regime: regime as TaxRegime, services },
        });

        revalidatePath('/admin/clients');
        revalidatePath('/admin/dashboard');
        return { success: true };
    } catch (error) {
        console.error('Failed to update client:', error);
        return { error: 'Falha ao atualizar cliente.' };
    }
}

export async function deleteClient(id: string) {
    try {
        // Delete ALL associated records first (no cascade in schema)
        await prisma.ticket.deleteMany({ where: { clientId: id } });
        await prisma.financialRecord.deleteMany({ where: { clientId: id } });
        await prisma.protocol.deleteMany({ where: { clientId: id } });
        await prisma.legalProcess.deleteMany({ where: { clientId: id } });
        await prisma.employee.deleteMany({ where: { clientId: id } });
        await prisma.tax.deleteMany({ where: { clientId: id } });
        await prisma.task.deleteMany({ where: { clientId: id } });
        await prisma.document.deleteMany({ where: { clientId: id } });

        await prisma.client.delete({
            where: { id },
        });

        revalidatePath('/admin/clients');
        revalidatePath('/admin/dashboard');
        return { success: true };
    } catch (error) {
        console.error('Failed to delete client:', error);
        return { error: 'Falha ao excluir cliente.' };
    }
}

export async function updateClientStatus(id: string, status: ClientStatus) {
    try {
        await prisma.client.update({
            where: { id },
            data: { status },
        });
        revalidatePath('/admin/dashboard');
        return { success: true };
    } catch (error) {
        return { error: 'Falha ao atualizar status.' };
    }
}

export async function getClients() {
    try {
        return await prisma.client.findMany({
            orderBy: { createdAt: 'desc' },
            include: {
                _count: {
                    select: { tasks: { where: { completed: false } } }
                }
            }
        });
    } catch (error) {
        return [];
    }
}
