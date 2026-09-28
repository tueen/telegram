import { defineConfig, type DefaultTheme } from 'vitepress'
import fs from 'node:fs'
import path from 'node:path'
import { fileURLToPath } from 'node:url'
import versions from '../versions.json'

const __dirname = path.dirname(fileURLToPath(import.meta.url))

export function getSidebar(basePath = '/guide/'): DefaultTheme.SidebarItem[] {
  return [
    {
      text: 'Getting Started',
      items: [
        { text: 'Introduction & Setup', link: `${basePath}getting-started` },
        { text: 'Configuration', link: `${basePath}configuration` },
      ]
    },
    {
      text: 'Updates & Running Modes',
      items: [
        { text: 'Running Modes (Webhook & Polling)', link: `${basePath}running-modes` },
        { text: 'Update & Message Helpers', link: `${basePath}update-and-message-helpers` },
        { text: 'Update Routing & Attributes', link: `${basePath}routing` },
        { text: 'Conversation Flows (Flow)', link: `${basePath}flows` },
      ]
    },
    {
      text: 'Client Features',
      items: [
        { text: 'Calling Methods & Types', link: `${basePath}methods-and-types` },
        { text: 'Fluent Keyboards', link: `${basePath}keyboards` },
        { text: 'Text Formatting & Escaping', link: `${basePath}formatting` },
        { text: 'File Upload & Download', link: `${basePath}file-upload-download` },
        { text: 'Error Handling & Hooks', link: `${basePath}error-handling-and-hooks` },
        { text: 'Pipeline & Middlewares', link: `${basePath}pipeline-middleware` },
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

export default defineConfig({
  title: "Tueen Telegram",
  description: "The Royal Telegram Bot API Client for Tueen",
  head: [
    ['link', { rel: 'icon', type: 'image/png', href: '/icon.png' }]
  ],
  themeConfig: {
    logo: '/icon.png',
    nav: [
      { text: 'Home', link: '/' },
      { text: 'Guide', link: '/guide/getting-started' },
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
