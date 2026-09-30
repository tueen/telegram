import fs from 'node:fs'
import path from 'node:path'
import { fileURLToPath } from 'node:url'
import JSZip from 'jszip'

const __dirname = path.dirname(fileURLToPath(import.meta.url))
const docsDir = path.resolve(__dirname, '..')
const guideDir = path.resolve(docsDir, 'guide')
const publicDir = path.resolve(docsDir, 'public')
const llmsOutputFile = path.resolve(publicDir, 'llms.txt')
const llmsFullOutputFile = path.resolve(publicDir, 'llms-full.txt')
const zipOutputFile = path.resolve(publicDir, 'tueen-telegram-bot-api-sdk.zip')
const llmsPageFile = path.resolve(docsDir, 'llms.md')

// Determine canonical online documentation site URL
export function getCanonicalSiteUrl() {
  if (process.env.DOCS_URL) return process.env.DOCS_URL.replace(/\/$/, '')
  if (process.env.SITE_URL) return process.env.SITE_URL.replace(/\/$/, '')

  const cnameFile = path.resolve(publicDir, 'CNAME')
  if (fs.existsSync(cnameFile)) {
    const cname = fs.readFileSync(cnameFile, 'utf-8').trim()
    if (cname) return `https://${cname}`
  }

  if (process.env.GITHUB_REPOSITORY) {
    const [owner, repo] = process.env.GITHUB_REPOSITORY.split('/')
    if (repo && owner) {
      if (repo.toLowerCase() === `${owner.toLowerCase()}.github.io`) {
        return `https://${owner}.github.io`
      }
      return `https://${owner}.github.io/${repo}`
    }
  }

  return 'https://tueen.github.io/telegram'
}

const siteUrl = getCanonicalSiteUrl()

// Helper to slugify headings to match VitePress anchor IDs
function slugify(text) {
  return text
    .trim()
    .toLowerCase()
    .replace(/<[^>]+>/g, '') // remove HTML tags
    .replace(/[^\w\s-]/g, '') // remove non-word chars except space & hyphen
    .replace(/\s+/g, '-') // spaces to hyphen
    .replace(/--+/g, '-') // collapse hyphens
    .replace(/^-+|-+$/g, '') // trim hyphens
}

// Ordered structure based on documentation sidebar
const docSections = [
  {
    category: 'Getting Started',
    files: [
      { file: 'getting-started.md', relLink: 'guide/getting-started' },
      { file: 'telegram-client.md', relLink: 'guide/telegram-client' },
      { file: 'configuration.md', relLink: 'guide/configuration' },
    ]
  },
  {
    category: 'Core Essentials',
    files: [
      { file: 'methods-and-types.md', relLink: 'guide/methods-and-types' },
      { file: 'keyboards.md', relLink: 'guide/keyboards' },
      { file: 'formatting.md', relLink: 'guide/formatting' },
      { file: 'file-upload-download.md', relLink: 'guide/file-upload-download' },
    ]
  },
  {
    category: 'Updates & Routing',
    files: [
      { file: 'running-modes.md', relLink: 'guide/running-modes' },
      { file: 'routing.md', relLink: 'guide/routing' },
      { file: 'update-and-message-helpers.md', relLink: 'guide/update-and-message-helpers' },
    ]
  },
  {
    category: 'Conversational Flows',
    files: [
      { file: 'flow/index.md', relLink: 'guide/flow/' },
      { file: 'flow/conversational-flows.md', relLink: 'guide/flow/conversational-flows' },
      { file: 'flow/interactive-screens.md', relLink: 'guide/flow/interactive-screens' },
      { file: 'flow/lifecycle-hooks.md', relLink: 'guide/flow/lifecycle-hooks' },
      { file: 'flow/keyboards-and-actions.md', relLink: 'guide/flow/keyboards-and-actions' },
      { file: 'flow/navigation-and-stack.md', relLink: 'guide/flow/navigation-and-stack' },
      { file: 'flow/state-storage.md', relLink: 'guide/flow/state-storage' },
    ]
  },
  {
    category: 'Application & Production',
    files: [
      { file: 'app.md', relLink: 'guide/app' },
      { file: 'pipeline-middleware.md', relLink: 'guide/pipeline-middleware' },
      { file: 'error-handling-and-hooks.md', relLink: 'guide/error-handling-and-hooks' },
      { file: 'testing.md', relLink: 'guide/testing' },
      { file: 'enums.md', relLink: 'guide/enums' },
    ]
  }
]

// Extract metadata and headings from markdown
function parseMarkdownDoc(filePath, relLink) {
  if (!fs.existsSync(filePath)) {
    return null
  }

  const rawContent = fs.readFileSync(filePath, 'utf-8')
  const lines = rawContent.split(/\r?\n/)

  let title = ''
  let description = ''
  const headings = []

  let inCodeBlock = false

  for (let i = 0; i < lines.length; i++) {
    const line = lines[i]

    if (line.trim().startsWith('```')) {
      inCodeBlock = !inCodeBlock
      continue
    }
    if (inCodeBlock) continue

    // Match H1
    if (!title) {
      const h1Match = line.match(/^#\s+(.+)$/)
      if (h1Match) {
        title = h1Match[1].trim()
        continue
      }
    }

    // Capture first substantive paragraph as description
    if (title && !description && line.trim() && !line.startsWith('#') && !line.startsWith('<') && !line.startsWith('---') && !line.startsWith('![')) {
      description = line.trim().replace(/[*_`]/g, '')
    }

    // Match H2 and H3
    const h2Match = line.match(/^##\s+(.+)$/)
    if (h2Match) {
      const headingText = h2Match[1].trim()
      headings.push({
        level: 2,
        text: headingText,
        anchor: slugify(headingText)
      })
      continue
    }

    const h3Match = line.match(/^###\s+(.+)$/)
    if (h3Match) {
      const headingText = h3Match[1].trim()
      headings.push({
        level: 3,
        text: headingText,
        anchor: slugify(headingText)
      })
    }
  }

  return {
    title: title || path.basename(filePath, '.md'),
    description,
    headings,
    rawContent,
    relLink,
    filePath
  }
}

// Generate the standalone SKILL.md packaged inside the zip
function generateSkillContent(parsedSections) {
  let skill = `---
name: tueen-telegram-bot-api-sdk
description: >-
  The official agent skill for tueen/telegram (The Royal Telegram Bot SDK for Modern PHP).
  Provides complete architectural guidelines, PHP 8.4+ standards, property hooks,
  asymmetric visibility, flows, and complete offline documentation guides.
---

# Tueen Telegram Bot API SDK Skill

Welcome to the **tueen/telegram** agent skill. This skill packages the complete offline documentation and coding standards for developing Telegram bots using modern PHP 8.4+.

---

## 👑 Library Identity & Tenets

- **Package Name:** \`tueen/telegram\`
- **Slogan:** *The Royal Telegram Bot SDK for Modern PHP*
- **Target Runtime:** PHP 8.4+ (strict types, property hooks, asymmetric visibility \`private(set)\`, pipeline pattern).
- **Coverage:** Complete Telegram Bot API (all methods, types, and property enums).
- **Bot API Version Constant:** \`Telegram::BOT_API_VERSION\`
- **Online Documentation Base URL:** ${siteUrl}/

---

## 💎 Core Architectural Rules for AI Agents

When writing, refactoring, or generating code using \`tueen/telegram\`, AI assistants MUST adhere to these rules:

1. **Strict Types:** Every file must start with \`declare(strict_types=1);\`.
2. **Asymmetric Visibility:** Always use clean \`private(set)\` across response types (never \`public private(set)\`):
   \`\`\`php
   private(set) int $id;
   private(set) ?string $username;
   \`\`\`
3. **Property Hooks:** Prefer modern PHP 8.4 property hooks over verbose getters/setters:
   \`\`\`php
   public string $fullName {
       get => trim(($this->firstName ?? '') . ' ' . ($this->lastName ?? ''));
   }
   \`\`\`
4. **Universal \`ok()\` Check:**
   All response objects inherit from base \`Type\` and supply \`$result->ok(): bool\` and \`$result->isOk(): bool\`.
5. **Dual Error Handling Modes:**
   - Default: Throws typed exceptions (\`TelegramException\`, \`RequestException\`, \`RateLimitException\`).
   - Non-throwing mode: Enabled via \`Telegram::create($token)->withErrorObjectMode()->build()\`, returns typed \`Error\` objects.
6. **Forward Compatibility:**
   Unknown future Telegram fields are captured in \`$extra\` and accessible via magic property access or \`ArrayAccess\`.
7. **Pipeline Pattern & Middleware:**
   All middlewares implement \`MiddlewareInterface\`. Handlers and updates are callable-friendly and support the PHP 8.5 Pipe Operator (\`|>\`).

---

## 📚 Complete Offline Documentation Map

All documentation guides are bundled alongside this skill in the \`./docs/\` folder:

`

  for (const sec of parsedSections) {
    skill += `\n### ${sec.category}\n`
    for (const item of sec.items) {
      const cleanRelPath = item.relLink.replace(/^guide\//, '')
      const localDocPath = cleanRelPath.endsWith('/')
        ? `docs/${cleanRelPath}index.md`
        : (cleanRelPath.endsWith('.md') ? `docs/${cleanRelPath}` : `docs/${cleanRelPath}.md`)

      skill += `- [${item.title}](./${localDocPath}): ${item.description || item.title}\n`
      if (item.headings.length > 0) {
        for (const h of item.headings) {
          const indent = h.level === 3 ? '    * ' : '  - '
          skill += `${indent}${h.text}\n`
        }
      }
    }
  }

  skill += `\n---\n*Generated automatically from the official tueen/telegram documentation suite.*\n`
  return skill
}

export async function generateLlms() {
  fs.mkdirSync(publicDir, { recursive: true })

  const parsedSections = []
  let fullConcatenatedContent = ''

  for (const section of docSections) {
    const items = []
    for (const item of section.files) {
      const absPath = path.resolve(guideDir, item.file)
      const parsed = parseMarkdownDoc(absPath, item.relLink)
      if (parsed) {
        items.push(parsed)

        // Clean raw content for llms-full.txt
        let cleanedContent = parsed.rawContent
          .replace(/<Mermaid\s+code="([^"]+)"\s*\/>/g, (_, code) => {
            return '```mermaid\n' + decodeURIComponent(code) + '\n```'
          })
          .replace(/<p align="center"[^>]*>[\s\S]*?<\/p>/g, '') // remove large logo HTML blocks

        fullConcatenatedContent += `\n\n# ==============================================================================\n`
        fullConcatenatedContent += `# File: /${parsed.relLink}\n`
        fullConcatenatedContent += `# Section: ${section.category} > ${parsed.title}\n`
        fullConcatenatedContent += `# ==============================================================================\n\n`
        fullConcatenatedContent += cleanedContent.trim() + '\n'
      }
    }
    parsedSections.push({
      category: section.category,
      items
    })
  }

  // 1. Build llms.txt (llmstxt.org specification with Base URL definition)
  let llmsTxt = `# tueen/telegram
> The Royal Telegram Bot SDK for Modern PHP (PHP 8.4+)

Package Name: tueen/telegram
Base URL: ${siteUrl}/
Documentation Root: ${siteUrl}/guide/
Coverage: Complete Telegram Bot API (all methods, types, and property enums)
Bot API Constant: Telegram::BOT_API_VERSION

> Note: All documentation links below are relative to \`Base URL\`.

## AI Agent Skill & Coding Guidelines
When writing code for \`tueen/telegram\`, AI assistants and agents MUST adhere to these architectural rules:
1. Always enable strict types: \`declare(strict_types=1);\`.
2. Property access & visibility: Use asymmetric visibility \`private(set)\` (never \`public private(set)\`).
3. Property hooks: Prefer property hooks over repetitive getter/setter methods.
4. Universal ok(): All response types inherit from base \`Type\` and supply \`ok(): bool\` and \`isOk(): bool\`.
5. Error Handling: Default mode throws typed exceptions; or use \`withErrorObjectMode()\` to receive typed \`Error\` objects.
6. Forward compatibility: Unknown future Telegram fields are dynamically accessed or retrieved via \`$extra\`.
7. Pipeline pattern: Callables and middlewares support the Pipe Operator (\`|>\`) and fluent config builder.

## Complete Documentation Map & Topic Outline
`

  for (const sec of parsedSections) {
    llmsTxt += `\n### ${sec.category}\n`
    for (const item of sec.items) {
      llmsTxt += `- [${item.title}](/${item.relLink}): ${item.description || item.title}\n`
      if (item.headings.length > 0) {
        for (const h of item.headings) {
          const indent = h.level === 3 ? '    * ' : '  - '
          llmsTxt += `${indent}[${h.text}](/${item.relLink}#${h.anchor})\n`
        }
      }
    }
  }

  llmsTxt += `\n## Downloadable Packages & Full Context\n`
  llmsTxt += `- [Download Complete Agent Skill ZIP](/tueen-telegram-bot-api-sdk.zip): Standalone skill folder (tueen-telegram-bot-api-sdk/) with SKILL.md and offline docs/.\n`
  llmsTxt += `- [Full Documentation (Single Context)](/llms-full.txt): Complete, concatenated markdown documentation for one-shot LLM ingestion.\n`

  // 2. Build ZIP Archive (tueen-telegram-bot-api-sdk.zip)
  const zip = new JSZip()
  const zipRoot = zip.folder('tueen-telegram-bot-api-sdk')

  // Put SKILL.md at the root of the folder
  const skillFileContent = generateSkillContent(parsedSections)
  zipRoot.file('SKILL.md', skillFileContent)

  // Put all docs into docs/ inside the zip
  const zipDocs = zipRoot.folder('docs')
  for (const sec of parsedSections) {
    for (const item of sec.items) {
      const cleanRelPath = item.relLink.replace(/^guide\//, '')
      const fileName = cleanRelPath.endsWith('/')
        ? `${cleanRelPath}index.md`
        : (cleanRelPath.endsWith('.md') ? cleanRelPath : `${cleanRelPath}.md`)
      
      // Clean custom tags so offline markdown is 100% standard
      const cleanRaw = item.rawContent
        .replace(/<Mermaid\s+code="([^"]+)"\s*\/>/g, (_, code) => {
          return '```mermaid\n' + decodeURIComponent(code) + '\n```'
        })
      
      zipDocs.file(fileName, cleanRaw)
    }
  }

  const zipBuffer = await zip.generateAsync({
    type: 'nodebuffer',
    compression: 'DEFLATE',
    compressionOptions: { level: 9 }
  })

  // 3. Build docs/llms.md (The VitePress Web Page - Clean, professional, clutter-free)
  let llmsMd = `---
title: LLMs & AI Context Hub
description: Official machine-readable context, prompt skills, and offline packages for AI coding assistants.
---

<script setup>
import LlmSkillCopy from './.vitepress/theme/components/LlmSkillCopy.vue'
</script>

# 🤖 LLM & AI Context Hub

Official machine-readable documentation, prompt skills, and offline packages for Large Language Models and AI coding assistants (**Cursor**, **Claude**, **ChatGPT**, **Copilot**, **Antigravity**).

<LlmSkillCopy />

---

## ⚡ Quick Integration

::: tip 💡 Cursor & VS Code
Add the **llms.txt** URL (\`/llms.txt\`) to Cursor Docs (\`Cursor Settings > Features > Docs\`), or extract the **Skill Package (.zip)** into your project's \`.agents/skills/\` directory.
:::

::: tip 🧠 Claude Projects & ChatGPT
Upload \`/llms-full.txt\` directly as Project Knowledge, or click **Copy AI Agent Skill** above and paste into your Custom Instructions.
:::

---

## 🏛️ Core Architectural Tenets for AI

When generating code for \`tueen/telegram\`, models MUST follow these principles:

- **PHP 8.4+ Strict Types:** Always start files with \`declare(strict_types=1);\`.
- **Clean Asymmetric Visibility:** Use \`private(set)\` without redundant \`public\` (e.g. \`private(set) int $id;\`).
- **Property Hooks:** Use \`get => ...\` for computed properties instead of repetitive getters.
- **Universal \`ok()\`:** All responses extend \`Type\` and provide \`$res->ok(): bool\` and \`$res->isOk(): bool\`.
- **Forward Compatibility:** Unknown Telegram fields are preserved in \`$extra\` and dynamically accessible.
- **Pipeline Pattern:** Middlewares and handlers support first-class callables and the PHP 8.5 Pipe Operator (\`|>\`).

---

## 📄 Machine-Readable Assets

| Asset | Format | Purpose | Link |
| :--- | :--- | :--- | :--- |
| **Agent Skill Package** | \`.zip\` archive | Offline skill with \`SKILL.md\` and complete \`docs/\` | [Download ZIP](/tueen-telegram-bot-api-sdk.zip) |
| **Standard LLM Summary** | \`llms.txt\` | [llmstxt.org](https://llmstxt.org) structured outline & docs map | [View \`/llms.txt\`](/llms.txt) |
| **Consolidated Context** | \`llms-full.txt\` | Full single-file markdown for context ingestion | [View \`/llms-full.txt\`](/llms-full.txt) |
`

  // Safe file write helper
  function safeWrite(dest, content) {
    if (fs.existsSync(dest)) {
      const existing = fs.readFileSync(dest, 'utf-8')
      if (existing === content) return false
    }
    fs.writeFileSync(dest, content, 'utf-8')
    return true
  }

  function safeWriteBuffer(dest, buffer) {
    if (fs.existsSync(dest)) {
      const existing = fs.readFileSync(dest)
      if (existing.equals(buffer)) return false
    }
    fs.writeFileSync(dest, buffer)
    return true
  }

  const w1 = safeWrite(llmsOutputFile, llmsTxt)
  const w2 = safeWrite(llmsFullOutputFile, fullConcatenatedContent.trim() + '\n')
  const w3 = safeWrite(llmsPageFile, llmsMd)
  const w4 = safeWriteBuffer(zipOutputFile, zipBuffer)

  console.log(`🤖 [LLM Generator] llms.txt: ${w1 ? 'Updated' : 'Unchanged'}, zip: ${w4 ? 'Updated' : 'Unchanged'} (${zipBuffer.length} bytes), llms-full.txt: ${w2 ? 'Updated' : 'Unchanged'}, llms.md: ${w3 ? 'Updated' : 'Unchanged'}`)
}

// Direct execution from CLI
if (process.argv[1] && path.resolve(process.argv[1]) === path.resolve(fileURLToPath(import.meta.url))) {
  generateLlms().catch(err => {
    console.error('Failed to generate LLM assets:', err)
    process.exit(1)
  })
}
