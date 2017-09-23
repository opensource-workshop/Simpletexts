<?php
/**
 * Pluginのnamespace間違い修正
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @license https://www.freebsd.org/copyright/freebsd-license.html FreeBSD License
 * @copyright 2007 OpenSource-WorkShop Co.,Ltd.
 */

App::uses('NetCommonsMigration', 'NetCommons.Config/Migration');

/**
 * Pluginのnamespace間違い修正
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Simpletexts\Config\Migration
 */
class PluginNamespaceUpdate extends NetCommonsMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'plugin_namespace_update';

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

		$plugins = $this->Plugin->find('all', array(
			'fields' => array('Plugin.id', 'Plugin.namespace'),
			'conditions' => array(
				'namespace' => 'netcommons/simpletexts'
			),
			'recursive' => -1
		));
		if (empty($plugins)) {
			return true;
		}

		foreach ($plugins as &$plugin) {
			$plugin['Plugin']['namespace'] = 'opensource-workshop/simpletexts';
		}

		//トランザクションBegin
		$this->Plugin->begin();

		try {
			if (! $this->Plugin->saveMany($plugins, ['validate' => false, 'callbacks' => false])) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			//トランザクションCommit
			$this->Plugin->commit();

		} catch (Exception $ex) {
			//トランザクションRollback
			$this->Plugin->rollback($ex);
		}

		return true;
	}
}
