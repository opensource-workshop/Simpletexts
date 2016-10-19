<?php
/**
 * SimpletextsController Test Case
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @license https://www.freebsd.org/copyright/freebsd-license.html FreeBSD License
 * @copyright 2007 OpenSource-WorkShop Co.,Ltd.
 */

App::uses('SimpletextsController', 'Simpletexts.Controller');

/**
 * Summary for SimpletextsController Test Case
 */
class SimpletextsControllerTest extends ControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.simpletexts.simpletext',
		'plugin.simpletexts.user',
		'plugin.simpletexts.role',
		'plugin.simpletexts.user_role_setting',
		'plugin.simpletexts.users_language',
		'plugin.simpletexts.language'
	);

}
