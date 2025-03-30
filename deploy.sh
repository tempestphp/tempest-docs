#!/bin/bash

. /home/forge/.bashrc
. ~/.nvm/nvm.sh

# Composer
php8.4 /usr/local/bin/composer install --no-dev

# Bun
/home/forge/.bun/bin/bun --version
/home/forge/.bun/bin/bun install
php8.4 tempest command-palette:index
/home/forge/.bun/bin/bun run build

# Tempest
php8.4 tempest cache:clear --all
php8.4 tempest discovery:generate
php8.4 tempest migrate:up --force
php8.4 tempest static:clean --force
php8.4 tempest static:generate

# Supervisor
sudo supervisorctl restart all
