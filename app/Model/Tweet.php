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

	// ツイートデータから画像をローカルに保存する処理を行うメソッド
	public function saveImageData($imagePostArray) {

		// 画像データ保存用のディレクトリ（/tmp/uploads）がなければ作成
		if(!file_exists("/var/www/html/app/tmp/uploads")){
			mkdir("/var/www/html/app/tmp/uploads");
		};

		// ツイートデータ毎にforeach
		foreach ( $imagePostArray as $tweetId => $imageUrlArray ) {
			// ツイートの画像データ１件毎にforeach
			foreach ( $imageUrlArray as $imageUrl ) {
				// 一応URLが正常かどうかを確認(URLが不正だった場合は次へ)
				if (filter_var( $imageUrl,FILTER_VALIDATE_URL )) {
					$fileName = pathinfo($imageUrl);
					$fileName = $fileName['basename'];
					$img = file_get_contents($imageUrl);
					// ローカルのapp/tmp/uploadsに保存
					file_put_contents("../tmp/uploads/".$fileName,$img);
					// DB保存用に画像パスとツイートIDをセットにしてデータ組み立て
					$data = array(
						'Tweet' => array(
							'tw_id' => $tweetId,
							'image' => "/var/www/html/app/tmp/uploads/".$fileName
							)
						);
					// DBに保存(失敗した場合次へ)	
					if($this->save($data) === false){
						continue;
					}
				} else {
					continue;
				};
			};
			
		};
	}

	// ツイートIDに応じてローカルに保存した画像のパスをDBから取得し、配列にまとめて返すメソッド
	public function getImagePath($tweetId){
		// DBからはツイートIDで取得する。
		$options = array(
			'conditions' => array(
				'Tweet.tw_id' => $tweetId
				)
			);
		// ツイートIDに一致する項目を全件取得
		$allImageData = $this->find('all',$options);

		// メソッドのレスポンスとして返す配列を生成
		$allImagePath = [];
		
		foreach ($allImageData as $imageData){
			$imagePath = $imageData['Tweet']['image'];
			array_push($allImagePath,$imagePath);
		};
		return $allImagePath;
	}
}
