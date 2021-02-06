<?php
include "updates.php";

header("Content-type: application/json");
$output = array("tweets" => [], "events" => []);

$twitterKeys = [
    getenv("TWITTER_CONSUMER_KEY"),
    getenv("TWITTER_CONSUMER_SECRET"),
    getenv("TWITTER_ACCESS_TOKEN"),
    getenv("TWITTER_ACCESS_SECRET"),
];
$twitterAuth = new TwitterOAuth(...$twitterKeys);
$twitter = new TwitterCache($twitterAuth, "DilgerTV", 20, CACHE_DIR . "/tweets.json");
$twitter->getTweets();
// Render tweet HTML from template and add to output array
foreach ($twitter->newTweets() as $tweet) {
	ob_start();
	include("../templates/tweet.php");
	$tweet["html"] = preg_replace("/[\n\r\t]/", "", ob_get_clean());
	array_unshift($output["tweets"], $tweet);
}

$calendarUrl = getenv("EVENTS_CALENDAR_URL");
$calendar = new CalendarCache($calendarUrl, 60, 60, CACHE_DIR . "/events.json");
$calendar->getEvents();
// Render event HTML from template and add to output array
foreach ($calendar->newEvents() as $event) {
	ob_start();
	include("../templates/event.php");
	$event["html"] = preg_replace("/[\n\r\t]/", "", ob_get_clean());
	array_unshift($output["events"], $event);
}

echo(json_encode($output));
