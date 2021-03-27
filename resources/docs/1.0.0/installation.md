# Installation

---

- [Intall](#install)
- [View](#view)

<a name="install"></a>
## Install
Navigate to root  directory using terminal or git bash. 
```bash
cd <your-dir>/<another-dir>/<root-project>
```
Copy `.env.example` and rename to `.env`.
On linux terminal or using **git bash** in windows
```bash
cp .env.example .env
```
Configure `.env` file that was copied from `.env.example` with your configuration
```.env
APP_NAME=(your app name)
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=(your app url)

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=(your database connection)
DB_HOST=(your database host)
DB_PORT=(your database port)
DB_DATABASE=(your database name)
DB_USERNAME=(your database username)
DB_PASSWORD=(your database password)

BROADCAST_DRIVER=log
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=null
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1

MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"  
```
Generate `APP_KEY` using artisan command.
```php
php artisan key:generate
```
Create symlink from `storage` folder to the `public` folder.
```php
php artisan storage:link
```
Create folder for storing user image profile and web logo (if no exists, better check first).
```php
mkdir storage/app/public/image_profiles
mkdir storage/app/public/logo
```
Running database migration.
```php
php artisan migrate:fresh
```
Running default database seeder.

```php
php artisan db:seed --class=DefaultSeeder
```

<a name="view"></a>
## View
1. Using laravel valet: visit `http://yourdomain.test`
2. Using laravel serve `php artisan serve`: visit `http://localhost:8000` or `http://127.0.0.1:8000`
3. Login page to the admin panel: visit `http://yourdomain.test/auth/login` or `http://localhost:8000/auth/login` or `http://127.0.0.1:8000/auth/login`
