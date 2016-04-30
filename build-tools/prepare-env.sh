#!/bin/bash
VERSION={$1:-"5.5"}
PATH="/opt/phpenv/shims:/opt/phpenv/bin:/opt/php-build/bin/:/opt/composer/vendor/bin:$PATH"
phpenv global $VERSION
sed -i -- 's/;phar.readonly = On/phar.readonly = Off/g' /opt/phpenv/versions/$VERSION/etc/php.ini