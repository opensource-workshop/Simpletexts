<?php
/**
 * メール設定 template
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @license https://opensource.org/licenses/BSD-2-Clause FreeBSD License
 * @copyright 2007 OpenSource-WorkShop Co.,Ltd.
 */
?>

<div class="block-setting-body">
	<?php echo $this->BlockTabs->main(BlockTabsHelper::MAIN_TAB_BLOCK_INDEX); ?>

	<div class="tab-content">
		<?php echo $this->BlockTabs->block(BlockTabsHelper::MAIN_TAB_MAIL_SETTING); ?>

		<?php /** @see MailFormHelper::editFrom() 承認メール通知機能を使う のみ表示 */ ?>
		<?php echo $this->MailForm->editFrom(array(), NetCommonsUrl::backToIndexUrl('default_setting_action'), 0, 0, 1, 0); ?>
	</div>
</div>
