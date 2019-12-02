<?php
App::uses('TweetsController', 'Controller');


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
	// $_GET["screen_name"]に正常なユーザーIDが入ったリクエストのテスト
	public function testShow() {
    $GLOBALS["_GET"]['screen_name'] = 'Twitter';
		$result = $this->testAction('/tweets/show');
		$this->assertTextContains('>このアカウントの画像を取得する!</a>', $result);
	}

	public function testTweetImage() {
		$this->markTestIncomplete('testTweetImage not implemented.');
	}

}
