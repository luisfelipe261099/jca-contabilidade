import puppeteer from 'puppeteer-core';
import { existsSync } from 'fs';

const CHROME_PATHS = [
    'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe',
    'C:\\Program Files (x86)\\Google\\Chrome\\Application\\chrome.exe',
    'C:\\Program Files (x86)\\Microsoft\\Edge\\Application\\msedge.exe',
];

export async function getBrowser() {
    let executablePath = '';

    for (const path of CHROME_PATHS) {
        if (existsSync(path)) {
            executablePath = path;
            break;
        }
    }

    if (!executablePath) {
        throw new Error('Nenhum navegador (Chrome ou Edge) encontrado no servidor.');
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
