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
        text: 'Getting Started',
        items: [
          { text: 'Introduction & Setup', link: '/guide/getting-started' },
          { text: 'Configuration', link: '/guide/configuration' },
        ]
      },
      {
        text: 'Updates & Running Modes',
        items: [
          { text: 'Running Modes (Webhook & Polling)', link: '/guide/running-modes' },
          { text: 'Update & Message Helpers', link: '/guide/update-and-message-helpers' },
        ]
      },
      {
        text: 'Client Features',
        items: [
          { text: 'Calling Methods & Types', link: '/guide/methods-and-types' },
          { text: 'Error Handling & Hooks', link: '/guide/error-handling-and-hooks' },
          { text: 'File Upload & Download', link: '/guide/file-upload-download' },
          { text: 'Pipeline & Middlewares', link: '/guide/pipeline-middleware' },
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
