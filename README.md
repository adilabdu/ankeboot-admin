### Ankeboot Admin

A company management platform for companies to manage their inventory, record incoming and outgoings, keep up with daily
sales, get aggregate data on transactions, manage company letters and employees. 

A web-based system built with a Laravel, Vue, Tailwind and MySQL stack.

#### Setting up project on local dev machine

Clone the project and follow these procedures to get it up and running on your machine. All the dependencies 
(PHP 8, MySQL composer, node) are assumed to be available.

Generate your own copy of environmental files from the example file
```shell
cp .env.example .env
```

Login to your MySQL server and create a new database
```shell
mysql -u <username> -p<password>
CREATE DATABASE <dbname>
```

Update the `.env` DB block with your environment data
```shell
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=<dbname>
DB_USERNAME=<username>
DB_PASSWORD=<password>
```

Install PHP dependencies with composer.
```shell
composer install
```

Install NPM dependencies
```shell
npm install
```

Set an application key for your project and save the configurations in cache
```shell
php artisan key:generate
php artisan config:cache
```

Migrate the database table schema
```shell
php artisan migrate
```

Create test user
```shell
php artisan db:seed
```
The above command will create a new test user with these credentials you can use to log into the system
- Username `testuser`
- Phone `0912345678`
- Password `helloworld`

#### Running dev servers on the local machine

Run this command to start the Vite development server,
```shell
npm run dev
```

and this to start the PHP development server
```shell
php artisan serve
```

The project should now be available on `localhost` with port number set by the PHP dev server (commonly `localhost:8000`)

#### Note 
If port `8000` is in use and the PHP dev server assigns the project onto another port number `XXXX` eg. (`8001`, `8002`... ), 
go to `.env` and update Laravel sanctum's configuration to reflect this change
```shell
SANCTUM_STATEFUL_DOMAINS=localhost:XXXX
```
