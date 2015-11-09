<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<?php
	function getAge($time) {
		// calculate time difference from now and return in format eg "16 hours" or "3 days" or "A long time" etc
		$seconds = time() - $time;
		$minutes = round($seconds / 60); $age = $minutes . " minute"; if ($minutes != 1) $age .= "s";
		if ($minutes >= 60) {$hours = round($minutes / 60); $age = $hours . " hour"; if ($hours > 1) $age .= "s";}
		if ($hours >= 24) {$days = round($hours / 24); $age = $days . " day"; if ($days > 1) $age .= "s";}
		if ($days >= 7) {$weeks = round($days / 7); $age = $weeks . " week"; if ($weeks > 1) $age .= "s";}
		if ($weeks > 6) $age = "A long time";
		return $age;
	}
	// create event entries frim iCal array, with the maximum to be displayed definrd by $max, if at all
	function createEvents($events,$max) {
		foreach ($events as $event) {
			$count ++;
			$title = $event["SUMMARY"];
			$description = $event["DESCRIPTION"];
			$time = iCalGetStartTime($event);
			$location = $event["LOCATION"];
			echo "<div class=\"event\"><div class=\"title\">" . $title . "</div>";
			if ($location != "")
				echo "<div class=\"location\">Location: " . $location . "</div>";
			echo "<div class=\"description\">" . $description . "</div><div class=\"date\">" . date("l j F Y",$time) . "</div></div>";
			if (isset($max) && $count == $max)
				break;
		}
	}
	
	require_once "script/iCalReader.php";
	require_once "script/iCalTools.php";

	// load events from iCal calendar
	$calendar = "http://www.google.com/calendar/ical/051fm5ptecji1l0sjivgmcvv60%40group.calendar.google.com/private-5e3d0ec9c21edee625b2537be9f37949/basic.ics";
	// check file is accessible
	if (file($calendar)) {
		$ical = new iCalReader($calendar);
		$events = $ical -> getEvents();
		
		// split events into past and future
		$pastEvents = iCalPastEvents($events);
		$futureEvents = iCalFutureEvents($events,30);
		
		// sort events
		uasort($futureEvents,"iCalSort");
		$futureEvents = array_reverse($futureEvents);
		uasort($pastEvents,"iCalSort");
	}
?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Mike Dilger - Updates</title>
	
	<meta http-equiv="content-Type" content="text/html; charset=iso-8859-1" />
	<meta name="copyright" content="" />
	<meta name="content-Language" content="english" /> 
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<meta name="created" content="December 2006" />
	<meta name="updated" content="March 2010" />

	<link rel="stylesheet" href="styles.css" type="text/css" />
	<style type="text/css">#link_updates{color:black}#arrow_updates{visibility:visible !important}</style>
</head>
<body>
	<?php include("header.php"); ?>
	<div id="tweetsList">
		<div class="title1">Latest tweets</div>
		<?php
			// check availability of file and load - number of tweets loaded is set by the "count" parameter
			if ($xml_tweets = simplexml_load_file("http://twitter.com/statuses/user_timeline/46637251.rss?count=50")) {
			
				// find length of tweet prefix name (MikeTV) and adjust for spacing etc
				$nameLength = strlen($xml_tweets->channel->title) - 8;

				// max number of tweets to display
				$tweetLimit = 10;

				// loop through and print global tweets, ie only ones without "@"
				foreach ($xml_tweets->channel->item as $tweet) {
					// filter tweets containing "@"
					if (strpos($tweet->title,"@") != $nameLength and $tweetCount < $tweetLimit) {
						$tweetTimeStamp = strtotime($tweet->pubDate);
						// print text
						echo "<p class=\"tweet\"><b>&ldquo;</b>" . htmlspecialchars(substr($tweet->title,$nameLength)) . "<b>&rdquo;</b><br/>" . "<a href=\"" . $tweet->link . "\" title=\"View on twitter.com\">" . date("l j F Y",$tweetTimeStamp) . " (" . getAge($tweetTimeStamp) . " ago)</a></p>";
						$tweetCount ++;
					}
				}
			}
		?>
		<br/><img src="images/linkArrow.png" style="position:relative;top:-1px" alt="Follow"/> <a href="http://twitter.com/DilgerTV">Follow me on Twitter...</a>
	</div>
	<div id="eventsList">
		<div class="title1">What I'm doing</div>
		<div class="title2">Coming up...</div>
		<?php createEvents($futureEvents,null); ?>
		<div class="title2">Past events</div>
		<?php createEvents($pastEvents,10); ?>
	</div>
	<?php include("footer.php"); ?>

