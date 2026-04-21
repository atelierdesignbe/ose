import { execSync } from 'child_process';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const THEME_DIR = path.join(__dirname, 'wordpress/wp-content/themes', 'atelierdesign');
const TOKENS_SOURCE_DIR = path.join(THEME_DIR, 'tokens');
const UI_DIR = path.join(THEME_DIR, 'ad-ui');
const UI_TOKENS_DIR = path.join(UI_DIR, 'core', 'src', 'data');
const UI_REPO = 'git@github.com:atelierdesignbe/ui.git';
const PATCHES_DIR = path.join(__dirname, 'patches', 'ad-ui');
// const TOKENS_TEMPLATE = path.join(UI_DIR, 'core', 'src', 'data', 'tokens.example.json');
// const TOKENS_FILE = path.join(UI_DIR, 'core', 'src', 'data');

function exec(command, options = {}) {
  try {
    return execSync(command, { stdio: 'inherit', ...options });
  } catch (error) {
    console.error(`❌ Error: ${error.message}`);
    process.exit(1);
  }
}

function checkUIExists() {
  return fs.existsSync(UI_DIR) && fs.existsSync(path.join(UI_DIR, '.git'));
}

function copyTokens() {
  console.log('📝 Copying tokens...\n');

  // Vérifier que le dossier source existe
  if (!fs.existsSync(TOKENS_SOURCE_DIR)) {
    console.error(`❌ Tokens directory not found: ${TOKENS_SOURCE_DIR}`);
    console.log('💡 Create a "tokens" folder in your theme with your token files');
    return false;
  }

  // Vérifier que le dossier UI existe
  if (!fs.existsSync(UI_DIR)) {
    console.error('❌ UI directory not found. Run "init" first.');
    return false;
  }

  // Créer le dossier de destination si nécessaire
  if (!fs.existsSync(UI_TOKENS_DIR)) {
    fs.mkdirSync(UI_TOKENS_DIR, { recursive: true });
    console.log(`📁 Created directory: ${UI_TOKENS_DIR}`);
  }

  // Lire tous les fichiers du dossier tokens
  const tokenFiles = fs.readdirSync(TOKENS_SOURCE_DIR);

  if (tokenFiles.length === 0) {
    console.log('⚠️  No token files found in tokens directory');
    return false;
  }

  let copiedCount = 0;

  // Copier chaque fichier
  tokenFiles.forEach(file => {
    const sourcePath = path.join(TOKENS_SOURCE_DIR, file);
    const destPath = path.join(UI_TOKENS_DIR, file);

    // Ignorer les dossiers et fichiers cachés
    if (fs.statSync(sourcePath).isDirectory() || file.startsWith('.')) {
      return;
    }

    try {
      fs.copyFileSync(sourcePath, destPath);
      console.log(`   ✅ ${file} copied`);
      copiedCount++;
    } catch (error) {
      console.error(`   ❌ Failed to copy ${file}:`, error.message);
    }
  });

  console.log(`\n✅ ${copiedCount} token file(s) copied successfully!\n`);
  return true;
}


function applyPatches() {
  if (!fs.existsSync(PATCHES_DIR)) return;

  console.log('🩹 Applying ad-ui patches...\n');

  let count = 0;

  function walk(srcDir, relBase = '') {
    for (const entry of fs.readdirSync(srcDir, { withFileTypes: true })) {
      const relPath = path.join(relBase, entry.name);
      const srcPath = path.join(srcDir, entry.name);
      const dstPath = path.join(UI_DIR, relPath);

      if (entry.isDirectory()) {
        walk(srcPath, relPath);
      } else {
        fs.mkdirSync(path.dirname(dstPath), { recursive: true });
        fs.copyFileSync(srcPath, dstPath);
        console.log(`   → ${relPath}`);
        count++;
      }
    }
  }

  walk(PATCHES_DIR);
  console.log(`\n✅ ${count} file(s) patched.\n`);
}

function initUI() {
  console.log('🚀 Initializing UI repository...\n');

  if (checkUIExists()) {
    console.log('✅ UI repository already exists');
    return;
  }

  const originalDir = process.cwd();

  try {
    console.log('📦 Cloning UI repository...');
    console.log(`   Repository: ${UI_REPO}`);
    console.log(`   Destination: ${UI_DIR}\n`);

    process.chdir(THEME_DIR);
    exec(`git clone ${UI_REPO} ad-ui`);
    
    console.log('✅ UI repository cloned successfully!', UI_DIR);
  } catch (error) {
    console.error('\n❌ Failed to clone UI repository');
    console.error('💡 Make sure you have SSH access to the repository');
    console.error('Error:', error.message);
    process.exit(1);
  } finally {
    process.chdir(originalDir);
  }

  // Copier les tokens après le clone
  console.log('📝 Configuring project tokens...');
  copyTokens();
  applyPatches();
  console.log('🎉 Setup complete! You can now start developing.');

}

function updateUI() {
  console.log('🔄 Updating UI repository...\n');

  if (!checkUIExists()) {
    console.log('⚠️  UI repository not found. Initializing...\n');
    initUI();
    return;
  }

  const originalDir = process.cwd();

  try {
    process.chdir(UI_DIR);
    
    console.log('📥 Pulling latest changes...');
    exec('git pull origin main');
    
    console.log('\n✅ UI repository updated!\n');
  } catch (error) {
    console.error('\n❌ Failed to update UI repository');
    console.error('Error:', error.message);
    process.exit(1);
  } finally {
    process.chdir(originalDir);
  }

  // Re-copier les tokens après la mise à jour
  console.log('📝 Reapplying project tokens...');
  copyTokens();
  applyPatches();

  console.log('🎉 Update complete!');
}




// // CLI
// const command = process.argv[2];
const command = process.argv[2];

switch (command) {
  case 'init':
    initUI();
    break;
  case 'update':
    updateUI();
    break;
  case 'tokens':
    copyTokens();
    // updateUI();
    break;
  case 'status':
    // statusUI();
    break;
  default:
    console.log('Usage: node setup-ui.js [init|update|status]');
    process.exit(1);
}

