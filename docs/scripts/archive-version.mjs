import fs from 'node:fs'
import path from 'node:path'
import { fileURLToPath } from 'node:url'

const __dirname = path.dirname(fileURLToPath(import.meta.url))
const docsDir = path.resolve(__dirname, '..')
const versionsFile = path.resolve(docsDir, 'versions.json')

function readVersions() {
  if (!fs.existsSync(versionsFile)) {
    return { current: '1.0.0-alpha.1', archived: [] }
  }
  return JSON.parse(fs.readFileSync(versionsFile, 'utf-8'))
}

function writeVersions(data) {
  fs.writeFileSync(versionsFile, JSON.stringify(data, null, 2) + '\n', 'utf-8')
}

function copyDirRecursive(src, dest) {
  fs.mkdirSync(dest, { recursive: true })
  const entries = fs.readdirSync(src, { withFileTypes: true })
  for (const entry of entries) {
    const srcPath = path.join(src, entry.name)
    const destPath = path.join(dest, entry.name)
    if (entry.isDirectory()) {
      copyDirRecursive(srcPath, destPath)
    } else {
      fs.copyFileSync(srcPath, destPath)
    }
  }
}

const args = process.argv.slice(2)

if (args.length === 0 || args.includes('--help') || args.includes('-h')) {
  console.log(`
📖 Tueen Telegram Docs Versioning CLI

Usage:
  node scripts/archive-version.mjs <archive-version> [new-current-version]
  node scripts/archive-version.mjs --set-current <new-version>

Examples:
  npm run docs:archive 1.0.0-alpha.1 1.0.0-alpha.2
    -> Archives current docs as v1.0.0-alpha.1, then sets current to v1.0.0-alpha.2.

  npm run docs:archive 1.0.0-alpha.1
    -> Archives v1.0.0-alpha.1 and adds it to the archived list.

  npm run docs:version 1.0.0-beta.1
    -> Only updates the current active version string in versions.json.
`)
  process.exit(0)
}

if (args[0] === '--set-current') {
  const newVersion = args[1]
  if (!newVersion) {
    console.error('❌ Error: Please specify a version string.')
    process.exit(1)
  }
  const data = readVersions()
  data.current = newVersion
  writeVersions(data)
  console.log(`✅ Current docs version updated to v${newVersion}`)
  process.exit(0)
}

const archiveVersion = args[0]
const nextCurrentVersion = args[1]

if (!archiveVersion) {
  console.error('❌ Error: Missing archive version argument.')
  process.exit(1)
}

const targetDir = path.resolve(docsDir, 'versions', archiveVersion)

if (fs.existsSync(targetDir)) {
  console.error(`⚠️ Version ${archiveVersion} already exists at ${targetDir}. Remove it first if you want to overwrite.`)
  process.exit(1)
}

console.log(`📦 Archiving docs for version v${archiveVersion}...`)

// 1. Copy guide directory
const srcGuide = path.resolve(docsDir, 'guide')
const destGuide = path.resolve(targetDir, 'guide')
copyDirRecursive(srcGuide, destGuide)

// 2. Copy and adapt index.md if present
const srcIndex = path.resolve(docsDir, 'index.md')
const destIndex = path.resolve(targetDir, 'index.md')
if (fs.existsSync(srcIndex)) {
  let indexContent = fs.readFileSync(srcIndex, 'utf-8')
  // Update guide link in hero to point to this archived version's guide
  indexContent = indexContent.replace(
    /link:\s*\/guide\/getting-started/g,
    `link: /versions/${archiveVersion}/guide/getting-started`
  )
  fs.writeFileSync(destIndex, indexContent, 'utf-8')
}

// 3. Create frozen sidebar.json snapshot for this archived version
const versionPrefix = `/versions/${archiveVersion}/guide/`
const frozenSidebar = [
  {
    text: `Getting Started (v${archiveVersion})`,
    items: [
      { text: 'Introduction & Setup', link: `${versionPrefix}getting-started` },
      { text: 'Configuration', link: `${versionPrefix}configuration` },
    ]
  },
  {
    text: 'Updates & Running Modes',
    items: [
      { text: 'Running Modes (Webhook & Polling)', link: `${versionPrefix}running-modes` },
      { text: 'Update & Message Helpers', link: `${versionPrefix}update-and-message-helpers` },
    ]
  },
  {
    text: 'Bot Features & UI',
    items: [
      { text: 'Calling Methods & Types', link: `${versionPrefix}methods-and-types` },
      { text: 'Keyboards & Interactive UI', link: `${versionPrefix}keyboards` },
      { text: 'File Upload & Download', link: `${versionPrefix}file-upload-download` },
      { text: 'Payments & Telegram Stars', link: `${versionPrefix}payments-and-stars` },
    ]
  },
  {
    text: 'Architecture & Reference',
    items: [
      { text: 'Error Handling & Hooks', link: `${versionPrefix}error-handling-and-hooks` },
      { text: 'Pipeline & Middlewares', link: `${versionPrefix}pipeline-middleware` },
      { text: 'Enums Reference', link: `${versionPrefix}enums` },
      { text: 'Schema & Code Generator', link: `${versionPrefix}generator` },
    ]
  }
]
fs.writeFileSync(
  path.resolve(targetDir, 'sidebar.json'),
  JSON.stringify(frozenSidebar, null, 2) + '\n',
  'utf-8'
)

// 4. Update versions.json
const versionsData = readVersions()
const exists = versionsData.archived.some(v => v.version === archiveVersion)
if (!exists) {
  versionsData.archived.unshift({
    version: archiveVersion,
    link: `/versions/${archiveVersion}/`
  })
}

if (nextCurrentVersion) {
  versionsData.current = nextCurrentVersion
  console.log(`✨ Updated current active version to v${nextCurrentVersion}`)
}

writeVersions(versionsData)

console.log(`🎉 Successfully archived v${archiveVersion}!`)
console.log(`📁 Location: docs/versions/${archiveVersion}/`)
