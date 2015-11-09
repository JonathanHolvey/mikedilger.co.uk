<?php
	$calendarIn = "http://www.google.com/calendar/ical/051fm5ptecji1l0sjivgmcvv60%40group.calendar.google.com/private-5e3d0ec9c21edee625b2537be9f37949/basic.ics";
	$calendarOut = "../events.xml";
	
	$calendarPast = 60 * 60 * 24 * 365; // number of seconds in the past to keep events for
	$calendarFuture = 60 * 60 * 24 * 365; // number of seconds in the future to keep events for
	
	require_once "iCalReader.php";
	
	// check calendar file is available
	if (file($calendarIn)) {
		// create xml object
		$xmlCalendar = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<events/>");
		// load events from calendar
		$ical = new iCalReader($calendarIn);
		$events = $ical -> getEvents();
		// sort events chronologically
		uasort($events,"iCalSort");
		// copy event into xml object
		foreach ($events as $event) {
			// only include events in the specified time range
			if (iCalGetEndTime($event) <= time() + $calendarFuture && iCalGetStartTime($event) >= time() - $calendarPast) {
				$xmlEvent = $xmlCalendar -> addChild("event");
				$xmlEvent -> addChild("title",htmlspecialchars($event["SUMMARY"]));
				$xmlEvent -> addChild("description",htmlspecialchars($event["DESCRIPTION"]));
				$xmlEvent -> addChild("location",htmlspecialchars($event["LOCATION"]));
				$xmlEvent -> addAttribute("startTime",iCalGetStartTime($event));
				$xmlEvent -> addAttribute("endTime",iCalGetEndTime($event));
			}
		}
		// output xml file
		if ($xmlCalendar -> asXML($calendarOut))
			echo "Calendar events cached sucessfully - <a href=\"" . $calendarOut . "\">Click for file</a><br/>";
	}
	
	// comparison function for uasort() to sort events by date, i.e. use uasort($events,"iCalSort")
	function iCalSort($eventA,$eventB) {
		$timeA = iCalGetStartTime($eventA);
		$timeB = iCalGetStartTime($eventB);
		if ($timeA < $timeB)
			return -1;
		elseif ($timeA == $timeB)
			return 0;
		elseif ($timeA > $timeB)
			return 1;
	}
	// extract start timestamp from iCal event
	function iCalGetStartTime($event) {
		foreach ($event as $key => $value) {
			if (strpos($key,"DTSTART") === 0)
				return strtotime($value);
		}
	}
	// extract end timestamp from iCal event
	function iCalGetEndTime($event) {
		foreach ($event as $key => $value) {
			if (strpos($key,"DTEND") === 0)
				return strtotime($value);
		}
	}
?>