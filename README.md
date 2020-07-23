- `cp .env.dist .env`
- `cp phpunit.xml.dist phpunit.xml`
- `sudo apt-get install php-sqlite3 php7.2-cli php7.2-xml php7.2-mbstring composer`
- `wget https://get.symfony.com/cli/installer -O - | bash`

- `composer install`
- `bin/console doctrine:database:create`
- `php bin/console doctrine:migrations:migrate --no-interaction`
- `bin/console doctrine:fixtures:load --no-interaction`
- `cp phpunit.xml.dist phpunit.xml`

- To run tests: `/bin/phpunit`
