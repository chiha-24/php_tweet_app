<?php
App::uses('Tweet', 'Model');
require("/var/www/html/app/Vendor/abraham/twitteroauth/autoload.php");
use Abraham\TwitterOAuth\TwitterOAuth;

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

  // TwitterOAuthのAPIキーセットを行うtwitterOAuthInitialize()のテスト
	public function testTwitterOAuthInitialize(){
		// 実際のメソッドから値取得
		$result = $this->Tweet->twitterOAuthInitialize();

		// 同じ処理をこちらでも行う
		$consumerKey       = $_ENV['CONSUMER_KEY'];
		$consumerSecret    = $_ENV['CONSUMEE_SECRET'];
		$accessToken       = $_ENV['ACCESS_TOKEN'];
		$accessTokenSecret = $_ENV['ACCESS_TOKEN_SECRET'];
		$testTwitter = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);

		// 同じ内容かチェック
		$this->assertEquals($result,$testTwitter);
	}

	// ツイートIDと画像パスをDBに保存するSaveImageData()のテスト
	public function testSaveImageData(){

		// テストcreateするデータ用の配列
		$imageTweets = [];

		// メソッド最初に画像を取得して移動させる処理を行うので実際に画像が取得できるURL
		// 今回の処理では合計5つのレコードが保存される
		$imageTweets['1'] = [
			'https://chiha-blog-thumb.s3-ap-northeast-1.amazonaws.com/assets/20191118103311258450.jpeg',
			'https://chiha-blog-thumb.s3-ap-northeast-1.amazonaws.com/assets/20191118103206402926.jpeg'
		];

		$imageTweets['2'] = [
			'https://chiha-blog-thumb.s3-ap-northeast-1.amazonaws.com/assets/20191118104301798091.jpeg',
			'https://chiha-blog-thumb.s3-ap-northeast-1.amazonaws.com/assets/20191118103658613846.jpeg',
			'https://chiha-blog-thumb.s3-ap-northeast-1.amazonaws.com/assets/20191118103950819155.JPG'
		];

		// URLをテストするメソッドに渡す
		$this->Tweet->saveImageData($imageTweets);

		// saveImageDataで５つレコードが保存されているはずなので、全て呼び出す
		$result = $this->Tweet->find('all');

		// きちんと5つのデータがinsert出来ているか確認
		$this->assertEquals(count($result),5);
	}

	// 画像が取得できないURLが渡った時に何も保存されずエラーが出ないことのチェック
	public function testWrongURLSaveImageData(){

		// テストcreateするデータ用の配列
		$imageTweets = [];

		// 画像が取得できないURLを渡す
		$imageTweets['1'] = [
			'https://chiha-blog-thumb.s3-ap-northeast-1.amazonaws.com/asset',
			'https://chiha-blog-thumb.s3-ap-northeast-1.amazonaws.com/assets/20'
		];

		$imageTweets['2'] = [
			'https://chiha-blog-thumb.s3-ap-northeast-1.amazonaws.com/assets/201911181043',
			'https://chiha-blog-thumb.s3-ap-northeast-1.amaz',
			'https://chiha-blog-thumb.s3-ap-northeast-1.amazonaws.com/asset'
		];

		// $imageTweetsをメソッドに渡す
		$this->Tweet->saveImageData($imageTweets);

		// レコードを全て呼び出す
		$result = $this->Tweet->find('all');

		// insertされていないことを確認
		$this->assertEquals(count($result),0);
	}

	// URLではない文字列を渡した時にもエラーが出ずに何も保存されないことのテスト
	public function testNotURLSaveImageData(){

		// テストcreateするデータ用の配列
		$imageTweets = [];

		// 画像が取得できないURLを渡す
		$imageTweets['1'] = [
			'test1',
			'test2'
		];

		$imageTweets['2'] = [
			'test3',
			'test4',
			'test5'
		];

		// $imageTweetsをメソッドに渡す
		$this->Tweet->saveImageData($imageTweets);

		// レコードを全て呼び出す
		$result = $this->Tweet->find('all');

		// insertされていないことを確認
		$this->assertEquals(count($result),0);
	}


	// ツイートIDでDBを検索してローカル画像パスを返すgetImagePathのテスト
	public function testGetImagePath(){
		// テストcreateするデータ用の配列
		$imageTweets = [];

		// メソッド最初に画像を取得してローカルに移動させる処理を行うので実際に画像が取得できるURL
		// 今回の処理では合計2つのレコードが保存される
		$imageTweets['1'] = [
			'https://chiha-blog-thumb.s3-ap-northeast-1.amazonaws.com/assets/20191118103311258450.jpeg',
			'https://chiha-blog-thumb.s3-ap-northeast-1.amazonaws.com/assets/20191118103206402926.jpeg'
		];
		$this->Tweet->saveImageData($imageTweets);

		// メソッドの返り値との比較用の配列
		$expect = ['uploads/20191118103311258450.jpeg','uploads/20191118103206402926.jpeg'];

		// テストするメソッドをtw_id = '1'で呼び出し
		$result = $this->Tweet->getImagePath('1');

		// $resultと$expectの比較
		$this->assertEquals($result,$expect);
	}

	// twitterからの日時レスポンスを表示する形に変形するメソッドのテスト
	public function testProcessDateText(){
		$date = 'Thu Nov 27 11:11:11 +0000 2019';
		$result = $this->Tweet->processDateText($date);
		$expect = '2019年11月28日 20時11分';
		$this->assertEquals($result,$expect);
	}

}
