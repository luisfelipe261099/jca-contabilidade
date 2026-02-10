'use server';

import prisma from '@/lib/prisma';
import { revalidatePath } from 'next/cache';
import { TaxRegime, ClientStatus } from '@prisma/client';

export async function createClient(formData: FormData) {
    const name = formData.get('name') as string;
    const cnpj = formData.get('cnpj') as string;
    const regime = formData.get('regime') as TaxRegime;

    try {
        const client = await prisma.client.create({
            data: {
                name,
                cnpj,
                regime,
                status: 'ATENDIMENTO',
            },
        });

        // Auto-generate initial tasks based on regime
        const tasks = [
            { title: 'Apuração Fiscal Mensal', category: 'Fiscal' },
            { title: 'Fechamento de Folha (RH)', category: 'RH' },
            { title: 'Conciliação Contábil', category: 'Contábil' },
        ];

        await prisma.task.createMany({
            data: tasks.map(t => ({
                ...t,
                clientId: client.id,
                dueDate: new Date(new Date().getFullYear(), new Date().getMonth() + 1, 10), // Next month 10th
            }))
        });

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

    try {
        await prisma.document.create({
            data: {
                name: fileName,
                url: `/uploads/${fileName}`,
                type: 'OUTROS',
                clientId,
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

    try {
        await prisma.client.update({
            where: { id },
            data: { name, cnpj, regime },
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
