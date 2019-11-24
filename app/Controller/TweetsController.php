<?php
App::uses('AppController', 'Controller');
/**
 * Tweets Controller
 */
class TweetsController extends AppController {

	public function index() {
		$words = "テスト";
		$this->set("words",$words);
	}
}
