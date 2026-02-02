#!/bin/bash
set -e

echo "🗄️ Exécution des migrations..."
php bin/console doctrine:migrations:migrate --no-interaction

echo "🌱 Chargement des fixtures..."
php bin/console doctrine:fixtures:load --no-interaction

echo "✅ Démarrage d'Apache..."
apache2-foreground