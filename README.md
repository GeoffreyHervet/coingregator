* `docker-compose build`
* `docker-compose up -d`
* `sudo echo $(docker network inspect bridge | grep Gateway | grep -o -E '[0-9\.]+') "agregator.dev" >> /etc/hosts`
* `docker-compose exec php bash`
  * `composer install`
  * `bin/console doctrine:migrations:migrate`

-- https://github.com/maxpou/docker-symfony
