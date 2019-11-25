<?php
App::uses('AppController', 'Controller');
require("/var/www/html/app/Plugin/TwitterOAuth/autoload.php");
use Abraham\TwitterOAuth\TwitterOAuth;


/**
 * Tweets Controller
 */
class TweetsController extends AppController {

	// モデルに干渉しないformを使うので宣言
	var $useTable = false;
	
	// componentに定義した処理を使用するので宣言
	var $components = array('Tweets');

	public function index() {
	}

	public function result(){
		// 検索キーワード取得
		$keyWord = $this->request->data("keyWord");

		// マイページURLを入力された場合の処理
		$regexp = "/https:\/\/twitter.com\//";
		if(preg_match($regexp,$keyWord)){
			$keyWord = preg_replace($regexp,"",$keyWord);
		}

		// ユーザー検索
		$twitter = $this->Tweets->twitterOAuthInitialize();
		$result  = $twitter->get('users/search', ["q" => $keyWord]);	

		// 検索時にエラーが出ていた場合
		if(isset($result->errors)){
			return false;
		}
		// 出ていなかった場合
		elseif (count($result) != 0){
			$counted = count($result);
			$this->set("counted",$counted);
			$this->set("result",$result);
		}
		// 検索結果がなかった場合
		else{
			print_r($result);
			return false;
		}
		
	}

	public function show(){

		// リクエスト末尾のscreen_nameからtwitterIDを取得
		$userName = $_GET["screen_name"];

		// 特定のユーザー１件の取得
		$twitter    = $this->Tweets->twitterOAuthInitialize();
		$userDetail = $twitter->get('users/show', ["screen_name" => $userName]);

		// ビュー側に送信
		$this->set("userDetail",$userDetail);
		print_r($userDetail);
	}
}
