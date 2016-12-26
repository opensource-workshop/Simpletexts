<?php
/**
 * SimpletextFrameSettings Controller
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @license https://www.freebsd.org/copyright/freebsd-license.html FreeBSD License
 * @copyright 2007 OpenSource-WorkShop Co.,Ltd.
 */

App::uses('SimpletextsAppController', 'Simpletexts.Controller');

/**
 * SimpletextFrameSettings Controller
 *
 * [NetCommonsプラグイン] XxxxFrameSettingsControllerは他プラグインからほぼコピー
 */
class SimpletextFrameSettingsController extends SimpletextsAppController {

/**
 * [Cakephpの決まり] layout
 * http://book.cakephp.org/2.0/ja/views.html#view-layouts
 *
 * @var array
 */
	public $layout = 'NetCommons.setting';

/**
 * use component
 *
 * @var array
 */
	public $components = array(
		'NetCommons.Permission' => array(
			//アクセスの権限
			'allow' => array(
				'edit' => 'page_editable',
			),
		),
	);

/**
 * use helpers
 *
 * @var array
 */
	public $helpers = array(
		// [NetCommons独自] 設定画面のタブ表示
		// Plugin\Blocks\View\Helper\BlockTabsHelper.php
		'Blocks.BlockTabs' => array(
			'mainTabs' => array('block_index', 'frame_settings'),
			'blockTabs' => array('block_settings', 'mail_settings', 'role_permissions'),
		),
		// [NetCommons独自] １ページ毎の表示件数プルダウン部品
		// Plugin\NetCommons\View\Helper\DisplayNumberHelper.php
		'NetCommons.DisplayNumber',
	);

/**
 * 表示方法変更
 *
 * @return CakeResponse
 */
	public function edit() {
		if ($this->request->is('put') || $this->request->is('post')) {
			if ($this->SimpletextFrameSetting->saveSimpletextFrameSetting($this->data)) {
				$this->redirect(NetCommonsUrl::backToPageUrl(true));
				return;
			}
			$this->NetCommons->handleValidationError($this->SimpletextFrameSetting->validationErrors);

		} else {
			$this->request->data = $this->SimpletextFrameSetting->getSimpletextFrameSetting(true);
			$this->request->data['Frame'] = Current::read('Frame');
		}
	}

}
