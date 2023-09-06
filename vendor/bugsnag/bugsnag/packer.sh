#!/bin/sh

rm -rf build vendor composer.lock
composer install -o -n --prefer-dist
php -d phar.readonly=false packager.php
