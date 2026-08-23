// Capture des pages du site public en screenshot plein format, via Playwright.
// Usage : node .claude/skills/screenshot-site/scripts/screenshot.mjs [outDir] [baseUrl] [path1 path2 ...]
//   outDir  (optionnel) dossier de sortie, défaut: .claude/skills/screenshot-site/screenshots
//   baseUrl (optionnel) défaut: http://127.0.0.1:8123 (ou $SCREENSHOT_BASE_URL)
//   paths   (optionnel) liste de chemins à capturer ; défaut : pages publiques principales
//
// IMPORTANT : ce script doit être exécuté avec `node` depuis un répertoire
// où `playwright` est résolvable (la racine du projet, où il est installé
// en devDependency) — sinon Node ne trouve pas le module ESM.

import { chromium } from 'playwright';
import path from 'node:path';
import fs from 'node:fs';

const DEFAULT_PAGES = [
    { path: '/', name: 'accueil' },
    { path: '/formations', name: 'formations-liste' },
    { path: '/actualites', name: 'actualites-liste' },
    { path: '/qui-sommes-nous', name: 'a-propos' },
    { path: '/contact', name: 'contact' },
    { path: '/inscription', name: 'inscription' },
];

const args = process.argv.slice(2);
const outDir = args[0] || path.join('.claude', 'skills', 'screenshot-site', 'screenshots');
const baseUrl = args[1] || process.env.SCREENSHOT_BASE_URL || 'http://127.0.0.1:8123';
const extraPaths = args.slice(2);

const pages = extraPaths.length
    ? extraPaths.map((p) => ({ path: p, name: p.replace(/^\/|\/$/g, '').replace(/\//g, '-') || 'accueil' }))
    : DEFAULT_PAGES;

fs.mkdirSync(outDir, { recursive: true });

const browser = await chromium.launch({ args: ['--no-sandbox'] });
const context = await browser.newContext({ viewport: { width: 1440, height: 900 } });
const page = await context.newPage();

const errors = [];
page.on('console', (msg) => { if (msg.type() === 'error') errors.push(`[console] ${msg.text()}`); });
page.on('pageerror', (err) => errors.push(`[pageerror] ${err.message}`));

for (const { path: p, name } of pages) {
    const url = baseUrl + p;
    console.log(`-> ${url}`);
    await page.goto(url, { waitUntil: 'networkidle', timeout: 20000 });
    const file = path.join(outDir, `${name}.png`);
    await page.screenshot({ path: file, fullPage: true });
    console.log(`   ${file}`);
}

await browser.close();

console.log('\n--- erreurs console/page ---');
console.log(errors.length ? errors.join('\n') : '(aucune)');
