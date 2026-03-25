function normalizeUrl(rawUrl: string): string {
    const withProtocol = rawUrl.startsWith('http://') || rawUrl.startsWith('https://')
        ? rawUrl
        : `https://${rawUrl}`;

    return withProtocol.replace(/\/$/, '');
}

const fallbackUrl = 'http://localhost:3000';

export const siteUrl = normalizeUrl(
    process.env.NEXT_PUBLIC_SITE_URL ||
    process.env.NEXT_PUBLIC_APP_URL ||
    process.env.VERCEL_PROJECT_PRODUCTION_URL ||
    process.env.VERCEL_URL ||
    fallbackUrl
);
