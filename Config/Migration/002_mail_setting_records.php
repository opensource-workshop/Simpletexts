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
 * @var string
 */
	const PLUGIN_KEY = 'simpletexts';

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'mail_setting_records';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(),
		'down' => array(),
	);

/**
 * plugin data
 *
 * @var array $migration
 */
	public $records = array(
		'MailSetting' => array(
			//コンテンツ通知 - 設定 - 初期値
			array(
				'plugin_key' => self::PLUGIN_KEY,
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
				'plugin_key' => self::PLUGIN_KEY,
				'block_key' => null,				// 初期値のblock_keyは必ず null
				'type_key' => 'contents',			// contents 固定。定型文が複数ある場合は、任意でつける。日英同じ値。
				'mail_fixed_phrase_subject' => '[{X-SITE_NAME}-{X-PLUGIN_NAME}]({X-ROOM})',
				'mail_fixed_phrase_body' => 'You are receiving this email because a message was posted to Announcement.
Room\'s name:{X-ROOM}
user:{X-USER}
date:{X-TO_DATE}

{X-BODY}

Click on the link below to reply to this article.
{X-URL}',
			),
			// * 日本語
			array(
				'language_id' => '2',				// 2=日本語
				'plugin_key' => self::PLUGIN_KEY,
				'block_key' => null,				// 初期値のblock_keyは必ず null
				'type_key' => 'contents',			// contents 固定。定型文が複数ある場合は、任意でつける。日英同じ値。
				'mail_fixed_phrase_subject' => '[{X-SITE_NAME}-{X-PLUGIN_NAME}]({X-ROOM})',
				'mail_fixed_phrase_body' => '{X-PLUGIN_NAME}に投稿されたのでお知らせします。
ルーム名:{X-ROOM}
投稿者:{X-USER}
投稿日時:{X-TO_DATE}

{X-BODY}

この記事に返信するには、下記アドレスへ
{X-URL}',
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
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function after($direction) {
		// [NetCommons独自] 内容書く
		return parent::updateAndDelete($direction, self::PLUGIN_KEY);
	}
}
