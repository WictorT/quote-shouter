<h1 align="center">
  Quote shouter
</h1>
<p align="center">This is a single endpoint API that shouts quotes of famous people </p>

### Prerequisites
 - **Linux/OS X**
 - **PHP 7.2**
 - [**Git**](https://www.atlassian.com/git/tutorials/install-git)

### Set up (for linux systems)
1. Clone this repository using: `git clone https://github.com/WictorT/quote-shouter.git`
2. `cd quote-shouter`
3. Run `sudo apt-get install php-sqlite3 php7.2-cli php7.2-xml php7.2-mbstring php7.2-curl composer` to install the dependencies.
4. Copy `.env.dist` into `.env` and configure as you are wiling(better keep default values).
5. Install php dependencies: `composer install`
6. Run the following to bring up the project and setup the database:
    ```
    bin/console doctrine:database:create
    php bin/console doctrine:migrations:migrate --no-interaction
    bin/console doctrine:fixtures:load --no-interaction
    ```

### Usage
The given example will return 2 famous quotes from Steve Jobs:
- Console: 
    - PHP array: `bin/console app:call-route /shout/steve-jobs?limit=2`.
    - JSON: `bin/console app:call-route /shout/steve-jobs?limit=2 -f json`.

- HTTP: 
    - You need to have the symfony-cli [installed](https://symfony.com/download) first and run: `symfony serve`.
    - Then you can access http://127.0.0.1:8000/shout/steve-jobs?limit=2 in your browser.

### Launching tests
- To launch tests tests run: `cp phpunit.xml.dist phpunit.xml`, and adjust to your preferences, then `bin/phpunit`.
<h2 align="center"> Thank you! </h2>
