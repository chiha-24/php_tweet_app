<?php
App::uses('AppController', 'Controller');
require("/var/www/html/app/Plugin/TwitterOAuth/autoload.php");
use Abraham\TwitterOAuth\TwitterOAuth;


/**
 * Tweets Controller
 */
class TweetsController extends AppController {

	// DBに干渉しないがformで値送信を行うので宣言
	var $useTable = false;

	public $uses = array('Tweet');

	// indexアクション
	public function index() {
		$this->layout = 'tweets';
	}

	// resultアクション(検索結果表示ページ)
	public function result(){
		$this->layout = 'tweets';

		// 検索キーワード取得
		$keyWord = $this->request->data("keyWord");

		// マイページURLを入力された場合の処理
		$regexp = "/https:\/\/twitter.com\//";
		if(preg_match($regexp,$keyWord)){
			$keyWord = preg_replace($regexp,"",$keyWord);
		}

		// キーワードでユーザー検索
		$twitter = $this->Tweet->twitterOAuthInitialize();
		$result  = $twitter->get('users/search', ["q" => $keyWord]);	

		// 検索時にエラーが出ていた場合
		if(isset($result->errors)){
			return false;
		}
		// エラーが出てなかった場合
		elseif (count($result) != 0){
			$counted = count($result);
			$this->set("counted",$counted);
			$this->set("result",$result);
		}
		// 検索結果がなかった場合
		else{
			return false;
		}
		
	}

	// showアクション（ユーザー詳細表示）
	public function show(){
		$this->layout = 'tweets';

		// リクエストからscreen_nameを受け取る。無い場合トップページへリダイレクト
		if(!isset($_GET["screen_name"])){
			$this->redirect(array(
				'controller' => 'tweets',
				'action' => 'index'
			));
			return false;
		}

		// リクエスト末尾からtwitterIDを取得
		$userName = $_GET["screen_name"];

		// 特定のユーザー１件の取得
		$twitter    = $this->Tweet->twitterOAuthInitialize();
		$userDetail = $twitter->get('users/show', ["screen_name" => $userName]);

		// ビュー側に送信
		// 取得エラーの場合トップへリダイレクト
		if (isset($userDetail->errors)){
			$this->redirect(array(
				'controller' => 'tweets',
				'action' => 'index'
			));
		}
		// データがある場合はビューに送る 
		elseif (isset($userDetail)) {
			$this->set("userDetail",$userDetail);
		}
		// ない場合は何もしない
	}

	// tweetImageアクション（画像取得・表示アクション）
	public function tweetImage(){
		$this->layout = 'tweets';

		// リクエストに含まれるscreen_nameを確認。無い場合トップページへリダイレクト。
		if(!isset($_GET["screen_name"]) or strlen($_GET["screen_name"]) === 0){
			$this->redirect(array(
				'controller' => 'tweets',
				'action' => 'index'
			));
			return false;
		}

		// リクエストからユーザーIDを取得（ツイート取得に使用）
		$userName = $_GET["screen_name"];

		// 画像ツイートの取得（最新順）
		$twitter    = $this->Tweet->twitterOAuthInitialize();
		// 取得したユーザーからのツイート・RTを除く・最大５件・画像を含むツイートのみの条件で、文字数上限を超えた情報を全て取得する "result_type" => "recent"
		$imagePosts = $twitter->get('search/tweets', ["q" => "from:$userName filter:images exclude:retweets","count" => "5", "include_entities"=>true,"tweet_mode"=>"extended"]);

		// 画像ツイートが取得できた場合
		if (count($imagePosts) != 0){

		// DB保存用にレスポンスデータを加工
		// 配列形式で保存用データを格納する
		$imagePostArray = [];
		foreach ($imagePosts->statuses as $postData){

			// 画像urlをまとめる配列を作る
			$imageUrlArray = [];
			$tweetId = $postData->id;
			foreach ($postData->extended_entities->media as $media){
				$imageUrl = $media->media_url;
				array_push($imageUrlArray,$imageUrl);
			}
			// ツイートID(key) => array(画像URLの配列(value))の形で連想配列を組んで$imagePostArrayに格納
			$imagePostArray[$tweetId] = $imageUrlArray;
		};

		// 画像の保存処理
		$this->loadModel('Tweet');
		$this->Tweet->saveImageData($imagePostArray);

		// ビューに送るデータの組み立て
		$sendViewDataArray = [];

		foreach($imagePosts->statuses as $postData){
			// 1ツイート分のデータをまとめた連想配列を作る
			$sendViewData            				= [];
			$sendViewData['date']    				= $postData->created_at;
			$sendViewData['text']    				= $postData->full_text;
			$sendViewData['name']    				= $postData->user->name;
			$sendViewData['sc_name'] 				= $postData->user->screen_name;
			$sendViewData['profile_image']  = $postData->user->profile_image_url;
			// 画像パスをまとめた配列を返すgetImagePathを呼び出し
			$sendViewData['image_urls']     = $this->Tweet->getImagePath($postData->id);
			array_push($sendViewDataArray,$sendViewData);
		};
		$this->set("viewDataArray",$sendViewDataArray);
		}
		$this->set("userName",$userName);
	} 
}
