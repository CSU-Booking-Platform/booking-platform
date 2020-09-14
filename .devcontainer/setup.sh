#!/bin/bash

sudo rm -rf /var/www/html && sudo ln -s /workspace/public /var/www/html
sudo apache2ctl start
composer install
npm install
php artisan key:generate
composer ide
php artisan migrate