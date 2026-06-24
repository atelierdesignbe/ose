<?php

/**
 * Language switcher for ACF options pages (Polylang)
 * Custom dropdown avec vrai drapeau image + chevron SVG.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Retourne l'URL du drapeau Polylang pour un slug de langue.
 * Permet d'overrider certains slugs (ex: fr → drapeau belge).
 */
function acf_lang_flag_url( string $slug ): string {
    // Override : FR utilise le drapeau belge
    $overrides = [
        'fr' => 'be',
        'en' => 'gb',
    ];

    $flag_code = $overrides[ strtolower( $slug ) ] ?? strtolower( $slug );

    // Polylang stocke ses drapeaux dans /flags/*.png
    if ( defined( 'PLL_LOCAL_URL' ) ) {
        return PLL_LOCAL_URL . '/flags/' . $flag_code . '.png';
    }

    return plugins_url( 'polylang/flags/' . $flag_code . '.png' );
}

// ─── Styles ──────────────────────────────────────────────────────────────────
add_action( 'acf/input/admin_head', function () {

    $screen = get_current_screen();
    if ( ! $screen || strpos( $screen->id, 'acf-options' ) === false ) return;
    if ( ! function_exists( 'pll_the_languages' ) ) return;

    ?>
    <style>
        /* Row wrapper */
        #acf-lang-switcher-row {
            padding: 8px 12px;
            border-top: 1px solid #eee;
            align-items: center;
            gap: 8px;
        }

        #acf-lang-switcher-row > label {
            font-size: 12px;
            font-weight: 600;
            color: #50575e;
            margin: 0;
            white-space: nowrap;
        }

        /* Custom dropdown wrapper */
        .acf-lang-dropdown {
            position: relative;
            flex: 1;
            min-width: 0;
            font-size: 12px;
        }

        /* Trigger button */
        .acf-lang-trigger {
            display: flex;
            align-items: center;
            gap: 5px;
            width: 100%;
            padding: 3px 6px 3px 5px;
            border: 1px solid #8c8f94;
            border-radius: 3px;
            background: #fff;
            cursor: pointer;
            color: #2c3338;
            font-size: 12px;
            text-align: left;
            line-height: 1.4;
            box-sizing: border-box;
        }

        .acf-lang-trigger:focus,
        .acf-lang-dropdown.is-open .acf-lang-trigger {
            border-color: #2271b1;
            box-shadow: 0 0 0 1px #2271b1;
            outline: none;
        }

        .acf-lang-trigger img {
            width: 16px;
            height: 11px;
            object-fit: cover;
            flex-shrink: 0;
            border: 1px solid rgba(0,0,0,.1);
        }

        .acf-lang-trigger .acf-lang-label {
            flex: 1;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        .acf-lang-trigger .acf-lang-chevron {
            width: 10px;
            height: 10px;
            flex-shrink: 0;
            color: #8c8f94;
            transition: transform .15s ease;
        }

        .acf-lang-dropdown.is-open .acf-lang-chevron {
            transform: rotate(180deg);
        }

        /* Dropdown list */
        .acf-lang-list {
            position: absolute;
            top: calc(100% + 2px);
            left: 0;
            right: 0;
            z-index: 9999;
            margin: 0;
            padding: 3px 0;
            list-style: none;
            background: #fff;
            border: 1px solid #8c8f94;
            border-radius: 3px;
            box-shadow: 0 3px 8px rgba(0,0,0,.12);
            display: none;
        }

        .acf-lang-dropdown.is-open .acf-lang-list {
            display: block;
        }

        .acf-lang-list li {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 5px 8px;
            cursor: pointer;
            font-size: 12px;
            color: #2c3338;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        .acf-lang-list li:hover,
        .acf-lang-list li.is-active {
            background: #f0f6fc;
            color: #2271b1;
        }

        .acf-lang-list li.is-active {
            font-weight: 700;
        }

        .acf-lang-list li img {
            width: 16px;
            height: 11px;
            object-fit: cover;
            border: 1px solid rgba(0,0,0,.1);
            flex-shrink: 0;
        }

        /* Masquer la ligne native Polylang */
        #acf-lang-switcher-row ~ .misc-pub-section,
        .submitbox .misc-pub-section.pll-misc-pub-section {
            display: none !important;
        }
    </style>
    <?php
} );

// ─── HTML + JS ───────────────────────────────────────────────────────────────
add_action( 'acf/input/admin_footer', function () {

    $screen = get_current_screen();
    if ( ! $screen || strpos( $screen->id, 'acf-options' ) === false ) return;
    if ( ! function_exists( 'pll_the_languages' ) || ! function_exists( 'pll_current_language' ) ) return;

    $languages = pll_the_languages( [
        'show_flags'    => 0,
        'show_names'    => 1,
        'hide_if_empty' => 0,
        'raw'           => 1,
    ] );

    if ( empty( $languages ) ) return;

    // Priorité : 1) langue explicitement choisie via ?lang=, 2) langue admin courante de Polylang
    // (filtre "All languages" → false), 3) langue par défaut/active du site, 4) première langue dispo.
    $current_lang = isset( $_GET['lang'] ) ? sanitize_key( $_GET['lang'] ) : pll_current_language( 'slug' );

    if ( empty( $current_lang ) || ! isset( $languages[ $current_lang ] ) ) {
        $current_lang = function_exists( 'pll_default_language' ) ? pll_default_language( 'slug' ) : $current_lang;
    }

    $current = $languages[ $current_lang ] ?? reset( $languages );

    $query = $_GET;
    unset( $query['lang'] );
    $base_url = add_query_arg( $query, admin_url( 'admin.php' ) );

    // Chevron SVG
    $chevron = '<svg class="acf-lang-chevron" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';

    ?>
    <div id="acf-lang-switcher-row" style="display:none;">
        <label>Langue</label>
        <div class="acf-lang-dropdown" id="acf-lang-dropdown">

            <!-- Trigger -->
            <button type="button" class="acf-lang-trigger" id="acf-lang-trigger" aria-haspopup="listbox" aria-expanded="false">
                <img
                    src="<?php echo esc_url( acf_lang_flag_url( $current['slug'] ) ); ?>"
                    alt="<?php echo esc_attr( strtoupper( $current['slug'] ) ); ?>"
                    id="acf-lang-flag"
                >
                <span class="acf-lang-label" id="acf-lang-label"><?php echo esc_html( strtoupper( $current['slug'] ) ); ?></span>
                <?php echo $chevron; ?>
            </button>

            <!-- List -->
            <ul class="acf-lang-list" role="listbox" id="acf-lang-list">
                <?php foreach ( $languages as $lang ) :
                    $active = $lang['slug'] === $current_lang ? ' is-active' : '';
                    $url    = add_query_arg( array_merge( $query, [ 'lang' => $lang['slug'] ] ), admin_url( 'admin.php' ) );
                    ?>
                    <li
                        role="option"
                        class="acf-lang-option<?php echo $active; ?>"
                        data-slug="<?php echo esc_attr( $lang['slug'] ); ?>"
                        data-url="<?php echo esc_url( $url ); ?>"
                        data-flag="<?php echo esc_url( acf_lang_flag_url( $lang['slug'] ) ); ?>"
                    >
                        <img src="<?php echo esc_url( acf_lang_flag_url( $lang['slug'] ) ); ?>" alt="<?php echo esc_attr( strtoupper( $lang['slug'] ) ); ?>">
                        <?php echo esc_html( strtoupper( $lang['slug'] ) ); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <script>
    (function () {
        document.addEventListener('DOMContentLoaded', function () {
            var row     = document.getElementById('acf-lang-switcher-row');
            var dropdown= document.getElementById('acf-lang-dropdown');
            var trigger = document.getElementById('acf-lang-trigger');
            var list    = document.getElementById('acf-lang-list');
            var flagImg = document.getElementById('acf-lang-flag');
            var label   = document.getElementById('acf-lang-label');
            if (!row || !dropdown || !trigger) return;

            // ── 1. Injecter dans le metabox "Publier" ────────────────────
            var target = null;

            var submitDiv = document.getElementById('submitdiv');
            if (submitDiv) {
                var inside = submitDiv.querySelector('.inside');
                if (inside) target = { parent: inside, ref: inside.firstChild };
            }

            if (!target) {
                var boxes = document.querySelectorAll('#side-sortables .postbox, #side-sortables .acf-postbox');
                for (var i = 0; i < boxes.length; i++) {
                    if (boxes[i].querySelector('[name="save"]') || boxes[i].querySelector('[id$="-update"]')) {
                        var ins = boxes[i].querySelector('.inside, .acf-postbox-content');
                        if (ins) { target = { parent: ins, ref: ins.firstChild }; break; }
                    }
                }
            }

            if (!target) {
                var side = document.getElementById('side-sortables');
                if (side) target = { parent: side, ref: side.firstChild };
            }

            if (target) {
                // Masquer ligne native Polylang
                var pllRow = target.parent.querySelector('.misc-pub-section, .pll-language-row');
                if (pllRow) pllRow.style.display = 'none';

                target.parent.insertBefore(row, target.ref);
                row.style.display = 'flex';
            }

            // ── 2. Toggle dropdown ────────────────────────────────────────
            trigger.addEventListener('click', function (e) {
                e.stopPropagation();
                var isOpen = dropdown.classList.toggle('is-open');
                trigger.setAttribute('aria-expanded', isOpen);
            });

            // Fermer si clic ailleurs
            document.addEventListener('click', function () {
                dropdown.classList.remove('is-open');
                trigger.setAttribute('aria-expanded', 'false');
            });

            list.addEventListener('click', function (e) { e.stopPropagation(); });

            // ── 3. Sélection d'une langue ─────────────────────────────────
            var options = list.querySelectorAll('.acf-lang-option');
            options.forEach(function (opt) {
                opt.addEventListener('click', function () {
                    var url = this.dataset.url;
                    if (url) window.location.href = url;
                });
            });
        });
    })();
    </script>
    <?php
} );
