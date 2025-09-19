もぎたて
Dockerビルド
git@github.com:gally5ag/mogitate.git
docker-compose up-d--build

環境構築
1docker-compose exec php bash 
2composer install
3.env.example ファイルから .env を作成し、環境変数を変更
4php artisan key:generate 
5php artisan migrate 
6php artisan db:seed

使用技術

PHP PHP 8.3.6 
Laravel 8.83.8
MySQL 8.0

URL
開発環境：htto://localhost/products
