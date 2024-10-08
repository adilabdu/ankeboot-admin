### Ankeboot Admin

A company management platform for companies to manage their inventory, record incoming and outgoings, keep up with daily
sales, get aggregate data on transactions, manage company letters and employees.

A web-based system built with a Laravel, Vue, Tailwind and MySQL stack.

#### Setting up project on local dev machine

Clone the project and follow these procedures to get it up and running on your machine. All the required environments
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

# Alternatively, migrate data and populate test user
php artisan migrate --seed
```

Create test user

```shell
php artisan db:seed
```

The above command will create a new test user with these credentials you can use to log into the system

- Username `testuser`
- Phone `0912345678`
- Password `helloworld`

#### Job workers

Run the following commands to start the job workers. The default queue is used for mailables notifications and batch imports, while
the `scout` queue is used for indexing models into Meilisearch

```shell
php artisan queue:work
php artisan queue:work --queue=scout
```

Before running the queue workers, make sure to update the following environmental variables in `.env`

```shell
QUEUE_CONNECTION=database
```

#### Setting up Meilisearch

This project uses Meilisearch as its search engine. To set it up, follow the installation guide outlined [in their documentation](https://meilisearch.com/docs/learn/getting_started/quick_start).
Next, update the following environmental variables in `.env`

```shell
SCOUT_DRIVER=meilisearch
MEILISEARCH_HOST=http://127.0.0.1:7700
MEILISEARCH_KEY=masterKey
```

Once you have Meilisearch up and running, import a given model (e.g. `App\Models\Book`) into the search engine as such:

```shell
php artisan scout:import "App\Models\Book"
php artisan scout:meilisearch-update #Update Meilisearch's index and filterable attributes
```

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
