<?php
/**
 * Simpletexts AppController
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @license https://www.freebsd.org/copyright/freebsd-license.html FreeBSD License
 * @copyright 2007 OpenSource-WorkShop Co.,Ltd.
 */

App::uses('AppController', 'Controller');

/**
 * Simpletexts AppController
 *
 * [Cakephpの決まり] プラグインのControllerの親クラス
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
		'Pages.PageLayout',	// [NetCommonsプラグイン] 画面遷移後などに、左右カラムの表示
		'Security',			// [NetCommonsプラグイン] 設定しないとブロック設定の一覧表示で、別のシンプルテキスト選択時にSecurityComponent->blackHole()エラー
	);
}
