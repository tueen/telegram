---
layout: home

hero:
  name: "Tueen Telegram"
  text: "Telegram Bot API Client for PHP"
  tagline: "Strictly typed, forward-compatible Telegram Bot API client with running modes, dual error handling, and pipeline middleware."
  actions:
    - theme: brand
      text: Get Started
      link: /guide/getting-started
    - theme: alt
      text: View on GitHub
      link: https://github.com/tueen/telegram

features:
  - title: Modern PHP Architecture
    details: Built with strict typing, property hooks, and an extensible onion middleware pipeline.
  - title: Flexible Running Modes
    details: Seamlessly switch between WebhookMode (with secret token validation and safeResponse) and PollingMode.
  - title: Dual Error Handling & ok()
    details: Universal ok() checks across all models. Choose between standard exceptions or typed Error objects.
  - title: Smart Update & Message Helpers
    details: Instant type detection via $update->type, smart model extractors, and command argument parsing.
  - title: Complete API & Enum Coverage
    details: Complete coverage of Bot API methods, types, and enums with full IDE autocompletion.
  - title: File Transfers & Progress
    details: Upload files via InputFile and stream downloads with real-time percentage progress callbacks.
---
