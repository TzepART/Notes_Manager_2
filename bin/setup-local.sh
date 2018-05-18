#!/usr/bin/env sh

php bin/console doctrine:database:drop --force
php bin/console doctrine:database:create --if-not-exists
php bin/console doctrine:migration:migrate -n
php bin/console doctrine:fixtures:load -n
#php bin/console assets:install --symlink
#php bin/console sonata:media:fix-media-context