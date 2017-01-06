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
			// そのため、関連しているmodelはこのloadModelsに書いておき、BlockBehavior::deleteBlock()（ブロック削除）を呼び出せば削除してくれる
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
 * [Cakephpの決まり] validate 実行前に自動的に呼び出される
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
 * [Cakephpの決まり] 保存前に自動的に呼び出される
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

		// SimpletextSetting登録
		//
		// [cakephpの決まり] $this->saveSimpletext($data)にて、$this->save()をするまでに $this->set($data) をしているため、$this->dataに値がセットされている。
		// そのため、beforeSave()で$this->dataで値が取得できる
		if (isset($this->data['SimpletextSetting'])) {
			// [cakephpの決まり] Model::set()
			// http://book.cakephp.org/2.0/ja/models/saving-your-data.html#model-set-one-two-null
			$this->SimpletextSetting->set($this->data['SimpletextSetting']);
			// [cakephpの決まり] Model::save()
			// http://book.cakephp.org/2.0/ja/models/saving-your-data.html#model-save-array-data-null-boolean-validate-true-array-fieldlist-array
			$this->SimpletextSetting->save(null, false);
		}

		// SimpletextFrameSetting登録
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
			// [recursive] findでどこまで関連づけて取得するかの設定。0はこのモデルのhasOne,belongToのみ関連づけてデータを取ってくる
			// 下記URLはcakephp1.xのrecursive図だけど、cakephp2.xでも通用して、わかりやすいです
			// http://www.cpa-lab.com/tech/081
			// http://book.cakephp.org/2.0/ja/models/model-attributes.html#recursive
			'recursive' => 0,
			// [conditions] 検索条件の配列
			'conditions' => $this->getBlockConditionById($conditions),
		));

		// 取得結果が空や検索失敗(false)ならreturn
		if (!$simpletext) {
			return $simpletext;
		}
		// シンプルテキストデータに設定してある表示設定, ブロック設定をくっつけてreturn
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
		// [Cakephpの決まり] トランザクション開始
		//トランザクションBegin
		$this->begin();

		// [Cakephpの決まり] モデルに値をセットする
		//バリデーション
		$this->set($data);
		// [Cakephpの決まり] 入力値チェック
		// $this->validates() を実行することで、$this->beforeValidate()が自動実行され、入力値がチェックされる。
		// 値がNGなら、saveしないでここでreturn false
		/* @see beforeValidate() */
		if (! $this->validates()) {
			return false;
		}

		try {
			// [Cakephpの決まり] 保存（登録or更新）
			// 第一引数はdata。既に $this->set($data) で値をセットしているので、nullでOK。
			// 第二引数はvalidate。既に $this->validates() で値チェックしているので、false
			// [推測] ここでvalidateすると入力値エラーか、値不備によるsave失敗か、DB接続エラーなのか、判別つかないため
			// このような形にしていると思われる。
			// https://book.cakephp.org/2.0/ja/models/saving-your-data.html
			if (! $simpletext = $this->save(null, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			// [Cakephpの決まり] トランザクション終了 - 正常
			//トランザクションCommit
			$this->commit();

		} catch (Exception $ex) {
			// [Cakephpの決まり] トランザクション終了 - 異常
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
			// [NetCommons独自] Behaviorで関連データ削除する際に使用する。らしい。
			$this->contentKey = $data[$this->alias]['key'];
			// [Cakephp] 削除条件にkeyを設定
			// 第一引数の削除条件。$this->alias にこのモデルのテーブル別名がセットされているので、この書き方になる
			$conditions = array($this->alias . '.key' => $data[$this->alias]['key']);
			// [Cakephp] https://book.cakephp.org/2.0/ja/models/deleting-data.html#deleteall
			// 第二引数のカスケード。デフォルトtrueなので false を設定。アソシエーション（$belongsToとか）で設定した関連するテーブルデータを消す。
			//   このタイミングで消したくない（BlockBehaviorのloadModelsで指定した関連データを BlockBehavior::deleteBlock() で削除）のでfalse。
			// 第三引数のコールバック。デフォルトfalseなので trueに設定。trueにしないとビヘイビアのbeforeDelete(), afterDelete()が動かない。
			//   beforeDelete(), afterDelete()を使っているのは、ぱっと見 WorkflowCommentBehavior のみ。
			if (! $this->deleteAll($conditions, false, true)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			//Blockデータ削除
			// [NetCommonsの決まり] シンプルテキストはお知らせ系。
			// お知らせはブロックと対の関係なので、お知らせデータ消したらブロックデータも消す。
			// BlockBehaviorのloadModelsで指定したモデルも併せて消す。
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
