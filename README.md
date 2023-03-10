## About Loan API

Rest API that allows authenticated users to go through a loan application.

## Setup

- Copy `.env.example` file to `.env` and update `.env` file according to your needs

- Make sure you have Docker Desktop installed and run `./vendor/bin/sail up`

- Run the migration `php artisan migrate`

- Run the seeders `php artisan db:seed`

- The seeders will create on admin user and one customer which can be used to test the flow.

- The admin user will be created with the data specified in `.env` file(ADMIN_NAME, ADMIN_EMAIL and ADMIN_PASSWORD)

## Postman

- The Postman collection is located in the root directory `Loan-API.postman_collection.json`