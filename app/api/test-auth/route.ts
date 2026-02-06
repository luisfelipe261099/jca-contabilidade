import { NextResponse } from 'next/server';
import prisma from '@/lib/prisma';
import bcrypt from 'bcryptjs';

export async function POST(request: Request) {
    try {
        const { email, password } = await request.json();

        console.log('🔍 Testing login for:', email);

        // Find user
        const user = await prisma.user.findUnique({
            where: { email }
        });

        if (!user) {
            return NextResponse.json({
                success: false,
                error: 'User not found',
                email
            }, { status: 404 });
        }

        // Test password
        const passwordMatch = await bcrypt.compare(password, user.password);

        return NextResponse.json({
            success: passwordMatch,
            user: {
                email: user.email,
                name: user.name,
                role: user.role
            },
            passwordMatch,
            passwordHashPreview: user.password.substring(0, 20) + '...'
        });

    } catch (error: any) {
        console.error('Test login error:', error);
        return NextResponse.json({
            success: false,
            error: error.message
        }, { status: 500 });
    }
}
