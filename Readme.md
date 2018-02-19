* `docker-compose build`
* `docker-compose up -d`
* `sudo echo $(docker network inspect bridge | grep Gateway | grep -o -E '[0-9\.]+') "agregator.dev" >> /etc/hosts`
* `docker-compose exec php bash`
  * `composer.phar install`


-- https://github.com/maxpou/docker-symfony
