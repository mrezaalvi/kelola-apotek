<p align="center"><img src="./public/images/default.png" width="256" alt="Laravel Logo"></p>

<h2>Aplikasi Kelola Apotek</h2>

## Requirements

* PHP 8.1 or higher
* Database (eg: MySQL, PostgreSQL, SQLite)
* Web Server (eg: Apache, Nginx, IIS)
* Other libraries
* Composer
* NPM

## Installation

* Install [Composer](https://getcomposer.org/download) and [Npm](https://nodejs.org/en/download)
* Clone the repository: 
```bash
git clone https://github.com/mrezaalvi/kelola-apotek.git
```

* Install dependencies: 
```bash
composer install; npm install; npm run build;
``` 

* Copy .env.example to .env:
```bash
copy .env.example .env
```

* Change .env data:
```bash
DB_DATABASE=[YOUR_DB_NAME]
DB_USERNAME=[YOUR_DB_USERNAME]
DB_PASSWORD=[YOUR_DB_PASSWORD]
```
* Generate Key
```bash
php artisan key:generate
```

* Link Storage
```bash
php artisan storage:link
```
* Migrate database:
```bash
php artisan migrate
```
## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
