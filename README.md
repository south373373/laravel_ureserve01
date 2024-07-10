# 予約イベントシステム画面 開発

## 以下にダウンロード方法を記載。

git clone
git clone https://github.com/aokitashipro/laravel_uReserve.git

git clone ブランチを指定してダウンロードする場合

git clone -b [ブランチ名] https://github.com/aokitashipro/laravel_uReserve.git

もしくはzipファイルでダウンロードして下さい。

## インストール方法

cd laravel_uReserve
- composer install
- npm install
- npm run dev

.env.example をコピーして、 .envファイルを作成。

.envファイルの中の下記をご利用の環境に合わせて変更して下さい。

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
# DB_DATABASE=laravel
DB_DATABASE=laravel_ureserve
DB_USERNAME=ureserve
DB_PASSWORD=password123
DB_SOCKET=/Applications/MAMP/tmp/mysql/mysql.sock


XAMPP/MAMPまたは他の開発環境でDBを起動した後に、

php artisan migrate:fresh --seed

・・と実行して下さい。
※DBテーブルとダミーデータが追加されればOK。

最後に
php artisan key:generate
と入力してキーを生成後、

php artisan serve
で簡易サーバを立ち上げ、表示確認して下さい。


## インストール後の実施事項

画像のリンク
php artisan storage:link

プロフィールページで画像アップロード機能を使う場合は、
.envのAPP_URLを下記に変更して下さい。

# APP_URL=http://localhost
APP_URL=http://127.0.0.1:8000

Tailwindcss 3.xのJustInTime機能により、使ったHTML内クラスのみ
反映されるようになっていますので、 HTMLを編集する際は、
npm run watch も実行しながら編集するようにしてください。

