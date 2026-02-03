import './styles/tailwind.scss'
import './styles/app.scss'
console.log('🎨 Atelier Design Theme loaded');
console.log('HERE NEW')

// HMR Vite (pour JS/CSS uniquement)
if (import.meta.hot) {
  import.meta.hot.accept();
}

// Votre code
document.addEventListener('DOMContentLoaded', () => {
  console.log('✅ DOM ready');
});