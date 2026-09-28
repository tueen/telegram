---
layout: home

hero:
  name: "Tueen Telegram"
  text: "The Royal Telegram Bot API Client"
  tagline: "Strictly typed, forward-compatible, and crafted for modern PHP."
  image:
    src: /icon.png
    alt: Tueen Telegram Logo
  actions:
    - theme: brand
      text: Get Started
      link: /guide/getting-started
    - theme: alt
      text: View on GitHub
      link: https://github.com/tueen/telegram

features:
  - icon: 👑
    title: Modern PHP Architecture
    details: 100% strict typing, property hooks, asymmetric visibility (private(set)), and bulletproof forward compatibility for unknown fields.
  - icon: 🧭
    title: Attribute Routing & Dispatcher
    details: Declarative update dispatching via #[OnCommand], #[OnCallbackQuery], regex matchers, and organized controller classes.
  - icon: 💬
    title: Conversation Flows & State
    details: Stateful multi-step user dialogues with step navigation (to, stay, back, finish) and pluggable state storage drivers.
  - icon: ⌨️
    title: Fluent Keyboards & Formatting
    details: Expressive builders for Inline and Reply keyboards, alongside Telegram spec-compliant HTML and MarkdownV2 text escaping.
  - icon: 🔄
    title: Flexible Running Modes
    details: Production-ready WebhookMode with secret token validation and safeResponse, plus robust PollingMode with auto-backoff.
  - icon: 🎯
    title: Smart Helpers & Context
    details: Instant type detection via $update->type, argument parsing, model extractors, and contextual chat/user ID resolution.
  - icon: 🛡️
    title: Dual Error Handling & ok()
    details: Universal ok() checks across all response types. Choose seamlessly between traditional exceptions or typed Error objects.
  - icon: 🔌
    title: Resilient Pipeline Middleware
    details: Extensible onion architecture featuring token-bucket rate limiting, automatic 429 flood-wait pacing, and PSR-3 logging.
  - icon: 🧪
    title: Testing & In-Memory Fakes
    details: Test your bots with zero HTTP calls using TelegramFake, pre-configured stubs, and expressive assertions like assertSent.
---
