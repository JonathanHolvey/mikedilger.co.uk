<?php
	// list of scripts to run as this cron job - note full absolute address must be given
	file_get_contents("http://www.mikedilger.co.uk/script/retrieveEvents.php");
	file_get_contents("http://www.mikedilger.co.uk/script/retrieveTweets.php");
?>