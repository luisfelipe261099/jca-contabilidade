"use server";

import { PrismaClient } from "@prisma/client";

const prisma = new PrismaClient();

export async function getClienteDetalhes(id: number) {
    const cliente = await prisma.cliente.findUnique({
        where: { id },
        include: {
            contador: {
                select: {
                    nome: true,
                    email: true,
                },
            },
        },
    });

    if (!cliente) return null;

    const [
        totalFuncionarios,
        tarefasPendentes,
        totalDocumentos,
        totalCertificados
    ] = await Promise.all([
        prisma.funcionarioCliente.count({ where: { cliente_id: id, status: "Ativo" } }),
        prisma.tarefa.count({ where: { cliente_id: id, NOT: { status: "Concluida" } } }),
        prisma.documento.count({ where: { cliente_id: id } }),
        prisma.certificadoDigital.count({ where: { cliente_id: id, status: "Ativo" } }),
    ]);

    return {
        cliente,
        stats: {
            totalFuncionarios,
            tarefasPendentes,
            totalDocumentos,
            totalCertificados,
        },
    };
}
