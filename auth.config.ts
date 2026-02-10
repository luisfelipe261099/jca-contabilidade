import type { NextAuthConfig } from 'next-auth';

export const authConfig = {
    pages: {
        signIn: '/admin/login',
    },
    callbacks: {
        authorized({ auth, request: { nextUrl } }) {
            const isLoggedIn = !!auth?.user;
            const userRole = (auth?.user as any)?.role; // Casting to any because User type might not have role yet in types

            const isOnAdmin = nextUrl.pathname.startsWith('/admin');
            const isOnClient = nextUrl.pathname.startsWith('/client');
            const isOnLogin = nextUrl.pathname === '/admin/login';

            // 1. Se não estiver logado
            if (!isLoggedIn) {
                // Permite acesso apenas à página de login
                if (isOnLogin) return true;
                // Qualquer outra página privada redireciona para login
                if (isOnAdmin || isOnClient) return false; // Vai para página de login automaticamente
                return true; // Páginas públicas (home, etc)
            }

            // 2. Se estiver logado
            if (isLoggedIn) {
                // Se estiver na página de login, manda para o dashboard correto
                if (isOnLogin) {
                    if (userRole === 'CLIENT') return Response.redirect(new URL('/client/dashboard', nextUrl));
                    return Response.redirect(new URL('/admin/dashboard', nextUrl));
                }

                // Proteção de Rotas de ADMIN
                if (isOnAdmin) {
                    // Se for cliente tentando acessar admin -> Manda para client dashboard
                    if (userRole === 'CLIENT') {
                        return Response.redirect(new URL('/client/dashboard', nextUrl));
                    }
                    // Se for admin/employee -> OK
                    return true;
                }

                // Proteção de Rotas de CLIENT
                if (isOnClient) {
                    // Se for admin tentando acessar area de cliente -> Manda para admin dashboard
                    // (Opcional: permitir admin ver area de cliente, mas por segurança vamos separar por enquanto)
                    if (userRole !== 'CLIENT') {
                        return Response.redirect(new URL('/admin/dashboard', nextUrl));
                    }
                    // Se for cliente -> OK
                    return true;
                }
            }

            return true;
        },
    },
    providers: [], // Add providers with an empty array for now
} satisfies NextAuthConfig;
