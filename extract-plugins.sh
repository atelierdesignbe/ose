#!/bin/bash

set -e

GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
BLUE='\033[0;34m'
NC='\033[0m'

PLUGINS_DIR="wordpress/wp-content/plugins"

echo -e "${BLUE}📂 Extraction des plugins PRO depuis archives...${NC}\n"

extract_plugin() {
    local archive_name=$1
    local plugin_name=$2
    local archive_path="downloads/$archive_name"
    local dest_dir="$PLUGINS_DIR/$plugin_name"
    
    if [ ! -f "$archive_path" ]; then
        echo -e "${YELLOW}⚠️  $archive_name non trouvé - ignoré${NC}\n"
        return 1
    fi
    
    echo -e "${BLUE}   Extraction de $plugin_name...${NC}"
    
    if [ -d "$dest_dir" ]; then
        rm -rf "$dest_dir"
    fi
    
    tar -xzf "$archive_path" -C "$PLUGINS_DIR"
    
    if [ -d "$dest_dir" ]; then
        SIZE=$(du -sh "$dest_dir" | cut -f1)
        echo -e "${GREEN}   ✅ $plugin_name extrait ($SIZE)${NC}\n"
        return 0
    else
        echo -e "${RED}   ❌ Erreur lors de l'extraction${NC}\n"
        return 1
    fi
}

mkdir -p "$PLUGINS_DIR"

EXTRACTED=0
TOTAL=2

if extract_plugin "acf-extended-pro.tar.gz" "acf-extended-pro"; then
    EXTRACTED=$((EXTRACTED + 1))
fi

if extract_plugin "formidable-pro.tar.gz" "formidable-pro"; then
    EXTRACTED=$((EXTRACTED + 1))
fi

echo -e "${GREEN}═══════════════════════════════════════════════${NC}"
echo -e "${GREEN}  ✅ $EXTRACTED/$TOTAL plugins extraits${NC}"
echo -e "${GREEN}═══════════════════════════════════════════════${NC}\n"