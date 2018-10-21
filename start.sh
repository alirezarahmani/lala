#!/usr/bin/env bash
rm .env
if [ ! -f .env ]; then
    touch .env
    echo PHP_SSH_PORT=23 >> .env
    echo LALAMOVE_SERVER_PORT=8080 >> .env
    echo VENDOR_DIR=/var/www/vendor >> .env
    echo LOCAL_IP=0.0.0.0 >> .env
    echo MYSQL_PORT=3307 >> .env
    echo LOCAL_DEV_DIR=$(pwd) >> .env
    echo APP_ENV=prod >> .env
    echo APP_SECRET=039cc23343d3bc6f0a2281cb465a4665 >> .env
    echo DATABASE_URL=mysql://root:root@mysql:3306/lalamove >> .env
fi

docker-compose build
docker-compose up -d
docker-compose exec worker composer install
docker-compose down
docker-compose up -d