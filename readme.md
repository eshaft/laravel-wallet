## Развертывание проекта

git clone git@github.com:eshaft/laravel-wallet.git

composer install

cp .env.example .env - создать .env файл из .env.example и прописать в нем подключение к БД:

DB_CONNECTION=pgsql \
DB_HOST=postgres \
DB_PORT=5432 \
DB_DATABASE=wallet \
DB_USERNAME=postgres \
DB_PASSWORD=1234

php artisan key:generate

sudo chmod -R 0777 storage bootstrap/cache

./vendor/bin/phpunit - запуск тестов

Запрос на изменение баланса: 

curl -X POST \
  http://lwallet.local/api/wallet \
  -H 'Accept: application/json' \
  -H 'cache-control: no-cache' \
  -H 'content-type: multipart/form-data \
  -F wallet_id=1 \
  -F transaction_type=debit \
  -F amount=1000 \
  -F currency=RUB
