<?php
/**
 * シンプルテキスト削除エレメント
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @license https://www.freebsd.org/copyright/freebsd-license.html FreeBSD License
 * @copyright 2007 OpenSource-WorkShop Co.,Ltd.
 */
?>

<?php echo $this->NetCommonsForm->create('SimpletextBlocks', array('type' => 'delete', 'url' => $url)); ?>

	<?php echo $this->NetCommonsForm->hidden('Frame.id'); ?>
	<?php echo $this->NetCommonsForm->hidden('Block.id'); ?>
	<?php echo $this->NetCommonsForm->hidden('Block.key'); ?>
	<?php echo $this->NetCommonsForm->hidden('Simpletext.key'); ?>

	<?php echo $this->Button->delete('',
			sprintf(__d('net_commons', 'Deleting the %s. Are you sure to proceed?'), __d('simpletexts', 'シンプルテキスト'))
		); ?>
<?php echo $this->NetCommonsForm->end();
