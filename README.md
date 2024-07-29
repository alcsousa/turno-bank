# TurnoBank API

## Project setup

```shell
cp .env.example .env
```

Important `.env` variables to review
```env
# will be used for the Admin seeder
USER_ADMIN_EMAIL=admin@test.com
USER_ADMIN_PASSWORD="your-pass"
# cors
FRONTEND_URL=http://localhost:5176
SESSION_DOMAIN=localhost
SANCTUM_STATEFUL_DOMAINS=localhost:5176
```

Running containers
```bash
docker compose up -d
```

Install dependencies
```bash
docker compose exec laravel.test composer install
```

Create app key
```bash
./vendor/bin/sail artisan key:generate
```

Running migrations
```bash
./vendor/bin/sail artisan migrate
```

Running seeders
```bash
./vendor/bin/sail artisan db:seed
```
---

## Testing

Copy env vars
```shell
cp .env .env.testing
```

Update `.env.testing` values
```env
APP_ENV=testing
DB_DATABASE=testing
```

Running migrations
```bash
./vendor/bin/sail artisan migrate --env=testing
```

Running seeders
```bash
./vendor/bin/sail artisan db:seed --env=testing
```

Running tests
```bash
./vendor/bin/sail test
```

Running tests with coverage
```bash
./vendor/bin/sail test --coverage
```

---

## Project down

```bash
./vendor/bin/sail down
```
