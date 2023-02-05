#!/bin/sh

# Update Composer binary root.

export COMPOSER_HOME=/root

sudo COMPOSER_MEMORY_LIMIT=-1 /usr/bin/composer.phar self-update
