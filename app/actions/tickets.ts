'use server';

import prisma from '@/lib/prisma';
import { revalidatePath } from 'next/cache';

export async function createTicket(formData: FormData) {
    const title = formData.get('title') as string;
    const description = formData.get('description') as string;
    const priority = formData.get('priority') as string;
    const clientId = formData.get('clientId') as string;

    try {
        await prisma.ticket.create({
            data: {
                title,
                description,
                priority,
                status: 'OPEN',
                clientId,
            },
        });

        revalidatePath('/admin/tickets');
        revalidatePath('/client/requests');
        return { success: true };
    } catch (error) {
        console.error('Failed to create ticket:', error);
        return { error: 'Falha ao abrir chamado.' };
    }
}

export async function updateTicketStatus(id: string, status: string) {
    try {
        await prisma.ticket.update({
            where: { id },
            data: { status },
        });

        revalidatePath('/admin/tickets');
        return { success: true };
    } catch (error) {
        console.error('Failed to update ticket:', error);
        return { error: 'Falha ao atualizar chamado.' };
    }
}

export async function deleteTicket(id: string) {
    try {
        await prisma.ticket.delete({
            where: { id },
        });

        revalidatePath('/admin/tickets');
        return { success: true };
    } catch (error) {
        console.error('Failed to delete ticket:', error);
        return { error: 'Falha ao excluir chamado.' };
    }
}
