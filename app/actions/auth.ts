'use server';

import { auth } from '@/auth';
import prisma from '@/lib/prisma';
import bcrypt from 'bcryptjs';
import { revalidatePath } from 'next/cache';

export async function changePassword(formData: FormData) {
    try {
        const session = await auth();
        if (!session?.user?.email) {
            return { success: false, error: 'Não autorizado.' };
        }

        const currentPassword = formData.get('currentPassword') as string;
        const newPassword = formData.get('newPassword') as string;
        const confirmPassword = formData.get('confirmPassword') as string;

        if (newPassword !== confirmPassword) {
            return { success: false, error: 'As senhas não coincidem.' };
        }

        if (newPassword.length < 6) {
            return { success: false, error: 'A nova senha deve ter pelo menos 6 caracteres.' };
        }

        const user = await prisma.user.findUnique({
            where: { email: session.user.email },
        });

        if (!user) {
            return { success: false, error: 'Usuário não encontrado.' };
        }

        const passwordsMatch = await bcrypt.compare(currentPassword, user.password);
        if (!passwordsMatch) {
            return { success: false, error: 'Senha atual incorreta.' };
        }

        const hashedNewPassword = await bcrypt.hash(newPassword, 10);

        await prisma.user.update({
            where: { email: session.user.email },
            data: { password: hashedNewPassword },
        });

        revalidatePath('/admin/settings');
        return { success: true, message: 'Senha alterada com sucesso!' };
    } catch (error: any) {
        console.error('Change Password Error:', error);
        return { success: false, error: 'Erro ao alterar senha: ' + error.message };
    }
}
