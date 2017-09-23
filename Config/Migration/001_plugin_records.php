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
				'namespace' => 'opensource-workshop/simpletexts',		// packagist のパッケージ名。プラグイン管理でpackagistにリンク表示に利用。 日英同じ値
				'name' => 'シンプルテキスト',							// プラグイン名（日本語）
				'type' => 1,											// 1=一般プラグイン, 2=管理プラグイン
				'default_action' => 'simpletexts/view',					// 初期表示アクション
				'default_setting_action' => 'simpletext_blocks/index',	// 設定初期表示アクション
			),
			//英語
			array(
				'language_id' => '1',									// 1=英語
				'key' => 'simpletexts',									// プラグインキー。自プラグイン名をスネーク記法で直書きする。日英同じ値
				'namespace' => 'opensource-workshop/simpletexts',		// packagist のパッケージ名。プラグイン管理でpackagistにリンク表示に利用。 日英同じ値
				'name' => 'Simpletexts',								// プラグイン名（英語）
				'type' => 1,											// 1=一般プラグイン, 2=管理プラグイン
				'default_action' => 'simpletexts/view',					// 初期表示アクション
				'default_setting_action' => 'simpletext_blocks/index',	// 設定初期表示アクション
			),
		),
		// [NetCommonsプラグイン作成] plugins_rolesテーブル（どのロール（administratorやsystem_administrator）が管理プラグインを使えるか）。
		// 管理プラグインでない場合、登録不要。
		//room_administratorのデータは一般プラグインなので、使ってないと思われる（https://github.com/NetCommons3/NetCommons3/issues/729）
		// 一般プラグインが使えるかどうかは、plugins_roomsテーブル（どのルームでプラグインが使えるか）で管理している。
		//'PluginsRole' => array(
		//	array(
		//		'role_key' => 'room_administrator',
		//		'plugin_key' => 'simpletexts',
		//	),
		//),
		// [NetCommonsプラグイン作成] 初期データであるルームにプラグインを配置できるように設定
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
 * function内に処理があったら、それは独自で何か実装している。
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function after($direction) {
		// [NetCommons独自] モデルの読み込み。phpunitテスト時はtest設定のデータベースを参照してくれる。親クラスのfunction。
		// \Plugin\NetCommons\Config\Migration\NetCommonsMigration::loadModels()
		// コントローラの`$uses = ['Simpletexts.Simpletext']`とモデル名を設定すると、`$this->Simpletext`で使えるようになる仕組みをまねて、
		// マイグレーションでも同じような事ができるようにfunctionで独自実装している。
		// これはマイグレーション版で、モデル版のloadModelsもNetCommons独自実装として存在する。
		//
		// ちょっと下の $this->Plugin->uninstallPlugin()を使うために、$this->loadModels()を使っている。
		$this->loadModels([
			'Plugin' => 'PluginManager.Plugin',
		]);

		// [Cakephpの決まり] マイグレーションで down、つまりバージョンダウンのオプションが指定された時に動く動作
		if ($direction === 'down') {
			// [NetCommons独自] プラグイン関係のデータ（Plugin、PluginsRole、PluginsRoom）を削除する
			// \Plugin\PluginManager\Model\Behavior\PluginBehavior::uninstallPlugin()
			// PluginBehavior::uninstallPlugin()は、Pluginモデル（\Plugin\PluginManager\Model\Plugin.php）の$actsAsで設定されたビヘイビアのため、
			// 下記のように呼び出せる。
			$this->Plugin->uninstallPlugin($this->records['Plugin'][0]['key']);
			return true;
		}

		foreach ($this->records as $model => $records) {
			// [NetCommons独自] $this->recordsを1件づつ登録する。親クラスのfunction。
			// \Plugin\NetCommons\Config\Migration\NetCommonsMigration::updateRecords();
			if (!$this->updateRecords($model, $records)) {
				return false;
			}
		}
		return true;
	}
}
