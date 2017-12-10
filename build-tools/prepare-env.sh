#!/bin/bash
export PATH="/home/ubuntu/.phpenv/shims:/home/ubuntu/.phpenv/bin:/home/ubuntu/.phpenv/bin:${PATH}"
phpenv version
sed -i -- 's/;phar.readonly = On/phar.readonly = Off/g' /home/ubuntu/.phpenv/versions/*/etc/php.ini
composer self-update
composer install --no-progress
