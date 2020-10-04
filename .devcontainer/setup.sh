#!/bin/bash

composer install
npm install
php artisan key:generate
composer ide
php artisan migrate
