# Rese（リーズ） 飲食店予約サービス

## 環境構築

### 1. リポジトリをクローン → `.env` 作成

```bash
cp src/.env.example src/.env
```

---

### 2. `.env` 設定（`src/.env`）

#### DB 設定

```env
DB_CONNECTION=mysql
DB_HOST=mysql_db
DB_PORT=3306
DB_DATABASE=rese_db
DB_USERNAME=root
DB_PASSWORD=password
```

#### メール送信設定（MailHog）

```env
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=no-reply@example.test
MAIL_FROM_NAME="Rese"
```

- メール送信をスキップしたい場合：
  - `MAIL_MAILER=log`
  - メール本文は `storage/logs/laravel.log` に出力されます

#### その他推奨設定

```env
APP_URL=http://localhost:8085
SESSION_DRIVER=file
```

`.env` 更新後はキャッシュをクリアしてください。

```bash
docker compose exec app php artisan optimize:clear
```

---

### 3. コンテナ起動

```bash
docker compose up -d --build
```

---

### 4. 依存関係インストール & アプリキー生成

```bash
docker compose exec app composer install -o
docker compose exec app php artisan key:generate
docker compose exec app php artisan storage:link
```

---

### 5. マイグレーション & シーディング

```bash
docker compose exec app php artisan migrate:fresh --seed
```

---

## メール送信（MailHog）

- 管理画面：http://localhost:8025

---

## pull 後の再セットアップ（環境差異対策）

```bash
# コンテナ再ビルド
docker compose up -d --build

# 依存関係の再インストール
docker compose exec app composer install -o

# .env の DB / セッション設定を統一
docker compose exec app bash -lc "
sed -i '
s/^DB_CONNECTION=.*/DB_CONNECTION=mysql/;
s/^DB_HOST=.*/DB_HOST=mysql_db/;
s/^DB_PORT=.*/DB_PORT=3306/;
s/^DB_DATABASE=.*/DB_DATABASE=rese_db/;
s/^DB_USERNAME=.*/DB_USERNAME=root/;
s/^DB_PASSWORD=.*/DB_PASSWORD=password/;
s/^SESSION_DRIVER=.*/SESSION_DRIVER=file/
' .env || true
"

# Laravel キャッシュ全削除
docker compose exec app php artisan optimize:clear

# マイグレーション（必要に応じて）
docker compose exec app php artisan migrate --force || true

# storage / cache 権限調整
docker compose exec app bash -lc '
mkdir -p storage/framework/{cache,sessions,views} bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
find storage bootstrap/cache -type d -exec chmod 775 {} \;
find storage bootstrap/cache -type f -exec chmod 664 {} \;
'
```

---

## 権限エラーが出る場合（WSL / Linux）

`storage` や `bootstrap/cache` に書き込めない、または `src` 配下の編集ができない場合は、
ホスト側で所有者が `root` になっている可能性があります。

以下をプロジェクト直下で実行し、`src` の所有者を自分のユーザーに変更してください。

```bash
sudo chown -R $USER:$USER src
```

---

## 使用技術

- PHP 8.x / Laravel
- MySQL 8.0
- Docker / Docker Compose
- nginx
- MailHog

---

## 主要機能

### 一般ユーザー

- 会員登録 / ログイン / ログアウト
- 飲食店一覧表示
- エリア・ジャンル・店名検索
- 飲食店詳細表示
- お気に入り登録 / 解除
- 店舗予約
- マイページ
  - 予約一覧
  - お気に入り一覧

### 管理・拡張機能（実装予定含む）

- 予約変更機能
- レビュー・評価機能
- 店舗代表者管理画面
- 管理者管理画面
- メール認証
- 予約リマインダー
- QRコード発行
- 決済機能（Stripe）

---

## 画面一覧

- `/`：飲食店一覧
- `/register`：会員登録
- `/login`：ログイン
- `/mypage`：マイページ
- `/detail/{shop_id}`：飲食店詳細
- `/done`：予約完了

---

## テーブル仕様（例）

### users

| カラム名 | 型 | 制約 |
| --- | --- | --- |
| id | bigint | PK |
| name | varchar(255) | not null |
| email | varchar(255) | unique, not null |
| password | varchar(255) | not null |
| email_verified_at | timestamp | nullable |
| created_at / updated_at | timestamp | |

### reservations

| カラム名 | 型 | 制約 |
| --- | --- | --- |
| id | bigint | PK |
| user_id | bigint | FK -> users.id |
| shop_id | bigint | FK -> shops.id |
| reserve_date | date | not null |
| reserve_time | time | not null |
| number_of_people | tinyint | not null |
| note | varchar(255) | nullable |
| created_at / updated_at | timestamp | |

---

## テストアカウント（開発用）

- 管理者（Admin）
  - email: admin@example.com
  - password: password123

- 店舗代表者（Owner）
  - email: owner@example.com
  - password: password123

- 一般ユーザー（User）
  - email: user@example.com
  - password: password123

---

## テスト実行

```bash
docker compose exec app php artisan test
```
