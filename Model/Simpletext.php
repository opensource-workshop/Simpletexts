<?php
/**
 * Simpletext Model
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @license https://www.freebsd.org/copyright/freebsd-license.html FreeBSD License
 * @copyright 2007 OpenSource-WorkShop Co.,Ltd.
 */

App::uses('SimpletextsAppModel', 'Simpletexts.Model');

/**
 * Summary for Simpletext Model
 */
class Simpletext extends SimpletextsAppModel {

/**
 * 概要の文字数
 *
 * @var int
 */
	const DISPLAY_SUMMARY_LENGTH = 1000;

/**
 * [Cakephpの決まり] use behaviors
 * モデルの共通クラス
 * http://book.cakephp.org/2.0/ja/models/behaviors.html
 * > モデルのビヘイビアは、CakePHP のモデルに定義された機能のうちいくつかをまとめるひとつの方法です。 これを利用すると、継承を必要とせずに、典型的な挙動を示すロジックを分割し再利用することができます。
 *
 * 設定したビヘイビアの関数は、モデルの関数として使えるようになる。
 * 例）
 * ```php
 * 	public $actsAs = array(
 * 		'Workflow.WorkflowComment',
 * 	);
 * // Simpletextモデルに getCommentsByContentKey() 関数ないけど、$actsAsでWorkflowCommentBehaviorを設定しているから、呼び出せる。
 * $this->getCommentsByContentKey('key');
 *```
 *
 * @var array
 * @see MailQueueBehavior
 */
	public $actsAs = array(
		'Blocks.Block' => array(
			'name' => 'Simpletext.textarea',
			'nameHtml' => true,
			'loadModels' => array(
				'BlockSetting' => 'Blocks.BlockSetting',
			)
		),
		'NetCommons.OriginalKey',		// save時、自動でkeyセット
		'Workflow.Workflow',			// save時、自動でis_active, is_latestセット
		'Workflow.WorkflowComment',
		// [NetCommons独自] メール送信に必要。登録・編集時にメールキューに溜める
		'Mails.MailQueue' => array(
			'embedTags' => array(
				'X-BODY' => 'Simpletext.textarea',
			),
		),
	);

/**
 * [Cakephpの決まり] belongsTo associations
 * アソシエーション: モデル同士を繋ぐ。link参照
 *
 * @var array
 * @link http://book.cakephp.org/2.0/ja/models/associations-linking-models-together.html アソシエーション: モデル同士を繋ぐ
 */
	public $belongsTo = array(
		'Block' => array(
			'className' => 'Blocks.Block',
			'foreignKey' => 'block_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);

/**
 * [Cakephpの決まり] Validation rules
 *
 * ### [cakephpテクニック]
 * $validateはクラス内変数のため、多言語対応のファンクション __d()が利用できない。
 * そのため、$this->beforeValidate() に validateを記述する
 *
 * @var array
 */
	public $validate = array();

/**
 * [Cakephpの決まり] validate 実行前
 *
 * @param array $options Options passed from Model::save().
 * @return bool True if validate operation should continue, false to abort
 * @link http://book.cakephp.org/2.0/ja/models/callback-methods.html#beforevalidate
 * @see Model::save()
 */
	public function beforeValidate($options = array()) {
		$this->validate = Hash::merge($this->validate, array(
			'language_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					'message' => __d('net_commons', 'Invalid request.'),
					'required' => true,
				),
			),
			// block_idは自動的にセットされるため、validate不要
			//			'block_id' => array(
			//				'numeric' => array(
			//					'rule' => array('numeric'),
			//					'message' => __d('net_commons', 'Invalid request.'),
			//					'required' => true,
			//				),
			//			),
			'textarea' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' => sprintf(__d('net_commons', 'Please input %s.'), __d('simpletexts', '本文')),
					'required' => true,
				),
			),
		));

		return parent::beforeValidate($options);
	}

/**
 * [Cakephpの決まり] 保存後
 *
 * @param bool $created True if this save created a new record
 * @param array $options Options passed from Model::save().
 * @return void
 * @throws InternalErrorException
 * @link http://book.cakephp.org/2.0/ja/models/callback-methods.html#aftersave
 * @see Model::save()
 */
	public function afterSave($created, $options = array()) {
		$this->loadModels([
			'SimpletextSetting' => 'Simpletexts.SimpletextSetting',
			'SimpletextFrameSetting' => 'Simpletexts.SimpletextFrameSetting',
		]);

		//SimpletextSetting登録
		if (isset($this->data['SimpletextSetting'])) {
			$this->SimpletextSetting->set($this->data['SimpletextSetting']);
			$this->SimpletextSetting->save(null, false);
		}

		//SimpletextFrameSetting登録
		if (isset($this->data['SimpletextFrameSetting'])) {
			$this->SimpletextFrameSetting->set($this->data['SimpletextFrameSetting']);
			$this->SimpletextFrameSetting->save(null, false);
		}

		parent::afterSave($created, $options);
	}

/**
 * 取得
 *
 * @return array
 */
	public function getSimpletext() {
		$this->loadModels([
			'SimpletextSetting' => 'Simpletexts.SimpletextSetting',
			'SimpletextFrameSetting' => 'Simpletexts.SimpletextFrameSetting',
		]);

		if (Current::permission('content_editable')) {
			$conditions[$this->alias . '.is_latest'] = true;
		} else {
			$conditions[$this->alias . '.is_active'] = true;
		}
		$simpletext = $this->find('first', array(
			'recursive' => 0,
			'conditions' => $this->getBlockConditionById($conditions),
		));
		if (!$simpletext) {
			return $simpletext;
		}

		$result = Hash::merge($simpletext, $this->SimpletextFrameSetting->getSimpletextFrameSetting(false));
		$result = Hash::merge($result, $this->SimpletextSetting->getSimpletextSetting());
		return $result;
	}

/**
 * 保存
 *
 * @param array $data received post data
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
 */
	public function saveSimpletext($data) {
		//トランザクションBegin
		$this->begin();

		//バリデーション
		$this->set($data);
		/* @see beforeValidate() */
		if (! $this->validates()) {
			return false;
		}

		try {
			if (! $simpletext = $this->save(null, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			//トランザクションCommit
			$this->commit();

		} catch (Exception $ex) {
			//トランザクションRollback
			$this->rollback($ex);
		}

		return $simpletext;
	}

/**
 * 削除
 *
 * @param array $data received post data
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
 */
	public function deleteSimpletext($data) {
		//トランザクションBegin
		$this->begin();

		try {
			//Simpletextの削除
			/** @see NetCommonsAppModel::$contentKey */
			$this->contentKey = $data[$this->alias]['key'];
			$conditions = array($this->alias . '.key' => $data[$this->alias]['key']);
			if (! $this->deleteAll($conditions, false, true)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			//Blockデータ削除
			/** @see BlockBehavior::deleteBlock() */
			$this->deleteBlock($data['Block']['key']);

			//トランザクションCommit
			$this->commit();

		} catch (Exception $ex) {
			//トランザクションRollback
			$this->rollback($ex);
		}

		return true;
	}

}
