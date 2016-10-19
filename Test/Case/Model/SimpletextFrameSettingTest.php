<?php
/**
 * SimpletextFrameSetting Test Case
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @license https://www.freebsd.org/copyright/freebsd-license.html FreeBSD License
 * @copyright 2007 OpenSource-WorkShop Co.,Ltd.
 */

App::uses('SimpletextFrameSetting', 'Simpletexts.Model');

/**
 * Summary for SimpletextFrameSetting Test Case
 */
class SimpletextFrameSettingTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.simpletexts.simpletext_frame_setting',
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
		$this->SimpletextFrameSetting = ClassRegistry::init('Simpletexts.SimpletextFrameSetting');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->SimpletextFrameSetting);

		parent::tearDown();
	}

}
