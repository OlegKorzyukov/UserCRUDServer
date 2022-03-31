#!/bin/sh
export RUN_ENV=dev

docker-compose up -d --force-recreate --build
docker-compose exec php /bin/bash -c "cd /var/www/html && composer install --no-interaction"
docker-compose exec -it php /bin/bash -c "php bin/console doctrine:migrations:migrate --allow-no-migration --no-interaction --no-debug"
