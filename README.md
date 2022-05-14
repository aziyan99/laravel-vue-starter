<p align="center"><a href="javascript:void(0);" target="_blank"><img src="https://i.ibb.co/cX78VgF/laravel-vue-starter.png" width="100"></a></p>

<p align="center">
<img src="https://img.shields.io/github/issues/aziyan99/laravel-vue-starter">
<img src="https://img.shields.io/github/stars/aziyan99/laravel-vue-starter">
</p>


![ss1](https://i.ibb.co/zV5VDVV/laravel-vue-starter.png)


# Laravel vue starter
Simple implementation of laravel and vuejs starter with coreui admin template.

# Installation ⚙️
copy `.env-example` file and update the database credentials section according to yours.

First install the depedencies using composer and npm
```console
composer install && npm install
```
next, generate the key
```console
php artisan key:generate
```
next, running the migration and seeder
```console
php artisan migrate:fresh --seed
```
last make a storage link
```console
php artisan storage:link
```
optionally, refresh cache
```console
php artisan cache:clear
```

# Default Authentication Credentials
|Email|Password|Role|
| ------ | ------ | ------ |
| superadmin@example.com | password | `super admin` |

ACL or access control level using `laravel/spatie` package

# Troubleshooting 🔧
1. Deprecated: Return type of `Illuminate\Container\Container::offsetExists($key)`
   - Run `composer update`
   - Updating php version in `composer.json` [https://stackoverflow.com/questions/70245146/php-deprecated-issue-when-running-artisan-command](https://stackoverflow.com/questions/70245146/php-deprecated-issue-when-running-artisan-command)
