---
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
Add the **llms.txt** URL (`/llms.txt`) to Cursor Docs (`Cursor Settings > Features > Docs`), or extract the **Skill Package (.zip)** into your project's `.agents/skills/` directory.
:::

::: tip 🧠 Claude Projects & ChatGPT
Upload `/llms-full.txt` directly as Project Knowledge, or click **Copy AI Agent Skill** above and paste into your Custom Instructions.
:::

---

## 🏛️ Core Architectural Tenets for AI

When generating code for `tueen/telegram`, models MUST follow these principles:

- **PHP 8.4+ Strict Types:** Always start files with `declare(strict_types=1);`.
- **Clean Asymmetric Visibility:** Use `private(set)` without redundant `public` (e.g. `private(set) int $id;`).
- **Property Hooks:** Use `get => ...` for computed properties instead of repetitive getters.
- **Universal `ok()`:** All responses extend `Type` and provide `$res->ok(): bool` and `$res->isOk(): bool`.
- **Forward Compatibility:** Unknown Telegram fields are preserved in `$extra` and dynamically accessible.
- **Pipeline Pattern:** Middlewares and handlers support first-class callables and the PHP 8.5 Pipe Operator (`|>`).

---

## 📄 Machine-Readable Assets

| Asset | Format | Purpose | Link |
| :--- | :--- | :--- | :--- |
| **Agent Skill Package** | `.zip` archive | Offline skill with `SKILL.md` and complete `docs/` | [Download ZIP](/tueen-telegram-bot-api-sdk.zip) |
| **Standard LLM Summary** | `llms.txt` | [llmstxt.org](https://llmstxt.org) structured outline & docs map | [View `/llms.txt`](/llms.txt) |
| **Consolidated Context** | `llms-full.txt` | Full single-file markdown for context ingestion | [View `/llms-full.txt`](/llms-full.txt) |
