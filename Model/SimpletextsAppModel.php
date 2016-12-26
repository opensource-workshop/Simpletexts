<?php
/**
 * Simpletexts AppModel
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @license https://www.freebsd.org/copyright/freebsd-license.html FreeBSD License
 * @copyright 2007 OpenSource-WorkShop Co.,Ltd.
 */

App::uses('AppModel', 'Model');

/**
 * Summary for Simpletexts AppModel
 *
 * [Cakephpの決まり] XxxxAppModelは、プラグインのModelの親クラス
 *
 * [phpstrom用] ＠property にmodel, Component等を記載すると、子クラスで記載したclassをソースコード上にリンクして
 * 開く事ができる
 *
 * @property SimpletextSetting $SimpletextSetting
 * @property SimpletextFrameSetting $SimpletextFrameSetting
 */
class SimpletextsAppModel extends AppModel {

}
