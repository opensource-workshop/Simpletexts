<?php
/**
 * ブロック設定登録・編集画面
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @license https://www.freebsd.org/copyright/freebsd-license.html FreeBSD License
 * @copyright 2007 OpenSource-WorkShop Co.,Ltd.
 *
 * [NetCommonsプラグイン] 他のプラグインからコピペ
 */
?>

<div class="block-setting-body">

	<?php echo $this->BlockTabs->main(BlockTabsHelper::MAIN_TAB_BLOCK_INDEX); ?>

	<div class="tab-content">
		<?php echo $this->BlockTabs->block(BlockTabsHelper::BLOCK_TAB_SETTING); ?>

		<?php /* [Cakephpの決まり]Vidwの共通処理を呼び出す。Simpletexts/View/Elements/SimpletextBlocks/edit_form.ctp  */ ?>
		<?php /* [NetCommonsプラグイン]シンプルテキストはお知らせとほぼ同じ動きのため、お知らせをまねる */ ?>
		<?php echo $this->element('Simpletexts.SimpletextBlocks/edit_form'); ?>

		<?php echo $this->Workflow->comments(); ?>

	</div>

</div>
