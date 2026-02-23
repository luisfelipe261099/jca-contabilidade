'use server';

import prisma from '@/lib/prisma';
import { revalidatePath } from 'next/cache';
import bcrypt from 'bcryptjs';

// ===================== TAXES (Fiscal) =====================
export async function createTax(formData: FormData) {
    try {
        const name = formData.get('name') as string;
        const amount = parseFloat(formData.get('amount') as string);
        const dueDate = new Date(formData.get('dueDate') as string);
        const clientId = formData.get('clientId') as string;
        const status = (formData.get('status') as string) || 'PENDING';

        await prisma.tax.create({
            data: { name, amount, dueDate, status, clientId }
        });

        revalidatePath('/admin/fiscal');
        return { success: true };
    } catch (error: any) {
        console.error('Create tax error:', error);
        return { error: 'Falha ao cadastrar imposto: ' + error.message };
    }
}

export async function deleteTax(id: string) {
    try {
        await prisma.tax.delete({ where: { id } });
        revalidatePath('/admin/fiscal');
        return { success: true };
    } catch (error: any) {
        return { error: 'Falha ao excluir: ' + error.message };
    }
}

export async function updateTaxStatus(id: string, status: string) {
    try {
        await prisma.tax.update({ where: { id }, data: { status } });
        revalidatePath('/admin/fiscal');
        return { success: true };
    } catch (error: any) {
        return { error: 'Falha ao atualizar status: ' + error.message };
    }
}

// ===================== EMPLOYEES (DP) =====================
export async function createEmployee(formData: FormData) {
    try {
        const name = formData.get('name') as string;
        const role = formData.get('role') as string;
        const admissionDate = new Date(formData.get('admissionDate') as string);
        const salary = parseFloat(formData.get('salary') as string);
        const clientId = formData.get('clientId') as string;
        const status = (formData.get('status') as string) || 'ACTIVE';

        await prisma.employee.create({
            data: { name, role, admissionDate, salary, status, clientId }
        });

        revalidatePath('/admin/dp');
        return { success: true };
    } catch (error: any) {
        console.error('Create employee error:', error);
        return { error: 'Falha ao cadastrar funcionário: ' + error.message };
    }
}

export async function deleteEmployee(id: string) {
    try {
        await prisma.employee.delete({ where: { id } });
        revalidatePath('/admin/dp');
        return { success: true };
    } catch (error: any) {
        return { error: 'Falha ao excluir: ' + error.message };
    }
}

export async function updateEmployeeStatus(id: string, status: string) {
    try {
        await prisma.employee.update({ where: { id }, data: { status } });
        revalidatePath('/admin/dp');
        return { success: true };
    } catch (error: any) {
        return { error: 'Falha ao atualizar: ' + error.message };
    }
}

// ===================== LEGAL PROCESSES (Societário) =====================
export async function createLegalProcess(formData: FormData) {
    try {
        const title = formData.get('title') as string;
        const description = (formData.get('description') as string) || null;
        const clientId = formData.get('clientId') as string;
        const stage = (formData.get('stage') as string) || null;
        const status = (formData.get('status') as string) || 'OPEN';

        await prisma.legalProcess.create({
            data: { title, description, status, stage, clientId }
        });

        revalidatePath('/admin/societario');
        return { success: true };
    } catch (error: any) {
        console.error('Create legal process error:', error);
        return { error: 'Falha ao cadastrar processo: ' + error.message };
    }
}

export async function deleteLegalProcess(id: string) {
    try {
        await prisma.legalProcess.delete({ where: { id } });
        revalidatePath('/admin/societario');
        return { success: true };
    } catch (error: any) {
        return { error: 'Falha ao excluir: ' + error.message };
    }
}

export async function updateLegalProcessStatus(id: string, status: string) {
    try {
        await prisma.legalProcess.update({ where: { id }, data: { status } });
        revalidatePath('/admin/societario');
        return { success: true };
    } catch (error: any) {
        return { error: 'Falha ao atualizar: ' + error.message };
    }
}

// ===================== FINANCIAL RECORDS (Financeiro) =====================
export async function createFinancialRecord(formData: FormData) {
    try {
        const description = formData.get('description') as string;
        const amount = parseFloat(formData.get('amount') as string);
        const dueDate = new Date(formData.get('dueDate') as string);
        const type = (formData.get('type') as string) || 'INCOME';
        const status = (formData.get('status') as string) || 'PENDING';
        const clientId = (formData.get('clientId') as string) || null;

        await prisma.financialRecord.create({
            data: { description, amount, type, dueDate, status, clientId }
        });

        revalidatePath('/admin/financeiro');
        return { success: true };
    } catch (error: any) {
        console.error('Create financial record error:', error);
        return { error: 'Falha ao cadastrar registro: ' + error.message };
    }
}

export async function deleteFinancialRecord(id: string) {
    try {
        await prisma.financialRecord.delete({ where: { id } });
        revalidatePath('/admin/financeiro');
        return { success: true };
    } catch (error: any) {
        return { error: 'Falha ao excluir: ' + error.message };
    }
}

export async function updateFinancialRecordStatus(id: string, status: string) {
    try {
        await prisma.financialRecord.update({ where: { id }, data: { status } });
        revalidatePath('/admin/financeiro');
        return { success: true };
    } catch (error: any) {
        return { error: 'Falha ao atualizar: ' + error.message };
    }
}

// ===================== PROTOCOLS =====================
export async function createProtocol(formData: FormData) {
    try {
        const type = formData.get('type') as string;
        const description = formData.get('description') as string;
        const clientId = formData.get('clientId') as string;

        // Generate protocol code
        const year = new Date().getFullYear();
        const count = await prisma.protocol.count();
        const code = `PRT-${year}-${String(count + 1).padStart(3, '0')}`;

        await prisma.protocol.create({
            data: { code, type, description, clientId }
        });

        revalidatePath('/admin/protocolos');
        return { success: true };
    } catch (error: any) {
        console.error('Create protocol error:', error);
        return { error: 'Falha ao criar protocolo: ' + error.message };
    }
}

export async function confirmProtocol(id: string) {
    try {
        await prisma.protocol.update({
            where: { id },
            data: { receivedAt: new Date() }
        });
        revalidatePath('/admin/protocolos');
        return { success: true };
    } catch (error: any) {
        return { error: 'Falha ao confirmar: ' + error.message };
    }
}

// ===================== TICKETS =====================
export async function createTicket(formData: FormData) {
    try {
        const title = formData.get('title') as string;
        const description = formData.get('description') as string;
        const clientId = formData.get('clientId') as string;
        const priority = (formData.get('priority') as string) || 'NORMAL';

        await prisma.ticket.create({
            data: { title, description, clientId, priority }
        });

        revalidatePath('/admin/dashboard');
        revalidatePath('/client/requests');
        return { success: true };
    } catch (error: any) {
        console.error('Create ticket error:', error);
        return { error: 'Falha ao criar ticket: ' + error.message };
    }
}

export async function updateTicketStatus(id: string, status: string) {
    try {
        await prisma.ticket.update({ where: { id }, data: { status } });
        revalidatePath('/client/requests');
        return { success: true };
    } catch (error: any) {
        return { error: 'Falha ao atualizar: ' + error.message };
    }
}

// ===================== USER MANAGEMENT =====================
export async function createUser(formData: FormData) {
    try {
        const email = formData.get('email') as string;
        const name = formData.get('name') as string;
        const password = formData.get('password') as string;
        const role = formData.get('role') as string;
        const clientId = (formData.get('clientId') as string) || null;
        const department = (formData.get('department') as string) || 'GERAL';

        // Check if user already exists
        const existing = await prisma.user.findUnique({ where: { email } });
        if (existing) {
            return { error: 'Já existe um usuário com este e-mail.' };
        }

        if (password.length < 6) {
            return { error: 'A senha deve ter no mínimo 6 caracteres.' };
        }

        const hashedPassword = await bcrypt.hash(password, 10);

        await prisma.user.create({
            data: {
                email,
                name,
                password: hashedPassword,
                role: role as any,
                department: role === 'EMPLOYEE' ? department as any : 'GERAL',
                clientId: clientId || undefined,
            }
        });

        revalidatePath('/admin/users');
        revalidatePath('/admin/dashboard');
        return { success: true };
    } catch (error: any) {
        console.error('Create user error:', error);
        return { error: 'Falha ao criar usuário: ' + error.message };
    }
}

export async function deleteUser(id: string) {
    try {
        await prisma.user.delete({ where: { id } });
        revalidatePath('/admin/users');
        return { success: true };
    } catch (error: any) {
        return { error: 'Falha ao excluir: ' + error.message };
    }
}

export async function resetUserPassword(id: string, newPassword: string) {
    try {
        if (newPassword.length < 6) {
            return { error: 'A senha deve ter no mínimo 6 caracteres.' };
        }
        const hashedPassword = await bcrypt.hash(newPassword, 10);
        await prisma.user.update({
            where: { id },
            data: { password: hashedPassword }
        });
        revalidatePath('/admin/users');
        return { success: true };
    } catch (error: any) {
        return { error: 'Falha ao resetar senha: ' + error.message };
    }
}

// ===================== TASKS =====================
export async function createTask(formData: FormData) {
    try {
        const title = formData.get('title') as string;
        const description = (formData.get('description') as string) || undefined;
        const category = formData.get('category') as string;
        const dueDate = new Date(formData.get('dueDate') as string);
        const clientId = formData.get('clientId') as string;

        await prisma.task.create({
            data: { title, description, category, dueDate, clientId }
        });

        revalidatePath('/admin/dashboard');
        return { success: true };
    } catch (error: any) {
        console.error('Create task error:', error);
        return { error: 'Falha ao criar tarefa: ' + error.message };
    }
}
