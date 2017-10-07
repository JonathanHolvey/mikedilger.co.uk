<?php
include "updates.php";

$twitterKeys = new KeyStore("801bd3e4-f9e7-4495-9b20-f083fe2d9960");
$twitterAuth = new TwitterOAuth(...array_values($twitterKeys->keys));
$twitter = new TwitterCache($twitterAuth, "DilgerTV", 20, "../cache/tweets.json");
$twitter->getTweets();

$calendarKeys = new Keystore("e0ef9123-57d5-43ed-ad17-1f86c7a67ba3");
$calendarUrl = $calendarKeys->keys["url"];
$calendar = new CalendarCache($calendarUrl, 60, 60, "../cache/events.json");
$calendar->getEvents();

$output = array("tweets" => $twitter->newTweets(), "events" => $calendar->newEvents());
echo(json_encode($output, true));
