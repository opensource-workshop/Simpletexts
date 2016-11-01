<?php
/**
 * BlockRolePermissions Controller
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @license https://www.freebsd.org/copyright/freebsd-license.html FreeBSD License
 * @copyright 2007 OpenSource-WorkShop Co.,Ltd.
 */

App::uses('SimpletextsAppController', 'Simpletexts.Controller');

/**
 * BlockRolePermissions Controller
 *
 * [NetCommonsプラグイン作成]ブロックコントローラーは他プラグインからほぼコピペ
 */
class SimpletextBlockRolePermissionsController extends SimpletextsAppController {

/**
 * layout
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
 * use components
 *
 * @var array
 */
	public $components = array(
		'NetCommons.Permission' => array(
			'allow' => array(
				'edit' => 'block_permission_editable',
			),
		),
	);

/**
 * use helpers
 *
 * @var array
 */
	public $helpers = array(
		'Blocks.BlockRolePermissionForm',
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
