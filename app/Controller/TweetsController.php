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

		// リクエストに含まれるscreen_nameを受け取る。無い場合トップページへリダイレクト
		if(!isset($_GET["screen_name"])){
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
		$imagePosts = $twitter->get('search/tweets', ["q" => "from:$userName filter:images exclude:retweets","result_type" => "recent","count" => "5", "include_entities"=>true,"tweet_mode"=>"extended"]);

		// 画像ツイートが取得できた場合
		if (count($imagePosts) != 0){

		// DB保存用にレスポンスデータを加工
		// 配列形式で保存用データを格納する
		$allImagePosts = [];
		foreach ($imagePosts->statuses as $post){

			// 画像urlのみを配列にまとめる
			$tweetImageUrls = [];
			$tweetId = $post->id;

			foreach ($post->entities->media as $media){
				$tweetImageUrl = $media->media_url;
				array_push($tweetImageUrls,$tweetImageUrl);
			}
			// ツイートID(key) => 画像URLの配列(value)の形で連想配列を組んで$allImagePostsに格納
			$allImagePosts[$tweetId] = $tweetImageUrls;
		}
		print_r($allImagePosts);
		}
	}
}
