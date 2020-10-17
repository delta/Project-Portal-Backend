#!/bin/bash

STARTED="FIRST_RUN"
if [ ! -e $STARTED ]; then
    composer install
    php artisan key:generate
    php artisan migrate
    php artisan db:seed --class=StatusSeeder
    php artisan db:seed --class=TypeSeeder
    php artisan passport:install
fi

php artisan serve --host 0.0.0.0 --port 8000
