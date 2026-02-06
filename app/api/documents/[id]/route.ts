import { NextRequest, NextResponse } from 'next/server';
import prisma from '@/lib/prisma';
import { auth } from '@/auth';

export async function GET(
    request: NextRequest,
    { params }: { params: { id: string } }
) {
    const session = await auth();
    if (!session) {
        return new NextResponse('Não autorizado', { status: 401 });
    }

    const document = await prisma.document.findUnique({
        where: { id: params.id },
    });

    if (!document || !document.content) {
        return new NextResponse('Documento não encontrado', { status: 404 });
    }

    // Return the binary content as a PDF
    return new NextResponse(document.content, {
        headers: {
            'Content-Type': 'application/pdf',
            'Content-Disposition': `inline; filename="${document.name}.pdf"`,
        },
    });
}
