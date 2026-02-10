'use server';

import { auth } from '@/auth';
import prisma from '@/lib/prisma';
import { revalidatePath } from 'next/cache';

export async function uploadClientDocument(formData: FormData) {
    const session = await auth();

    // Authorization Check
    if (!session || !session.user) {
        return { error: 'Você precisa estar logado.' };
    }

    const role = (session.user as any).role;
    const clientId = (session.user as any).clientId;

    // Ensure only Clients can use this (or Admins acting on behalf, but let's restrict to Client for now in this portal)
    if (role !== 'CLIENT' || !clientId) {
        return { error: 'Perfil de cliente inválido ou não vinculado a uma empresa.' };
    }

    const file = formData.get('file') as File;
    if (!file) {
        return { error: 'Nenhum arquivo enviado.' };
    }

    try {
        // In a real app, upload to S3/Blob here. 
        // For now, we simulate by creating a database record.

        // Generate a protocol for the upload
        const year = new Date().getFullYear();
        const count = await prisma.protocol.count();
        const protocolCode = `UP-${year}-${String(count + 1).padStart(4, '0')}`;

        // Create Protocol
        await prisma.protocol.create({
            data: {
                code: protocolCode,
                type: 'UPLOAD_CLIENTE',
                description: `Upload de arquivo: ${file.name}`,
                receivedAt: new Date(), // Auto-confirm receipt
                clientId: clientId
            }
        });

        // Convert file to Buffer for DB storage
        const arrayBuffer = await file.arrayBuffer();
        const buffer = Buffer.from(arrayBuffer);

        // Create Document Record with Content
        await prisma.document.create({
            data: {
                name: file.name,
                url: `/uploads/${file.name}`, // Placeholder, but content is real
                type: 'OUTROS',
                clientId: clientId,
                content: buffer // <--- Saving the actual file
            }
        });

        revalidatePath('/client/dashboard');
        return { success: true, protocol: protocolCode };

    } catch (error) {
        console.error('Upload error:', error);
        return { error: 'Erro ao processar o arquivo. Tente novamente.' };
    }
}
