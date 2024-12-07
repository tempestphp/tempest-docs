git pull
php8.4 /usr/local/bin/composer install --no-dev
php8.4 tempest discovery:generate
npm install
npm run build
php8.4 tempest cache:clear --all
php8.4 tempest discovery:generate
php8.4 tempest static:clean --force
php8.4 tempest static:generate
