<?php
/**
 * SimpletextSetting Model
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @license https://www.freebsd.org/copyright/freebsd-license.html FreeBSD License
 * @copyright 2007 OpenSource-WorkShop Co.,Ltd.
 */

App::uses('BlockSettingBehavior', 'Blocks.Model/Behavior');
App::uses('BlockBaseModel', 'Blocks.Model');

/**
 * SimpletextSetting Model
 *
 * [NetCommonsプラグイン] XxxxxSettingは他プラグインからほぼコピペ
 */
class SimpletextSetting extends BlockBaseModel {

/**
 * Custom database table name
 *
 * [Cakephoの決まり] テーブルのないモデルは、falseを指定する
 *
 * @var string
 */
	public $useTable = false;

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array();

/**
 * use behaviors
 *
 * @var array
 */
	public $actsAs = array(
		'Blocks.BlockRolePermission',
		'Blocks.BlockSetting' => array(
			BlockSettingBehavior::FIELD_USE_WORKFLOW,
		),
	);


/**
 * ブロック設定 新規作成
 *
 * @return array
 * @see BlockSettingBehavior::createBlockSetting() 新規作成
 */
	public function createSimpletextSetting() {
		return $this->createBlockSetting();
	}

/**
 * ブロック設定 取得
 *
 * @return array
 * @see BlockSettingBehavior::getBlockSetting() 取得
 */
	public function getSimpletextSetting() {
		return $this->getBlockSetting();
	}

/**
 * ブロック設定 保存
 *
 * @param array $data リクエストデータ
 * @return bool
 * @throws InternalErrorException
 */
	public function saveSimpletextSetting($data) {
		//トランザクションBegin
		$this->begin();

		//バリデーション
		$this->set($data);
		if (! $this->validates()) {
			return false;
		}

		try {
			$this->save(null, false);

			//トランザクションCommit
			$this->commit();

		} catch (Exception $ex) {
			//トランザクションRollback
			$this->rollback($ex);
		}

		return true;
	}

}
