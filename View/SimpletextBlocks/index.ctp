<?php
/**
 * ブロック一覧表示画面
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @license https://www.freebsd.org/copyright/freebsd-license.html FreeBSD License
 * @copyright 2007 OpenSource-WorkShop Co.,Ltd.
 *
 * [NetCommonsプラグイン] 他のプラグインからコピペ
 */
?>

<article class="block-setting-body">
	<?php echo $this->BlockTabs->main(BlockTabsHelper::MAIN_TAB_BLOCK_INDEX); ?>

	<?php echo $this->BlockIndex->description(); ?>

	<div class="tab-content">
		<?php echo $this->BlockIndex->create(); ?>
			<?php echo $this->BlockIndex->addLink(); ?>

			<?php echo $this->BlockIndex->startTable(); ?>
				<thead>
					<tr>
						<?php echo $this->BlockIndex->tableHeader(
							'Frame.block_id'
						); ?>
						<?php echo $this->BlockIndex->tableHeader(
							'Block.name', __d('simpletexts', '本文'),
							array('sort' => true, 'editUrl' => true)
						); ?>
						<?php echo $this->BlockIndex->tableHeader(
							'Block.public_type', __d('blocks', 'Publishing setting'),
							array('sort' => true)
						); ?>
						<?php echo $this->BlockIndex->tableHeader(
							'TrackableUpdater.handlename', __d('net_commons', 'Modified user'),
							array('sort' => true, 'type' => 'handle')
						); ?>
						<?php echo $this->BlockIndex->tableHeader(
							'Block.modified', __d('net_commons', 'Modified datetime'),
							array('sort' => true, 'type' => 'datetime')
						); ?>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($simpletexts as $simpletext) : ?>
					<?php echo $this->BlockIndex->startTableRow($simpletext['Block']['id']); ?>
						<?php echo $this->BlockIndex->tableData(
							'Frame.block_id', $simpletext['Block']['id']
						); ?>
						<?php // [Cakephpの決まり] Simpletext::DISPLAY_SUMMARY_LENGTH (本文の表示最大文字数)の定数が使えるのは、呼び出し元のコントローラで $uses に('Simpletexts.Simpletext'モデルを)設定しているため。
						echo $this->BlockIndex->tableData(
							'Block.name',
							'<small>' . $this->Workflow->label($simpletext['Simpletext']['status']) . '</small>' . ' ' .
							h(mb_strimwidth($simpletext['Block']['name'], 0, Simpletext::DISPLAY_SUMMARY_LENGTH, '...')),
							array('editUrl' => array('block_id' => $simpletext['Block']['id']), 'escape' => false)
						); ?>
						<?php echo $this->BlockIndex->tableData(
							'Block.public_type', $simpletext
						); ?>
						<?php echo $this->BlockIndex->tableData(
							'TrackableUpdater', $simpletext,
							array('type' => 'handle')
						); ?>
						<?php echo $this->BlockIndex->tableData(
							'Block.modified', $simpletext['Block']['modified'],
							array('type' => 'datetime')
						); ?>
					<?php echo $this->BlockIndex->endTableRow(); ?>
				<?php endforeach; ?>
				</tbody>
			<?php echo $this->BlockIndex->endTable(); ?>

		<?php echo $this->BlockIndex->end(); ?>

		<?php echo $this->element('NetCommons.paginator'); ?>
	</div>

</article>
