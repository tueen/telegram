---
layout: home

hero:
  name: "Tueen Telegram"
  text: "The Royal Telegram Bot SDK for Modern PHP"
  tagline: "Declarative, stateful, and resilient."
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
  - icon: 🚀
    title: Zero-Config App & Dashboard
    details: Instant bot boot with App::run(), auto-detecting runners, CLI toolkit, and a built-in real-time Web Dashboard for webhook inspection.
  - icon: 🧭
    title: Attribute Routing & Controllers
    details: Declarative update dispatching via #[OnCommand], #[OnCallbackQuery], regex matchers, and organized controller classes.
  - icon: 💬
    title: Conversation Flows & State
    details: Stateful multi-step user dialogues with step navigation (to, stay, back, finish) and pluggable state storage drivers.
  - icon: 👑
    title: Modern PHP 8.4+ Architecture
    details: 100% strict typing, property hooks, asymmetric visibility (private(set)), and bulletproof forward compatibility for unknown fields.
  - icon: 🔄
    title: Adaptive Running Modes
    details: Seamless switching between WebhookMode (with secret token & safeResponse), PollingMode, and adaptive AutoMode.
  - icon: ⌨️
    title: Fluent Keyboards & Formatting
    details: Expressive builders for Inline and Reply keyboards, alongside Telegram spec-compliant HTML and MarkdownV2 text escaping.
  - icon: 🔌
    title: Resilient Pipeline Middleware
    details: Extensible onion architecture featuring token-bucket rate limiting, automatic 429 flood-wait pacing, and PSR-3 logging.
  - icon: 🎯
    title: Smart Helpers & Context
    details: Instant type detection via $update->type, argument parsing, model extractors, and contextual chat/user ID resolution.
  - icon: 🧪
    title: Testing & In-Memory Fakes
    details: Test your bots with zero HTTP calls using TelegramFake, pre-configured stubs, and expressive assertions like assertSent.
---
