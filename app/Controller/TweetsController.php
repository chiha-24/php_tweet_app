<?php
App::uses('AppController', 'Controller');
/**
 * Tweets Controller
 */
class TweetsController extends AppController {

	public function index() {
		$words = $_ENV['REMOTE_ADDR'];

		$this->set("words",$words);

		/**
		 * phpifoを表示するときに使う。
		 * $this->autoRender = false;
  	 * ob_start()；
  	 * phpinfo();
  	 * $html = ob_get_contents();
  	 * ob_end_clean();
  	 * return $html;
		 */
	}
}
