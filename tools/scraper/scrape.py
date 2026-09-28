#!/usr/bin/env python3
"""
Telegram Bot API Documentation Scraper
Scrapes official documentation from https://core.telegram.org/bots/api
and compiles it into a machine-readable JSON specification.

Origin: Based on PaulSonOfLars/telegram-bot-api-spec, tailored for Tueen Ecosystem.
"""

from __future__ import annotations

import argparse
import json
import os
import re
import string
import sys
from pathlib import Path

import requests
from bs4 import BeautifulSoup
from bs4.element import Tag

TG_CORE_TYPES = ["String", "Boolean", "Integer", "Float"]
ROOT_URL = "https://core.telegram.org"
DEFAULT_URL = ROOT_URL + "/bots/api"

METHODS = "methods"
TYPES = "types"

# List of all abstract types which don't have subtypes.
APPROVED_NO_SUBTYPES = {
    "InputFile"  # This is how telegram represents files
}

# List of all approved multi-returns.
APPROVED_MULTI_RETURNS = [
    ["Message", "Boolean"]  # Edit returns either the new message, or an OK to confirm the edit.
]


def fetch_page_content(url: str, proxy: str | None = None, timeout: int = 30) -> str:
    """
    Fetch the Telegram Bot API HTML documentation with proxy fallback.
    """
    headers = {
        "User-Agent": "Mozilla/5.0 (compatible; TueenTelegramBotApiScraper/1.0; +https://github.com/tueen/telegram)"
    }

    proxies_to_try: list[dict[str, str] | None] = []
    if proxy:
        proxies_to_try.append({"http": proxy, "https": proxy})
    else:
        # 1. Try direct connection first
        proxies_to_try.append(None)

        # 2. Try environment variables
        env_http = os.environ.get("HTTP_PROXY") or os.environ.get("http_proxy")
        env_https = os.environ.get("HTTPS_PROXY") or os.environ.get("https_proxy")
        if env_http or env_https:
            proxies_to_try.append({"http": env_http or env_https, "https": env_https or env_http})

        # 3. Try common local proxies (e.g. 10809, 7890) if available
        proxies_to_try.append({"http": "http://127.0.0.1:10809", "https": "http://127.0.0.1:10809"})
        proxies_to_try.append({"http": "http://127.0.0.1:7890", "https": "http://127.0.0.1:7890"})

    last_error: Exception | None = None
    for p in proxies_to_try:
        label = p["http"] if p else "direct connection"
        try:
            print(f"Fetching documentation via {label}...")
            r = requests.get(url, headers=headers, proxies=p, timeout=timeout)
            if r.status_code == 200 and len(r.text) > 10000:
                print(f"Successfully retrieved page ({len(r.text)} bytes).")
                return r.text
            print(f"Response status {r.status_code}, length: {len(r.text)}")
        except Exception as e:
            last_error = e
            continue

    if last_error:
        raise last_error
    raise RuntimeError(f"Could not retrieve Telegram Bot API documentation from {url}")


def retrieve_info(url: str, html_text: str) -> dict:
    soup = BeautifulSoup(html_text, features="html5lib")
    dev_rules = soup.find("div", {"id": "dev_page_content"})
    if not dev_rules:
        raise ValueError("Could not find #dev_page_content in documentation page")

    curr_type = ""
    curr_name = ""

    # First header in the body is the release date (with an anchor)
    release_tag = dev_rules.find("h4", recursive=False)
    changelog_url = url + release_tag.find("a").get("href")
    # First paragraph in the root body is the version
    version = dev_rules.find("p", recursive=False).get_text()

    items = {
        "version": version,
        "release_date": release_tag.get_text(),
        "changelog": changelog_url,
        METHODS: dict(),
        TYPES: dict(),
    }

    for x in list(dev_rules.children):  # type: Tag
        if x.name == "h3" or x.name == "hr":
            # New category; clear name and type.
            curr_name = ""
            curr_type = ""

        if x.name == "h4":
            anchor = x.find("a")
            name = anchor.get("name") if anchor else None
            if name and "-" in name:
                curr_name = ""
                curr_type = ""
                continue

            curr_name, curr_type = get_type_and_name(x, anchor, items, url)

        if not curr_type or not curr_name:
            continue

        if x.name == "p":
            items[curr_type][curr_name].setdefault("description", []).extend(clean_tg_description(x, url))

        if x.name == "table":
            get_fields(curr_name, curr_type, x, items, url)

        if x.name == "ul":
            get_subtypes(curr_name, curr_type, x, items, url)

        # Only methods have return types.
        # We check this every time just in case the description has been updated, and we have new return types to add.
        if curr_type == METHODS and items[curr_type][curr_name].get("description"):
            get_method_return_type(curr_name, curr_type, items[curr_type][curr_name].get("description"), items)

    return items


def get_subtypes(curr_name: str, curr_type: str, x: Tag, items: dict, url: str):
    if curr_name == "InputFile":  # Has no interesting subtypes
        return

    list_contents = []
    for li in x.find_all("li"):
        list_contents.extend(clean_tg_description(li, url))

    # List items found in types define possible subtypes.
    if curr_type == TYPES:
        items[curr_type][curr_name]["subtypes"] = list_contents

    # Always add the list to the description, for better docs.
    items[curr_type][curr_name]["description"] += [f"- {s}" for s in list_contents]


def get_fields(curr_name: str, curr_type: str, x: Tag, items: dict, url: str):
    body = x.find("tbody")
    if not body:
        return

    fields = []
    for tr in body.find_all("tr"):
        children = list(tr.find_all("td"))
        if curr_type == TYPES and len(children) == 3:
            desc = clean_tg_field_description(children[2], url)
            fields.append(
                {
                    "name": children[0].get_text(),
                    "types": clean_tg_type(children[1].get_text()),
                    "required": not desc.startswith("Optional. "),
                    "description": desc,
                }
            )

        elif curr_type == METHODS and len(children) == 4:
            fields.append(
                {
                    "name": children[0].get_text(),
                    "types": clean_tg_type(children[1].get_text()),
                    "required": children[2].get_text() == "Yes",
                    "description": clean_tg_field_description(children[3], url),
                }
            )

        else:
            print("An unexpected state has occurred!", file=sys.stderr)
            print("Type:", curr_type, file=sys.stderr)
            print("Name:", curr_name, file=sys.stderr)
            print("Number of children:", len(children), file=sys.stderr)
            print(children, file=sys.stderr)
            sys.exit(1)

    items[curr_type][curr_name]["fields"] = fields


def get_method_return_type(curr_name: str, curr_type: str, description_items: list[str], items: dict):
    description = "\n".join(description_items)
    ret_search = re.search(r".*(?:on success,)([^.]*)", description, re.IGNORECASE)
    ret_search2 = re.search(r".*(?:returns)([^.]*)(?:on success)?", description, re.IGNORECASE)
    ret_search3 = re.search(r".*([^.]*)(?:is returned)", description, re.IGNORECASE)
    if ret_search:
        extract_return_type(curr_type, curr_name, ret_search.group(1).strip(), items)
    elif ret_search2:
        extract_return_type(curr_type, curr_name, ret_search2.group(1).strip(), items)
    elif ret_search3:
        extract_return_type(curr_type, curr_name, ret_search3.group(1).strip(), items)
    else:
        print(f"WARN - failed to get return type for {curr_name}", file=sys.stderr)


def get_type_and_name(t: Tag, anchor: Tag, items: dict, url: str):
    if t.text[0].isupper():
        curr_type = TYPES
    else:
        curr_type = METHODS
    curr_name = t.get_text()
    items[curr_type][curr_name] = {"name": curr_name}

    if anchor:
        href = anchor.get("href")
        if href:
            items[curr_type][curr_name]["href"] = url + href

    return curr_name, curr_type


def extract_return_type(curr_type: str, curr_name: str, ret_str: str, items: dict):
    array_match = re.search(r"(?:array of )+(\w*)", ret_str, re.IGNORECASE)
    if array_match:
        ret = clean_tg_type(array_match.group(1))
        rets = [f"Array of {r}" for r in ret]
        items[curr_type][curr_name]["returns"] = rets
    else:
        words = ret_str.split()
        rets = [
            r
            for ret in words
            for r in clean_tg_type(ret.translate(str.maketrans("", "", string.punctuation)))
            if ret and ret[0].isupper()
        ]
        items[curr_type][curr_name]["returns"] = rets


def clean_tg_field_description(t: Tag, url: str) -> str:
    return " ".join(clean_tg_description(t, url))


def clean_tg_description(t: Tag, url: str) -> list[str]:
    # Replace HTML emoji images with actual emoji
    for i in t.find_all("img"):
        i.replace_with(i.get("alt") or "")

    # Make sure to include linebreaks, or spacing gets weird
    for br in t.find_all("br"):
        br.replace_with("\n")

    # Replace helpful anchors with the actual URL.
    for a in t.find_all("a"):
        anchor_text = a.get_text()
        if "»" not in anchor_text:
            continue

        link = a.get("href")
        # Page-relative URL
        if link.startswith("#"):
            link = url + link
        # Domain-relative URL
        elif link.startswith("/"):
            link = ROOT_URL + link

        anchor_text = anchor_text.replace(" »", ": " + link)
        a.replace_with(anchor_text)

    text = t.get_text()

    # Replace any weird double whitespaces with single occurrences
    text = re.sub(r"(\s){2,}", r"\1", text)

    # Replace weird UTF-8 quotes with proper quotes
    text = text.replace("”", '"').replace("“", '"')

    # Replace weird unicode ellipsis with three dots
    text = text.replace("…", "...")

    # Use sensible dashes
    text = text.replace("\u2013", "-")
    text = text.replace("\u2014", "-")
    # Use sensible single quotes
    text = text.replace("\u2019", "'")

    # Split on newlines to improve description output.
    return [line.strip() for line in text.split("\n") if line.strip()]


def get_proper_type(t: str) -> str:
    if t == "Messages":  # Avoids https://core.telegram.org/bots/api#sendmediagroup
        return "Message"
    elif t == "Float number":
        return "Float"
    elif t == "Int":
        return "Integer"
    elif t == "True" or t == "Bool":
        return "Boolean"
    return t


def clean_tg_type(t: str) -> list[str]:
    pref = ""
    if t.startswith("Array of "):
        pref = "Array of "
        t = t[len("Array of ") :]

    fixed_ors = [x.strip() for x in t.split(" or ")]
    fixed_ands = [x.strip() for fo in fixed_ors for x in fo.split(" and ")]
    fixed_commas = [x.strip() for fa in fixed_ands for x in fa.split(", ")]
    return [pref + get_proper_type(x) for x in fixed_commas if x]


def verify_type_parameters(items: dict) -> bool:
    issue_found = False

    for type_name, values in items[TYPES].items():
        if not values.get("href"):
            print(f"{type_name} has no link!", file=sys.stderr)
            issue_found = True
            continue

        fields = values.get("fields", [])
        if len(fields) == 0:
            subtypes = values.get("subtypes", [])
            description = "".join(values.get("description", []))
            if not subtypes and not (
                "currently holds no information" in description.lower() or type_name in APPROVED_NO_SUBTYPES
            ):
                print(f"TYPE {type_name} has no fields or subtypes, and is not approved", file=sys.stderr)
                issue_found = True
                continue

            m = re.search(r"it can be either (.*?), or any of the following types:", description, re.IGNORECASE)
            if m:
                parts = m.group(1).split(",")
                description_types = []
                for part in parts:
                    part = part.strip()
                    type_match = re.search(r"(?:a|an)\s+(?:(\w*\s+of\s+\w*)|(\w*))", part)
                    if type_match:
                        description_types.append(type_match.group(1) or type_match.group(2))
                subtypes = description_types + subtypes
                values["subtypes"] = subtypes

            for st in subtypes:
                cleaned = st.removeprefix("Array of ")
                if cleaned in TG_CORE_TYPES or cleaned == type_name:
                    pass
                elif st in items[TYPES]:
                    items[TYPES][st].setdefault("subtype_of", []).append(type_name)
                else:
                    print(f"TYPE {type_name} USES INVALID SUBTYPE {st}", file=sys.stderr)
                    issue_found = True

        for param in fields:
            field_types = param.get("types", [])
            for field_type_name in field_types:
                while field_type_name.startswith("Array of "):
                    field_type_name = field_type_name[len("Array of ") :]

                if field_type_name not in items[TYPES] and field_type_name not in TG_CORE_TYPES:
                    print(f"UNKNOWN FIELD TYPE {field_type_name}", file=sys.stderr)
                    issue_found = True

    return issue_found


def verify_method_parameters(items: dict) -> bool:
    issue_found = False

    for method, values in items[METHODS].items():
        if not values.get("href"):
            print(f"{method} has no link!", file=sys.stderr)
            issue_found = True
            continue

        returns = values.get("returns")
        if not returns:
            print(f"{method} has no return types!", file=sys.stderr)
            issue_found = True
            continue

        if len(returns) > 1 and returns not in APPROVED_MULTI_RETURNS:
            print(f"Notice: {method} has multiple return types: {returns}", file=sys.stderr)

        for param in values.get("fields", []):
            types = param.get("types", [])
            for t in types:
                while t.startswith("Array of "):
                    t = t[len("Array of ") :]

                if t not in items[TYPES] and t not in TG_CORE_TYPES:
                    issue_found = True
                    print(f"UNKNOWN PARAM TYPE {t}", file=sys.stderr)

        for ret in values.get("returns", []):
            while ret.startswith("Array of "):
                ret = ret[len("Array of ") :]

            if ret not in items[TYPES] and ret not in TG_CORE_TYPES:
                issue_found = True
                print(f"UNKNOWN RETURN TYPE {ret}", file=sys.stderr)

    return issue_found


def main():
    parser = argparse.ArgumentParser(description="Scrape Telegram Bot API documentation and generate JSON schema.")
    parser.add_argument(
        "-o",
        "--output",
        default="resources/api.json",
        help="Path to output JSON file (default: resources/api.json)",
    )
    parser.add_argument(
        "--min-output",
        default=None,
        help="Optional path to output minified JSON file",
    )
    parser.add_argument(
        "--url",
        default=DEFAULT_URL,
        help=f"Telegram Bot API docs URL (default: {DEFAULT_URL})",
    )
    parser.add_argument(
        "--proxy",
        default=None,
        help="Proxy URL to use (e.g. http://127.0.0.1:10809)",
    )
    parser.add_argument(
        "--timeout",
        type=int,
        default=30,
        help="HTTP request timeout in seconds (default: 30)",
    )

    args = parser.parse_args()

    # Determine paths relative to project root if needed
    output_path = Path(args.output).resolve()
    print(f"Scraping Telegram Bot API docs from: {args.url}")

    html_content = fetch_page_content(args.url, proxy=args.proxy, timeout=args.timeout)
    items = retrieve_info(args.url, html_content)

    print("Validating scraped schema...")
    has_type_issues = verify_type_parameters(items)
    has_method_issues = verify_method_parameters(items)

    if has_type_issues or has_method_issues:
        print("Error: Failed to validate schema. See logs above for details.", file=sys.stderr)
        sys.exit(1)

    output_path.parent.mkdir(parents=True, exist_ok=True)
    with open(output_path, "w", encoding="utf-8") as f:
        json.dump(items, f, indent=2, ensure_ascii=False)
        f.write("\n")

    print(f"Successfully generated specification at: {output_path}")
    print(f"API Version : {items.get('version', 'N/A')}")
    print(f"Release Date: {items.get('release_date', 'N/A')}")
    print(f"Methods     : {len(items.get(METHODS, {}))}")
    print(f"Types       : {len(items.get(TYPES, {}))}")

    if args.min_output:
        min_path = Path(args.min_output).resolve()
        min_path.parent.mkdir(parents=True, exist_ok=True)
        with open(min_path, "w", encoding="utf-8") as f:
            json.dump(items, f, separators=(",", ":"), ensure_ascii=False)
        print(f"Successfully generated minified spec at: {min_path}")


if __name__ == "__main__":
    main()
