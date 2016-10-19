<?php
/**
 * 権限設定編集エレメント
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @license https://www.freebsd.org/copyright/freebsd-license.html FreeBSD License
 * @copyright 2007 OpenSource-WorkShop Co.,Ltd.
 *
 * [NetCommonsプラグイン] 他のプラグインからコピペ
 */
?>

<?php echo $this->BlockRolePermissionForm->contentPublishablePermission(); ?>

<?php echo $this->element('Blocks.block_approval_setting', array(
	'model' => 'SimpletextSetting',
	'useWorkflow' => 'use_workflow',
	'options' => array(
		Block::NEED_APPROVAL => __d('blocks', 'Need approval'),
		Block::NOT_NEED_APPROVAL => __d('blocks', 'Not need approval'),
	),
));
