<?php
App::uses('AppController', 'Controller');
require("/var/www/html/app/Plugin/TwitterOAuth/autoload.php");
use Abraham\TwitterOAuth\TwitterOAuth;

/**
 * Tweets Controller
 */
class TweetsController extends AppController {
	var $useTable = false;
	public function index() {
	}

	public function result(){
		$screenName = $this->request->data("name");

		// APIキーの呼び出し
		$consumerKey       = $_ENV['CONSUMER_KEY'];
		$consumerSecret    = $_ENV['CONSUMEE_SECRET'];
		$accessToken       = $_ENV['ACCESS_TOKEN'];
		$accessTokenSecret = $_ENV['ACCESS_TOKEN_SECRET'];

		// ユーザー検索
		$twitter = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
		$search = $twitter->get('users/search', ["q" => $screenName]);	

		// 検索結果を配列として取得
		if(count($search) != 0){
			$this->set("search",$search);
		}
		else{
			return false;
		}
		
	}
}
