# Telegram Bot API Documentation Scraper

This tool scrapes the official Telegram Bot API documentation from [https://core.telegram.org/bots/api](https://core.telegram.org/bots/api) and compiles it into a structured, machine-readable JSON specification (`resources/api.json`).

The generated specification is used by `bin/generate.php` to generate all Methods, Types, and Mixin contracts for `tueen/telegram`.

---

## 🚀 Requirements

- Python 3.10+
- Dependencies listed in `requirements.txt`:
  - `requests`
  - `beautifulsoup4`
  - `html5lib`

Install dependencies:
```bash
pip install -r tools/scraper/requirements.txt
```

---

## 🛠️ Usage

### Via Composer (Recommended)
You can update the specification and regenerate all client classes in one step:
```bash
composer update-api
```

Or run the spec updater script directly:
```bash
php bin/update_spec.php
```

### Standalone Python Execution
You can also run the scraper script directly:

```bash
# Generate specification to default path (resources/api.json)
python tools/scraper/scrape.py

# Specify custom output path
python tools/scraper/scrape.py --output resources/api.json

# Use a proxy (e.g. if Telegram is blocked in your network environment)
python tools/scraper/scrape.py --proxy http://127.0.0.1:10809

# Output minified JSON as well
python tools/scraper/scrape.py --output resources/api.json --min-output resources/api.min.json
```

---

## 🛡️ Robustness & Proxy Fallback

`scrape.py` automatically attempts:
1. **Direct Connection** to `https://core.telegram.org/bots/api`.
2. **Environment Proxies** if set (`HTTP_PROXY`, `HTTPS_PROXY`, `ALL_PROXY`).
3. **Common Local Proxies** (`127.0.0.1:10809`, `127.0.0.1:7890`) for developers in restricted networks.

---

## 📜 Credits & License

Based on the [PaulSonOfLars/telegram-bot-api-spec](https://github.com/PaulSonOfLars/telegram-bot-api-spec) scraper by Paul Larsen, adapted and integrated locally into the **Tueen Ecosystem** under the MIT License.
