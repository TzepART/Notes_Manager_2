#!/usr/bin/env sh

php app/console doctrine:database:drop --force
php app/console doctrine:database:create --if-not-exists
php app/console doctrine:migration:migrate -n
php app/console doctrine:fixtures:load -n
#php app/console assets:install --symlink
php app/console sonata:media:fix-media-context