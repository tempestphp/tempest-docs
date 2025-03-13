# Contributing to Tempest's documentation

## Installing the project

First, clone and install the project.

```sh
# Clone the repository
git clone git@github.com:tempestphp/tempest-docs.git
cd tempest-docs

# Install dependencies
composer install
bun install

# Start the servers in two terminals
bun run dev
php tempest serve # not useful if you have Herd or Valet
```
