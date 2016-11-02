<?php
/**
 * Migration file
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @license https://www.freebsd.org/copyright/freebsd-license.html FreeBSD License
 * @copyright 2007 OpenSource-WorkShop Co.,Ltd.
 */

/**
 * Init CakeMigration
 *
 * [NetCommonsプラグイン作成] phpdocは他のプラグインからコピーしてくる
 *
 * [Cakephpの決まり] CakePHP 2系のCakeDC Migrationsプラグインで自動生成する
 * NetCommons3はCakeDC Migrationsプラグイン( https://github.com/CakeDC/migrations )を利用している。
 * Plugin\Migrations     <---CakeDC Migrationsプラグイン
 *
 * ### マイグレーションファイル
 *
 * * app\Plugin\Simpletexts\Config\Migration\001_plugin_records.php
 * * app\Plugin\Simpletexts\Config\Migration\1476855904_init.php
 *
 * マイグレーションは、若い番号から順に実行される。
 * 番号は、マイグレーションファイル名のはじめの番号。日付をint値に修正した値。
 * 番号は、`Console/cake Migrations.migration generate`実行時に自動的に振られる。
 * 上記の場合 001,1476855904の順に実行される。
 *
 * ### コマンド
 * #### Simpletextsプラグインのマイグレーションを全て実行
 * $ Console/cake Migrations.migration run all -c master -p Simpletexts
 *
 * #### Simpletextsプラグインのマイグレーションを１つ戻す
 * $ Console/cake Migrations.migration run down -c master -p Simpletexts
 *
 * #### Simpletextsプラグインのマイグレーションを１つ上げる
 * $ Console/cake Migrations.migration run up -c master -p Simpletexts
 *
 * #### Simpletextsプラグインのマイグレーションを選んで実行する
 * $ Console/cake Migrations.migration run -c master -p Simpletexts
 *
 * ### 蛇足
 *
 * マイグレーションのクラス名は唯一、他のプラグインと同じでも問題ない。
 * Cakephpでコントローラ等のクラス名が、他のプラグインと同じになると、なんらか不具合(Migrations.migration generateの途中停止等)が起きるので、同名クラスは避けるべき。
 */
class Init extends CakeMigration {

/**
 * Migration description
 * [Cakephpの決まり] 説明
 * `cake Migrations.migration generate`で自動生成される。
 *
 * @var string
 */
	public $description = 'init';

/**
 * Actions to be performed
 * [Cakephpの決まり] データパッチとかテーブルクリエイトとかする
 * 'up' が上げる時に動作する。
 * 'down' が戻す時に動作する。
 * 'create_table'はテーブルを作成する。
 * 'drop_table'はテーブルを削除する。
 * `cake Migrations.migration generate`で自動生成される。
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'simpletext_frame_settings' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary', 'comment' => 'ID |  |  | '),
					'frame_key' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index', 'collate' => 'utf8_general_ci', 'comment' => 'frame key | フレームKey | frames.key | ', 'charset' => 'utf8'),
					'textarea_edit_row' => array('type' => 'integer', 'null' => false, 'default' => '7', 'length' => 4, 'unsigned' => false, 'comment' => '高さ'),
					'created_user' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'comment' => 'created user | 作成者 | users.id | '),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'created datetime | 作成日時 |  | '),
					'modified_user' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'comment' => 'modified user | 更新者 | users.id | '),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'modified datetime | 更新日時 |  | '),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'frame_key' => array('column' => 'frame_key', 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'simpletexts' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary', 'comment' => 'ID |  |  | '),
					'key' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'language_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 6, 'unsigned' => false),
					'block_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => true, 'key' => 'index'),
					'textarea' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'created user | 作成者 | users.id | ', 'charset' => 'utf8'),
					'status' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 4, 'unsigned' => false, 'comment' => '公開状況  1:公開中、2:公開申請中、3:下書き中、4:差し戻し'),
					'is_active' => array('type' => 'boolean', 'null' => false, 'default' => null),
					'is_latest' => array('type' => 'boolean', 'null' => false, 'default' => null),
					'created_user' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'comment' => 'created user | 作成者 | users.id | '),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'created datetime | 作成日時 |  | '),
					'modified_user' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'comment' => 'modified user | 更新者 | users.id | '),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'modified datetime | 更新日時 |  | '),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'block_id' => array('column' => 'block_id', 'unique' => 0),
						'key' => array('column' => 'key', 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'simpletext_frame_settings', 'simpletexts'
			),
		),
	);

/**
 * Before migration callback
 * [Cakephpの決まり] マイグレーション実行前
 * `cake Migrations.migration generate`で下記のfunctionが自動生成される。
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 * [Cakephpの決まり] マイグレーション実行後
 * `cake Migrations.migration generate`で下記のfunctionが自動生成される。
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function after($direction) {
		return true;
	}
}
