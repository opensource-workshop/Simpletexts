<?php
/**
 * ブロック設定登録・編集エレメント
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @license https://www.freebsd.org/copyright/freebsd-license.html FreeBSD License
 * @copyright 2007 OpenSource-WorkShop Co.,Ltd.
 *
 * [NetCommonsプラグイン] 他のプラグインからコピペ
 */
?>

<div class="panel panel-default" >
	<?php echo $this->NetCommonsForm->create('Simpletext'); ?>
		<div class="panel-body">
			<?php
			/* [Cakephpの決まり] テーブルのidがあれば更新、なければ登録。そのため'SimpletextFrameSetting.id'をhiddenで保持する。
			   [NetCommonsプラグイン] ブロック設定登録時に、表示設定の初期値も登録するため、SimpletextFrameSetting系のhiddenで定義している。*/ ?>
			<?php echo $this->NetCommonsForm->hidden('SimpletextFrameSetting.id'); ?>
			<?php echo $this->NetCommonsForm->hidden('SimpletextFrameSetting.frame_key'); ?>
			<?php echo $this->NetCommonsForm->hidden('SimpletextFrameSetting.textarea_edit_row'); ?>
			<?php echo $this->NetCommonsForm->hidden('SimpletextFrameSetting.textarea_edit_col'); ?>

			<?php echo $this->element('Simpletexts/edit_form'); ?>

			<hr />

			<?php echo $this->Workflow->inputComment('Simpletext.status', false); ?>
		</div>

		<?php echo $this->Workflow->buttons('Simpletext.status', NetCommonsUrl::backToIndexUrl('default_setting_action')); ?>
	<?php echo $this->NetCommonsForm->end(); ?>

	<?php if (Hash::get($this->request->data, 'Simpletext.id')) : ?>
		<div class="panel-footer text-right">
			<?php echo $this->element('Simpletexts.Simpletexts/delete_form', array(
				'url' => NetCommonsUrl::actionUrl(array(
					'controller' => $this->params['controller'],
					'action' => 'delete',
					'block_id' => Current::read('Block.id'),
					'frame_id' => Current::read('Frame.id')
				))
			)); ?>
		</div>
	<?php endif; ?>
</div>
