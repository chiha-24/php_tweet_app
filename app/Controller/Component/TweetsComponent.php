<?php
  require("/var/www/html/app/Plugin/TwitterOAuth/autoload.php");
  use Abraham\TwitterOAuth\TwitterOAuth;

  class TweetsComponent extends Component{
    public function twitterOAuthInitialize(){

      $consumerKey       = $_ENV['CONSUMER_KEY'];
		  $consumerSecret    = $_ENV['CONSUMEE_SECRET'];
		  $accessToken       = $_ENV['ACCESS_TOKEN'];
      $accessTokenSecret = $_ENV['ACCESS_TOKEN_SECRET'];
      
      $twitter = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
      return $twitter;
    }
  }
?>