import NextAuth from 'next-auth';
import { authConfig } from './auth.config';
import Credentials from 'next-auth/providers/credentials';
import { z } from 'zod';
import prisma from './lib/prisma';
import bcrypt from 'bcryptjs';

export const { auth, signIn, signOut, handlers } = NextAuth({
    ...authConfig,
    callbacks: {
        async jwt({ token, user }: any) {
            if (user) {
                token.role = user.role;
                token.clientId = user.clientId;
                token.department = user.department;
            }
            return token;
        },
        async session({ session, token }: any) {
            if (token) {
                session.user.role = token.role;
                session.user.clientId = token.clientId;
                session.user.department = token.department;
            }
            return session;
        },
    },
    providers: [
        Credentials({
            async authorize(credentials) {
                console.log('🔐 [AUTH] Starting authorization...');
                console.log('🔐 [AUTH] Credentials received:', {
                    email: credentials?.email,
                    hasPassword: !!credentials?.password
                });

                const parsedCredentials = z
                    .object({ email: z.string().email(), password: z.string().min(6) })
                    .safeParse(credentials);

                console.log('🔐 [AUTH] Validation result:', {
                    success: parsedCredentials.success,
                    errors: parsedCredentials.success ? null : parsedCredentials.error.issues
                });

                if (parsedCredentials.success) {
                    const { email, password } = parsedCredentials.data;
                    console.log('🔐 [AUTH] Looking for user:', email);

                    const user = await prisma.user.findUnique({ where: { email } });
                    console.log('🔐 [AUTH] User found:', !!user);

                    if (!user) {
                        console.log('❌ [AUTH] User not found in database');
                        return null;
                    }

                    console.log('🔐 [AUTH] Comparing passwords...');
                    const passwordsMatch = await bcrypt.compare(password, user.password);
                    console.log('🔐 [AUTH] Password match:', passwordsMatch);

                    if (passwordsMatch) {
                        console.log('✅ [AUTH] Login successful for:', email);
                        return user;
                    } else {
                        console.log('❌ [AUTH] Password mismatch');
                    }
                }

                console.log('❌ [AUTH] Authorization failed');
                return null;
            },
        }),
    ],
});
