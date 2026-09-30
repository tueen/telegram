<script setup lang="ts">
import { computed } from 'vue'

const props = withDefaults(
  defineProps<{
    sig?: string
    name?: string
    returns?: string
    badge?: string
    badgeType?: string
    aliasFor?: string
    desc?: string
    type?: 'method' | 'property' | 'constant'
    example?: string
    deprecated?: boolean | string
  }>(),
  {
    type: 'method'
  }
)

function escapeHtml(text: string): string {
  return text
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;')
}

function splitParams(str: string): string[] {
  const result: string[] = []
  let depth = 0
  let current = ''
  for (let i = 0; i < str.length; i++) {
    const char = str[i]
    if (char === '<' || char === '(' || char === '[' || char === '{') {
      depth++
      current += char
    } else if (char === '>' || char === ')' || char === ']' || char === '}') {
      depth--
      current += char
    } else if (char === ',' && depth === 0) {
      if (current.trim().length > 0) {
        result.push(current.trim())
      }
      current = ''
    } else {
      current += char
    }
  }
  if (current.trim().length > 0) {
    result.push(current.trim())
  }
  return result
}

function tokenizeType(typeStr: string): string {
  // Handles union or intersection types like string|callable, ?array, list<string>
  const parts = typeStr.split(/([|&])/)
  return parts.map(part => {
    if (part === '|' || part === '&') {
      return `<span class="token-punct">${escapeHtml(part)}</span>`
    }
    return `<span class="token-type">${escapeHtml(part.trim())}</span>`
  }).join('')
}

function tokenizeParam(param: string): string {
  if (param === '...') {
    return `<span class="token-punct">...</span>`
  }

  // Regex to match optional type, variable (with optional ... or &), and optional default value
  // Example: "int $timeout = 30", "?array $updates = null", "mixed ...$handlers", "$text"
  const m = param.match(/^(?:([\s\S]+?)\s+)?(&?(?:\.{3})?\$[a-zA-Z0-9_]+)(?:\s*=\s*([\s\S]+))?$/)
  if (!m) {
    return `<span class="token-param-raw">${escapeHtml(param)}</span>`
  }

  const [, typePart, varPart, defaultPart] = m
  let out = ''

  if (typePart) {
    out += tokenizeType(typePart) + ' '
  }

  if (varPart.startsWith('...')) {
    out += `<span class="token-punct">...</span><span class="token-var">${escapeHtml(varPart.slice(3))}</span>`
  } else if (varPart.startsWith('&')) {
    out += `<span class="token-punct">&amp;</span><span class="token-var">${escapeHtml(varPart.slice(1))}</span>`
  } else {
    out += `<span class="token-var">${escapeHtml(varPart)}</span>`
  }

  if (defaultPart !== undefined) {
    out += ` <span class="token-punct">=</span> <span class="token-val">${escapeHtml(defaultPart)}</span>`
  }

  return out
}

const highlightedSignature = computed(() => {
  if (!props.sig) {
    if (props.name) {
      return `<span class="token-fn">${escapeHtml(props.name)}</span>`
    }
    return ''
  }

  const s = props.sig.trim()

  // 1. Constants: e.g. "public const string BOT_API_VERSION = '10.3'" or "public const string API_VERSION = self::BOT_API_VERSION"
  const constMatch = s.match(/^(?:(public|protected|private)\s+)?(?:(final)\s+)?(const)\s+([a-zA-Z0-9_\\]+)\s+([a-zA-Z0-9_]+)\s*=\s*([\s\S]+)$/)
  if (constMatch) {
    const [, vis, finalKw, c, type, name, val] = constMatch
    let out = ''
    if (vis) {
      out += `<span class="token-keyword">${escapeHtml(vis)}</span> `
    }
    if (finalKw) {
      out += `<span class="token-keyword">${escapeHtml(finalKw)}</span> `
    }
    out += `<span class="token-keyword">${escapeHtml(c)}</span> `
    out += `<span class="token-type">${escapeHtml(type)}</span> `
    out += `<span class="token-const">${escapeHtml(name)}</span> `
    out += `<span class="token-punct">=</span> `

    if (val.trim().startsWith('self::')) {
      out += `<span class="token-class">self</span><span class="token-punct">::</span><span class="token-const">${escapeHtml(val.trim().slice(6))}</span>`
    } else {
      out += `<span class="token-val">${escapeHtml(val.trim())}</span>`
    }
    return out
  }

  // 2. Class Properties (PHP 8.4 asymmetric visibility or standard)
  // Handles:
  // "public private(set) ?Update $update = null"
  // "public private(set) ContextResolver $context"
  // "protected ?FlowManager $flowManager = null"
  // "private Config $config"
  const propMatch = s.match(/^(?:((?:public|protected|private)(?:\s+(?:private\(set\)|protected\(set\)|readonly))?|(?:private\(set\)|protected\(set\)|readonly))\s+)?(?:(\??[a-zA-Z0-9_\\]+(?:<[^>]+>)?(?:\|[a-zA-Z0-9_\\?]+)*)\s+)?(\$[a-zA-Z0-9_]+)(?:\s*=\s*([\s\S]+))?$/)
  if (propMatch && (props.type === 'property' || !s.includes('(') || s.includes('(set)'))) {
    const [, vis, type, variable, val] = propMatch
    let out = ''
    if (vis) {
      const visParts = vis.trim().split(/\s+/)
      out += visParts.map(vp => `<span class="token-keyword">${escapeHtml(vp)}</span>`).join(' ') + ' '
    }
    if (type) {
      out += tokenizeType(type) + ' '
    }
    out += `<span class="token-var">${escapeHtml(variable)}</span>`
    if (val !== undefined) {
      out += ` <span class="token-punct">=</span> <span class="token-val">${escapeHtml(val.trim())}</span>`
    }
    return out
  }

  // 3. Methods:
  // Pattern: (prefix)? (name) ( "(" params? ")" ) ( ":" returnType )?
  const methodMatch = s.match(/^(?:(Telegram::|\$bot->|new\s+))?([a-zA-Z0-9_]+)\s*\(([\s\S]*?)\)(?:\s*:\s*([\s\S]+))?$/)
  if (!methodMatch) {
    // If not matching strict pattern, format gracefully
    return `<span class="token-fn">${escapeHtml(s)}</span>`
  }

  const [, prefix, name, paramsStr, retType] = methodMatch
  let result = ''

  if (prefix) {
    if (prefix.trim() === 'new') {
      result += `<span class="token-keyword">new</span> `
    } else if (prefix.startsWith('Telegram::')) {
      result += `<span class="token-class">Telegram</span><span class="token-punct">::</span>`
    } else if (prefix.startsWith('$bot->')) {
      result += `<span class="token-var">$bot</span><span class="token-punct">-&gt;</span>`
    } else {
      result += `<span class="token-class">${escapeHtml(prefix)}</span>`
    }
  }

  result += `<span class="token-fn">${escapeHtml(name)}</span><span class="token-punct">(</span>`

  if (paramsStr && paramsStr.trim().length > 0) {
    const params = splitParams(paramsStr.trim())
    const tokenized = params.map(p => tokenizeParam(p))
    result += tokenized.join('<span class="token-punct">, </span>')
  }

  result += `<span class="token-punct">)</span>`

  const effectiveReturn = retType || props.returns
  if (retType) {
    result += `<span class="token-punct">: </span><span class="token-ret">${escapeHtml(retType.trim())}</span>`
  }

  return result
})

const resolvedBadgeClass = computed(() => {
  if (props.badgeType) {
    return `badge-${props.badgeType}`
  }
  if (!props.badge) return ''
  const b = props.badge.toLowerCase()
  if (b.includes('canonical')) return 'badge-canonical'
  if (b.includes('alias')) return 'badge-alias'
  if (b.includes('hook')) return 'badge-hook'
  if (b.includes('internal')) return 'badge-internal'
  if (b.includes('factory')) return 'badge-factory'
  if (b.includes('dynamic')) return 'badge-dynamic'
  if (b.includes('context') || b.includes('shortcut')) return 'badge-context'
  if (b.includes('visibility') || b.includes('property') || b.includes('private(set)')) return 'badge-prop'
  if (b.includes('constant')) return 'badge-const'
  return 'badge-default'
})
</script>

<template>
  <div class="api-card" :class="[`type-${type}`, { 'is-deprecated': deprecated }]">
    <div class="api-card-header">
      <code class="api-signature" v-html="highlightedSignature" />

      <div class="api-card-badges">
        <span v-if="returns && !sig?.includes(':')" class="api-badge return">
          <span class="badge-arrow">→</span>
          <code>{{ returns }}</code>
        </span>
        <span v-if="badge" class="api-badge" :class="resolvedBadgeClass">
          {{ badge }}
        </span>
        <span v-if="deprecated" class="api-badge badge-deprecated">
          {{ typeof deprecated === 'string' ? deprecated : 'Deprecated' }}
        </span>
      </div>
    </div>

    <div v-if="aliasFor" class="api-alias-banner">
      <span class="alias-icon">👉</span>
      <span class="alias-text">
        <strong>DX Shorthand:</strong> Alias for <code>{{ aliasFor }}</code>
      </span>
    </div>

    <div class="api-card-desc">
      <slot>{{ desc }}</slot>
    </div>

    <div v-if="example" class="api-card-example">
      <div class="example-title">Usage Example</div>
      <pre class="example-code"><code>{{ example }}</code></pre>
    </div>

    <slot name="extra" />
  </div>
</template>
