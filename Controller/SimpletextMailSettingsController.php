<?php
/**
 * メール設定Controller
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @license https://opensource.org/licenses/BSD-2-Clause FreeBSD License
 * @copyright 2007 OpenSource-WorkShop Co.,Ltd.
 */

App::uses('MailSettingsController', 'Mails.Controller');

/**
 * メール設定Controller
 *
 * [NetCommons独自] メール設定の親クラス（MailSettingsController）を継承する
 * \Plugin\Mails\Controller\MailSettingsController.php
 *
 * [NetCommons独自] メール設定は$helpersのみ、各プラグインで設定する
 * 基本はこの設定のみで大丈夫。
 * もし親クラスの他の処理を変えたい場合は、オーバーライトして処理を変更できる
 *
 * [NetCommons独自] メール設定のactionはedit()１つのみ。登録も編集もedit()で処理している
 * \Plugin\Mails\Controller\MailSettingsController::edit()
 *
 * [NetCommons独自] メール設定のmodelはMailsプラグインの共通モデル MailSetting を使うため、各プラグインで実装不要。
 * \Plugin\Mails\Model\MailSetting.php
 *
 * ## [NetCommons独自] メール設定を実装するには
 * ### 概要
 *
 * * 他のプラグインからコピペで３ファイル新規作成
 * * コントローラ、モデルを４ファイルをちょびっと編集
 * * 初期データの登録するため、マイグレーションを実行
 *
 * で簡単に実装できる。
 *
 * ### 詳細
 * #### 他のプラグインからコピペで３ファイル新規作成
 *
 * 以下のファイルを新規作成する。他プラグインからのコピペして作成。
 * \Plugin\Simpletexts\Config\Migration\002_mail_setting_records.php	// メール定型文の初期データ、マイグレーション
 * \Plugin\Simpletexts\Controller\SimpletextMailSettingsController.php	// コントローラ、当ファイル
 * \Plugin\Simpletexts\View\SimpletextMailSettings\edit.ctp				// 画面
 *
 * #### コントローラ、モデルを４ファイルをちょびっと編集
 *
 * 以下のコントローラの$helpersを編集して、Blocks.BlockTabsの設定値に mail_settings を追加する。各コントローラとも設定値は同じにする
 * \Plugin\Simpletexts\Controller\SimpletextBlockRolePermissionsController.php	// 設定画面 - 権限設定コントローラ
 * \Plugin\Simpletexts\Controller\SimpletextBlocksController.php				// 設定画面 - ブロック一覧コントローラ
 * \Plugin\Simpletexts\Controller\SimpletextFrameSettingsController.php			// 設定画面 - 表示方法変更コントローラ
 * ```php
 * 	public $helpers = array(
 *		'Blocks.BlockTabs' => array(
 *			'mainTabs' => array('block_index', 'frame_settings'),
 *			'blockTabs' => array('block_settings', 'mail_settings', 'role_permissions'),
 *		),
 * 	);
 * ```
 *
 * 以下のモデルの$actsAsを編集して、Mails.MailQueueビヘイビアを追加する
 * \Plugin\Simpletexts\Model\Simpletext.php
 * ```php
 *	public $actsAs = array(
 *		'Mails.MailQueue' => array(
 *			'embedTags' => array(
 *				'X-BODY' => 'Simpletext.textarea',
 *			),
 *		),
 *	);
 * ```
 *
 * （以下は各プラグインの実装不要。共通がある）
 * \Plugin\Mails\Model\MailSetting.php									// モデル
 * \Plugin\Mails\View\Helper\MailFormHelper.php							// 画面ヘルパー
 *
 * #### 初期データの登録するため、マイグレーションを実行
 *
 * ```
 * # cd /var/www/app/app
 * # Console/cake Migrations.migration run all -c master -p Simpletexts
 *
 * Cake Migration Shell
 * ---------------------------------------------------------------
 * You did not set a migration connection (-i), which connection do you want to use? (master/slave1/test)
 * [master] >    (←空エンター)
 * Running migrations:
 * [002] 002_mail_setting_records
 *
 * ---------------------------------------------------------------
 * All migrations have completed.
 * ```
 */
class SimpletextMailSettingsController extends MailSettingsController {

/**
 * use helpers
 *
 * @var array
 */
	public $helpers = array(
		// [NetCommons独自] 権限設定の入力項目部品
		// Plugin\Blocks\View\Helper\BlockRolePermissionFormHelper.php
		'Blocks.BlockRolePermissionForm',
		// [NetCommons独自] 設定画面のタブ表示
		// Plugin\Blocks\View\Helper\BlockTabsHelper.php
		'Blocks.BlockTabs' => array(
			'mainTabs' => array('block_index', 'frame_settings'),
			'blockTabs' => array('block_settings', 'mail_settings', 'role_permissions'),
		),
		// [NetCommons独自] メール設定の入力項目部品
		// Plugin\Mails\View\Helper\MailFormHelper.php
		'Mails.MailForm',
	);

}
