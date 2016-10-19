<?php
/**
 * シンプルテキスト表示画面
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @license https://www.freebsd.org/copyright/freebsd-license.html FreeBSD License
 * @copyright 2007 OpenSource-WorkShop Co.,Ltd.
 *
 * [NetCommonsプラグイン] 他のプラグインからコピペ
 */
?>

<?php
/* [NetCommons独自] 会員のパーミッションチェック。コンテンツ編集許可があるか。
	--------
	NC3ではロール（役割）=編集長、編集者、システム管理者等
	パーミッション（許可）= content_editable(コンテンツを編集できる)　となった。
	ロールは複数のパーミッションを持てます。
	しかしパーミッション（許可）だけど、画面では承認権限とかで表示しているので、混乱しないよう注意が必要です。 */ ?>
<?php if (Current::permission('content_editable')) : ?>
	<?php /* 上部エリア */ ?>
	<header class="clearfix">
		<div class="pull-left">
			<?php
			/* [NetCommons独自] 一時保存や、未承認等のラベル表示
			   Plugin\Workflow\View\Helper\WorkflowHelper.php を呼び出し */
			echo $this->Workflow->label($simpletext['status']); ?>
		</div>

		<div class="pull-right">
			<?php
			/* [NetCommons独自] 編集ボタン表示
				ButtonHelper.php には editLink()ない。
				Plugin\NetCommons\View\Helper\ButtonHelper.php　の __call() で LinkButtonHelper::edit() 呼び出し
				↓
				Plugin\NetCommons\View\Helper\LinkButtonHelper.php の edit()
			*/
			echo $this->Button->editLink(); ?>
		</div>
	</header>
<?php endif; ?>

<?php if ($simpletext['textarea']) : ?>
	<?php /* 本文 */ ?>
	<?php echo $simpletext['textarea']; ?>
<?php else : ?>
	<article>
		<?php
		/* [Cakephpの決まり] __d()は国際化対応の function
		   __d(＜プラグイン名（スネーク表記）＞, ＜メッセージ＞, ＜オプション＞)
			＜オプション＞に値を指定すると、メッセージの %s を置き換えてくれる。内部で vsprintf使ってます。
			__d()を使えば、後からコマンド一発でプラグインの言語ファイルを自動生成できます */
		echo __d('net_commons', 'Not found %s.', __d('simpletexts', '本文')); ?>
	</article>
<?php endif;