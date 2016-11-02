<?php
/**
 * メール設定データのMigration
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @license https://www.freebsd.org/copyright/freebsd-license.html FreeBSD License
 * @copyright 2007 OpenSource-WorkShop Co.,Ltd.
 */

App::uses('MailsMigration', 'Mails.Config/Migration');

/**
 * メール設定データのMigration
 *
 * [NetCommonsプラグイン作成] 他のプラグインからコピーしてくる
 * Plugin\Mails\Config\Migration\MailsMigration.php を継承
 */
class MailSettingRecords extends MailsMigration {

/**
 * プラグインキー
 *
 * [NetCommons独自] 各プラグインで値を変える
 *
 * @var string
 */
	const PLUGIN_KEY = 'simpletexts';

/**
 * Migration description
 *
 * [Cakephpの決まり] 説明
 * `cake Migrations.migration generate`で自動生成される。
 * [NetCommons独自] 他のプラグインからコピーしてきたなら、このままで大丈夫。
 *
 * @var string
 */
	public $description = 'mail_setting_records';

/**
 * Actions to be performed
 * [Cakephpの決まり] データパッチとかテーブルクリエイトとかする
 * `cake Migrations.migration generate`で自動生成される。
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(),
		'down' => array(),
	);

/**
 * plugin data
 * [NetCommons独自] データベースに登録するデータを設定する
 *
 * @var array $migration
 */
	public $records = array(
		'MailSetting' => array(
			//コンテンツ通知 - 設定 - 初期値
			array(
				'plugin_key' => self::PLUGIN_KEY,	// プラグインキー
				'block_key' => null,				// 初期値のblock_keyは必ず null
				'is_mail_send' => false,			// メール通知を使わない（シンプルテキストは、メール通知を使わない。画面からも設定表示せず、変更させない。（お知らせ同様）
				'is_mail_send_approval' => true,	// 承認メール通知機能を使う
			),
		),
		'MailSettingFixedPhrase' => array(
			//コンテンツ通知 - 定型文 - 初期値
			// * 英語
			array(
				'language_id' => '1',				// 1=英語
				'plugin_key' => self::PLUGIN_KEY,	// プラグインキー
				'block_key' => null,				// 初期値のblock_keyは必ず null
				'type_key' => 'contents',			// contents 固定。定型文が複数ある場合は、任意でつける。日英同じ値。
				'mail_fixed_phrase_subject' => '[{X-SITE_NAME}-{X-PLUGIN_NAME}]({X-ROOM})',	// 件名
				'mail_fixed_phrase_body' => 'You are receiving this email because a message was posted to Announcement.
Room\'s name:{X-ROOM}
user:{X-USER}
date:{X-TO_DATE}

{X-BODY}

Click on the link below to reply to this article.
{X-URL}',											// 本文
			),
			// * 日本語
			array(
				'language_id' => '2',				// 2=日本語
				'plugin_key' => self::PLUGIN_KEY,	// プラグインキー
				'block_key' => null,				// 初期値のblock_keyは必ず null
				'type_key' => 'contents',			// contents 固定。定型文が複数ある場合は、任意でつける。日英同じ値。
				'mail_fixed_phrase_subject' => '[{X-SITE_NAME}-{X-PLUGIN_NAME}]({X-ROOM})',	// 件名
				'mail_fixed_phrase_body' => '{X-PLUGIN_NAME}に投稿されたのでお知らせします。
ルーム名:{X-ROOM}
投稿者:{X-USER}
投稿日時:{X-TO_DATE}

{X-BODY}

この記事に返信するには、下記アドレスへ
{X-URL}',											// 本文
			),
		),
	);

/**
 * Before migration callback
 * [Cakephpの決まり] マイグレーション実行前
 * `cake Migrations.migration generate`で自動生成される。
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 * [Cakephpの決まり] マイグレーション実行後
 * `cake Migrations.migration generate`で自動生成される。
 * function内に処理があったら、それは独自で何か実装している。
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function after($direction) {
		// [NetCommons独自] マイグレーションで（MailSetting, MailSettingFixedPhrase）の 登録・更新・削除する。親クラスのfunction。
		// \Plugin\Mails\Config\Migration\MailsMigration::updateAndDelete()
		return parent::updateAndDelete($direction, self::PLUGIN_KEY);
	}
}
