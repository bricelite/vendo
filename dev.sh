#!/usr/bin/env bash
# Démarre tout l'environnement de développement en une seule commande.
#
# Le projet vit sur un montage 9p très lent (voir AGENTS.md §4.2) : le serveur
# doit tourner depuis la copie rapide /tmp/opencode/vendo-build, pas depuis le
# dossier du projet. Ce script s'occupe de tout : création de la copie si
# besoin, synchronisation du code, démarrage du serveur.
#
# Usage :
#   ./dev.sh        # synchronise puis démarre (ou vérifie) le serveur
#   ./dev.sh stop   # arrête le serveur
set -euo pipefail

PROJET="$(cd "$(dirname "$0")" && pwd)"
MIROIR="/tmp/opencode/vendo-build"
URL="http://127.0.0.1:8000"

serveur_repond() {
    # Toute réponse HTTP (même une erreur) prouve que le serveur est lancé
    curl -o /dev/null -s --max-time 3 "$URL"
}

arreter_serveur() {
    # On cible précisément "artisan serve" pour ne rien tuer d'autre
    if pgrep -f 'artisan serve' > /dev/null; then
        pkill -f 'artisan serve'
        echo "Serveur arrêté."
    else
        echo "Aucun serveur en cours."
    fi
}

creer_miroir_si_absent() {
    if [ -f "$MIROIR/artisan" ] && [ -d "$MIROIR/vendor" ]; then
        return
    fi
    echo "Copie rapide absente ou incomplète : recréation (environ 1 minute)..."
    mkdir -p "$MIROIR"
    rsync -a \
        --exclude='.git/' \
        --exclude='vendor/' \
        --exclude='node_modules/' \
        --exclude='storage/' \
        "$PROJET/" "$MIROIR/"
    (cd "$MIROIR" && composer install --no-interaction --prefer-dist --quiet)
    # Arborescence minimale attendue par Laravel (sessions, vues compilées, logs)
    mkdir -p "$MIROIR/storage/app/public" \
             "$MIROIR/storage/framework/cache/data" \
             "$MIROIR/storage/framework/sessions" \
             "$MIROIR/storage/framework/testing" \
             "$MIROIR/storage/framework/views" \
             "$MIROIR/storage/logs"
    echo "Copie rapide prête."
}

case "${1:-start}" in
    stop)
        arreter_serveur
        ;;
    start)
        creer_miroir_si_absent
        "$PROJET/sync.sh" push
        if serveur_repond; then
            echo "Le serveur tourne déjà : $URL"
            exit 0
        fi
        echo "Démarrage du serveur..."
        # opcache évite de recharger tous les fichiers PHP à chaque page ;
        # revalidate_freq=0 garde les modifications de code visibles immédiatement.
        # setsid donne au serveur son propre groupe de processus : il survit
        # à la fermeture du terminal qui l'a lancé.
        (cd "$MIROIR" && setsid nohup php -d opcache.enable_cli=1 \
            -d opcache.validate_timestamps=1 -d opcache.revalidate_freq=0 \
            artisan serve --host=127.0.0.1 --port=8000 \
            > storage/logs/serve.log 2>&1 < /dev/null &)
        # On attend que le serveur réponde avant d'annoncer l'adresse
        for _ in $(seq 1 30); do
            if serveur_repond; then
                echo ""
                echo "C'est prêt : $URL"
                exit 0
            fi
            sleep 0.5
        done
        echo "Le serveur n'a pas répondu dans les 15 secondes." >&2
        echo "Log : $MIROIR/storage/logs/serve.log" >&2
        exit 1
        ;;
    *)
        echo "Usage : $0 {start|stop}" >&2
        exit 1
        ;;
esac
