<?php
App::uses('AppModel', 'Model');
App::uses('Folder', 'Utility');

require("/var/www/html/app/Vendor/abraham/twitteroauth/autoload.php");
use Abraham\TwitterOAuth\TwitterOAuth;

class Tweet extends AppModel {
	public $validate = array(
		'image' => array(
			'rule' => 'isUnique'
		)
	);
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
		if(!file_exists("/var/www/html/app/webroot/img/uploads")){
			mkdir("/var/www/html/app/webroot/img/uploads");
		};

		foreach ( $imagePostArray as $tweetId => $imageUrlArray ) {
			// ツイートの画像データ１件毎にforeach
			foreach ( $imageUrlArray as $imageUrl ) {
				// URLが正常かどうか確認し、正常なら画像取得処理実行
				if( filter_var( $imageUrl, FILTER_VALIDATE_URL ) ){
					// チェック後、画像URLから情報を取得
					$fileName = pathinfo($imageUrl);
					$fileName = $fileName['basename'];

					// 画像取得に際して例外処理
					try{
						$img = file_get_contents($imageUrl);
					} catch (Exception $e)  {
						continue;
					};

					// ローカルのapp/tmp/uploadsに保存
					file_put_contents("/var/www/html/app/webroot/img/uploads/".$fileName,$img);
					// DB保存用に画像パスとツイートIDをセットにしてデータ組み立て
					$data = array(
						'Tweet' => array(
							'tw_id' => $tweetId,
							'image' => "uploads/".$fileName
							)
						);
					// ループcreateなので宣言
					$this->create(false);  
					// DBに保存(失敗した場合次へ)	
					if(!$this->save($data)){
						continue;
					};
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

	// ツイート日時をビューで表示する形に加工して返すメソッド
	// 例）Thu Nov 27 11:11:11 +0000 2019 => 2019年11月28日 20時11分
	public function processDateText($date){
		$timestamp = strtotime($date); 
		$jp_time = date('Y年m月d日 H時i分', $timestamp);
		return $jp_time;
	}	
}
