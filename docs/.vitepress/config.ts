import { defineConfig, type DefaultTheme } from 'vitepress'
import fs from 'node:fs'
import path from 'node:path'
import { fileURLToPath } from 'node:url'
import versions from '../versions.json'
import { generateLlms, getCanonicalSiteUrl } from '../scripts/generate-llms.mjs'

const __dirname = path.dirname(fileURLToPath(import.meta.url))
const publicDir = path.resolve(__dirname, '../public')

function llmsPlugin() {
  return {
    name: 'vitepress-llms-generator',
    async buildStart() {
      await generateLlms()
    },
    async handleHotUpdate({ file }: { file: string }) {
      if (file.endsWith('.md') && !file.endsWith('llms.md')) {
        await generateLlms()
      }
    },
    configureServer(server: any) {
      server.middlewares.use((req: any, res: any, next: any) => {
        const url = req.url?.split('?')[0]
        if (url === '/llms.txt' || url?.endsWith('/llms.txt')) {
          const host = req.headers.host || '127.0.0.1:5173'
          const protocol = req.headers['x-forwarded-proto'] || 'http'
          const currentOrigin = `${protocol}://${host}`
          const filePath = path.resolve(publicDir, 'llms.txt')
          if (fs.existsSync(filePath)) {
            const content = fs.readFileSync(filePath, 'utf-8')
            const dynamicContent = content.replaceAll(getCanonicalSiteUrl(), currentOrigin)
            res.setHeader('Content-Type', 'text/plain; charset=utf-8')
            res.end(dynamicContent)
            return
          }
        }
        if (url === '/llms-full.txt' || url?.endsWith('/llms-full.txt')) {
          const host = req.headers.host || '127.0.0.1:5173'
          const protocol = req.headers['x-forwarded-proto'] || 'http'
          const currentOrigin = `${protocol}://${host}`
          const filePath = path.resolve(publicDir, 'llms-full.txt')
          if (fs.existsSync(filePath)) {
            const content = fs.readFileSync(filePath, 'utf-8')
            const dynamicContent = content.replaceAll(getCanonicalSiteUrl(), currentOrigin)
            res.setHeader('Content-Type', 'text/plain; charset=utf-8')
            res.end(dynamicContent)
            return
          }
        }
        next()
      })
    }
  }
}

export function getSidebar(basePath = '/guide/'): DefaultTheme.SidebarItem[] {
  return [
    {
      text: 'Getting Started',
      items: [
        { text: 'Introduction & Setup', link: `${basePath}getting-started` },
        { text: 'The Telegram Client', link: `${basePath}telegram-client` },
        { text: 'Configuration Reference', link: `${basePath}configuration` },
      ]
    },
    {
      text: 'Core Essentials',
      items: [
        { text: 'Calling Methods & Types', link: `${basePath}methods-and-types` },
        { text: 'Interactive Keyboards', link: `${basePath}keyboards` },
        { text: 'Text Formatting & Escaping', link: `${basePath}formatting` },
        { text: 'File Upload & Download', link: `${basePath}file-upload-download` },
      ]
    },
    {
      text: 'Updates & Routing',
      items: [
        { text: 'Running Modes (Polling & Webhook)', link: `${basePath}running-modes` },
        { text: 'Update Routing & Attributes', link: `${basePath}routing` },
        { text: 'Update & Message Helpers', link: `${basePath}update-and-message-helpers` },
      ]
    },
    {
      text: 'Conversational Flows',
      items: [
        { text: 'Overview & Quickstart', link: `${basePath}flow/` },
        { text: 'Conversational Flows', link: `${basePath}flow/conversational-flows` },
        { text: 'Interactive Screens', link: `${basePath}flow/interactive-screens` },
        { text: 'Lifecycle Hooks', link: `${basePath}flow/lifecycle-hooks` },
        { text: 'Keyboards & Actions', link: `${basePath}flow/keyboards-and-actions` },
        { text: 'Navigation & Stack', link: `${basePath}flow/navigation-and-stack` },
        { text: 'State Storage', link: `${basePath}flow/state-storage` },
      ]
    },
    {
      text: 'Application & Production',
      items: [
        { text: 'Zero-Config App & Web Dashboard', link: `${basePath}app` },
        { text: 'Pipeline & Middlewares', link: `${basePath}pipeline-middleware` },
        { text: 'Error Handling & Hooks', link: `${basePath}error-handling-and-hooks` },
        { text: 'Testing & Fakes', link: `${basePath}testing` },
        { text: 'Enums Reference', link: `${basePath}enums` },
      ]
    }
  ]
}

// Build multi-sidebar supporting current version and all archived versions
const sidebar: DefaultTheme.SidebarMulti = {
  '/guide/': getSidebar('/guide/'),
}

for (const item of versions.archived) {
  const versionPrefix = `/versions/${item.version}/`
  const customSidebarFile = path.resolve(__dirname, `../versions/${item.version}/sidebar.json`)
  
  if (fs.existsSync(customSidebarFile)) {
    try {
      sidebar[versionPrefix] = JSON.parse(fs.readFileSync(customSidebarFile, 'utf-8'))
    } catch {
      sidebar[versionPrefix] = getSidebar(`${versionPrefix}guide/`)
    }
  } else {
    sidebar[versionPrefix] = getSidebar(`${versionPrefix}guide/`)
  }
}

// Determine base path for GitHub Pages or custom domain:
// 1. If public/CNAME exists, custom domain is used -> '/'
// 2. Explicit non-empty VITEPRESS_BASE environment variable -> normalized with leading & trailing slashes
// 3. In GitHub Actions without a custom CNAME:
//    - if repo is <user>.github.io -> '/'
//    - otherwise -> '/<repo>/' (e.g. '/telegram/')
// 4. Otherwise default to '/' (local development or preview)
const hasCustomDomain = fs.existsSync(path.resolve(__dirname, '../public/CNAME'))

function resolveBase(): string {
  if (hasCustomDomain) {
    return '/'
  }

  const envBase = process.env.VITEPRESS_BASE?.trim()
  if (envBase) {
    const withLeading = envBase.startsWith('/') ? envBase : `/${envBase}`
    return withLeading.endsWith('/') ? withLeading : `${withLeading}/`
  }

  if (process.env.GITHUB_ACTIONS) {
    if (process.env.GITHUB_REPOSITORY) {
      const [owner, repo] = process.env.GITHUB_REPOSITORY.split('/')
      if (repo && owner && repo.toLowerCase() === `${owner.toLowerCase()}.github.io`) {
        return '/'
      }
      if (repo) {
        return `/${repo}/`
      }
    }
    return '/telegram/'
  }

  return '/'
}

const base = resolveBase()

export default defineConfig({
  base,
  title: "Tueen Telegram",
  description: "The Royal Telegram Bot SDK for Modern PHP",
  vite: {
    plugins: [llmsPlugin()]
  },
  head: [
    ['link', { rel: 'icon', type: 'image/png', href: `${base === '/' ? '' : base.replace(/\/$/, '')}/icon.png` }]
  ],
  markdown: {
    config(md) {
      const defaultFence = md.renderer.rules.fence!.bind(md.renderer.rules)
      md.renderer.rules.fence = (tokens, idx, options, env, self) => {
        const token = tokens[idx]
        if (token.info.trim() === 'mermaid') {
          return `<Mermaid code="${encodeURIComponent(token.content)}" />`
        }
        return defaultFence(tokens, idx, options, env, self)
      }
    }
  },
  themeConfig: {
    logo: '/icon.png',
    nav: [
      { text: 'Home', link: '/' },
      { text: 'Guide', link: '/guide/getting-started' },
      { text: '🤖 LLMs', link: '/llms' },
      {
        text: `v${versions.current}`,
        items: [
          { text: `v${versions.current} (latest)`, link: '/guide/getting-started' },
          ...versions.archived.map((v: { version: string; link?: string }) => ({
            text: `v${v.version}`,
            link: v.link || `/versions/${v.version}/`
          })),
          { text: 'Changelog', link: 'https://github.com/tueen/telegram/releases' }
        ]
      },
      { text: 'GitHub', link: 'https://github.com/tueen/telegram' }
    ],
    sidebar,
    socialLinks: [
      { icon: 'github', link: 'https://github.com/tueen/telegram' }
    ],
    footer: {
      message: 'Released under the MIT License.',
      copyright: 'Copyright © 2026 Tueen Ecosystem'
    }
  }
})
