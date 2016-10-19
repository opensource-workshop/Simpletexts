<?php
/**
 * SimpletextFrameSettingsController Test Case
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @license https://www.freebsd.org/copyright/freebsd-license.html FreeBSD License
 * @copyright 2007 OpenSource-WorkShop Co.,Ltd.
 */

App::uses('SimpletextFrameSettingsController', 'Simpletexts.Controller');

/**
 * Summary for SimpletextFrameSettingsController Test Case
 */
class SimpletextFrameSettingsControllerTest extends ControllerTestCase {

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

}
