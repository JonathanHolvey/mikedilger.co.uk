<?php
	// comparison function for uasort() to sort events by date, i.e. use uasort($events,"iCalSort")
	function iCalSort($eventA,$eventB) {
		$timeA = iCalGetStartTime($eventA);
		$timeB = iCalGetStartTime($eventB);
		if ($timeA > $timeB)
			return -1;
		elseif ($timeA == $timeB)
			return 0;
		elseif ($timeA < $timeB)
			return 1;
	}
	
	// extract start timestmap from iCal event
	function iCalGetStartTime($event) {
		foreach ($event as $key => $value) {
			if (strpos($key,"DTSTART") === 0)
				return strtotime($value);
		}
	}
	// extract end timestmap from iCal event
	function iCalGetEndTime($event) {
		foreach ($event as $key => $value) {
			if (strpos($key,"DTEND") === 0)
				return strtotime($value);
		}
	}
	
	// create array of past events
	function iCalPastEvents($events) {
		$pastEvents = array();
		foreach ($events as $event) {
			if (iCalGetEndTime($event) < time())
				array_push($pastEvents,$event);
		}
		return $pastEvents;
	}
	
	// create array of future events within the next number of days
	function iCalFutureEvents($events,$days) {
		$futureEvents = array();
		foreach ($events as $event) {
			if (iCalGetEndTime($event) >= time() && iCalGetEndTime($event) <= time() + 60 * 60 * 24 * $days)
				array_push($futureEvents,$event);
		}
		return $futureEvents;
	}
?>