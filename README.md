# php_tweet_app
日本語で失礼します。

## 概要
このアプリは以下の機能で構成されています。

① Twitter REST APIを用いたキーワードによるユーザー検索機能

② ユーザー詳細表示機能

③ ユーザーの画像ツイート取得およびツイート画像のアプリローカルディレクトリへの保存機能

Docker,docker-compose環境にてCakePHP2系での開発を行なっています。

## 開発環境
- MacOS Mojave 10.14.6
- Docker 19.03.5
- docker-compose version 1.25.0
- PHP 5.6-apache
- CakePHP 2.10.19
- MySQL 5

## 利用方法
以下の①~⑥の手順にてセットアップを行なってください。
### ① Docker、docker-composeのインストール
[Docker](http://docs.docker.jp/engine/installation/toc.html)および[docker-compose](http://docs.docker.jp/compose/toc.html)をインストールします。

### ② リポジトリのクローン
当リポジトリをgit clone又はDownload Zipします。

```
$ git clone https://github.com/chiha-24/php_tweet_app.git
```

### ③ 環境変数の設定
現在の構成
```
php_tweet_app/
 ├ app/
 ├ docker/
 |	├ php.ini
 |	└ dockerfile
 ├ lib/
 └ docker-compose.yml
```	
.envを作成し、[Twitter REST API](https://developer.twitter.com/)のキーを記入します。
```
php_tweet_app/
 ├ app/
 ├ docker/
 |	├ php.ini
 |	└ dockerfile
 ├ lib/
 ├ docker-compose.yml
 └ .env
```	

**.env**
```
CONSUMER_KEY=Twitter REST APIのconsumer_key
CONSUMEE_SECRET=Twitter REST APIのconsumer_secret
ACCESS_TOKEN=Twitter REST APIのaccess_token
ACCESS_TOKEN_SECRET=Twitter REST APIのaccess_token_secret
```

### ④ Dockerのビルド、コンテナ起動
cloneしたアプリのディレクトリに移動し、サービスのビルド・コンテナの起動を行います。
```
$ cd ~/php_tweet_app
$ docker-compose build
$ docker-compose up -d
```

### ⑤ MySQLデータベーステーブルの作成
起動中のappコンテナに入り、schema.phpの設定からデータベースを作成します。

```
$ cd ~/php_tweet_app
$ docker-compose exec app /bin/bash
# cake schema create

Are you sure you want to drop the table(s)? (y/n) 
[n] > y

Are you sure you want to create the table(s)? (y/n) 
[y] > y
```

## ⑥ ブラウザからアプリへアクセス
[http://localhost:8000/](http://localhost:8000/)へアクセスし、アプリが正常に動作していることを確認し、利用開始します。

