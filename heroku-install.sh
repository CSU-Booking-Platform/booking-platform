#!/usr/bin/env bash
if [ $APP_ENV == 'local' ] || [ $APP_ENV == 'staging'  ] ; then
    composer install
    php artisan migrate:fresh --seed
else
    php artisan migrate --force
fi
