#!/usr/bin/env bash
# Synchronise le projet (montage 9p lent) avec le miroir rapide /tmp/opencode/vendo-build.
#
# Usage :
#   ./sync.sh push   # projet → miroir (à faire avant de lancer le serveur ou les tests)
#   ./sync.sh pull   # miroir → projet (récupère les fichiers modifiés côté miroir)
set -euo pipefail

PROJET="/mnt/chromeos/MyFiles/vendo"
MIROIR="/tmp/opencode/vendo-build"

if [ ! -d "$MIROIR" ]; then
    echo "Miroir absent : lance d'abord la création du miroir (voir AGENTS.md §4.2)." >&2
    exit 1
fi

case "${1:-}" in
    push)
        # On envoie tout le projet sauf ce qui se reconstruit dans le miroir :
        # vendor (composer install), node_modules (build local), storage (cache/logs).
        # .env est inclus volontairement : le projet reste la source de vérité.
        rsync -a --delete \
            --exclude='.git/' \
            --exclude='vendor/' \
            --exclude='node_modules/' \
            --exclude='storage/' \
            "$PROJET/" "$MIROIR/"
        echo "Projet synchronisé vers le miroir."
        ;;
    pull)
        # On ne récupère que ce qui peut être modifié pendant le développement.
        # Jamais .env (le projet fait foi), jamais vendor/, node_modules/ ni storage/.
        # Pas de --delete : une suppression se fait manuellement des deux côtés.
        rsync -a \
            --include='app/***' \
            --include='resources/***' \
            --include='routes/***' \
            --include='config/***' \
            --include='database/***' \
            --include='lang/***' \
            --include='tests/***' \
            --include='public/build/***' \
            --include='composer.json' \
            --include='composer.lock' \
            --exclude='*' \
            "$MIROIR/" "$PROJET/"
        echo "Miroir synchronisé vers le projet."
        ;;
    *)
        echo "Usage : $0 {push|pull}" >&2
        exit 1
        ;;
esac
