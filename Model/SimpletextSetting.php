<?php
/**
 * SimpletextSetting Model
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @license https://www.freebsd.org/copyright/freebsd-license.html FreeBSD License
 * @copyright 2007 OpenSource-WorkShop Co.,Ltd.
 */

// [Cakephpの決まり] Cakephp用のinclude
// http://book.cakephp.org/2.0/ja/core-utility-libraries/app.html#App::uses
App::uses('BlockSettingBehavior', 'Blocks.Model/Behavior');
App::uses('BlockBaseModel', 'Blocks.Model');

/**
 * SimpletextSetting Model
 *
 * [NetCommonsプラグイン] XxxxxSettingは他プラグインからほぼコピペ
 * [NetCommons独自] XxxxxSettingはBlockBaseModelを継承する
 * Plugin\Blocks\Model\BlockBaseModel.php
 *   XxxxxSettingは通常、対応するテーブルがない。
 *   そのため、BlockBaseModelでは、テーブルを使わない（$useTable = false;） が初期値になっており、
 *   save()も上書きされていて、テーブルを使わない場合の処理が拡張されている。
 */
class SimpletextSetting extends BlockBaseModel {

/**
 * Custom database table name
 *
 * [Cakephoの決まり] テーブルのないモデルは、falseを指定する
 * [NetCommonsプラグイン] BlockBaseModelで $useTable = false; なので本来不要
 * 後から BlockBaseModel つけたした経緯から、残っているものと思われる
 *
 * @var string
 */
	public $useTable = false;

/**
 * Validation rules
 *
 * @var array
 * @see Simpletext::$validate と説明同様
 * [NetCommons独自] XxxxxSettingは $actsAs の BlockSettingBehavior を使う事が必須で、
 * BlockSettingBehavior::beforeValidate() で validateが動くので、このクラスでvalidate定義は不要。
 */
	public $validate = array();

/**
 * [Cakephpの決まり] use behaviors
 *
 * @var array
 */
	public $actsAs = array(
		// [NetCommons独自]
		'Blocks.BlockRolePermission',
		// [NetCommons独自] ブロック設定（縦持ちデータ）のcreate, save, deleteのファンクションや
		// 自動的にvalidateするbeforeValidateを提供
		// Plugin\Blocks\Model\Behavior\BlockSettingBehavior.php
		// https://netcommons3.github.io/NetCommons3Docs/phpdoc/Blocks/classes/BlockSettingBehavior.html
		'Blocks.BlockSetting' => array(
			// 利用するブロック設定
			// BlockSettingBehaviorの定数も利用できるし、独自のStringで設定する事もできる。
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
