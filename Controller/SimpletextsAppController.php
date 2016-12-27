<?php
/**
 * Simpletexts AppController
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @license https://www.freebsd.org/copyright/freebsd-license.html FreeBSD License
 * @copyright 2007 OpenSource-WorkShop Co.,Ltd.
 */

// [Cakephpの決まり] Cakephp用のinclude
// http://book.cakephp.org/2.0/ja/core-utility-libraries/app.html#App::uses
App::uses('AppController', 'Controller');

/**
 * Simpletexts AppController
 *
 * [Cakephpの決まり] XxxxAppControllerは、プラグインのControllerの親クラス
 * [Cakephpの決まり] XxxxAppControllerはは、AppControllerを継承する
 *
 * [phpstrom用] ＠property にmodel, Component等を記載すると、子クラスで記載したclassをソースコード上にリンクして
 * 開く事ができる
 *
 * @property Simpletext $Simpletext
 * @property SimpletextSetting $SimpletextSetting
 * @property SimpletextFrameSetting $SimpletextFrameSetting
 * @property WorkflowComponent $Workflow
 * @property NetCommonsComponent $NetCommons
 */
class SimpletextsAppController extends AppController {

/**
 * use component
 *
 * @var array
 */
	public $components = array(
		// [NetCommonsプラグイン] 画面遷移後などに、左右カラムの表示
		// Plugin\Pages\Controller\Component\PageLayoutComponent.php
		'Pages.PageLayout',
		// [Cakephpの決まり] Security コンポーネント
		// http://book.cakephp.org/2.0/ja/core-libraries/components/security-component.html
		// > 自動的に CSRF とフォーム改ざんを 防止します。
		// [NetCommonsプラグイン] 設定しないとブロック設定の一覧表示で、選択時にSecurityComponent->blackHole()エラーになった。
		'Security',
	);
}
