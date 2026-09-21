import { defineConfig } from 'vitepress'

export default defineConfig({
  title: "Tueen Telegram",
  description: "The Royal Telegram Bot API Client for PHP 8.4 & 8.5",
  themeConfig: {
    nav: [
      { text: 'Home', link: '/' },
      { text: 'Guide', link: '/guide/getting-started' },
      { text: 'GitHub', link: 'https://github.com/tueen/telegram' }
    ],
    sidebar: [
      {
        text: 'Introduction',
        items: [
          { text: 'Getting Started', link: '/guide/getting-started' },
          { text: 'Methods & Types', link: '/guide/methods-and-types' },
          { text: 'File Upload & Download', link: '/guide/file-upload-download' },
          { text: 'Pipeline & Middleware', link: '/guide/pipeline-middleware' }
        ]
      }
    ],
    socialLinks: [
      { icon: 'github', link: 'https://github.com/tueen/telegram' }
    ],
    footer: {
      message: 'Released under the MIT License.',
      copyright: 'Copyright © 2026 Tueen Ecosystem'
    }
  }
})
