<?php
App::uses('TweetsController', 'Controller');
App::uses('AppModel','Model');
App::uses('Tweet','Model');

/**
 * TweetsController Test Case
 */
class TweetsControllerTest extends ControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.tweet'
	);

  // indexアクションのレスポンステスト
	public function testIndex() {
		$result = $this->testAction('/tweets/index');
    debug($result);
		$this->assertTextContains('キーワード検索</h1>', $result);
	}

	// ここからtweets/result
	// ユーザーが見つかるパターンの検索キーワードを与えて検索アクションにPOSTした場合
	public function testResult() {
		$data = array(
			'keyWord' => 'a' 
		);
		$result = $this->testAction('/tweets/result',array('data' => $data));
		$this->assertTextContains('件見つかりました', $result);
	}

  // リクエストにデータが含まれないgetメソッドの場合
	public function testGetResult() {
		$result = $this->testAction('/tweets/result',array('method'=>'get'));
		$this->assertTextContains(">ユーザーが見つかりませんでした。</p>",$result);
	}

	// リクエストにデータが含まれないpostメソッドの場合
	public function testPostResult() {
		$result = $this->testAction('/tweets/result',array('method'=>'post'));
		$this->assertTextContains(">ユーザーが見つかりませんでした。</p>",$result);
	}

	// データはあるがキーワード空欄でpostした場合
	public function testBlankPostResult(){
		$data = array(
			'keyWord' => ''
		);
		$result = $this->testAction('/tweets/result',array('data' => $data));
		$this->assertTextContains('>ユーザーが見つかりませんでした。</p>', $result);
	}

	// キーワードはあるがマッチするユーザーが居なかった場合
	public function testTooLongPostResult(){
		$data = array(
			'keyWord' => 'isgjaeorhgilehrgiuheriugheiurhgilauehrgwrgw32352353gfdgerkgjhaelugrhlieu'
		);
		$result = $this->testAction('/tweets/result',array('data' => $data));
		$this->assertTextContains('>ユーザーが見つかりませんでした。</p>', $result);
	}

	// ここからtweets/show
	// params['named']['screen_name']に正常なユーザーIDが入ったリクエストのテスト
	public function testShow() {
		$result = $this->testAction('/tweets/show/screen_name:Twitter');
		$this->assertTextContains('>このアカウントの画像を取得する！</a>', $result);
	}

	// params['named']['screen_name'] はあるが値が無い場合のリクエストのテスト
	public function testScNameBlankShow() {
		$result = $this->testAction('/tweets/show/screen_name:');
		$this->assertTextContains('>ユーザーが取得できませんでした</p>', $result);
	}

	// params['named']['screen_name'] はあるが該当ユーザーが存在しない場合のリクエストのテスト
	public function testScNameToolongShow() {
		$result = $this->testAction('/tweets/show/screen_name:jdrhgiehriughieurhgiueherger9e9rgergeriguhie');
		$this->assertTextContains('>ユーザーが取得できませんでした</p>', $result);
	}

	// params['named']['screen_name'] が無い場合のリダイレクトチェック
	public function testWithoutScNameShow() {
		$this->testAction('/tweets/show');
		$this->assertRegExp('/http:\/\/localhost:8000\//', $this->headers['Location']);
	}

	// ここからtweets/tweet_image
	// params['named']['screen_name'] の値に該当するユーザーが存在する場合のレスポンステスト
	public function testGetUserTweetImage(){
		$result = $this->testAction('/tweets/tweetImage/screen_name:Twitter');
		$this->assertTextContains('さんの画像ツイート</h1>', $result);
	}

	// params['named']['screen_name']　の値に該当するユーザーが存在し、画像ツイートが取得できなかった場合のレスポンステスト
	public function testCanNotGetUserTweetImage(){
		$result = $this->testAction('/tweets/tweetImage/screen_name:Twitter');
		$this->assertTextContains('取得できませんでした</p>', $result);
	}

	// params['named']['screen_name']　の値に該当するユーザーが存在し、画像ツイートが取得できた場合のレスポンステスト
	// screen_name:○○は確実に画像を取得できるアカウントのTwitterIDに書き換える
	public function testGetTweetImage(){
		$result = $this->testAction('/tweets/tweetImage/screen_name:t7s_staff');
		$this->assertTextContains('<div class="tweet-data-container">', $result);
	}

	// params['named']['screen_name'] の値に該当するユーザーが存在しない場合のレスポンステスト
	public function testScNameToolongTweetImage(){
		$result = $this->testAction('/tweets/tweetImage/screen_name:whrthrtsr;thkpsirjtohijsoritjhoirsht');
		$this->assertTextContains('取得できませんでした</p>', $result);
	}

	// params['named']['screen_name'] が空の場合のリダイレクトテスト
	public function testBlankScNameImage() {
		$this->testAction('/tweets/tweetImage/screen_name:');
		$this->assertRegExp('/http:\/\/localhost:8000\//', $this->headers['Location']);
	}

	// params['named']['screen_name'] が存在しない場合のリダイレクトテスト
	public function testWithoutScNameImage() {
		$this->testAction('/tweets/tweetImage');
		$this->assertRegExp('/http:\/\/localhost:8000\//', $this->headers['Location']);
	}
}
