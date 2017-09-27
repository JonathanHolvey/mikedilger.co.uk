<?php
include "updates.php";
$twitterKeys = new KeyStore("801bd3e4-f9e7-4495-9b20-f083fe2d9960");
$twitterAuth = new TwitterOAuth(...array_values($twitterKeys->keys));
$twitter = new TwitterCache($twitterAuth, "DilgerTV", 100, "../cache/tweets.json");
$twitter->getTweets();
$twitter->sendNew();
