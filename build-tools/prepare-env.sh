#!/bin/bash
export PATH="/home/ubuntu/.phpenv/shims:/home/ubuntu/.phpenv/bin:/home/ubuntu/.phpenv/bin:${PATH}"
phpenv version
composer self-update
composer install --no-progress