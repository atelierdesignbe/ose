#!/bin/bash

set -e

GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${GREEN}╔═══════════════════════════════════════════════╗${NC}"
echo -e "${GREEN}║   Installation des plugins WordPress         ║${NC}"
echo -e "${GREEN}╚═══════════════════════════════════════════════╝${NC}\n"

if [ ! -f .env ]; then
    echo -e "${RED}❌ Erreur: Le fichier .env n'existe pas${NC}"
    echo -e "${YELLOW}💡 Copiez .env.example vers .env et remplissez vos clés${NC}"
    exit 1
fi

source .env

# Vérifier seulement ACF_PRO_KEY
echo -e "${BLUE}🔑 Vérification de la clé de licence...${NC}\n"

if [ -z "$ACF_PRO_KEY" ] || [ "$ACF_PRO_KEY" = "your_acf_pro_license_key_here" ]; then
    echo -e "${RED}❌ ACF_PRO_KEY non définie${NC}"
    echo -e "\n${YELLOW}💡 Ajoutez votre clé de licence ACF Pro dans le fichier .env${NC}"
    exit 1
fi

echo -e "${GREEN}✅ Clé de licence présente${NC}\n"

mkdir -p wordpress/wp-content/plugins

# ============================================
# ÉTAPE 1 : Plugins gratuits via Composer
# ============================================
echo -e "${GREEN}═══════════════════════════════════════════════${NC}"
echo -e "${GREEN}  ÉTAPE 1/4 : Plugins gratuits (Composer)${NC}"
echo -e "${GREEN}═══════════════════════════════════════════════${NC}\n"

composer install --no-dev --optimize-autoloader

echo -e "\n${GREEN}✅ Plugins gratuits installés${NC}\n"

# ============================================
# ÉTAPE 2 : Plugins PRO depuis archives
# ============================================
echo -e "${GREEN}═══════════════════════════════════════════════${NC}"
echo -e "${GREEN}  ÉTAPE 2/4 : Plugins PRO (archives locales)${NC}"
echo -e "${GREEN}═══════════════════════════════════════════════${NC}\n"

if [ -f "./extract-plugins.sh" ]; then
    chmod +x ./extract-plugins.sh
    ./extract-plugins.sh
else
    echo -e "${RED}❌ Script extract-plugins.sh non trouvé${NC}\n"
fi

# ============================================
# ÉTAPE 3 : ACF Pro (téléchargement avec licence)
# ============================================
echo -e "${GREEN}═══════════════════════════════════════════════${NC}"
echo -e "${GREEN}  ÉTAPE 3/4 : ACF Pro 6.8.0.1 (licence)${NC}"
echo -e "${GREEN}═══════════════════════════════════════════════${NC}\n"

ACF_PRO_DIR="wordpress/wp-content/plugins/advanced-custom-fields-pro"

if [ -d "$ACF_PRO_DIR" ]; then
    echo -e "${YELLOW}🗑️  Suppression de l'ancienne version...${NC}"
    rm -rf "$ACF_PRO_DIR"
fi

ACF_PRO_URL="https://connect.advancedcustomfields.com/v2/plugins/download?p=pro&k=${ACF_PRO_KEY}&t=6.8.0.1"
ACF_PRO_ZIP="/tmp/acf-pro.zip"

echo -e "${BLUE}📥 Téléchargement d'ACF Pro 6.8.0.1...${NC}"

if curl -L -f -o "$ACF_PRO_ZIP" "$ACF_PRO_URL" 2>/dev/null; then
    if [ -f "$ACF_PRO_ZIP" ] && [ -s "$ACF_PRO_ZIP" ]; then
        echo -e "${BLUE}📂 Installation...${NC}"
        unzip -q "$ACF_PRO_ZIP" -d "wordpress/wp-content/plugins/"
        rm "$ACF_PRO_ZIP"
        SIZE=$(du -sh "$ACF_PRO_DIR" | cut -f1)
        echo -e "${GREEN}✅ ACF Pro installé ($SIZE)${NC}\n"
    else
        echo -e "${RED}❌ Téléchargement échoué (clé invalide ?)${NC}\n"
        exit 1
    fi
else
    echo -e "${RED}❌ Erreur de téléchargement${NC}\n"
    exit 1
fi

# ============================================
# ÉTAPE 4 : Vérification Formidable Pro
# ============================================
echo -e "${GREEN}═══════════════════════════════════════════════${NC}"
echo -e "${GREEN}  ÉTAPE 4/4 : Formidable Pro (archive locale)${NC}"
echo -e "${GREEN}═══════════════════════════════════════════════${NC}\n"

if [ -d "wordpress/wp-content/plugins/formidable-pro" ]; then
    SIZE=$(du -sh "wordpress/wp-content/plugins/formidable-pro" | cut -f1)
    echo -e "${GREEN}✅ Formidable Pro installé ($SIZE)${NC}\n"
else
    echo -e "${YELLOW}⚠️  Formidable Pro non trouvé${NC}"
    echo -e "${YELLOW}💡 Pour installer Formidable Pro :${NC}"
    echo -e "${YELLOW}   1. Téléchargez depuis https://formidableforms.com/account/${NC}"
    echo -e "${YELLOW}   2. unzip formidable-pro.zip${NC}"
    echo -e "${YELLOW}   3. tar -czf formidable-pro.tar.gz formidable-pro/${NC}"
    echo -e "${YELLOW}   4. mv formidable-pro.tar.gz downloads/${NC}"
    echo -e "${YELLOW}   5. ./install-plugins.sh${NC}\n"
fi

# ============================================
# RÉSUMÉ FINAL
# ============================================
echo -e "${GREEN}╔═══════════════════════════════════════════════╗${NC}"
echo -e "${GREEN}║   ✅ Installation terminée avec succès        ║${NC}"
echo -e "${GREEN}╚═══════════════════════════════════════════════╝${NC}\n"

echo -e "${YELLOW}📦 Plugins installés :${NC}\n"

echo -e "${BLUE}Gratuits (Composer):${NC}"
echo -e "   • Formidable Forms (base)"
echo -e "   • Polylang"
echo -e "   • Cookie Law Info"
echo -e "   • Duplicate Post"
echo -e "   • Google Site Kit"
echo -e "   • LiteSpeed Cache"
echo -e "   • Query Monitor"
echo -e "   • Redirection"
echo -e "   • WebP Converter for Media"

echo -e "\n${BLUE}PRO (Archives locales):${NC}"
if [ -d "wordpress/wp-content/plugins/acf-extended-pro" ]; then
    echo -e "   • ACF Extended Pro ✅"
else
    echo -e "   • ACF Extended Pro ❌"
fi

if [ -d "wordpress/wp-content/plugins/formidable-pro" ]; then
    echo -e "   • Formidable Forms Pro ✅"
else
    echo -e "   • Formidable Forms Pro ❌"
fi

echo -e "\n${BLUE}PRO (Licence):${NC}"
if [ -d "wordpress/wp-content/plugins/advanced-custom-fields-pro" ]; then
    echo -e "   • ACF Pro 6.8.0.1 ✅"
else
    echo -e "   • ACF Pro ❌"
fi

echo -e "\n${BLUE}📁 Emplacement: wordpress/wp-content/plugins/${NC}\n"

