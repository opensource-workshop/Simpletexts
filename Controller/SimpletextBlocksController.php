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
		$this->Paginator->settings = array(
			/** @see BlockBehavior::getBlockIndexSettings() */
			// Plugin\Blocks\Model\Behavior\BlockBehavior::getBlockIndexSettings()
			// Simpletextモデルで読み込んでいる BlockBehavior のファンクション getBlockIndexSettings()を使って、ブロック一覧データを取得する検索条件をセット
			'Simpletext' => $this->Simpletext->getBlockIndexSettings([
				'conditions' => array('Simpletext.is_latest' => true)
			])
		);

		// [Cakephpの決まり]
		$simpletexts = $this->Paginator->paginate('Simpletext');
		if (! $simpletexts) {
			$this->view = 'Blocks.Blocks/not_found';
			return;
		}
		$this->set('simpletexts', $simpletexts);

		$this->request->data['Frame'] = Current::read('Frame');
	}

/**
 * ブロック設定 登録
 *
 * @return CakeResponse
 */
	public function add() {
		$this->view = 'edit';

		if ($this->request->is('post')) {
			$data = $this->data;
			$data['Simpletext']['status'] = $this->Workflow->parseStatus();

			if ($this->Simpletext->saveSimpletext($data)) {
				return $this->redirect(NetCommonsUrl::backToIndexUrl('default_setting_action'));
			}
			$this->NetCommons->handleValidationError($this->Simpletext->validationErrors);

		} else {
			//表示処理(初期データセット)
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
		if ($this->request->is('put')) {
			$data = $this->data;
			$data['Simpletext']['status'] = $this->Workflow->parseStatus();
			unset($data['Simpletext']['id']);

			if ($this->Simpletext->saveSimpletext($data)) {
				return $this->redirect(NetCommonsUrl::backToIndexUrl('default_setting_action'));
			}
			$this->NetCommons->handleValidationError($this->Simpletext->validationErrors);

		} else {
			//初期データセット
			$this->request->data = $this->Simpletext->getSimpletext();
			$this->request->data = Hash::merge($this->request->data,
				$this->SimpletextFrameSetting->getSimpletextFrameSetting(false));
			$this->request->data['Frame'] = Current::read('Frame');
		}

		/** @see WorkflowCommentBehavior::getCommentsByContentKey() */
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
		if ($this->request->is('delete')) {
			if ($this->Simpletext->deleteSimpletext($this->data)) {
				return $this->redirect(NetCommonsUrl::backToIndexUrl('default_setting_action'));
			}
		}

		$this->throwBadRequest();
	}
}
