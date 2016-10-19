<?php
/**
 * SimpletextFrameSetting Model
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @license https://www.freebsd.org/copyright/freebsd-license.html FreeBSD License
 * @copyright 2007 OpenSource-WorkShop Co.,Ltd.
 */

App::uses('SimpletextsAppModel', 'Simpletexts.Model');

/**
 * Summary for SimpletextFrameSetting Model
 *
 * [NetCommonsプラグイン] XxxxxFrameSettingは他プラグインからほぼコピペ
 */
class SimpletextFrameSetting extends SimpletextsAppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array();

/**
 * validate 実行前
 *
 * @param array $options Options passed from Model::save().
 * @return bool True if validate operation should continue, false to abort
 * @link http://book.cakephp.org/2.0/ja/models/callback-methods.html#beforevalidate
 * @see Model::save()
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
 * belongsTo associations
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
 *
 * @param bool $created If True, the results of the Model::find() to create it if it was null
 * @return array
 */
	public function getSimpletextFrameSetting($created) {
		$conditions = array(
			'frame_key' => Current::read('Frame.key')
		);

		$simpletextFrameSetting = $this->find('first', array(
			'recursive' => -1,
			'conditions' => $conditions,
		));

		if ($created && ! $simpletextFrameSetting) {
			$simpletextFrameSetting = $this->create(array(
				'frame_key' => Current::read('Frame.key'),
			));
		}

		return $simpletextFrameSetting;
	}

/**
 * 表示方法 保存
 *
 * @param array $data received post data
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
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
