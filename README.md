# Simpletexts

Simpletexts は [opensource-workshop](https://opensource-workshop.jp/) が作成した NetCommons3 の追加プラグインです。

入力した内容を「そのまま」表示します。

HTMLチェックや自動修正を行わないことが特徴です。<br />
そのため、もしHTMLに間違いがあっても、そのまま表示するため、注意してください。

## ライセンス

[FreeBSD License](LICENSE)<br />
FreeBSD License は BSD 2-Clause Licenseです。[詳しくはこちら](https://opensource.org/licenses)

## 目的

NetCommons3の追加プラグインの各処理で、どういった処理を行っているか理解を深める。

初めてNetCommons3の追加プラグインを開発する方にもわかるように、下記のような感じで、ここは[Cakephpの決まり]、ここは[NetCommons独自]や[NetCommonsプラグイン]とコメント多めで作成しています。<br />
https://github.com/opensource-workshop/Simpletexts/blob/master/Controller/SimpletextsController.php

少しでもNetCommons3プラグイン開発者の助けになるのであれば幸いです。

## プラグインインストール・アンインストール

### インストール

(1) Pluginディレクトリ配下にSimpletestsプラグインのソースを配置します。ソースはgithubからzipをダウンロード後、解凍します
```
配置例）/var/www/html/app/Plugin/Simpletexts
```
(2) cakeコマンドを使ってmigrationを実行します。（実行するとDBに初期データが登録されます）
```
# cd /var/www/html/app
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

(3) DBキャッシュファイルのオーナーをapacheのオーナーに変更する

```
# chown -R apache:apache /var/www/html/app/tmp/cache/*
```

これで画面のプラグイン追加に、シンプルテキストが表示されます。

### アンインストール

(1) cakeコマンドを使ってmigrationのdownオプションを実行します。（実行するとDBデータが削除されます）
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

(2) 複数回上記作業を繰り返します。下記メッセージが表示されたら、全て削除されています。

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
|Config    |cakephpプラグインの関連。CakeDC/migrationsプラグインのマイグレーションファイル。内容によりデータベースのテーブル作成やデータ追加・更新等が出来る|
|Controller|cakephpプラグイン関連。メインの処理関連|
|Locale    |cakephpプラグイン関連。言語ファイル関連|
|Model     |cakephpプラグイン関連。データベース関連|
|Test      |cakephpプラグイン関連。phpunitテストファイル。本番運用時には不要なディレクトリ|
|View      |cakephpプラグイン関連。画面関連|
|.gitignore|gitで管理しないファイルを記した設定ファイル|
|LICENSE   |プログラムライセンスを記したテキストファイル|
|README.md |github.comのリポジトリTOPページで表示される、マークダウン方式のReadMeファイル（当ファイル）|

## 作業状況・残タスク

[issue](https://github.com/opensource-workshop/Simpletexts/issues)を参照してください。
