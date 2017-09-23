Simpletexts(シンプルテキスト)プラグイン は [opensource-workshop](https://opensource-workshop.jp/) が作成した NetCommons3 の追加プラグインです。

入力した内容を「そのまま」表示します。

HTMLチェックや自動修正を行わないことが特徴です。<br />
そのため、もしHTMLに間違いがあっても、そのまま表示するため、注意してください。

* [ライセンス](#ライセンス)
* [目的](#目的)
* [プラグインインストール・アンインストール](#プラグインインストールアンインストール)
* [ディレクトリ説明](#ディレクトリ説明)
* [作業状況・残タスク](#作業状況残タスク)

## ライセンス

[FreeBSD License](LICENSE)  
FreeBSD License は BSD 2-Clause Licenseです。[詳しくはこちら](https://opensource.org/licenses)

## 目的

NetCommons3の追加プラグインの各処理で、どういった処理を行っているか理解を深める。

初めてNetCommons3の追加プラグインを開発する方にもわかるように、下記のような感じで、ここは[Cakephpの決まり]、ここは[NetCommons独自]や[NetCommonsプラグイン]とコメント多めで作成しています。  
https://github.com/opensource-workshop/Simpletexts/blob/master/Controller/SimpletextsController.php

少しでもNetCommons3プラグイン開発者の参考になるのであれば幸いです。

## プラグインインストール・アンインストール

### インストール

#### zipファイルから

##### (1) Pluginディレクトリ配下にSimpletestsプラグインのソースを配置します。

ソースはgithubからzipをダウンロード後、解凍します

```
配置例）/var/www/html/nc3/app/Plugin/Simpletexts
```

##### (2) cakeコマンドを使ってmigrationを実行します。

実行するとDBに初期データが登録されて、画面のプラグイン追加で、シンプルテキストが表示されるようになります。

```
# cd /var/www/html/nc3/app
# Console/cake Migrations.migration run all -c master -p Simpletexts

Cake Migration Shell
---------------------------------------------------------------
You did not set a migration connection (-i), which connection do you want to use? (master/slave1/test)
[master] >      (←空エンター)
Running migrations:
  [001] 001_plugin_records

  [002] 002_mail_setting_records

  [1476855904] 1476855904_init (2016-10-19 05:45:04)
      > Creating table "simpletext_frame_settings".
      > Creating table "simpletexts".

---------------------------------------------------------------
All migrations have completed.
```

##### (3) DBキャッシュファイルのオーナーをapacheのオーナーに変更する

```
# chown -R apache:apache /var/www/html/nc3/app/tmp/cache/*
```

##### (4) composer.lockにSimpletestsプラグインの内容を追記する

これをすると一括アップデートで削除されなくなります。

```
# vi /var/www/html/nc3/composer.lock
```

【ハッシュ値】はここからコピーして読み替えてください。
https://github.com/opensource-workshop/Simpletexts/commits/master
![ハッシュ値](https://github.com/opensource-workshop/Simpletexts/wiki/images/readme/hash.PNG)

ここのボタン押すと、ハッシュ値がコピーされます

追記する内容
```
        {
            "name": "opensource-workshop/simpletexts",
            "version": "dev-master",
            "source": {
                "type": "git",
                "url": "https://github.com/opensource-workshop/Simpletexts.git",
                "reference": "【ハッシュ値】"
            },
            "dist": {
                "type": "zip",
                "url": "https://api.github.com/repos/opensource-workshop/Simpletexts/zipball/【ハッシュ値】",
                "reference": "【ハッシュ値】",
                "shasum": ""
            },
            "require": {
                "cakedc/migrations": "~2.2",
                "netcommons/blocks": "@dev",
                "netcommons/mails": "@dev",
                "netcommons/net-commons": "@dev",
                "netcommons/pages": "@dev",
                "netcommons/plugin-manager": "@dev",
                "netcommons/topics": "@dev",
                "netcommons/workflow": "@dev",
                "netcommons/wysiwyg": "@dev"
            },
            "type": "cakephp-plugin",
            "extra": {
                "installer-paths": {
                    "app/Plugin/{$name}": [
                        "type:cakephp-plugin"
                    ]
                }
            },
            "notification-url": "https://packagist.org/downloads/",
            "license": [
                "FreeBSD License"
            ],
            "authors": [
                {
                    "name": "Mitsuru Mutaguchi(OpenSource WorkShop)",
                    "email": "mutaguchi@opensource-workshop.jp",
                    "homepage": "https://opensource-workshop.jp/",
                    "role": "Developer"
                },
                {
                    "name": "OpenSource WorkShop",
                    "homepage": "https://opensource-workshop.jp/"
                }
            ],
            "description": "Simpletexts for NetCommons Plugin",
            "homepage": "https://opensource-workshop.jp/",
            "keywords": [
                "cakephp",
                "simpletexts"
            ],
            "time": "2017-09-23T13:24:34+00:00"
        },
```

#### composerから

##### (1) composer install

```
$ cd /var/www/html
$ php composer.phar install opensource-workshop/simpletexts
```

##### (2) migrationを実行

[cakeコマンドを使ってmigrationを実行する](#2-cake%E3%82%B3%E3%83%9E%E3%83%B3%E3%83%89%E3%82%92%E4%BD%BF%E3%81%A3%E3%81%A6migration%E3%82%92%E5%AE%9F%E8%A1%8C%E3%81%97%E3%81%BE%E3%81%99)参照

### アンインストール

##### (1) cakeコマンドを使ってmigrationのdownオプションを実行します。（実行するとDBデータが削除されます）

```
# cd /var/www/html/app
# Console/cake Migrations.migration run down -c master -p Simpletexts

Cake Migration Shell
---------------------------------------------------------------
You did not set a migration connection (-i), which connection do you want to use? (master/slave1/test)
[master] >      (←空エンター)
Running migrations:
  [1476855904] 1476855904_init (2016-10-19 05:45:04)
      > Dropping table "simpletext_frame_settings".
      > Dropping table "simpletexts".

---------------------------------------------------------------
All migrations have completed.
```

##### (2) 複数回上記作業を繰り返します。下記メッセージが表示されたら、全て削除されています。

```
# Console/cake Migrations.migration run down -c master -p Simpletexts
Cake Migration Shell
---------------------------------------------------------------
You did not set a migration connection (-i), which connection do you want to use? (master/slave1/test)
[master] >      (←空エンター)
Not a valid migration version.
```

これでアンインストール完了です。

## ディレクトリ説明

|ディレクトリ・ファイル|説明|
|---|---|
|Config    |cakephpプラグイン関連。[CakeDC/migrationsプラグイン](https://github.com/CakeDC/migrations)のマイグレーションファイル関連ディレクトリ。cakeコマンドでデータベースのテーブル作成やデータ追加・更新等が出来る|
|Controller|cakephpプラグイン関連。メイン処理関連のディレクトリ|
|Locale    |cakephpプラグイン関連。言語ファイル関連のディレクトリ|
|Model     |cakephpプラグイン関連。データベース関連のディレクトリ|
|Test      |cakephpプラグイン関連。phpunitテストファイル。本番運用時には不要なディレクトリ|
|View      |cakephpプラグイン関連。画面関連のディレクトリ|
|.gitignore|gitで管理しないファイルを記した設定ファイル|
|LICENSE   |プログラムライセンスを記したテキストファイル|
|composer.json |composer関連。[composer](https://github.com/NetCommons3/NetCommons3/wiki/composer)はPHPの依存管理ツールでこのファイルがあるとcomposerに対応している|

## 作業状況・残タスク

[Issue](https://github.com/opensource-workshop/Simpletexts/issues)を参照してください。
