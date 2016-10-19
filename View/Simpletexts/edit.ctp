<?php
/**
 * シンプルテキスト登録・編集画面
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @license https://www.freebsd.org/copyright/freebsd-license.html FreeBSD License
 * @copyright 2007 OpenSource-WorkShop Co.,Ltd.
 *
 * [NetCommonsプラグイン] 他のプラグインからコピペ
 */
?>

<article class="block-setting-body">

	<div class="panel panel-default" >
		<div class="panel-body">
			<?php echo $this->NetCommonsForm->create('Simpletext'); ?>
			<?php echo $this->element('Simpletexts/edit_form'); ?>

			<hr />

			<?php echo $this->Workflow->inputComment('Simpletext.status'); ?>
		</div>

		<?php echo $this->Workflow->buttons('Simpletext.status', NetCommonsUrl::backToPageUrl()); ?>
		<?php echo $this->NetCommonsForm->end(); ?>

		<?php /* 削除 */ ?>
		<?php if ($this->Workflow->canDelete('Simpletexts.Simpletext', $this->request->data) &&
			Hash::get($this->request->data, 'Simpletext.id')) : ?>
			<div class="panel-footer text-right">
				<?php echo $this->element('Simpletexts.Simpletexts/delete_form', array(
					'url' => NetCommonsUrl::blockUrl(array('action' => 'delete', 'key' => $this->data['Simpletext']['key']))
				)); ?>
			</div>
		<?php endif; ?>
	</div>

	<?php echo $this->Workflow->comments(); ?>

</article>