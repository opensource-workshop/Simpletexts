<?php
/**
 * SimpletextFrameSettings Controller
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @license https://www.freebsd.org/copyright/freebsd-license.html FreeBSD License
 * @copyright 2007 OpenSource-WorkShop Co.,Ltd.
 */

// [Cakephpの決まり] Cakephp用のinclude
// http://book.cakephp.org/2.0/ja/core-utility-libraries/app.html#App::uses
App::uses('SimpletextsAppController', 'Simpletexts.Controller');

/**
 * SimpletextFrameSettings Controller
 *
 * [NetCommonsプラグイン] XxxxFrameSettingsControllerは他プラグインからほぼコピー
 * [Cakephpの決まり] XxxxAppControllerを継承する
 */
class SimpletextFrameSettingsController extends SimpletextsAppController {

/**
 * [Cakephpの決まり] layout
 *
 * [NetCommons独自] 謎。定義しなくても正常動作する。
 * 設定画面は下記 $layout を指定する必要ありと思っていたが、
 * NetCommonsプラグインのLayoutsディレクトリに setting.ctp は無かった。
 * また、$layout を適当な文字列にしても動いており、機能していないと思われる。
 * 継承先の NetCommonsAppController の $layout も同様だった。
 * Plugin\NetCommons\Controller\NetCommonsAppController::$layout
 *
 * @var array
 */
	public $layout = 'NetCommons.setting';

/**
 * [Cakephpの決まり] use components
 * 下記と説明同様
 * Plugin\Simpletexts\Controller\SimpletextsController::$components
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
 * [Cakephpの決まり] use helpers
 * 下記と説明同様
 * Plugin\Simpletexts\Controller\SimpletextsController::$helpers
 *
 * @var array
 */
	public $helpers = array(
		// [NetCommons独自] 設定画面のタブ表示
		// Plugin\Blocks\View\Helper\BlockTabsHelper.php
		// https://netcommons3.github.io/NetCommons3Docs/phpdoc/Blocks/classes/BlockTabsHelper.html#method_beforeRender
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
		// [Cakephpの決まり] 下記と説明同様
		// Plugin\Simpletexts\Controller\SimpletextsController::edit()
		if ($this->request->is('put') || $this->request->is('post')) {
			// 登録、更新
			// [Cakephpの決まり] $this->data - 受け取ったリクエストデータです
			if ($this->SimpletextFrameSetting->saveSimpletextFrameSetting($this->data)) {
				return $this->redirect(NetCommonsUrl::backToPageUrl(true));
			}
			$this->NetCommons->handleValidationError($this->SimpletextFrameSetting->validationErrors);

		} else {
			// 初期データセット
			// $this->request->dataにセットして、FormHelperを使う事で初期表示してくれる
			$this->request->data = $this->SimpletextFrameSetting->getSimpletextFrameSetting(true);
			$this->request->data['Frame'] = Current::read('Frame');
		}
	}

}
