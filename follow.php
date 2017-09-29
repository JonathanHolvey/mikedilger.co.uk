<!DOCTYPE html>
<?php
	$maxTweets = 6;
	$maxEvents = 8;

	$tweetCache = json_decode(file_get_contents("cache/tweets.json"), true);
	$eventCache = json_decode(file_get_contents("cache/events.json"), true);

	$futureEvents = array();
	$pastEvents = array();
	foreach ($eventCache["events"] as $event) {
		if ($event["end"] > time())
			$futureEvents[] = $event;
		else
			$pastEvents[] = $event;
	}
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Follow - Mike Dilger</title>
	<meta http-equiv="content-Type" content="text/html; charset=iso-8859-1" />
	<meta name="copyright" content="" />
	<meta name="content-Language" content="english" /> 
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<meta name="author" content="Jonathan Holvey" />

	<?php include("templates/resources.php"); ?>
	<style type="text/css">
		header .image.left {
			background-image: url('images/banner_05.jpg');
		}
		header .image.right {
			background-image: url('images/banner_04.jpg');
		}
		#follow-link {
			color:#D16F24 !important;
			cursor:default;
		}
	</style>
</head>
<body>
	<?php include("templates/header.php"); ?>
	<div class="content">
		<div class="half left">
			<div style="float:right"><a href="https://twitter.com/DilgerTV"><i>@DilgerTV</i> on twitter</a></div>
			<h1>twitter</h1>
			<?php 
				$tweets = array_slice($tweetCache["tweets"], 0, $maxTweets);
				include("templates/tweets.php");
			?>
			<a href="https://twitter.com/DilgerTV">follow me on twitter</a>
		</div>
		<div class="half right">
			<h1>coming up</h1>
			<?php
				$events = $futureEvents;
				if (count($events) > 0)
					include("templates/events.php");
				else
					echo("[No up-coming events]");
			?>
			<h1>past events</h1>
			<?php
				$events = array_slice(array_reverse($pastEvents), 0, $maxEvents);
				if (count($events) > 0)
					include("templates/events.php");
				else
					echo("[No recent events]");
			?>
		</div>
	</div>
	<?php include("templates/footer.php"); ?>
	<?php include("templates/defer.php"); ?>
</body>
</html>
