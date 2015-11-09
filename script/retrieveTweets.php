<?php
	// use twitter oauth script from https://github.com/abraham/twitteroauth
	require_once("twitteroauth.php");

	$userId = "DilgerTV";
	$tweetLimit = 100;
	$tweetsOut = "../tweets.xml";

	// access credentials from http://dev.twitter.com
	$consumerKey = "lk56EKZ3fI9E54CEeWQ";
	$consumerSecret = "hTYauXbumIfKHI7BrSCkhiXU1P9UaFAq1tHJEjw4M";
	$accessToken = "1723744310-wL94o81R2FS7lhkjQJYuo9AwgSmlEsl5ZEuLVuo";
	$accessSecret = "VxWcK1KjD2xqBdXGtxBqG7uFYQnj97rUKNst7COmd8";

	// request authentication from Twitter API
	$connection = new TwitterOAuth($consumerKey,$consumerSecret,$accessToken,$accessSecret);

	$xmlTweets = new SimpleXMLElement("<tweets/>");
	$xmlTweets -> addAttribute("link","http://twitter.com/" . $userId);

	// retrieve latest tweets
	if ($connection) {
		$getTweets = $connection -> get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=" . $userId . "&count=" . $tweetLimit.  "&include_rts=false&exclude_replies=true");
		if (count($getTweets)) {
			$count = 0;
			foreach ($getTweets as $tweet) {
				$xmlTweet = $xmlTweets -> addChild("tweet");
				$xmlTweet -> addAttribute("time",strtotime($tweet -> created_at));
				$xmlTweet -> addChild("title",$tweet -> text);
				$xmlTweet -> addChild("link","http://twitter.com/" . $userId . "/statuses/" . $tweet -> id_str);
				$count ++;
				if ($count >= $tweetLimit)
					break;
			}
			// output xml file
			if ($xmlTweets -> asXML($tweetsOut))
				echo "Tweets cached sucessfully - <a href=\"" . $tweetsOut . "\">Click for file</a><br/>";
		}
	}
?>