<?php
/**
 * SimpletextBlocks Controller
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @license https://www.freebsd.org/copyright/freebsd-license.html FreeBSD License
 * @copyright 2007 OpenSource-WorkShop Co.,Ltd.
 */

// [Cakephpの決まり] Cakephp用のinclude
// http://book.cakephp.org/2.0/ja/core-utility-libraries/app.html#App::uses
App::uses('SimpletextsAppController', 'Simpletexts.Controller');

/**
 * BlockRolePermissions Controller
 *
 * [NetCommonsプラグイン作成] XxxxxxBlocksControllerは他プラグインからほぼコピー
 * [Cakephpの決まり] XxxxAppControllerを継承する
 */
class SimpletextBlocksController extends SimpletextsAppController {

/**
 * [Cakephpの決まり] layout
 * http://book.cakephp.org/2.0/ja/views.html#view-layouts
 *
 * [NetCommons独自] 下記と説明同様
 * Plugin\Simpletexts\Controller\SimpletextFrameSettingsController::$layout
 *
 * @var array
 */
	public $layout = 'NetCommons.setting';

/**
 * [Cakephpの決まり] use model
 * 下記と説明同様
 * Plugin\Simpletexts\Controller\SimpletextsController::$uses
 *
 * @var array
 */
	public $uses = array(
		'Simpletexts.Simpletext',
		'Simpletexts.SimpletextFrameSetting',
		'Simpletexts.SimpletextSetting',
	);

/**
 * [Cakephpの決まり] use components
 * 下記と説明同様
 * Plugin\Simpletexts\Controller\SimpletextsController::$components
 *
 * @var array
 */
	public $components = array(
		// [NetCommons独自] ワークフローで使うファンクション達
		// Plugin\Workflow\Controller\Component\WorkflowComponent.php
		// https://netcommons3.github.io/NetCommons3Docs/phpdoc/Workflow/classes/WorkflowComponent.html
		'Workflow.Workflow',
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
				'index,add,edit,delete' => 'block_editable',
			),
		),
		// [Cakephpの決まり] Paginator コンポーネント
		// https://book.cakephp.org/2.0/ja/core-libraries/components/pagination.html
		// > ページ制御
		'Paginator',
	);

/**
 * [Cakephpの決まり] use helpers
 * 下記と説明同様
 * Plugin\Simpletexts\Controller\SimpletextsController::$helpers
 *
 * @var array
 */
	public $helpers = array(
		// [NetCommons独自] ブロック編集画面用Helper
		// Plugin\Blocks\View\Helper\BlockFormHelper.php
		'Blocks.BlockForm',
		// [NetCommons独自] ブロック一覧用Helper
		// Plugin\Blocks\View\Helper\BlockIndexHelper.php
		'Blocks.BlockIndex',
		// [NetCommons独自] 設定画面のタブ表示
		// Plugin\Blocks\View\Helper\BlockTabsHelper.php
		// https://netcommons3.github.io/NetCommons3Docs/phpdoc/Blocks/classes/BlockTabsHelper.html#method_beforeRender
		'Blocks.BlockTabs' => array(
			'mainTabs' => array('block_index', 'frame_settings'),
			'blockTabs' => array('block_settings', 'mail_settings', 'role_permissions'),
		),
		// [NetCommons独自] 承認コメント入力に必要
		// Plugin\Workflow\View\Helper\WorkflowHelper.php
		'Workflow.Workflow',
	);

/**
 * ブロック一覧表示
 *
 * @return CakeResponse
 * @throws Exception
 */
	public function index() {
		// [Cakephpの決まり] ページ制御 コンポーネント に 検索条件(conditions) をセット
		// https://book.cakephp.org/2.0/ja/core-libraries/components/pagination.html#id3
		$this->Paginator->settings = array(
			/** @see BlockBehavior::getBlockIndexSettings() */
			// Plugin\Blocks\Model\Behavior\BlockBehavior::getBlockIndexSettings()
			// Simpletextモデルで読み込んでいる BlockBehavior のファンクション getBlockIndexSettings()を使って、ブロック一覧データを取得する検索条件をセット
			'Simpletext' => $this->Simpletext->getBlockIndexSettings([
				'conditions' => array('Simpletext.is_latest' => true)
			])
		);

		// [Cakephpの決まり] ページ制御された結果を返します。
		// https://book.cakephp.org/2.0/ja/core-libraries/components/pagination.html#id2
		$simpletexts = $this->Paginator->paginate('Simpletext');
		if (! $simpletexts) {
			// [Cakephpの決まり] 表示する画面（view）を指定できる
			// [NetCommons独自] 0件なら、ブロック一覧でデータなし画面を表示
			// Plugin\Blocks\View\Blocks\not_found.ctp
			$this->view = 'Blocks.Blocks/not_found';
			return;
		}
		// [Cakephpの決まり] Viewに値を渡します。Viewでは　$simpletexts で取得できます。
		$this->set('simpletexts', $simpletexts);

		// [Cakephpの決まり] $this->request->dataにセットして、FormHelperを使う事で初期表示してくれる
		// [NetCommons独自] Frameテーブルの値をまるごと取得
		// Plugin\NetCommons\Utility\Current::read()
		$this->request->data['Frame'] = Current::read('Frame');
	}

/**
 * ブロック設定 登録
 *
 * @return CakeResponse
 */
	public function add() {
		// [Cakephpの決まり] 表示する画面（view）を指定できる
		$this->view = 'edit';

		// [Cakephpの決まり]
		// http://book.cakephp.org/2.0/ja/controllers/request-response.html#check-the-request
		// > is('post') 現在のリクエストが POST かどうかを調べます。
		if ($this->request->is('post')) {
			// --- 登録
			// [Cakephpの決まり] $this->data - 受け取ったリクエストデータです
			// 以下は同じものです。
			// $this->data;
			// $this->request->data;
			$data = $this->data;
			// [NetCommonsの決まり] $this->Workflow->parseStatus() - 承認状態を取得
			// Plugin\Workflow\Controller\Component\WorkflowComponent::parseStatus()
			$data['Simpletext']['status'] = $this->Workflow->parseStatus();

			if ($this->Simpletext->saveSimpletext($data)) {
				// 正常処理
				//
				// [Cakephpの決まり] 初期画面をリダイレクト表示します
				// http://book.cakephp.org/2.0/ja/controllers.html#Controller::redirect
				// vendors\cakephp\cakephp\lib\Cake\Controller\Controller::redirect()
				//
				// [NetCommonsの決まり] NetCommonsUrl::backToPageUrl() - 初期画面のURLを返します
				// Plugin\NetCommons\Utility\NetCommonsUrl::backToPageUrl()
				return $this->redirect(NetCommonsUrl::backToIndexUrl('default_setting_action'));
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
			// --- 表示
			//初期データセット
			// [Cakephpの決まり] $this->request->dataにセットして、FormHelperを使う事で初期表示してくれる
			// [Cakephpの決まり] $this->＜モデル＞->createAll();
			// 関連するモデルも初期化したデータくれる
			// https://book.cakephp.org/2.0/ja/models/saving-your-data.html#model-create-array-data-array
			// [Cakephpの決まり] Hash::merge() - Arrayをマージします
			// http://book.cakephp.org/2.0/ja/core-utility-libraries/hash.html#Hash::merge
			$this->request->data = $this->Simpletext->createAll();
			$this->request->data = Hash::merge($this->request->data,
				$this->SimpletextSetting->getSimpletextSetting());
			$this->request->data = Hash::merge($this->request->data,
				$this->SimpletextFrameSetting->getSimpletextFrameSetting(true));
			$this->request->data['Frame'] = Current::read('Frame');
		}
	}

/**
 * ブロック設定 編集
 *
 * @return CakeResponse
 */
	public function edit() {
		// [Cakephpの決まり]
		// http://book.cakephp.org/2.0/ja/controllers/request-response.html#check-the-request
		// > is('put') 現在のリクエストが PUT かどうかを調べます。
		if ($this->request->is('put')) {
			// --- 更新
			$data = $this->data;
			$data['Simpletext']['status'] = $this->Workflow->parseStatus();
			// [NetCommonsの決まり] cakephpの決まりで更新にはid必要
			// しかしNetCommonsの決まりで、コンテンツは一時保存や承認前に表示できるように履歴を持っている。
			// そのため、更新でも登録にするのため、idをunsetで消している
			unset($data['Simpletext']['id']);

			if ($this->Simpletext->saveSimpletext($data)) {
				return $this->redirect(NetCommonsUrl::backToIndexUrl('default_setting_action'));
			}
			$this->NetCommons->handleValidationError($this->Simpletext->validationErrors);

		} else {
			// --- 表示
			//初期データセット
			$this->request->data = $this->Simpletext->getSimpletext();
			$this->request->data = Hash::merge($this->request->data,
				$this->SimpletextFrameSetting->getSimpletextFrameSetting(false));
			$this->request->data['Frame'] = Current::read('Frame');
		}

		// [NetCommonsの決まり] 承認コメント取得
		/** @see WorkflowCommentBehavior::getCommentsByContentKey() */
		// Plugin\Workflow\Model\Behavior\WorkflowCommentBehavior::getCommentsByContentKey()
		$comments = $this->Simpletext->getCommentsByContentKey(
			$this->request->data['Simpletext']['key']
		);
		$this->set('comments', $comments);
	}

/**
 * ブロック設定 削除
 *
 * @throws BadRequestException
 * @return void
 */
	public function delete() {
		// [Cakephpの決まり]
		// http://book.cakephp.org/2.0/ja/controllers/request-response.html#check-the-request
		// > is('delete') 現在のリクエストが DELETE かどうかを調べます。
		if ($this->request->is('delete')) {
			if ($this->Simpletext->deleteSimpletext($this->data)) {
				// 正常
				return $this->redirect(NetCommonsUrl::backToIndexUrl('default_setting_action'));
			}
		}

		// DELETE以外のリクエスト来たら異常
		$this->throwBadRequest();
	}
}
