"use server";

import { PrismaClient, Prisma, RegimeTributario, StatusContrato } from "@prisma/client";

const prisma = new PrismaClient();

export async function getClientes({
    search = "",
    regime = "",
    status = "",
    page = 1,
    limit = 20,
}: {
    search?: string;
    regime?: string;
    status?: string;
    page?: number;
    limit?: number;
}) {
    const skip = (page - 1) * limit;

    const where: Prisma.ClienteWhereInput = {
        ativo: true,
    };

    if (search) {
        where.OR = [
            { razao_social: { contains: search } },
            { nome_fantasia: { contains: search } },
            { cnpj: { contains: search } },
        ];
    }

    if (regime) {
        where.regime_tributario = regime as any;
    }

    if (status) {
        where.status_contrato = status as any;
    }

    const [clientes, total] = await Promise.all([
        prisma.cliente.findMany({
            where,
            include: {
                contador: {
                    select: {
                        nome: true,
                    },
                },
            },
            orderBy: {
                razao_social: "asc",
            },
            skip,
            take: limit,
        }),
        prisma.cliente.count({ where }),
    ]);

    return {
        clientes,
        total,
        totalPages: Math.ceil(total / limit),
    };
}

export async function getClientStats() {
    const [total, ativos, inadimplentes] = await Promise.all([
        prisma.cliente.count({ where: { ativo: true } }),
        prisma.cliente.count({ where: { status_contrato: "Ativo", ativo: true } }),
        prisma.cliente.count({ where: { status_contrato: "Inadimplente", ativo: true } }),
    ]);

    return {
        total,
        ativos,
        inadimplentes,
    };
}
