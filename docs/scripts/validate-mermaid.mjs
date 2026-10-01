import { JSDOM } from 'jsdom';
import fs from 'fs';
import path from 'path';

const dom = new JSDOM('<!DOCTYPE html><html><body><div id="container"></div></body></html>', {
  url: 'http://localhost/',
  pretendToBeVisual: true,
});

globalThis.window = dom.window;
globalThis.document = dom.window.document;
try {
  Object.defineProperty(globalThis, 'navigator', {
    value: dom.window.navigator,
    configurable: true,
    writable: true,
  });
} catch (e) {
  // Ignore if already defined
}

if (!globalThis.CSSStyleSheet) {
  globalThis.CSSStyleSheet = dom.window.CSSStyleSheet || class CSSStyleSheet {
    constructor() { this.cssRules = []; }
    insertRule(rule, index) { this.cssRules.splice(index || 0, 0, rule); return index || 0; }
    replaceSync(text) {}
    replace(text) { return Promise.resolve(this); }
  };
}

// Mock required browser SVG / canvas methods if needed by mermaid
if (!dom.window.SVGElement.prototype.getBBox) {
  dom.window.SVGElement.prototype.getBBox = () => ({
    x: 0,
    y: 0,
    width: 100,
    height: 100,
  });
}

const { default: mermaid } = await import('mermaid');

mermaid.initialize({
  startOnLoad: false,
  securityLevel: 'loose',
  theme: 'default',
});

function findMdFiles(dir, fileList = []) {
  const files = fs.readdirSync(dir);
  for (const file of files) {
    const filePath = path.join(dir, file);
    const stat = fs.statSync(filePath);
    if (stat.isDirectory()) {
      if (file !== 'node_modules' && file !== '.vitepress' && file !== 'dist') {
        findMdFiles(filePath, fileList);
      }
    } else if (file.endsWith('.md')) {
      fileList.push(filePath);
    }
  }
  return fileList;
}

const mdFiles = findMdFiles('.');
console.log(`Found ${mdFiles.length} Markdown files to check.\n`);

let totalBlocks = 0;
let failedBlocks = 0;

for (const filePath of mdFiles) {
  const content = fs.readFileSync(filePath, 'utf-8');
  const matches = [...content.matchAll(/```mermaid\r?\n([\s\S]*?)```/g)];

  if (matches.length === 0) continue;

  console.log(`📄 Checking: ${filePath} (${matches.length} mermaid block${matches.length > 1 ? 's' : ''})`);

  for (let idx = 0; idx < matches.length; idx++) {
    totalBlocks++;
    const code = matches[idx][1].trim();
    const id = `test-diagram-${totalBlocks}`;

    try {
      await mermaid.render(id, code);
      console.log(`  ✅ Block #${idx + 1}: Render OK`);
    } catch (err) {
      failedBlocks++;
      console.error(`  ❌ Block #${idx + 1} FAILED:`, err.message || err);
      console.error(`     Raw Code Snippet:`);
      console.error(code.split('\n').map(l => '       | ' + l).join('\n'));
    }
  }
}

console.log(`\n================================`);
console.log(`Summary: ${totalBlocks} diagrams scanned, ${failedBlocks} failed.`);
console.log(`================================`);

if (failedBlocks > 0) {
  process.exit(1);
} else {
  console.log('🎉 All Mermaid diagrams are 100% valid and parseable by Mermaid!');
}
