#!/usr/bin/env bash
set -e

echo "🚀 Installation des dépendances..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "🧹 Nettoyage du cache..."
php bin/console cache:clear --env=prod --no-debug

echo "✅ Build terminé !"