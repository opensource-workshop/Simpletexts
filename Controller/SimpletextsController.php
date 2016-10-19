<?php
/**
 * Simpletexts Controller
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @license https://www.freebsd.org/copyright/freebsd-license.html FreeBSD License
 * @copyright 2007 OpenSource-WorkShop Co.,Ltd.
 */

App::uses('SimpletextsAppController', 'Simpletexts.Controller');

/**
 * Simpletexts Controller
 */
class SimpletextsController extends SimpletextsAppController {

/**
 * [Cakephpの決まり] use model
 *
 * コントローラから利用するmodelはここに記載する。
 * コントローラで `$uses` を定義しなければ、自プラグイン内のモデルを自動的に設定されるようだ。
 *
 * #### 記載例）＜プラグイン名＞.＜モデル名＞
 * ```php
 *	public $uses = array(
 *		'Simpletexts.Simpletext',
 *		'Simpletexts.SimpletextFrameSetting',
 *		'Simpletexts.SimpletextSetting',
 *	);
 * ```
 *
 * 記載ないし、自プラグイン内モデルであれば、$this->＜モデル名＞にモデルオブジェクトが自動的にセットされる
 * 例）シンプルテキスト
 * ````php
 * $this->Simpletext
 * $this->SimpletextFrameSetting
 * $this->SimpletextSetting
 * ```
 *
 * @var array
 * @link http://book.cakephp.org/2.0/ja/models.html
 */
	public $uses = array(
		'Simpletexts.Simpletext',
		'Simpletexts.SimpletextFrameSetting',
		'Simpletexts.SimpletextSetting',
	);

/**
 * [Cakephpの決まり] use components
 * http://book.cakephp.org/2.0/ja/controllers/components.html
 * > コンポーネントはコントローラ間で共有されるロジックのパッケージです
 *
 * コンポーネント設定はarrayで設定すると、コンポーネントの $settings に値を渡せます。
 * ```php
 * 'NetCommons.Permission' => array(     // こっちのarrayがコンポーネントの $settingsに渡してる
 * 		'allow' => array(
 * 			'add,edit,delete' => 'content_creatable',
 * 		),
 *  ),
 *```
 *
 * @var array
 */
	public $components = array(
		// [NetCommons独自] 各アクションのパーミッション(許可)制限
		// 下記の設定は、content_creatable（コンテンツ作成許可）ありのユーザのみ
		// add,edit,deleteアクションを実行できる
		'NetCommons.Permission' => array(
			//アクセスの権限
			'allow' => array(
				'add,edit,delete' => 'content_creatable',
			),
		),
	);

/**
 * [Cakephpの決まり] use helpers
 * http://book.cakephp.org/2.0/ja/views/helpers.html
 * > ヘルパーはプレゼンテーションレイヤーのためのコンポーネントのようなクラスです。 多くのビューやエレメント、レイアウトで共有される表示ロジックを含んでいます。
 *
 *
 * @var array
 * @see NetCommonsAppController::$helpers
 */
	public $helpers = array(
		'Workflow.Workflow',	// [NetCommons] 承認コメント入力に必要
	);

/**
 * [Cakephpの決まり] beforeFilter
 * http://book.cakephp.org/2.0/ja/controllers.html#Controller::beforeFilter
 * > コントローラの各アクションの前に実行されます
 *
 * @return void
 * @see NetCommonsAppController::beforeFilter()
 */
	public function beforeFilter() {
		// [Cakephpの決まり]
		// http://book.cakephp.org/2.0/ja/controllers.html#appcontroller
		// > 子コントローラのコールバック中で AppController のコールバックを呼び出すのは、 このようにするのがベストです。
		parent::beforeFilter();

		// [NetCommons独自] ブロック未選択は、何も表示しない
		// Plugin\NetCommons\Utility\Current::read()
		// staticなので、コントローラー、モデル、ビュー等どこでも呼び出せて便利な function
		// Current::$currentに値のセットしているのは、
		// SimpletextsController::beforeFilter (呼び出し)--> NetCommonsAppController::beforeFilter() (呼び出し)--> Current::initialize() (呼び出し)--> CurrentFrame->initialize() (呼び出し)--> CurrentFrame::setBlock() ここ
		if (! Current::read('Block.id')) {
			// [NetCommons独自] 何も表示しません。
			// setAction()は、親クラスのNetCommonsAppControllerのfunctionです。
			// Plugin\NetCommons\Controller\NetCommonsAppController::emptyRender()
			// NetCommonsAppControllerは、全てのプラグインのコントローラの共通の親クラスです。
			// SimpletextsController (継承)--> SimpletextsAppController (継承)--> (AppController)AppControllerNC.php (継承)--> NetCommonsAppController (継承)--> [Cakephpの決まり] Controller
			$this->setAction('emptyRender');
			return false;
		}
	}

/**
 * 詳細
 *
 * @return CakeResponse
 */
	public function view() {
		$simpletext = $this->Simpletext->getSimpletext();
		if (! $simpletext) {
			// [NetCommons独自] コンテンツ編集許可(content_editable)はあるか
			// Plugin\NetCommons\Utility\Current::permission()
			// staticなので、コントローラー、モデル、ビュー等どこでも呼び出せて便利な function
			if (Current::permission('content_editable')) {
				// [NetCommons独自] modelからアソシエーション（関連モデル）も含めて新規データを生成します。
				// シンプルテキストの場合、Simpletextモデルと、Blockモデルの新規データを生成します。
				// createAll()は、親クラスのNetCommonsAppModelのfunctionです。
				// Plugin\NetCommons\Model\NetCommonsAppModel::createAll()
				// NetCommonsAppModelは、全てのプラグインのモデルの共通の親クラスです。
				// Simpletext (継承)--> SimpletextsAppModel (継承)--> AppModel.php (継承)--> NetCommonsAppModel (継承)--> [Cakephpの決まり] Model
				$simpletext = $this->Simpletext->createAll();
			} else {
				// [NetCommons独自] 何も表示しません。
				// Plugin\NetCommons\Controller\NetCommonsAppController::emptyRender()
				return $this->setAction('emptyRender');
			}
		}
		// [Cakephpの決まり] Viewに値を渡します。Viewでは　$simpletext で取得できます。
		$this->set('simpletext', $simpletext['Simpletext']);
	}

/**
 * 編集
*
* @return CakeResponse
*/
	public function edit() {
		if ($this->request->is('post') || $this->request->is('put')) {
			$data = $this->data;
			$data['Simpletext']['status'] = $this->Workflow->parseStatus();
			unset($data['Simpletext']['id']);

			if ($this->Simpletext->saveSimpletext($data)) {
				return $this->redirect(NetCommonsUrl::backToPageUrl());
			}
			$this->NetCommons->handleValidationError($this->Simpletext->validationErrors);

		} else {
			//初期データセット
			if (! $this->request->data = $this->Simpletext->getSimpletext()) {
				$this->request->data = $this->Simpletext->createAll();
				$this->request->data = Hash::merge($this->request->data,
					$this->SimpletextFrameSetting->getSimpletextFrameSetting(true));
				$this->request->data = Hash::merge($this->request->data,
					$this->SimpletextSetting->createSimpletextSetting());
			}
			$this->request->data['Frame'] = Current::read('Frame');
		}

		/** @see WorkflowCommentBehavior::getCommentsByContentKey() */
		$comments = $this->Simpletext->getCommentsByContentKey(
			$this->request->data['Simpletext']['key']
		);
		$this->set('comments', $comments);
	}
}
