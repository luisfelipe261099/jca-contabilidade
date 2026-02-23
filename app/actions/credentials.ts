'use server';

import prisma from '@/lib/prisma';
import { revalidatePath } from 'next/cache';

export async function createCredential(formData: FormData) {
    const system = formData.get('system') as string;
    const username = formData.get('username') as string;
    const password = formData.get('password') as string;
    const notes = formData.get('notes') as string | null;
    const clientId = formData.get('clientId') as string;

    try {
        await prisma.credential.create({
            data: {
                system,
                username,
                password,
                notes,
                clientId,
            },
        });

        revalidatePath('/admin/clients');
        return { success: true };
    } catch (error) {
        console.error('Failed to create credential:', error);
        return { error: 'Falha ao salvar senha.' };
    }
}

export async function deleteCredential(id: string) {
    try {
        await prisma.credential.delete({
            where: { id },
        });

        revalidatePath('/admin/clients');
        return { success: true };
    } catch (error) {
        console.error('Failed to delete credential:', error);
        return { error: 'Falha ao excluir senha.' };
    }
}

export async function getClientCredentials(clientId: string) {
    try {
        const credentials = await prisma.credential.findMany({
            where: { clientId },
            orderBy: { system: 'asc' },
        });
        return { success: true, credentials };
    } catch (error) {
        console.error('Failed to get credentials:', error);
        return { error: 'Falha ao carregar senhas.' };
    }
}
