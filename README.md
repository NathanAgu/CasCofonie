# Initialisation docker

```sh
# la première fois
docker compose build
docker compose up -d
docker compose exec php-fpm chown www-data:www-data -R /var/www/public /var/www/vendor

# renommer le repertoire courrant dans composer.lock pour l'autoload
docker compose exec -u www-data php-fpm composer install

# create/insert database
docker compose exec -u www-data php-fpm php install.php

# allumer la stack
docker compose up -d

# l'éteindre
docker compose down

# lancer en shell dans le conteneur PHP
docker compose exec -u www-data php-fpm bash
```

# Site en localhost

le site est heberger sur le localhost et se trouve sur le port :6443 

soit 127.0.0.1:6443 

