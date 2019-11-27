<?php
App::uses('AppModel', 'Model');
App::uses('Folder', 'Utility');

require("/var/www/html/app/Plugin/TwitterOAuth/autoload.php");
use Abraham\TwitterOAuth\TwitterOAuth;

class Tweet extends AppModel {
/**
 * Display field
 *
 * @var string
 */

	// TwitterOAuth用のAPIキーをまとめて呼び出すメソッド
	public function twitterOAuthInitialize(){

		$consumerKey       = $_ENV['CONSUMER_KEY'];
		$consumerSecret    = $_ENV['CONSUMEE_SECRET'];
		$accessToken       = $_ENV['ACCESS_TOKEN'];
		$accessTokenSecret = $_ENV['ACCESS_TOKEN_SECRET'];
		
		// APIをセットしリクエストの準備をした状態の$twitterを返す
		$twitter = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
		return $twitter;
	}

	public function saveImageData($imagePostArray){
		$dir = new Folder('../tmp/uploads');
		foreach ($imagePostArray as $tweetId => $imageUrlArray){
			foreach ($imageUrlArray as $imageUrl){
				$fileName = pathinfo($imageUrl);
				$fileName = $fileName['basename'];
				$img = file_get_contents($imageUrl);
				file_put_contents("../tmp/uploads/".$fileName,$img);
				$data = array(
					'Tweet' => array(
						'tw_id' => $tweetId,
						'image' => "../tmp/uploads/".$fileName
					)
					);
				$this->save($data);
			};
		}
	}

}
