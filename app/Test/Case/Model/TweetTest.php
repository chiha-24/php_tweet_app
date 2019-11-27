<?php
App::uses('Tweet', 'Model');

/**
 * Tweet Test Case
 */
class TweetTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.tweet'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Tweet = ClassRegistry::init('Tweet');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Tweet);

		parent::tearDown();
	}

}
