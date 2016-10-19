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
 * [NetCommonsプラグイン] 他のプラグインからコピー
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
		'Plugin' => array(
			//日本語
			array(
				'language_id' => '2',
				'key' => 'simpletexts',
				'namespace' => 'netcommons/simpletexts',
				'name' => 'シンプルテキスト',
				'type' => 1,
				'default_action' => 'simpletexts/view',
				'default_setting_action' => 'simpletext_blocks/index',
			),
			//英語
			array(
				'language_id' => '1',
				'key' => 'simpletexts',
				'namespace' => 'netcommons/simpletexts',
				'name' => 'Simpletexts',
				'type' => 1,
				'default_action' => 'simpletexts/view',
				'default_setting_action' => 'simpletext_blocks/index',
			),
		),
		'PluginsRole' => array(
			array(
				'role_key' => 'room_administrator',
				'plugin_key' => 'simpletexts',
			),
		),
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
