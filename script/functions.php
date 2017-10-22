<?php
// Return a string describing the time since a given date
function friendlyAge($time) {
	$seconds = time() - $time;
	$minutes = round($seconds / 60);
	$age = $minutes . " minute" . ($minutes > 1 ? "s" : "");
	if ($minutes >= 60) {
		$hours = round($minutes / 60);
		$age = $hours . " hour" . ($hours > 1 ? "s" : "");
	}
	if ($hours >= 24) {
		$days = round($hours / 24);
		$age = $days . " day" . ($days > 1 ? "s" : "");
	}
	if ($days >= 7) {
		$weeks = round($days / 7);
		$age = $weeks . " week" . ($weeks > 1 ? "s" : "");
	}
	if ($weeks > 6)
		$age = "A long time";
	return $age;
}

// Return a string descriging the time between two dates
function friendlyDateRange($start, $end) {
	$range = date("l j F Y", $start);
	if ($start >= mktime(0, 0, 0, date("m"), date("d"), date("y")) && $end <= mktime(0, 0, 0, date("m"), date("d") + 1, date("y")) && $end > time())
		$range .= " (today)";
	if ($start >= mktime(0, 0, 0, date("m"), date("d") + 1, date("y")) && $end <= mktime(0, 0, 0, date("m"), date("d") + 2, date("y")))
		$range .= " (tomorrow)";
	if ($end - $start > 86400) {
		$end -= 1; // Prevent events running into the first second of the next day
		$range = date("l j", $start) . " to " . date("l j F Y", $end);
		if (date("F", $start) != date("F", $end))
			$range = date("l j F", $start) . " to " . date("l j F Y", $end);
		if (date("F Y", $start) != date("F Y", $end))
			$range = date("l j F Y", $start) . " to " . date("l j F Y", $end);
	}
	return $range;
}
	
// Replace url with anchor, and twitter tags with search links
function addLinks($string) {
	$string = preg_replace("/(https?:\/\/[^\s]+)/", "<a href=\"$1\">$1</a>", $string);
	$string = preg_replace("/#(\w+)/", "<a href=\"http://twitter.com/hashtag/$1\">#$1</a>", $string);
	$string = preg_replace("/(@\w+)/", "<a href=\"http://twitter.com/$1\">$1</a>", $string);
	return $string;
}

function escape($string) {
	return htmlspecialchars($string);
}
