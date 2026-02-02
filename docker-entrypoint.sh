#!/bin/bash
set -e

echo "🗄️ Exécution des migrations..."
php bin/console doctrine:migrations:migrate --no-interaction

echo "✅ Démarrage d'Apache..."
apache2-foreground