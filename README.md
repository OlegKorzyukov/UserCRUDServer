STACK
============================================
* PHP -version 8.0.2
* Symfony -version 5.4
* MySQL -version 8.0
* Composer -version 2.0.14

INSTALL
============================================
Install docker and docker-compose

Go to folder project

**On Linux:**
   
Run: sh redeploy.sh

**On Windows:**

Run:

`docker-compose up -d --force-recreate --build`

`docker-compose exec php /bin/bash -c "cd /var/www/html && composer install --no-interaction"`

`docker-compose exec php /bin/bash -c "php bin/console doctrine:migrations:migrate --allow-no-migration --no-interaction --no-debug"`

`docker-compose exec php /bin/bash -c "php bin/console doctrine:fixtures:load --no-interaction"`

---
If need it, change services port in file `docker-compose.override.yml`

Links
============================================
http://127.0.0.1/api/doc - to Swagger
