<?php
/**
 * Schema file
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @license https://www.freebsd.org/copyright/freebsd-license.html FreeBSD License
 * @copyright 2007 OpenSource-WorkShop Co.,Ltd.
 */

/**
 * Simpletexts CakeSchema
 *
 * [NetCommonsプラグイン作成] phpdocは他のプラグインからコピーしてくる
 *
 * [Cakephpの決まり] CakePHP 2系のCakeDC Migrationsプラグインで自動生成する
 * NetCommons3はCakeDC Migrationsプラグイン( https://github.com/CakeDC/migrations )を利用している。
 * Plugin\Migrations     <---CakeDC Migrationsプラグイン
 *
 * ### データベースにCreate文でテーブルを作成してから、コマンドを実行すると２ファイル自動作成
 *
 * * app\Plugin\Simpletexts\Config\Schema\schema.php   (テーブル構成)
 * * app\Plugin\Simpletexts\Config\Migration\9999999999_＜記述名＞.php   (DBパッチ)
 *
 * ### コマンド
 * ```
 * $ Console/cake Migrations.migration generate -c master -p Simpletexts
 *
 * Cake Migration Shell
 * ---------------------------------------------------------------
 * You did not set a migration connection (-i), which connection do you want to use? (master/slave1/test)
 * [master] >  (←空Enter)
 * Do you want to generate a dump from the current database? (y/n)
 * [y] > (←空Enter)
 * ---------------------------------------------------------------
 * Generating dump from the current database...
 * Do you want to preview the file before generation? (y/n)
 * [y] > (←空Enter)
 *
 * Please enter the descriptive name of the migration to generate:
 * > init    (←記述名を入力)
 * Generating Migration...
 *
 * Done.
 * Do you want to update the schema.php file? (y/n)
 * 	[y] > (←空Enter)
 *
 * Welcome to CakePHP v2.8.9 Console
 * ---------------------------------------------------------------
 * App : app
 * Path: /var/www/app/app/
 * ---------------------------------------------------------------
 * Cake Schema Shell
 * ---------------------------------------------------------------
 * Generating Schema...
 * Schema file: schema.php generated
 * ```
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class SimpletextsSchema extends CakeSchema {

/**
 * Database connection
 *
 * @var string
 */
	public $connection = 'master';

/**
 * before
 *
 * @param array $event event
 * @return bool
 */
	public function before($event = array()) {
		return true;
	}

/**
 * after
 *
 * @param array $event event
 * @return bool
 */
	public function after($event = array()) {
	}

/**
 * simpletext_frame_settings table
 *
 * @var array
 */
	public $simpletext_frame_settings = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary', 'comment' => 'ID |  |  | '),
		'frame_key' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index', 'collate' => 'utf8_general_ci', 'comment' => 'frame key | フレームKey | frames.key | ', 'charset' => 'utf8'),
		'textarea_edit_row' => array('type' => 'integer', 'null' => false, 'default' => '7', 'length' => 4, 'unsigned' => false, 'comment' => '高さ'),
		'created_user' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'comment' => 'created user | 作成者 | users.id | '),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'created datetime | 作成日時 |  | '),
		'modified_user' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'comment' => 'modified user | 更新者 | users.id | '),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'modified datetime | 更新日時 |  | '),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'frame_key' => array('column' => 'frame_key', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

/**
 * simpletexts table
 *
 * @var array
 */
	public $simpletexts = array(
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
			'key' => array('column' => 'key', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

}
