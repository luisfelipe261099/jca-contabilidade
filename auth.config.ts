import type { NextAuthConfig } from 'next-auth';

export const authConfig = {
    pages: {
        signIn: '/admin/login',
    },
    callbacks: {
        authorized({ auth, request: { nextUrl } }) {
            const isLoggedIn = !!auth?.user;
            const userRole = (auth?.user as any)?.role;
            const isOnAdmin = nextUrl.pathname.startsWith('/admin');
            const isOnClient = nextUrl.pathname.startsWith('/client');

            if (isOnAdmin) {
                if (isLoggedIn && userRole !== 'CLIENT') return true;
                return false;
            } else if (isOnClient) {
                if (isLoggedIn && userRole === 'CLIENT') return true;
                return false;
            } else if (isLoggedIn) {
                if (userRole === 'CLIENT') {
                    return Response.redirect(new URL('/client/dashboard', nextUrl));
                }
                return Response.redirect(new URL('/admin/dashboard', nextUrl));
            }
            return true;
        },
    },
    providers: [], // Add providers with an empty array for now
} satisfies NextAuthConfig;
