#!/bin/bash

sudo rm -rf /var/www/html && sudo ln -s /workspace/public /var/www/html
sudo apache2ctl start
composer install
npm install
cp .env.example .env
php artisan key:generate
