<?php
/**
 * BlockRolePermissions Controller
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
 * [NetCommonsプラグイン作成]ブロックコントローラーは他プラグインからほぼコピペ
 * [Cakephpの決まり] XxxxAppControllerを継承する
 */
class SimpletextBlockRolePermissionsController extends SimpletextsAppController {

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
		// [NetCommons独自] 各アクションのパーミッション(許可)制限
		// Plugin\NetCommons\Controller\Component\PermissionComponent.php
		// 下記の設定は、content_creatable（コンテンツ作成許可）ありのユーザのみ
		// add,edit,deleteアクションを実行できる
		//
		// --- 備考
		// NetCommonsでは登録するメインの内容（動画とか、お知らせなら本文とか）をコンテンツと呼んでいる
		'NetCommons.Permission' => array(
			'allow' => array(
				'edit' => 'block_permission_editable',
			),
		),
	);

/**
 * [Cakephpの決まり] use helpers
 * 下記と説明同様
 * Plugin\Simpletexts\Controller\SimpletextsController::$helpers
 *
 * @var array
 */
	public $helpers = array(
		// [NetCommons独自]
		'Blocks.BlockRolePermissionForm',
		// [NetCommons独自] 設定画面のタブ表示
		// Plugin\Blocks\View\Helper\BlockTabsHelper.php
		// https://netcommons3.github.io/NetCommons3Docs/phpdoc/Blocks/classes/BlockTabsHelper.html#method_beforeRender
		'Blocks.BlockTabs' => array(
			'mainTabs' => array('block_index', 'frame_settings'),
			'blockTabs' => array('block_settings', 'mail_settings', 'role_permissions'),
		),
	);

/**
 * edit
 *
 * @return void
 */
	public function edit() {
		$simpletext = $this->Simpletext->getSimpletext();
		if (! $simpletext) {
			return $this->setAction('throwBadRequest');
		}

		$permissions = $this->Workflow->getBlockRolePermissions(
			array(
				'content_publishable',
			)
		);
		$this->set('roles', $permissions['Roles']);

		if ($this->request->is('post')) {
			if ($this->SimpletextSetting->saveSimpletextSetting($this->request->data)) {
				return $this->redirect(NetCommonsUrl::backToIndexUrl('default_setting_action'));
			}
			$this->NetCommons->handleValidationError($this->SimpletextSetting->validationErrors);
			$this->request->data['BlockRolePermission'] = Hash::merge(
				$permissions['BlockRolePermissions'],
				$this->request->data['BlockRolePermission']
			);

		} else {
			$this->request->data['SimpletextSetting'] = $simpletext['SimpletextSetting'];
			$this->request->data['Block'] = $simpletext['Block'];
			$this->request->data['BlockRolePermission'] = $permissions['BlockRolePermissions'];
			$this->request->data['Frame'] = Current::read('Frame');
		}
	}
}
