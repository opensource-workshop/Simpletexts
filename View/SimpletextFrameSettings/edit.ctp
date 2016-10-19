<?php
/**
 * 表示方法変更 登録・編集画面
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @license https://www.freebsd.org/copyright/freebsd-license.html FreeBSD License
 * @copyright 2007 OpenSource-WorkShop Co.,Ltd.
 *
 * [NetCommonsプラグイン] XxxxFrameSettings/edit.ctpは　他のプラグインからコピペ
 */
?>

<article class="block-setting-body">
	<?php echo $this->BlockTabs->main(BlockTabsHelper::MAIN_TAB_FRAME_SETTING); ?>

	<div class="tab-content">
		<?php
		// [NetCommonsプラグイン] 設定画面は Plugin/Blocks/View/Elements/edit_form.ctp エレメント を呼ぶ
		// [NetCommonsプラグイン - Blocks.edit_formエレメント独自] callbackオプションで、自プラグインの Plugin/Simpletexts/View/Elements//SimpletextFrameSettings/edit_form.ctp エレメント を呼ぶ
		echo $this->element('Blocks.edit_form', array(
			'model' => 'SimpletextFrameSetting',
			'callback' => 'Simpletexts.SimpletextFrameSettings/edit_form',
			'cancelUrl' => NetCommonsUrl::backToPageUrl(true),
		)); ?>
	</div>
</article>