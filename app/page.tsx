import React from 'react';
import type { Metadata } from 'next';
import Header from '../components/Header';
import Hero from '../components/Hero';
import About from '../components/About';
import Services from '../components/Services';
import Contact from '../components/Contact';
import Footer from '../components/Footer';

const siteUrl = process.env.NEXT_PUBLIC_SITE_URL || 'http://localhost:3000';

export const metadata: Metadata = {
    title: 'Contabilidade em Curitiba para Empresas e MEI',
    description: 'Jade Cristina Contabilidade: abertura de empresa, imposto de renda, departamento pessoal, fiscal, BPO financeiro e consultoria tributaria em Curitiba.',
    alternates: {
        canonical: '/',
    },
};

export default function Home() {
    const jsonLd = {
        '@context': 'https://schema.org',
        '@type': 'AccountingService',
        name: 'Jade Cristina Contabilidade',
        url: siteUrl,
        telephone: '+55-41-98858-4456',
        email: 'jadycris@yahoo.com.br',
        address: {
            '@type': 'PostalAddress',
            addressLocality: 'Curitiba',
            addressRegion: 'PR',
            addressCountry: 'BR',
        },
        areaServed: 'Brasil',
        sameAs: ['https://www.instagram.com/jc_contabilidade_cwb/'],
        serviceType: [
            'Imposto de renda pessoa fisica e juridica',
            'Abertura e regularizacao de empresas',
            'Contabilidade consultiva',
            'Planejamento tributario',
            'Departamento pessoal e folha de pagamento',
            'BPO financeiro',
            'Assessoria fiscal e societaria',
        ],
    };

    return (
        <main className="min-h-screen text-[#163244] selection:bg-cyan-200/70">
            <script
                type="application/ld+json"
                dangerouslySetInnerHTML={{ __html: JSON.stringify(jsonLd) }}
            />
            <Header />
            <Hero />
            <About />
            <Services />
            <Contact />
            <Footer />
        </main>
    );
}
