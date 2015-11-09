<?php
	// calculate time difference from now and return in format eg "16 hours" or "3 days" or "A long time" etc
	function getAge($time) {
		$seconds = time() - $time;
		$minutes = round($seconds / 60); $age = $minutes . " minute"; if ($minutes > 1) $age .= "s";
		if ($minutes >= 60) {$hours = round($minutes / 60); $age = $hours . " hour"; if ($hours > 1) $age .= "s";}
		if ($hours >= 24) {$days = round($hours / 24); $age = $days . " day"; if ($days > 1) $age .= "s";}
		if ($days >= 7) {$weeks = round($days / 7); $age = $weeks . " week"; if ($weeks > 1) $age .= "s";}
		if ($weeks > 6) $age = "A long time";
		return $age;
	}
	// calculate date range from a start and end time, return in format eg "Monday 16 August 2010 (tomorrow)" or "Monday 16 to Tuesday 17 August 2010" etc
	function getDateRange($startTime, $endTime) {
		$range = date("l j F Y", $startTime);
		if ($startTime >= mktime(0, 0, 0, date("m"), date("d"), date("y")) && $endTime <= mktime(0, 0, 0, date("m"), date("d") + 1, date("y")) && $endTime > time())
			$range .= " (today)";
		if ($startTime >= mktime(0, 0, 0, date("m"), date("d") + 1, date("y")) && $endTime <= mktime(0, 0, 0, date("m"), date("d") + 2, date("y")))
			$range .= " (tomorrow)";
		if ($endTime - $startTime > 86400) {
			$endTime -= 1; // prevent events running into the first second of the next day
			$range = date("l j", $startTime) . " to " . date("l j F Y", $endTime);
			if (date("F", $startTime) != date("F", $endTime))
				$range = date("l j F", $startTime) . " to " . date("l j F Y", $endTime);
			if (date("F Y", $startTime) != date("F Y", $endTime))
				$range = date("l j F Y", $startTime) . " to " . date("l j F Y", $endTime);
		}
		return $range;
	}
	
	// create tweet entry from simple xml element
	function createTweet($tweet) {
		return "<div class=\"tweet\"><div class=\"title\">" . createLinks(htmlspecialchars($tweet -> title)) . "</div><div class=\"time\"><a href=\"" . $tweet -> link . "\" title=\"View on twitter.com\">" . date("l j F Y", (int)$tweet["time"]) . " (" . getAge($tweet["time"]) . " ago)</a></div></div>";
	}
	// create event entry from simple xml element
	function createEvent($event) {
		$location = $event->location != ""? "<div class=\"location\">Location: " . htmlspecialchars($event -> location) . "</div>": "";
		return "<div class=\"event\"><div class=\"title\">" . htmlspecialchars($event -> title) . "</div><div class=\"time\">" . getDateRange((int)$event["startTime"], (int)$event["endTime"]) . "</div>" . $location . "<div class=\"description\">" . htmlspecialchars($event -> description) . "</div></div>";
	}
	
	// replace url with anchor, and twitter tags with search links
	function createLinks($string) {
		$string = preg_replace("/(https?:\/\/[^\s]+)/", "<a href=\"$1\">$1</a>", $string);
		$string = preg_replace("/#(\w+)/", "<a href=\"http://twitter.com/hashtag/$1\">#$1</a>", $string);
		$string = preg_replace("/(@\w+)/", "<a href=\"http://twitter.com/$1\">$1</a>", $string);
		return $string;
	}
?>