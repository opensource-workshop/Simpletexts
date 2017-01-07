<?php
/**
 * SimpletextFrameSetting Model
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @license https://www.freebsd.org/copyright/freebsd-license.html FreeBSD License
 * @copyright 2007 OpenSource-WorkShop Co.,Ltd.
 */

// [Cakephpの決まり] Cakephp用のinclude
// http://book.cakephp.org/2.0/ja/core-utility-libraries/app.html#App::uses
App::uses('SimpletextsAppModel', 'Simpletexts.Model');

/**
 * Summary for SimpletextFrameSetting Model
 *
 * [NetCommonsプラグイン] XxxxxFrameSettingは他プラグインからほぼコピペ
 */
class SimpletextFrameSetting extends SimpletextsAppModel {

/**
 * [Cakephpの決まり] Validation rules
 *
 * @var array
 * @see Simpletext::$validate と説明同様
 */
	public $validate = array();

/**
 * [Cakephpの決まり] validate 実行前に自動的に呼び出される
 *
 * @param array $options Options passed from Model::save().
 * @return bool True if validate operation should continue, false to abort
 * @link http://book.cakephp.org/2.0/ja/models/callback-methods.html#beforevalidate
 * @see Model::save()
 * @see Simpletext::beforeValidate() と説明同様
 */
	public function beforeValidate($options = array()) {
		$this->validate = Hash::merge($this->validate, array(
			'frame_key' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' => __d('net_commons', 'Invalid request.'),
					'required' => true,
				),
			),
			'textarea_edit_row' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					'message' => __d('net_commons', 'Invalid request.'),
					'required' => true,
				),
			),
		));

		return parent::beforeValidate($options);
	}

/**
 * [Cakephpの決まり] belongsTo associations
 * アソシエーション: モデル同士を繋ぐ。link参照
 *
 * @var array
 * @link http://book.cakephp.org/2.0/ja/models/associations-linking-models-together.html アソシエーション: モデル同士を繋ぐ
 */
	public $belongsTo = array(
		'Frame' => array(
			'className' => 'Frames.Frame',
			'foreignKey' => false,
			'conditions' => array(
				'Frame.key = SimpletextFrameSetting.frame_key',
			),
			'fields' => 'block_id',
			'order' => ''
		),
	);

/**
 * 表示方法 取得
 * [NetCommons独自] ファンクション名 get(動詞)+SimpletextFrameSetting(モデル名)
 *
 * @param bool $created If True, the results of the Model::find() to create it if it was null
 * @return array
 */
	public function getSimpletextFrameSetting($created) {
		// [NetCommons独自] Current::read()
		// Plugin\NetCommons\Utility\Current::read()
		// https://netcommons3.github.io/NetCommons3Docs/phpdoc/NetCommons/classes/Current.html
		// BlockやFrame, Pluginといった共通テーブルのデータがあるかチェックできるファンクション。
		//
		// NetCommonsの便利クラス Current
		// - NetCommonsAppController::beforeFilter で初期処理が呼び出され、値が設定されます。
		// - NetCommonsAppControllerは、全てのプラグインのコントローラの共通の親クラスなので、画面系ならコントローラーが動くので、コントローラー以降に動くモデルやビューでも Current クラスを使う事ができます。
		$conditions = array(
			'frame_key' => Current::read('Frame.key')
		);

		// [Cakephpの決まり] $this->find() DB検索
		// 詳しくはこちら Plugin\Simpletexts\Model\Simpletext::getSimpletext()
		$simpletextFrameSetting = $this->find('first', array(
			'recursive' => -1,
			'conditions' => $conditions,
		));

		// 新規作成でデータなしなら、新規作成
		if ($created && ! $simpletextFrameSetting) {
			// [Cakephpの決まり] $this->create() データ新規作成
			// https://book.cakephp.org/2.0/ja/models/saving-your-data.html#model-create-array-data-array
			// $this->create()をすると
			// ・空データを新規作成して取得できる
			// ・モデル内のクラス変数初期化
			// ・引数にデータをセットすると、取得する新規作成データにセットしてくれる
			$simpletextFrameSetting = $this->create(array(
				'frame_key' => Current::read('Frame.key'),
			));
		}

		return $simpletextFrameSetting;
	}

/**
 * 表示方法 保存
 * [NetCommons独自] ファンクション名 save(動詞)+SimpletextFrameSetting(モデル名)
 *
 * @param array $data received post data
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
 * @see Simpletext::saveSimpletext() と説明同様
 */
	public function saveSimpletextFrameSetting($data) {
		//トランザクションBegin
		$this->begin();

		//バリデーション
		$this->set($data);
		if (! $this->validates()) {
			$this->rollback();
			return false;
		}

		try {
			// 保存
			if (! $simpletextFrameSetting = $this->save(null, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			//トランザクションCommit
			$this->commit();

		} catch (Exception $ex) {
			//トランザクションRollback
			$this->rollback($ex);
		}

		return $simpletextFrameSetting;
	}
}
