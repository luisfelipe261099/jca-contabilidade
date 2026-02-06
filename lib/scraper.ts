import puppeteer from 'puppeteer-core';
import { existsSync } from 'fs';

const CHROME_PATHS = [
    'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe',
    'C:\\Program Files (x86)\\Google\\Chrome\\Application\\chrome.exe',
    'C:\\Program Files (x86)\\Microsoft\\Edge\\Application\\msedge.exe',
    '/usr/bin/google-chrome',
    '/usr/bin/chromium-browser',
    '/usr/bin/chromium',
];

export async function getBrowser() {
    let executablePath = '';

    // If running on Vercel/Production, we might need a different strategy 
    // but for now we search common paths.
    for (const path of CHROME_PATHS) {
        if (existsSync(path)) {
            executablePath = path;
            break;
        }
    }

    if (!executablePath && process.env.NODE_ENV === 'production') {
        // Fallback or specific error for Vercel environment
        console.warn('Alerta: Navegador nativo não encontrado no ambiente de produção do Vercel.');
    }

    return await puppeteer.launch({
        executablePath,
        headless: true, // Use false for debugging
        args: ['--no-sandbox', '--disable-setuid-sandbox'],
    });
}

export async function solveCaptcha(page: any) {
    // Placeholder for future CAPTCHA solving logic if needed
    // For now, we hope the simple government sites don't block us immediately
    console.log('Captcha logic placeholder');
}
