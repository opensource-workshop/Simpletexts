<?php
/**
 * シンプルテキスト登録・編集エレメント
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @license https://www.freebsd.org/copyright/freebsd-license.html FreeBSD License
 * @copyright 2007 OpenSource-WorkShop Co.,Ltd.
 */
?>

<?php echo $this->element('Blocks.form_hidden'); ?>

<?php echo $this->NetCommonsForm->hidden('Simpletext.id'); ?>
<?php echo $this->NetCommonsForm->hidden('Simpletext.key'); ?>
<?php echo $this->NetCommonsForm->hidden('Simpletext.block_id'); ?>
<?php echo $this->NetCommonsForm->hidden('Simpletext.language_id'); ?>
<?php echo $this->NetCommonsForm->hidden('Simpletext.status'); ?>

<?php echo $this->NetCommonsForm->hidden('Simpletext.use_workflow'); ?>

<?php echo $this->NetCommonsForm->input('Simpletext.textarea', array(
	'type' => 'textarea',
	'class' => 'form-control nc-noresize',
	'label' => __d('simpletexts', '本文'),
	'required' => true,
	'rows' => (int)Hash::get($this->request->data, 'SimpletextFrameSetting.textarea_edit_row'),
)); ?>

<?php if (Current::permission('block_editable')) : ?>
	<?php echo $this->element('Blocks.public_type'); ?>
	<?php echo $this->element('Blocks.modifed_info', array(
		'displayModified' => (bool)Hash::get($this->request->data, 'Simpletext.id')
	)); ?>
<?php endif;