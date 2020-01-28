# Laravel World Cities

## Instructions

### Add to dependencies

```
composer require paulintrognon/laravel-world-cities
```

### Load the files from geonames.org

```
php artisan lwc:download
```

If you want to only download specific countries:

```
php artisan lwc:download --countries=US,GB
```

### Insert the cities in the database

First, you need to migrate to create the new `lwc_cities` table:

```
php artisan migrate
```

Then, insert the cities in your `lwc_cities` table:

```
php artisan lwc:seed
```
This command will only work if you have not specified any countries in previous step. If you did, use this command instead:
```
php artisan lwc:seed --countries=US,GB
```