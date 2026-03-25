import type { MetadataRoute } from 'next';
import { siteUrl } from '@/lib/site-url';

export default function sitemap(): MetadataRoute.Sitemap {
    const now = new Date();

    return [
        {
            url: `${siteUrl}/`,
            lastModified: now,
            changeFrequency: 'weekly',
            priority: 1,
        },
        {
            url: `${siteUrl}/apoio`,
            lastModified: now,
            changeFrequency: 'weekly',
            priority: 0.8,
        },
        {
            url: `${siteUrl}/google1259a4f2911dc151.html`,
            lastModified: now,
            changeFrequency: 'yearly',
            priority: 0.1,
        },
        {
            url: `${siteUrl}/google1259a412911dc151.html`,
            lastModified: now,
            changeFrequency: 'yearly',
            priority: 0.1,
        },
    ];
}
