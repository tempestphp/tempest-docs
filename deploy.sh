git pull
php8.3 /usr/local/bin/composer install --no-dev
npm install
npm run build
php8.3 tempest cache:clear --all
php8.3 tempest discovery:generate
php8.3 tempest static:clean --force
php8.3 tempest static:generate
