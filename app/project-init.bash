#!/bin/bash
composer install
php vendor/bin/doctrine orm:schema-tool:update --force
