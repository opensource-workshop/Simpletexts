<?php
/**
 * Simpletext Model
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @license https://www.freebsd.org/copyright/freebsd-license.html FreeBSD License
 * @copyright 2007 OpenSource-WorkShop Co.,Ltd.
 */

// [Cakephpの決まり] Cakephp用のinclude
// http://book.cakephp.org/2.0/ja/core-utility-libraries/app.html#App::uses
App::uses('SimpletextsAppModel', 'Simpletexts.Model');

/**
 * Summary for Simpletext Model
 *
 * [Cakephpの決まり] XxxxAppModelを継承する
 */
class Simpletext extends SimpletextsAppModel {

/**
 * 概要の文字数
 * view等で使う定数を定義
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
		// [NetCommons独自] ブロック関係をsave, delete
		// Plugin\Blocks\Model\Behavior\BlockBehavior.php
		// https://netcommons3.github.io/NetCommons3Docs/phpdoc/Blocks/classes/BlockBehavior.html
		'Blocks.Block' => array(
			// [name] ブロック名を下記のようなフィールド名で指定
			'name' => 'Simpletext.textarea',
			// [nameHtml] true にすると、strip_tags等行って、ブロック名を生成する。結果空になったら"（テキストなし）"をセット
			'nameHtml' => true,
			// [loadModels] save, delete時にloadModels()してくれる
			// loadModels()とは、モデルで使える $this->loadModels() の事。ClassRegistry::init()を使ってモデルをnewして、クラス変数（$this->$model）にセットしてくれる。
			// Plugin\NetCommons\Model\NetCommonsAppModel::loadModels()
			// ブロック削除時（delete時）にblock_id, block_keyで紐づいてるデータ削除
			// そのため、関連しているmodelはこのloadModelsに書いておけば、ブロック削除時に自動的に削除してくれる
			'loadModels' => array(
				'BlockSetting' => 'Blocks.BlockSetting',
			)
		),
		// [NetCommons独自] save時、自動でkeyセット
		// Plugin\NetCommons\Model\Behavior\OriginalKeyBehavior.php
		'NetCommons.OriginalKey',
		// [NetCommons独自] save時、自動でis_active, is_latestセット
		// Plugin\Workflow\Model\Behavior\WorkflowBehavior.php
		'Workflow.Workflow',
		// [NetCommons独自] 承認コメントのsave, delete
		// Plugin\Workflow\Model\Behavior\WorkflowCommentBehavior.php
		'Workflow.WorkflowComment',
		// [NetCommons独自] メール送信に必要。登録・編集時に自動でメールキューの登録, 削除。ワークフロー利用時はWorkflow.Workflowより下に記述する
		// Plugin\Mails\Model\Behavior\MailQueueBehavior.php
		// https://netcommons3.github.io/NetCommons3Docs/phpdoc/Mails/classes/MailQueueBehavior.html#method_setup
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
		// Plugin\Blocks\Model\Block.php を紐づけてる
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
 * http://book.cakephp.org/2.0/ja/models/data-validation.html
 *
 * @param array $options Options passed from Model::save().
 * @return bool True if validate operation should continue, false to abort
 * @link http://book.cakephp.org/2.0/ja/models/callback-methods.html#beforevalidate
 * @see Model::save()
 */
	public function beforeValidate($options = array()) {
		// [Cakephpの決まり] Hash::merge() - Arrayをマージします
		// http://book.cakephp.org/2.0/ja/core-utility-libraries/hash.html#Hash::merge
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

		// [phpの決まり] 継承を考慮して parent(親) のファンクションを呼び出す
		return parent::beforeValidate($options);
	}

/**
 * [Cakephpの決まり] 保存前
 *
 * @param array $options Options passed from Model::save().
 * @return bool True if the operation should continue, false if it should abort
 * @link http://book.cakephp.org/2.0/ja/models/callback-methods.html#beforesave
 * @see Model::save()
 */
	public function beforeSave($options = array()) {
		// [NetCommons独自] ClassRegistry::init()を使ってモデルをnewして、クラス変数（$this->$model）にセットしてくれる。
		// Plugin\NetCommons\Model\NetCommonsAppModel::loadModels()
		// 元ネタは、[Cakephpの決まり] Controller::loadModel()。これをモデルでも使えるようにアレンジしたもの
		// http://book.cakephp.org/2.0/ja/controllers.html#Controller::loadModel
		$this->loadModels([
			'SimpletextSetting' => 'Simpletexts.SimpletextSetting',
			'SimpletextFrameSetting' => 'Simpletexts.SimpletextFrameSetting',
		]);

		//SimpletextSetting登録
		if (isset($this->data['SimpletextSetting'])) {
			// [cakephpの決まり] Model::set()
			// http://book.cakephp.org/2.0/ja/models/saving-your-data.html#model-set-one-two-null
			$this->SimpletextSetting->set($this->data['SimpletextSetting']);
			// [cakephpの決まり] Model::save()
			// http://book.cakephp.org/2.0/ja/models/saving-your-data.html#model-save-array-data-null-boolean-validate-true-array-fieldlist-array
			$this->SimpletextSetting->save(null, false);
		}

		//SimpletextFrameSetting登録
		if (isset($this->data['SimpletextFrameSetting'])) {
			$this->SimpletextFrameSetting->set($this->data['SimpletextFrameSetting']);
			$this->SimpletextFrameSetting->save(null, false);
		}

		// [phpの決まり] 継承を考慮して parent(親) のファンクションを呼び出す
		parent::beforeSave($options);
	}

/**
 * 取得
 * [NetCommons独自] ファンクション名 get(動詞)+Simpletext(モデル名)
 *
 * @return array
 */
	public function getSimpletext() {
		$this->loadModels([
			'SimpletextSetting' => 'Simpletexts.SimpletextSetting',
			'SimpletextFrameSetting' => 'Simpletexts.SimpletextFrameSetting',
		]);

		// [NetCommons独自] Current::permission()
		// Plugin\NetCommons\Utility\Current::permission()
		// https://netcommons3.github.io/NetCommons3Docs/phpdoc/NetCommons/classes/Current.html
		// ユーザにパーミッション（操作許可）があるかチェックできるファンクション。
		//
		// コンテンツ編集許可があるか
		// Current::permission('content_editable')
		//
		// NC2では、操作できる・できないは権限（システム管理者、主担等）に紐づいて切り離せないものでしたが、
		// NC3では、権限とは別に、ルームの役割（ロール：ルーム管理者、編集長、編集者等）にパーミッションがくっついているデータ構造のため、柔軟に操作できる・できないを変える事もできます。
		// [蛇足] 2016-12-29時点で、パーミッションを自在に変える画面は用意されていないので、変えるとすればDB値を直接変える必要があります。
		//
		// NetCommonsの便利クラス Current
		// - NetCommonsAppController::beforeFilter で初期処理が呼び出され、値が設定されます。
		// - NetCommonsAppControllerは、全てのプラグインのコントローラの共通の親クラスなので、画面系ならコントローラーが動くので、コントローラー以降に動くモデルやビューでも Current クラスを使う事ができます。
		if (Current::permission('content_editable')) {
			// コンテンツ編集許可ありなら、最新版を取得条件に追加
			$conditions[$this->alias . '.is_latest'] = true;
		} else {
			// コンテンツ編集許可なしなら、表示している版（最新版ではない）を取得条件に追加
			$conditions[$this->alias . '.is_active'] = true;
		}
		// [Cakephpの決まり] $this->find() DB検索
		// http://book.cakephp.org/2.0/ja/models/retrieving-your-data.html#find
		$simpletext = $this->find('first', array(
			// [recursive] 下記URLはcakephp1.xのrecursive図だけど、cakephp2.xでも通用して、わかりやすいです
			// http://www.cpa-lab.com/tech/081
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
 * [NetCommons独自] ファンクション名 save(動詞)+Simpletext(モデル名)
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
 * [NetCommons独自] ファンクション名 delete(動詞)+Simpletext(モデル名)
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
