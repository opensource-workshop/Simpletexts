<?php
/**
 * SimpletextBlocksController Test Case
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @license https://www.freebsd.org/copyright/freebsd-license.html FreeBSD License
 * @copyright 2007 OpenSource-WorkShop Co.,Ltd.
 */

App::uses('SimpletextBlocksController', 'Simpletexts.Controller');

/**
 * Summary for SimpletextBlocksController Test Case
 */
class SimpletextBlocksControllerTest extends ControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.simpletexts.simpletext_block',
		'plugin.simpletexts.language',
		'plugin.simpletexts.user',
		'plugin.simpletexts.role',
		'plugin.simpletexts.user_role_setting',
		'plugin.simpletexts.users_language'
	);

}
