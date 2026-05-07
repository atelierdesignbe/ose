# Hero

Bloc d'introduction principal d'une page. Supporte plusieurs configurations de layout, d'image de couverture et de contenu éditorial. Son thème visuel est entièrement piloté par les tokens.

## Fichiers

| Fichier | Rôle |
|---|---|
| `fields.php` | Déclaration des champs ACF (field group) |
| `markup.php` | Template PHP/HTML |
| `hero.scss` | Styles |
| `hero.js` | Comportement JavaScript (parallax, etc.) |
| `hero.tokens.json` | Configuration par défaut |

## Tokens (`hero.tokens.json`)

### Types de layout (`default.types`)

| Valeur | Description |
|---|---|
| `fullsize` | Hero plein écran, image en fond *(défaut)* |
| `auto` | Hauteur automatique selon le contenu |
| `below` | Image positionnée sous le bloc de texte |

### Styles de couverture (`default.coverStyle`)

| Valeur | Comportement |
|---|---|
| `fullsize` | Image en fond plein format avec effet parallax *(défaut)* |
| `fit` | Image contenue, rendue statiquement (AOS fadeinup) |
| `fill` | Image en remplissage du conteneur |
| `none` | Aucune image — le champ Cover est masqué dans l'admin |

### Labels (`default.labels`)

| Valeur | Comportement |
|---|---|
| `default` | Aucun badge affiché |
| `title` | Badge affichant le titre de la page courante |
| `custom` | Badge affichant une valeur libre saisie dans l'admin |

### Autres tokens notables

| Token | Défaut | Description |
|---|---|---|
| `type` | `fullsize` | Layout par défaut |
| `hasCover` | `true` | Active/désactive le champ image |
| `hasTitle` | `true` | Active/désactive le champ titre |
| `hasButtons` | `true` | Active/désactive les boutons CTA |
| `maxButtons` | `2` | Nombre max de boutons (1 = champ link simple) |
| `hasScroll` | `true` | Affiche le composant scroll |
| `hasSocial` | `true` | Affiche le composant social |
| `headings` | `2xl, xl` | Classes de taille autorisées pour le titre |
| `paragraphs` | `xl, lg, md` | Classes de taille autorisées pour le contenu |

## Champs ACF (`fields.php`)

| Champ (`name`) | Type | Condition | Description |
|---|---|---|---|
| `type` | `button_group` | — | Layout : `fullsize` / `auto` / `below` |
| `cover-status` | `button_group` | `type != below` | Style de la couverture |
| `cover` | `image` | `cover-status != none` ou `type = below` | Image de couverture |
| `label-status` | `button_group` | — | Mode du badge |
| `label` | `text` | `label-status = custom` | Texte libre du badge |
| `title` | `wysiwyg` | `hasTitle` | Titre (headings : `2xl`, `xl`) |
| `content` | `wysiwyg` | — | Contenu (paragraphs : `xl`, `lg`, `md`) |
| `buttons` | `repeater` | `hasButtons` | Boutons CTA (max `maxButtons`). Si `maxButtons = 1` : champ `link` simple |

**Sous-champs du repeater `buttons` :** `link` (required) · `color` (button_group, depuis colorSystem) · `style` (button_group, depuis responsiveSizing)

## Markup (`markup.php`)

### Variables calculées

| Variable | Description |
|---|---|
| `$label` | Titre de page ou valeur custom selon `label-status` |
| `$coverState` | État résolu : `none` / `fullsize` / `fit` / `fill` / `below` |
| `$theme` | Tableau `[thème, bg-layout]` issu de `tokens[theme][default][$coverState]` |
| `$firstColor` | Première couleur de section suivante (logique `hero-collapse`) |

### Structure HTML

```html
<div class="hero hero-{coverState} hero-type-{type} bg-layout-{layout} theme-{theme} [hero-collapse]">
  <div class="hero-inside [theme-{contentTheme}]">
    <div class="hero-wrapper">
      <div class="hero-container">
        <div class="hero-content">
          <!-- Badge label (si label-status != default) -->
          <!-- Wysiwyg title -->
          <!-- Wysiwyg content -->
          <!-- Boutons CTA (si hasButtons) -->
          <!-- Scroll + Social (si type = below → placés ici) -->
        </div>
      </div>
      <!-- Cover image (si coverState != none) -->
    </div>
    <!-- hero-below-layout (si type = below) -->
    <!-- Scroll + Social (si type != below) -->
  </div>
</div>
```

### Logique cover

| `coverState` | Rendu |
|---|---|
| `fit` | Image statique — AOS : `animate-fadeinup animate-delay-400` |
| `fullsize` / `fill` | Image en `parallax-image-wrapper` — AOS : `animate-fadeinzoomout` |
| `none` | Bloc `.hero-cover` non rendu |
| `below` | `$coverState` forcé à `below`, image rendue via le champ `cover` |

### Classe `hero-collapse`

Appliquée automatiquement quand `$theme[0]` (thème du hero) correspond à `$firstColor` (première couleur de section suivante). Permet une transition visuelle sans rupture entre le hero et le contenu.

### Scroll & Social

Position dans le DOM selon le type de layout :
- `type = below` → injectés dans `.hero-content`
- `type = fullsize` / `auto` → injectés après `.hero-wrapper`, dans `.hero-inside`

Chacun conditionnel via `hasScroll` / `hasSocial` (`true` par défaut).
