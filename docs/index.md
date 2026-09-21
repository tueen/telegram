---
layout: home

hero:
  name: "Tueen Telegram"
  text: "The Royal Telegram Bot API Client"
  tagline: "Built for PHP 8.4 & 8.5 with Property Hooks, Running Modes, Dual Error Handling, and 100% Bot API 10.3 Coverage."
  actions:
    - theme: brand
      text: Get Started
      link: /guide/getting-started
    - theme: alt
      text: View on GitHub
      link: https://github.com/tueen/telegram

features:
  - title: PHP 8.4 & 8.5 Native
    details: Leverages property hooks, asymmetric visibility (public private(set)), and modern pipeline architecture.
  - title: Nutgram-Style Running Modes
    details: Seamlessly toggle between WebhookMode (with secret token validation and safeResponse) and PollingMode (with automatic offset advancement).
  - title: Universal ok() & Dual Error Handling
    details: Universal ok() checks across all models. Choose between standard exceptions or non-throwing Error objects.
  - title: Smart Update & Message Helpers
    details: Instant type detection via $update->type and $message->type, smart extractors ($update->getUser()), and command parsing ($msg->getCommand()).
  - title: 100% Full API 10.3 Coverage
    details: All 185 Bot API methods, 400+ Types, and property Enums generated with strict typing and complete IDE autocompletion.
  - title: File Uploads & Progress Tracking
    details: Upload files via InputFile and stream downloads with real-time percentage progress callbacks.
---
