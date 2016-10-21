<?php
/**
 * Add plugin migration
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @license https://www.freebsd.org/copyright/freebsd-license.html FreeBSD License
 * @copyright 2007 OpenSource-WorkShop Co.,Ltd.
 */

App::uses('NetCommonsMigration', 'NetCommons.Config/Migration');

/**
 * Add plugin migration
 *
 * [NetCommonsプラグイン作成] 他のプラグインからコピーしてくる
 * Plugin\NetCommons\Config\Migration\NetCommonsMigration.php を継承
 *
 * このマイグレーションを実行する事で、「プラグイン追加」に表示される
 */
class PluginRecords extends NetCommonsMigration {

/**
 * Migration description
 * [Cakephpの決まり] 説明
 * `cake Migrations.migration generate`で自動生成される。
 *
 * @var string
 */
	public $description = 'plugin_records';

/**
 * Actions to be performed
 * [Cakephpの決まり] データパッチとかテーブルクリエイトとかする
 * `cake Migrations.migration generate`で自動生成される。
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(),
		'down' => array(),
	);

/**
 * plugin data
 * [NetCommons独自] データベースに登録するデータを設定する
 *
 * @var array $migration
 */
	public $records = array(
		// [NetCommonsプラグイン作成] プラグインによって内容を変更する
		'Plugin' => array(
			//日本語
			array(
				'language_id' => '2',									// 2=日本語
				'key' => 'simpletexts',									// プラグインキー。自プラグイン名をスネーク記法で直書きする。日英同じ値
				'namespace' => 'netcommons/simpletexts',				// packagist のパッケージ名。プラグイン管理でpackagistにリンク表示に利用。 日英同じ値
				'name' => 'シンプルテキスト',							// プラグイン名（日本語）
				'type' => 1,											// 1=一般プラグイン, 2=管理プラグイン
				'default_action' => 'simpletexts/view',					// 初期表示アクション
				'default_setting_action' => 'simpletext_blocks/index',	// 設定初期表示アクション
			),
			//英語
			array(
				'language_id' => '1',									// 1=英語
				'key' => 'simpletexts',									// プラグインキー。自プラグイン名をスネーク記法で直書きする。日英同じ値
				'namespace' => 'netcommons/simpletexts',				// packagist のパッケージ名。プラグイン管理でpackagistにリンク表示に利用。 日英同じ値
				'name' => 'Simpletexts',								// プラグイン名（英語）
				'type' => 1,											// 1=一般プラグイン, 2=管理プラグイン
				'default_action' => 'simpletexts/view',					// 初期表示アクション
				'default_setting_action' => 'simpletext_blocks/index',	// 設定初期表示アクション
			),
		),
		// 内容書く
		'PluginsRole' => array(
			array(
				'role_key' => 'room_administrator',
				'plugin_key' => 'simpletexts',
			),
		),
		// 初期データであるルームにプラグインを配置できるように設定
		// PluginsRoomの該当ルームにないプラグインは配置できない
		'PluginsRoom' => array(
			//パブリックスペース
			array('room_id' => '1', 'plugin_key' => 'simpletexts', ),
			//プライベートスペース
			array('room_id' => '2', 'plugin_key' => 'simpletexts', ),
			//グループスペース
			array('room_id' => '3', 'plugin_key' => 'simpletexts', ),
		),
	);

/**
 * Before migration callback
 * [Cakephpの決まり] マイグレーション実行前
 * `cake Migrations.migration generate`で自動生成される。
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
 * `cake Migrations.migration generate`で自動生成される。
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function after($direction) {
		// [NetCommons独自] 内容書く
		$this->loadModels([
			'Plugin' => 'PluginManager.Plugin',
		]);

		if ($direction === 'down') {
			$this->Plugin->uninstallPlugin($this->records['Plugin'][0]['key']);
			return true;
		}

		foreach ($this->records as $model => $records) {
			if (!$this->updateRecords($model, $records)) {
				return false;
			}
		}
		return true;
	}
}
