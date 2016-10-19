<?php
/**
 * Simpletext Test Case
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @license https://www.freebsd.org/copyright/freebsd-license.html FreeBSD License
 * @copyright 2007 OpenSource-WorkShop Co.,Ltd.
 */

App::uses('Simpletext', 'Simpletexts.Model');

/**
 * Summary for Simpletext Test Case
 */
class SimpletextTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.simpletexts.simpletext',
		'plugin.simpletexts.block',
		'plugin.simpletexts.user',
		'plugin.simpletexts.role',
		'plugin.simpletexts.user_role_setting',
		'plugin.simpletexts.users_language',
		'plugin.simpletexts.language'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Simpletext = ClassRegistry::init('Simpletexts.Simpletext');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Simpletext);

		parent::tearDown();
	}

}
