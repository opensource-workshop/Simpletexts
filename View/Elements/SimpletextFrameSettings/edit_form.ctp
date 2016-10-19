<?php
/**
 * 表示方法変更 エレメント
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Ryo Ozawa <ozawa.ryo@withone.co.jp>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php echo $this->NetCommonsForm->hidden('Frame.id'); ?>
<?php echo $this->NetCommonsForm->hidden('SimpletextFrameSetting.id'); ?>
<?php echo $this->NetCommonsForm->hidden('SimpletextFrameSetting.frame_key'); ?>

<div class="row form-group">
	<div class="col-xs-12">
		<?php echo $this->NetCommonsForm->input('SimpletextFrameSetting.textarea_edit_row', array(
			'type' => 'text',
			'label' => __d('simpletexts', '高さ'),
			'required' => true,
		)); ?>
	</div>
</div>
