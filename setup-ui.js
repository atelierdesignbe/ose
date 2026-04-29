#!/usr/bin/env node
/**
 * setup-ui.js — Gestion du dépôt ad-ui
 * Usage: node setup-ui.js [init|update|tokens|build|status]
 */

import { execSync }      from 'child_process';
import fs                from 'fs';
import path              from 'path';
import { fileURLToPath } from 'url';

// ============================================================
// Constantes
// ============================================================
const __dirname     = path.dirname( fileURLToPath( import.meta.url ) );
const THEME_DIR     = path.join( __dirname, 'wordpress/wp-content/themes/atelierdesign' );
const UI_DIR        = path.join( THEME_DIR,  'ad-ui' );
const UI_CORE_DIR   = path.join( UI_DIR,     'core' );
const UI_TOKENS_DIR = path.join( UI_CORE_DIR, 'src', 'data' );
const TOKENS_DIR    = path.join( THEME_DIR,  'tokens' );
const UI_REPO       = 'git@github.com:atelierdesignbe/ui.git';
const UI_BRANCH     = 'main'; // fallback si aucun tag trouvé
const PATCHES_DIR   = path.join( __dirname, 'patches', 'ad-ui' );

// ============================================================
// Helpers
// ============================================================

/** Log formaté */
const log = {
  info:    ( msg ) => console.log( `\n${msg}` ),
  success: ( msg ) => console.log( `✅ ${msg}` ),
  warning: ( msg ) => console.warn( `⚠️  ${msg}` ),
  error:   ( msg ) => console.error( `❌ ${msg}` ),
  step:    ( msg ) => console.log( `   → ${msg}` ),
};

/** Exécuter une commande shell — exit si erreur */
function exec( command, cwd = process.cwd() ) {
  try {
    execSync( command, { stdio: 'inherit', cwd } );
  } catch ( error ) {
    log.error( `Commande échouée : ${command}` );
    log.error( error.message );
    process.exit( 1 );
  }
}

/** Exécuter une commande et retourner le résultat (sans afficher) */
function execSilent( command, cwd = process.cwd() ) {
  return execSync( command, { cwd, stdio: 'pipe' } ).toString().trim();
}

/** Vérifier si le repo ad-ui existe */
function uiExists() {
  return fs.existsSync( UI_DIR ) && fs.existsSync( path.join( UI_DIR, '.git' ) );
}

/** Vérifier les prérequis système */
function checkDependencies() {
  [ 'git', 'node' ].forEach( dep => {
    try {
      execSync( `command -v ${dep}`, { stdio: 'ignore' } );
    } catch {
      log.error( `"${dep}" est requis mais non installé.` );
      process.exit( 1 );
    }
  } );
}

/**
 * Récupérer le dernier tag Git du repo
 * Fallback sur UI_BRANCH si aucun tag trouvé
 */
function getLatestTag( dir ) {
  try {
    execSync( 'git fetch origin --tags --force', { cwd: dir, stdio: 'pipe' } );

    const allTags = execSilent( 'git tag', dir );
    const tags    = allTags.split( '\n' ).map( t => t.trim() ).filter( Boolean );

    // Tri sémantique en ignorant le préfixe "v" (gère v0.6.0 et 0.8.0)
    const normalize = v => v.replace( /^v/, '' ).split( '.' ).map( n => parseInt( n ) || 0 );
    tags.sort( ( a, b ) => {
      const va = normalize( a );
      const vb = normalize( b );
      for ( let i = 0; i < Math.max( va.length, vb.length ); i++ ) {
        const diff = ( vb[ i ] || 0 ) - ( va[ i ] || 0 );
        if ( diff !== 0 ) return diff;
      }
      return 0;
    } );

    return tags[ 0 ];
  } catch {
    log.warning( `Aucun tag trouvé, fallback sur "${UI_BRANCH}".` );
    return UI_BRANCH;
  }
}

// ============================================================
// Patches — écrasement de fichiers ad-ui par des versions custom
// Structure miroir : patches/ad-ui/core/src/js/foo.js
//                 → ad-ui/core/src/js/foo.js
// ============================================================
function applyPatches() {
  if ( ! fs.existsSync( PATCHES_DIR ) ) return; // rien à faire

  log.info( '🩹 Application des patches ad-ui...' );

  let count = 0;

  function walk( srcDir, relBase = '' ) {
    for ( const entry of fs.readdirSync( srcDir, { withFileTypes: true } ) ) {
      const relPath = path.join( relBase, entry.name );
      const srcPath = path.join( srcDir, entry.name );
      const dstPath = path.join( UI_DIR, relPath );

      if ( entry.isDirectory() ) {
        walk( srcPath, relPath );
      } else {
        fs.mkdirSync( path.dirname( dstPath ), { recursive: true } );
        fs.copyFileSync( srcPath, dstPath );
        log.step( `${relPath}` );
        count++;
      }
    }
  }

  walk( PATCHES_DIR );
  log.success( `${count} fichier(s) patchés.\n` );
}

// ============================================================
// Tokens
// ============================================================
function copyTokens() {
  log.info( '📝 Copie des tokens...' );

  if ( ! fs.existsSync( TOKENS_DIR ) ) {
    log.error( `Dossier tokens introuvable : ${TOKENS_DIR}` );
    log.info(  '💡 Créez un dossier "tokens" dans votre thème avec vos fichiers de tokens.' );
    return false;
  }

  if ( ! uiExists() ) {
    log.error( 'Dossier ad-ui introuvable. Lancez "init" d\'abord.' );
    return false;
  }

  fs.mkdirSync( UI_TOKENS_DIR, { recursive: true } );

  const files = fs.readdirSync( TOKENS_DIR ).filter( file => {
    const fullPath = path.join( TOKENS_DIR, file );
    return ! fs.statSync( fullPath ).isDirectory() && ! file.startsWith( '.' );
  } );

  if ( files.length === 0 ) {
    log.warning( 'Aucun fichier trouvé dans le dossier tokens.' );
    return false;
  }

  let copied = 0;
  files.forEach( file => {
    try {
      fs.copyFileSync(
        path.join( TOKENS_DIR, file ),
        path.join( UI_TOKENS_DIR, file )
      );
      log.step( `${file} copié` );
      copied++;
    } catch ( error ) {
      log.error( `Échec copie de ${file} : ${error.message}` );
    }
  } );

  log.success( `${copied} fichier(s) de tokens copiés.\n` );
  return true;
}

// ============================================================
// Build
// ============================================================
function buildUI() {
  log.info( '⚙️  Build de ad-ui...' );

  if ( ! uiExists() ) {
    log.error( 'ad-ui introuvable. Lancez "init" d\'abord.' );
    process.exit( 1 );
  }

  // npx pnpm bypass le conflit corepack/yarn du package.json racine
  exec( 'COREPACK_ENABLE_STRICT=0 npx pnpm install', UI_CORE_DIR );
  exec( 'COREPACK_ENABLE_STRICT=0 npx pnpm run build', UI_CORE_DIR );

  log.success( 'Build terminé !' );
}

// ============================================================
// Init
// ============================================================
function initUI() {
  log.info( '🚀 Initialisation de ad-ui...\n' );
  checkDependencies();

  if ( uiExists() ) {
    log.success( 'Le dépôt ad-ui existe déjà.' );
  } else {
    log.info( `📦 Clonage de ${UI_REPO}...` );
    exec( `git clone ${UI_REPO} ad-ui`, THEME_DIR );
    log.success( 'Dépôt cloné !' );
  }

  // Checkout du dernier tag
  const tag = getLatestTag( UI_DIR );
  log.info( `🏷️  Checkout du tag : ${tag}` );
  exec( `git checkout --force ${tag}`, UI_DIR );
  log.success( `Tag ${tag} actif !` );

  applyPatches();
  copyTokens();
  buildUI();

  log.info( '🎉 Installation terminée ! Vous pouvez démarrer le développement.\n' );
}

// ============================================================
// Update
// ============================================================
function updateUI() {
  log.info( '🔄 Mise à jour de ad-ui...\n' );

  if ( ! uiExists() ) {
    log.warning( 'ad-ui introuvable, initialisation en cours...' );
    initUI();
    return;
  }

  // Fetch + checkout du dernier tag
  const tag = getLatestTag( UI_DIR );
  log.info( `🏷️  Mise à jour vers le tag : ${tag}` );
  exec( `git checkout --force ${tag}`, UI_DIR );
  log.success( `Tag ${tag} actif !` );

  applyPatches();
  copyTokens();
  buildUI();

  log.info( '🎉 Mise à jour terminée !\n' );
}

// ============================================================
// Status
// ============================================================
function statusUI() {
  log.info( '📊 Status du projet\n' );

  const hasUI    = uiExists();
  const hasTokens = fs.existsSync( TOKENS_DIR );
  const hasDist   = fs.existsSync( path.join( THEME_DIR, 'dist' ) );

  console.log( `UI repo    : ${ hasUI     ? '✅ Présent'  : '❌ Absent' }` );
  console.log( `Tokens     : ${ hasTokens ? '✅ Présent'  : '❌ Absent' }` );
  console.log( `Build dist : ${ hasDist   ? '✅ Buildé'   : '❌ Non buildé' }` );

  if ( hasUI ) {
    try {
      const currentTag    = execSilent( 'git describe --tags --abbrev=0', UI_DIR );
      const latestTag     = getLatestTag( UI_DIR );
      const isUpToDate    = currentTag === latestTag;

      console.log( `Tag actuel : 🏷️  ${currentTag}` );
      console.log( `Dernier tag: 🏷️  ${latestTag} ${ isUpToDate ? '✅ À jour' : '⚠️  Mise à jour disponible' }` );
    } catch {
      log.warning( 'Impossible de lire le tag Git.' );
    }

    log.info( '📋 Git status de ad-ui :' );
    exec( 'git status --short', UI_DIR );
  }
}

// ============================================================
// CLI
// ============================================================
const command = process.argv[2];
const handlers = {
  init:   initUI,
  update: updateUI,
  tokens: copyTokens,
  build:  buildUI,
  status: statusUI,
};

if ( ! handlers[ command ] ) {
  console.log( '\nUsage: node setup-ui.js [init|update|tokens|build|status]\n' );
  console.log( '  init    → Clone ad-ui + checkout dernier tag + tokens + build' );
  console.log( '  update  → Fetch + checkout dernier tag + tokens + build' );
  console.log( '  tokens  → Copie uniquement les tokens' );
  console.log( '  build   → Build uniquement ad-ui' );
  console.log( '  status  → État du projet + tag actuel vs dernier tag\n' );
  process.exit( 1 );
}

handlers[ command ]();