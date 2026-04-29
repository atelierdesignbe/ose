/**
 * vite-plugin-tokens.js
 *
 * Génère un virtual module CSS à partir des blocs "styles" des *.tokens.json.
 * → Aucun fichier écrit sur disque — tout est injecté directement dans le bundle.
 * → Les @media queries sont résolues en JS (pas de dépendance PostCSS/@screen).
 *
 * Flow : *.tokens.json → virtual module CSS → bundle final (dev & prod)
 *
 * ─── Format JSON ────────────────────────────────────────────────────────────
 *  "styles": {
 *    "radius-top":  24,           // number → fluid(24, 'current')
 *    "shadow":      "0 0 20px…",  // string → valeur brute CSS
 *    "item": {                    // objet imbriqué → --cta-item-*
 *      "radius":        0,
 *      "content-width": "100%"
 *    },
 *    "sm": {                      // fluid(n, 'sm') — CSS mobile-first (pas de @media)
 *      "item": { "padding-y": 48 }
 *    },
 *    "md": {                      // @media (min-width: 600px) { fluid(n, 'lg') }
 *      "item": { "padding-y": 112 }
 *    }
 *  }
 * ────────────────────────────────────────────────────────────────────────────
 */

import { readFileSync, readdirSync, existsSync } from 'node:fs'
import { resolve, join, basename, dirname } from 'node:path'
import { fileURLToPath } from 'node:url'

const __dirname = dirname(fileURLToPath(import.meta.url))

const VIRTUAL_ID      = 'virtual:design-tokens'
const RESOLVED_ID     = '\0virtual:design-tokens.css'
const SCREEN_KEYS     = ['sm', 'md', 'lg', 'xl', '2xl']
const FLUID_SCREEN_MD = 'lg'   // dans @media md → fluid(n, 'lg')

// Propriétés qui doivent utiliser 'sm' (mobile) au lieu de 'current'
const SIZE_PROP_PATTERNS = ['font-size', 'line-height', 'size']

// Breakpoints résolus en dur depuis tailwind.config (ad-ui core)
// → évite de devoir importer le config TW au runtime du plugin
const BREAKPOINTS = {
  sm:  '0px',
  md:  '600px',
  lg:  '1025px',
  xl:  '1440px',
}

// ── fluid() reproduit en JS ───────────────────────────────────────────────────
// Identique à la fonction SASS dans _functions.scss :
//   calc((#{$value} / var(--tw-screen-#{$screen})) * var(--tw-screen-max) * var(--tw-scale))

function fluid(value, screen) {
  return `calc((${value} / var(--tw-screen-${screen})) * var(--tw-screen-max) * var(--tw-scale))`
}

// Si fluidScreen === 'current' et que la propriété est une taille → utilise 'sm'
function resolveFluidScreen(propKey, fluidScreen) {
  if (fluidScreen !== 'current') return fluidScreen
  if (SIZE_PROP_PATTERNS.some(p => propKey.includes(p))) return 'sm'
  return fluidScreen
}

function resolveValue(val, fluidScreen) {
  if (typeof val === 'number') return fluid(val, fluidScreen)
  return String(val)
}

// ── Aplatissement récursif ────────────────────────────────────────────────────

function flattenStyles(obj, component, prefix = '', fluidScreen = 'current') {
  const vars = {}
  for (const [key, val] of Object.entries(obj)) {
    if (SCREEN_KEYS.includes(key)) continue
    const fullKey  = prefix ? `${prefix}-${key}` : key
    const propName = `--${component}-${fullKey}`
    if (val !== null && typeof val === 'object' && !Array.isArray(val)) {
      Object.assign(vars, flattenStyles(val, component, fullKey, fluidScreen))
    } else {
      vars[propName] = resolveValue(val, resolveFluidScreen(fullKey, fluidScreen))
    }
  }
  return vars
}

// Extrait uniquement les props "size" (number) avec le screen donné — pour le bloc @media
function flattenSizeStyles(obj, component, prefix = '', fluidScreen) {
  const vars = {}
  for (const [key, val] of Object.entries(obj)) {
    if (SCREEN_KEYS.includes(key)) continue
    const fullKey  = prefix ? `${prefix}-${key}` : key
    const propName = `--${component}-${fullKey}`
    if (val !== null && typeof val === 'object' && !Array.isArray(val)) {
      Object.assign(vars, flattenSizeStyles(val, component, fullKey, fluidScreen))
    } else if (typeof val === 'number' && SIZE_PROP_PATTERNS.some(p => fullKey.includes(p))) {
      vars[propName] = resolveValue(val, fluidScreen)
    }
  }
  return vars
}

// ── Génération CSS par composant ──────────────────────────────────────────────

function generateComponentCSS(name, styles) {
  const lines = [`.${name} {`]

  // Racine → 'current' pour les autres props, 'sm' pour les size props (via resolveFluidScreen)
  for (const [p, v] of Object.entries(flattenStyles(styles, name, '', 'current'))) {
    lines.push(`  ${p}: ${v};`)
  }

  // "sm" block → fluid(n, 'sm') dans le CSS par défaut (mobile-first, pas de @media)
  if (styles.sm) {
    const smVars = flattenStyles(styles.sm, name, '', 'sm')
    if (Object.keys(smVars).length) {
      lines.push('')
      for (const [p, v] of Object.entries(smVars)) lines.push(`  ${p}: ${v};`)
    }
  }

  lines.push('}')

  // "@media md" :
  //   - size props depuis la racine (fluid 'lg') → auto-générées même sans bloc "md"
  //   - surchargées/complétées par le bloc "md" explicite
  const rootSizeForMd = flattenSizeStyles(styles, name, '', FLUID_SCREEN_MD)
  const mdOverrides   = styles.md ? flattenStyles(styles.md, name, '', FLUID_SCREEN_MD) : {}
  const mdVars        = { ...rootSizeForMd, ...mdOverrides }

  if (Object.keys(mdVars).length) {
    const bp = BREAKPOINTS.md
    lines.push('', `@media (min-width: ${bp}) {`, `.${name} {`)
    for (const [p, v] of Object.entries(mdVars)) lines.push(`  ${p}: ${v};`)
    lines.push('}', '}')
  }

  return lines.join('\n')
}

// ── Scan tokens ───────────────────────────────────────────────────────────────

function findTokenFiles(dir) {
  if (!existsSync(dir)) return []
  const results = []
  function walk(d) {
    for (const entry of readdirSync(d, { withFileTypes: true })) {
      const p = join(d, entry.name)
      if (entry.isDirectory()) walk(p)
      else if (entry.isFile() && entry.name.endsWith('.tokens.json')) results.push(p)
    }
  }
  walk(dir)
  return results.sort()
}

function generateTokensCSS(componentsDir) {
  const blocks = []

  for (const file of findTokenFiles(componentsDir)) {
    let content
    try { content = JSON.parse(readFileSync(file, 'utf-8')) }
    catch { console.warn(`[tokens] ⚠️  JSON invalide: ${basename(file)}`); continue }

    const styles = content.styles
    if (!styles || !Object.keys(styles).length) continue

    blocks.push(generateComponentCSS(basename(file).replace(/\.tokens\.json$/, ''), styles))
  }

  return blocks.join('\n\n')
}

// ── Plugin Vite ───────────────────────────────────────────────────────────────

export function tokensPlugin(options = {}) {
  const themeRoot = resolve(__dirname, 'wordpress/wp-content/themes/atelierdesign')

  // Accepte un tableau de répertoires ou les options legacy
  const tokensDirs = options.tokensDirs ?? [
    options.componentsDir ?? resolve(themeRoot, 'components'),
    resolve(themeRoot, 'tokens/global'),
  ]

  const allTokenFiles = () => tokensDirs.flatMap(findTokenFiles)

  let cssCache = ''

  return {
    name: 'vite-plugin-tokens',

    // Résout l'import "virtual:design-tokens"
    resolveId(id) {
      if (id === VIRTUAL_ID) return RESOLVED_ID
    },

    // Retourne le CSS généré depuis tous les répertoires
    load(id) {
      if (id !== RESOLVED_ID) return null
      cssCache = tokensDirs.map(generateTokensCSS).filter(Boolean).join('\n\n')
      const count = cssCache.split('\n').filter(l => l.trim().startsWith('--')).length
      const dirs = tokensDirs.map(d => d.split('/').slice(-2).join('/')).join(', ')
      console.log(`[tokens] ✅  virtual:design-tokens — ${count} propriétés CSS (${dirs})`)
      return cssCache
    },

    // Watch en dev → invalide le module + HMR
    configureServer(server) {
      for (const f of allTokenFiles()) server.watcher.add(f)
      server.watcher.on('change', (path) => {
        if (!path.endsWith('.tokens.json')) return
        console.log(`[tokens] 🔄  ${basename(path)}`)
        const mod = server.moduleGraph.getModuleById(RESOLVED_ID) ?? server.moduleGraph.getModuleById(VIRTUAL_ID)
        if (mod) server.moduleGraph.invalidateModule(mod)
        server.ws.send({ type: 'full-reload' })
      })
    },
  }
}
