<?php
/**
 * SimpletextBlocks Controller
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @license https://www.freebsd.org/copyright/freebsd-license.html FreeBSD License
 * @copyright 2007 OpenSource-WorkShop Co.,Ltd.
 */

App::uses('SimpletextsAppController', 'Simpletexts.Controller');

/**
 * BlockRolePermissions Controller
 *
 * [NetCommonsプラグイン作成] XxxxxxBlocksControllerは他プラグインからほぼコピー
 */
class SimpletextBlocksController extends SimpletextsAppController {

/**
 * layout
 *
 * [NetCommonsプラグイン作成] 設定画面は下記必須
 *
 * @var array
 */
	public $layout = 'NetCommons.setting';

/**
 * use model
 *
 * @var array
 */
	public $uses = array(
		'Simpletexts.Simpletext',
		'Simpletexts.SimpletextFrameSetting',
		'Simpletexts.SimpletextSetting',
	);

/**
 * use component
 *
 * @var array
 */
	public $components = array(
		'Workflow.Workflow',
		'NetCommons.Permission' => array(
			//アクセスの権限
			'allow' => array(
				'index,add,edit,delete' => 'block_editable',
			),
		),
		'Paginator',	// ページャー
	);

/**
 * use helpers
 *
 * @var array
 */
	public $helpers = array(
		'Blocks.BlockForm',
		'Blocks.BlockIndex',
		'Blocks.BlockTabs' => array(
			'mainTabs' => array('block_index', 'frame_settings'),
			'blockTabs' => array('block_settings', 'mail_settings', 'role_permissions'),
		),
		'Workflow.Workflow',	// [NetCommonsプラグイン] ワークフローコメント入力
	);

/**
 * ブロック一覧表示
 *
 * @return CakeResponse
 * @throws Exception
 */
	public function index() {
		$this->Paginator->settings = array(
			/** @see BlockBehavior::getBlockIndexSettings() */
			'Simpletext' => $this->Simpletext->getBlockIndexSettings([
				'conditions' => array('Simpletext.is_latest' => true)
			])
		);

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
