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
 *
 * このマイグレーションを実行する事で、「プラグイン追加」に表示される
 */
class PluginRecords extends NetCommonsMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'plugin_records';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(),
		'down' => array(),
	);

/**
 * plugin data
 *
 * @var array $migration
 */
	public $records = array(
		// [NetCommonsプラグイン作成] プラグインによって内容を変更する
		'Plugin' => array(
			//日本語
			array(
				'language_id' => '2',
				'key' => 'simpletexts',
				'namespace' => 'netcommons/simpletexts',
				'name' => 'シンプルテキスト',							// プラグイン名（日本語）
				'type' => 1,
				'default_action' => 'simpletexts/view',					// 初期表示アクション
				'default_setting_action' => 'simpletext_blocks/index',	// 設定初期表示アクション
			),
			//英語
			array(
				'language_id' => '1',
				'key' => 'simpletexts',
				'namespace' => 'netcommons/simpletexts',
				'name' => 'Simpletexts',								// プラグイン名（英語）
				'type' => 1,
				'default_action' => 'simpletexts/view',					// 初期表示アクション
				'default_setting_action' => 'simpletext_blocks/index',	// 設定初期表示アクション
			),
		),
		'PluginsRole' => array(
			array(
				'role_key' => 'room_administrator',
				'plugin_key' => 'simpletexts',
			),
		),
		// プラグインをどこのスペースに配置できるか設定
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
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function after($direction) {
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
