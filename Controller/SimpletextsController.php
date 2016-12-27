<?php
/**
 * Simpletexts Controller
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @license https://www.freebsd.org/copyright/freebsd-license.html FreeBSD License
 * @copyright 2007 OpenSource-WorkShop Co.,Ltd.
 */

// [Cakephpの決まり] Cakephp用のinclude
// http://book.cakephp.org/2.0/ja/core-utility-libraries/app.html#App::uses
App::uses('SimpletextsAppController', 'Simpletexts.Controller');

/**
 * Simpletexts Controller
 *
 * [Cakephpの決まり] XxxxAppControllerを継承する
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
 * この記載例）の書き方は「プラグイン記法」と呼ばれる。http://book.cakephp.org/2.0/ja/appendices/glossary.html
 *
 * 記載ないし、自プラグイン内モデルであれば、$this->＜モデル名＞にモデルオブジェクトが自動的にセットされる
 * 例）シンプルテキスト
 * ````php
 * $this->Simpletext
 * $this->SimpletextFrameSetting
 * $this->SimpletextSetting
 * ```
 *
 * [NetCommons独自] 継承した親クラス NetCommonsAppControllerにも $uses 設定あり
 * NetCommonsAppControllerは、全てのプラグインのコントローラの共通の親クラスです。
 * SimpletextsController (継承)--> SimpletextsAppController (継承)--> (AppController)AppControllerNC.php (継承)--> NetCommonsAppController (継承)--> [Cakephpの決まり] Controller
 * Plugin\NetCommons\Controller\NetCommonsAppController::$uses
 *
 * @var array
 * @link http://book.cakephp.org/2.0/ja/models.html
 * @see NetCommonsAppController::$uses
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
 * [NetCommons独自] 継承した親クラス NetCommonsAppControllerにも $components 設定あり
 *
 * @var array
 * @see NetCommonsAppController::$components
 */
	public $components = array(
		// [NetCommons独自] 各アクションのパーミッション(許可)制限
		// Plugin\NetCommons\Controller\Component\PermissionComponent.php
		// 下記の設定は、content_creatable（コンテンツ作成許可）ありのユーザのみ
		// add,edit,deleteアクションを実行できる
		//
		// --- 備考
		// NetCommonsでは登録するメインの内容（動画とか、お知らせなら本文とか）をコンテンツと呼んでいる
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
 * [NetCommons独自] 継承した親クラス NetCommonsAppControllerにも $helpers 設定あり
 *
 * @var array
 * @see NetCommonsAppController::$helpers
 */
	public $helpers = array(
		'Workflow.Workflow',	// [NetCommons独自] 承認コメント入力に必要
	);

	// 以下の処理はシンプルテキストには不要でした。
	///**
	// * [Cakephpの決まり] beforeFilter
	// * http://book.cakephp.org/2.0/ja/controllers.html#Controller::beforeFilter
	// * > コントローラの各アクションの前に実行されます
	// *
	// * [NetCommons独自] 継承した親クラス NetCommonsAppControllerにも beforeFilter() あり
	// *
	// * @return void
	// * @see NetCommonsAppController::beforeFilter()
	// */
	//	public function beforeFilter() {
	//		// [Cakephpの決まり]
	//		// http://book.cakephp.org/2.0/ja/controllers.html#appcontroller
	//		// > 子コントローラのコールバック中で AppController のコールバックを呼び出すのは、 このようにするのがベストです。
	//		parent::beforeFilter();
	//
	//		// [NetCommons独自] ブロック未選択は、何も表示しない
	//		// Plugin\NetCommons\Utility\Current::read()
	//		// staticなので、コントローラー、モデル、ビュー等どこでも呼び出せて便利な function
	//		// Current::$currentに値のセットしているのは、
	//		// SimpletextsController::beforeFilter (呼び出し)--> NetCommonsAppController::beforeFilter() (呼び出し)--> Current::initialize() (呼び出し)--> CurrentFrame->initialize() (呼び出し)--> CurrentFrame::setBlock() ここ
	//		if (! Current::read('Block.id')) {
	//			// [NetCommons独自] 何も表示しません。
	//			// setAction()を使って、親クラスで継承しているNetCommonsAppController::emptyRenderを呼んでいます。
	//			// [Cakephpの決まり] setAction()、Action間の移動を可能にするsetAction関数
	//			// http://api.cakephp.org/2.9/class-Controller.html#_setAction
	//			// vendors\cakephp\cakephp\lib\Cake\Controller\Controller::setAction()
	//			// [NetCommons独自] emptyRender()
	//			// Plugin\NetCommons\Controller\NetCommonsAppController::emptyRender()
	//			$this->setAction('emptyRender');
	//			return false;
	//		}
	//	}

/**
 * 詳細
 *
 * @return CakeResponse
 */
	public function view() {
		// [Cakephpの決まり] SimpletextモデルのgetSimpletext関数を呼びます
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
		// [Cakephpの決まり] $this->request->is('post')
		// http://book.cakephp.org/2.0/ja/controllers/request-response.html#cakerequest
		// > デフォルトの CakeRequest は $this->request に設定され、コントローラ、ビュー、 ヘルパーの中で利用できます。またコントローラの参照を使うことでコンポーネントの中からも アクセスすることが出来ます。
		// vendors\cakephp\cakephp\lib\Cake\Controller\Controller::$request
		//
		// http://book.cakephp.org/2.0/ja/controllers/request-response.html#check-the-request
		// > is('post') 現在のリクエストが POST かどうかを調べます。
		// > is('put') 現在のリクエストが PUT かどうかを調べます。
		//
		// Cakephp2でも実際は、HTTPメソッドのGET, POSTのみを使っています。
		// Cakephp2のFormHelperを使うと、<input type="hidden" name="_method" value="PUT">等を自動作成して、疑似的にputやdeleteに対応しています。
		// 代表的に使うのは、is('get')=表示か、is('post')=登録か、is('put')=編集か、is('post')=削除か です。
		//
		// --- 備考
		// Cakephpフレームワークのphpdocに記載している@linkは en の部分を ja に置換すると、だいたい日本語ドキュメントが開けます。
		// 例）Controller::$requestのphpdoc
		//  * @link http://book.cakephp.org/2.0/en/controllers/request-response.html#cakerequest
		//  ↓
		//  * @link http://book.cakephp.org/2.0/ja/controllers/request-response.html#cakerequest
		if ($this->request->is('post') || $this->request->is('put')) {
			// [Cakephpの決まり] $this->data - 受け取ったリクエストデータです
			// http://book.cakephp.org/2.0/ja/controllers/request-response.html#put-post
			// > 2.2 において application/x-www-form-urlencoded リクエストボディのデータは PUT と DELETE リクエストでは自動的に構文解析され $this->data に設定されます。
			//
			// vendors\cakephp\cakephp\lib\Cake\Controller\Controller::__get()
			//
			// つまり以下は同じものです。
			// $this->data;
			// $this->request->data;
			$data = $this->data;
			// [NetCommonsの決まり] $this->Workflow->parseStatus() - 承認状態を取得
			// Plugin\Workflow\Controller\Component\WorkflowComponent::parseStatus()
			$data['Simpletext']['status'] = $this->Workflow->parseStatus();
			// [NetCommonsの決まり] 登録、編集でもコンテンツは常に登録
			// 承認フロー搭載のため、コンテンツにバージョンを持っている。（承認前や一時保存でも１つ前のバージョンが表示できる）
			// 編集であっても、常に登録して、前のバージョンを消さないようにしているため、idをunsetしている
			//
			// --- 補足
			// [Cakephpの決まり] Cakephpはidが無ければ、登録。あれば更新するようにmodelが作りこまれている。
			// そのため、CakephpのDBテーブルを設計したら、id カラムを付ける。
			unset($data['Simpletext']['id']);

			if ($this->Simpletext->saveSimpletext($data)) {
				// 正常処理
				//
				// [Cakephpの決まり] 初期画面をリダイレクト表示します
				// http://book.cakephp.org/2.0/ja/controllers.html#Controller::redirect
				// vendors\cakephp\cakephp\lib\Cake\Controller\Controller::redirect()
				//
				// [NetCommonsの決まり] NetCommonsUrl::backToPageUrl() - 初期画面のURLを返します
				// Plugin\NetCommons\Utility\NetCommonsUrl::backToPageUrl()
				return $this->redirect(NetCommonsUrl::backToPageUrl());
			}
			// 入力エラーあり
			//
			// [NetCommonsの決まり] エラーメッセージのフラッシュ表示（画面上部に一時的に表示されるメッセージ）と
			// 入力項目下部にvalidationErrorsのエラーメッセージを表示します。
			// Plugin\NetCommons\Controller\Component\NetCommonsComponent.php
			//
			// [Cakephpの決まり] $this->＜モデル＞->validationErrors
			// 入力エラーだと validationErrors に配列でエラー内容がセットされます。
			// 例）
			//	[Simpletext] => Array
			//	(
			//		[textarea] => Array
			//		(
			//			[0] => 本文を入力してください。
			//		)
			//	)
			$this->NetCommons->handleValidationError($this->Simpletext->validationErrors);

		} else {
			//初期データセット
			//
			// [Cakephpの決まり] 登録(post)や編集(put)する値は $this->request->dataにセット
			// Security コンポーネントを利用するため、$this->request->data と FormHelperを使う必要があります。（使わないと全てSecurityComponent->blackHole()エラー）
			// http://book.cakephp.org/2.0/ja/core-libraries/helpers/form.html#id1
			// > FormHelper はフォームの追加や編集のどちらなのかを自動的に 検出し、 $this->request->data を適切に利用します。
			// > もし $this->request->data にフォームのモデルに関連する名前がついた 配列要素が含まれていて、かつその配列に含まれるモデルのプライマリキー の値が空でなければ、FormHelper はそのレコードの編集用フォームを作成 します。
			//
			// [NetCommonsの決まり] FormHelperの代わりに NetCommonsFormHelper を使っています。
			// NetCommonsFormHelper は  FormHelper の機能を組み込んでます。
			if (! $this->request->data = $this->Simpletext->getSimpletext()) {
				// データなし（＝新規登録）
				// [NetCommons独自] modelからアソシエーション（関連モデル）も含めて新規データを生成します。
				$this->request->data = $this->Simpletext->createAll();
				// [Cakephpの決まり] Hash::merge() - Arrayをマージします
				// http://book.cakephp.org/2.0/ja/core-utility-libraries/hash.html#Hash::merge
				//
				// --- 備考
				// Hash関数はとっても便利でよく使います。
				// Hash::get() - Arrayであれば、キーが無かったとしても、nullを返してくれる（undefind indexエラーを回避できる） http://book.cakephp.org/2.0/ja/core-utility-libraries/hash.html#Hash::get
				// Hash::extract() - 値のみのArrayに組みなおし  http://book.cakephp.org/2.0/ja/core-utility-libraries/hash.html#Hash::extract
				// Hash::combine() - 連想配列のArrayに組みなおし  http://book.cakephp.org/2.0/ja/core-utility-libraries/hash.html#Hash::combine
				$this->request->data = Hash::merge($this->request->data,
					$this->SimpletextFrameSetting->getSimpletextFrameSetting(true));
				$this->request->data = Hash::merge($this->request->data,
					$this->SimpletextSetting->createSimpletextSetting());
			}
			// [NetCommons独自] Frameテーブルの値をまるごと取得
			// Plugin\NetCommons\Utility\Current::read()
			$this->request->data['Frame'] = Current::read('Frame');
		}

		// [NetCommons独自] このコンテンツの承認コメントを取得
		// Simpletextモデルの$actsAsに設定した WorkflowCommentBehavior の getCommentsByContentKey 関数 を呼んでいる。
		// Plugin\Workflow\Model\Behavior\WorkflowCommentBehavior::getCommentsByContentKey()
		/** @see WorkflowCommentBehavior::getCommentsByContentKey() */
		$comments = $this->Simpletext->getCommentsByContentKey(
			$this->request->data['Simpletext']['key']
		);
		// [NetCommons独自] 表示する承認コメントは必ず 'comments' でセットする。（別名つけるとエラーにはならず、表示されなくなる）
		$this->set('comments', $comments);
	}


/**
 * 削除
 *
 * @throws BadRequestException
 * @return void
 */
	public function delete() {
		// [Cakephpの決まり] $this->request->is('delete')
		// $this->edit() の説明と同様
		// delete処理なので、delete以外のリクエストは例外スローしてる
		if (! $this->request->is('delete')) {
			// 異常処理
			//
			// [NetCommons独自] 例外 BadRequestExceptionをスローする。ajax対応済み
			// Plugin\NetCommons\Controller\NetCommonsAppController::throwBadRequest()
			// throwBadRequest()は戻り値はないけど、return $this->throwBadRequest();としているのは、ajax(JSON)対応のため。
			// ajaxで処理がきた場合、throw new BadRequestException();を通ってもthrowされず、後続の処理が実行されるため、returnしている。
			// 蛇足：2016/12/17時点でNetCommons対応のCakephpプラグインの中で、ajax処理は使ってないと思う
			return $this->throwBadRequest();
		}
		// 正常処理
		//
		$this->Simpletext->deleteSimpletext($this->data);
		$this->redirect(NetCommonsUrl::backToPageUrl());
	}
}
