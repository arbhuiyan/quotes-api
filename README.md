# Quotes API

## Local Setup

### Option 1: The Docker way

#### System requirements
- Docker

#### Steps:
- Install dependencies

```shell
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```
- Copy example env file and configure as necessary `cp .env.example .env`
- Generate application key `./vendor/bin/sail artisan key:generate --ansi`
- Serve: `./vendor/bin/sail up -d`
- Migrations: `./vendor/bin/sail artisan migrate --seed`
- Run tests: `./vendor/bin/sail test`

### Option 2: The traditional way 

#### System requirements
- PHP 8.3
- Composer
- pdo_sqlite driver
- php extensions: [laravel](https://laravel.com/docs/11.x/deployment#server-requirements)

### Steps:
- Install dependencies
```shell
composer install --ignore-platform-reqs
```
- Copy example env and configure if necessary `cp .env.example .env`
- Generate application key `php artisan key:generate --ansi`
- Serve: `php artisan serve`
- Migrations: `php artisan migrate --seed`
- Run tests: `php artisan test`


### Test User
By default, a test user is created with the following credentials:
- email: test@example.com
- password: password

You can use these credentials to generate an access token to then make authenticated requests to the quotes API.

### API Documentation
The API schema can be found on the root when the application is running.

Root: [http:localhost](http://localhost)
