<?php
/**
 * Simpletexts AppModel
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @license https://www.freebsd.org/copyright/freebsd-license.html FreeBSD License
 * @copyright 2007 OpenSource-WorkShop Co.,Ltd.
 */

// [Cakephpの決まり] Cakephp用のinclude
// http://book.cakephp.org/2.0/ja/core-utility-libraries/app.html#App::uses
App::uses('AppModel', 'Model');

/**
 * Summary for Simpletexts AppModel
 *
 * [Cakephpの決まり] XxxxAppModelは、プラグインのModelの親クラス
 * [Cakephpの決まり] XxxxAppModelは、AppModelを継承する
 *
 * [phpstrom用] ＠property にmodel, Component等を記載すると、子クラスで記載したclassをソースコード上にリンクして
 * 開く事ができる
 *
 * @property SimpletextSetting $SimpletextSetting
 * @property SimpletextFrameSetting $SimpletextFrameSetting
 */
class SimpletextsAppModel extends AppModel {

}
