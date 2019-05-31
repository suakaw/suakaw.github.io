<?php
	session_start();
	require_once("twitteroauth/twitteroauth/twitteroauth.php"); //Path to twitteroauth library
	
	$twitteruser = $_GET['twitterid'];
	$notweets = $_GET['numtweets'];
	$consumerkey = 'YOUR-API-KEY';
	$consumersecret = 'YOUR-API-SECRET';
	$accesstoken = 'YOUR-ACCESS-TOKEN';
	$accesstokensecret = 'YOUR-ACCESS-TOKEN-SECRET';
	
	function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
		$connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
		return $connection;
	}
	
	$connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);
	
	$tweets = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$twitteruser."&count=".$notweets);
	
	echo json_encode($tweets);
?>