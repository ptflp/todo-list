#!/bin/bash
php db-init.php
composer install
php vendor/bin/doctrine orm:schema-tool:update --force
