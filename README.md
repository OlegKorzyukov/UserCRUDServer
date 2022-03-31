STACK
============================================
* PHP -version 8.1
* Symfony -version 5.0
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

docker-compose up -d --force-recreate --build

docker-compose exec php /bin/bash -c "cd /var/www/html && composer install --no-interaction"

docker-compose exec php /bin/bash -c "cd /var/www/html && php artisan migrate:refresh"

**ATTEMPT - long execute**

docker-compose exec php /bin/bash -c "cd /var/www/html && php artisan parse:file"

Links
============================================
http://127.0.0.1/api/doc - to Swagger

First register user - http://127.0.0.1/api/v1/auth/register

After login - http://127.0.0.1/api/v1/auth/login, take 'access_token' and push into header to all request

Header: Authorization: Bearer 'access_token'
