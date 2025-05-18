#!/bin/bash

. /home/forge/.bashrc
. ~/.nvm/nvm.sh

# Dependencies
php8.4 /usr/local/bin/composer install --no-dev
/home/forge/.bun/bin/bun --version
/home/forge/.bun/bin/bun install

# Tempest
php8.4 tempest cache:clear --all
php8.4 tempest discovery:generate
php8.4 tempest migrate:up --force
php8.4 tempest static:clean --force

# Build front-end
php8.4 tempest command-palette:index
/home/forge/.bun/bin/bun run build
php8.4 tempest static:generate --allow-dead-links --verbose=true

# Supervisor
sudo supervisorctl restart all
