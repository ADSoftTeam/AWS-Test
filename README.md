## Test API service

Service in stored files in cloud storage (AWS S3)

composer require league/flysystem-aws-s3-v3 "^1.0"

Block DB in .ENV for Postgres

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=aws
DB_USERNAME=postgres
DB_PASSWORD=postgres

for MySQL

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=aws
DB_USERNAME=root
DB_PASSWORD=root

Migrate DB - php artisan migrate


