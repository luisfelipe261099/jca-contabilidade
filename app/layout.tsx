import type { Metadata } from "next";
import { Montserrat, Nunito_Sans } from "next/font/google";
import "./globals.css";

const siteUrl = process.env.NEXT_PUBLIC_SITE_URL || "http://localhost:3000";

const montserrat = Montserrat({
    subsets: ["latin"],
    variable: "--font-display",
    weight: ["600", "700", "800", "900"],
});

const nunitoSans = Nunito_Sans({
    subsets: ["latin"],
    variable: "--font-body",
    weight: ["400", "500", "600", "700"],
});

export const metadata: Metadata = {
    metadataBase: new URL(siteUrl),
    title: {
        default: "Jade Cristina Contabilidade | Solucoes Contabeis Digitais",
        template: "%s | Jade Cristina Contabilidade",
    },
    description: "Contabilidade digital e consultiva em Curitiba: abertura de empresa, imposto de renda, folha de pagamento, BPO financeiro, fiscal e societario.",
    keywords: [
        "contabilidade em curitiba",
        "escritorio de contabilidade",
        "contabilidade digital",
        "imposto de renda",
        "imposto de renda pessoa fisica",
        "imposto de renda pessoa juridica",
        "abertura de empresa",
        "mei",
        "planejamento tributario",
        "folha de pagamento",
        "bpo financeiro",
        "departamento fiscal",
        "contabilidade consultiva",
    ],
    alternates: {
        canonical: "/",
    },
    openGraph: {
        type: "website",
        url: "/",
        title: "Jade Cristina Contabilidade",
        description: "Solucoes contabeis digitais para empresas: imposto de renda, fiscal, contabil, societario e financeiro.",
        siteName: "Jade Cristina Contabilidade",
        locale: "pt_BR",
    },
    twitter: {
        card: "summary_large_image",
        title: "Jade Cristina Contabilidade",
        description: "Contabilidade digital em Curitiba para empresas e profissionais.",
    },
    robots: {
        index: true,
        follow: true,
        googleBot: {
            index: true,
            follow: true,
            "max-snippet": -1,
            "max-image-preview": "large",
            "max-video-preview": -1,
        },
    },
    verification: {
        google: process.env.GOOGLE_SITE_VERIFICATION,
    },
    category: "business",
};

export default function RootLayout({
    children,
}: Readonly<{
    children: React.ReactNode;
}>) {
    return (
        <html lang="pt-BR">
            <body className={`${montserrat.variable} ${nunitoSans.variable} font-body antialiased`}>{children}</body>
        </html>
    );
}
