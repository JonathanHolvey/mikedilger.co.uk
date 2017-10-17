<?php
include "updates.php";

header("Content-type: application/json");
$output = array("tweets" => [], "events" => []);

$twitterKeys = new KeyStore("801bd3e4-f9e7-4495-9b20-f083fe2d9960");
$twitterAuth = new TwitterOAuth(...array_values($twitterKeys->keys));
$twitter = new TwitterCache($twitterAuth, "DilgerTV", 20, "../cache/tweets.json");
$twitter->getTweets();
// Render tweet HTML from template and add to output array
foreach ($twitter->newTweets() as $tweet) {
	ob_start();
	include("../templates/tweet.php");
	$tweet["html"] = preg_replace("/[\n\r\t]/", "", ob_get_clean());
	array_unshift($output["tweets"], $tweet);
}

$calendarKeys = new Keystore("e0ef9123-57d5-43ed-ad17-1f86c7a67ba3");
$calendarUrl = $calendarKeys->keys["url"];
$calendar = new CalendarCache($calendarUrl, 60, 60, "../cache/events.json");
// Render event HTML from template and add to output array
foreach ($calendar->newEvents() as $event) {
	ob_start();
	include("../templates/event.php");
	$event["html"] = preg_replace("/[\n\r\t]/", "", ob_get_clean());
	array_unshift($output["events"], $event);
}

echo(json_encode($output));
