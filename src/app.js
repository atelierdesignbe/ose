import './styles/app.scss'

import './scripts/expand'
import './scripts/menu'
import './scripts/ajax'
// import './styles/tailwind.scss'
console.log('🎨 Atelier Design Theme loaded');

// HMR Vite (pour JS/CSS uniquement)
if (import.meta.hot) {
  import.meta.hot.accept();
}

// Votre code
document.addEventListener('DOMContentLoaded', () => {
  console.log('✅ DOM ready');
});