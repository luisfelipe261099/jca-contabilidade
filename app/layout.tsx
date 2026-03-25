import type { Metadata } from "next";
import { Montserrat, Nunito_Sans } from "next/font/google";
import "./globals.css";

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
    title: "Jade Cristina Contabilidade | Soluções Contábeis Digitais",
    description: "Contabilidade consultiva e digital para empresas que querem crescer com segurança.",
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
