#!/bin/bash

sudo service nginx start
composer install
npm install
php artisan key:generate
composer ide
php artisan migrate