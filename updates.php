<!DOCTYPE html>

<?php
	include_once "script/createUpdates.php";
	$xmlTweets = simplexml_load_file("tweets.xml");
	$xmlEvents = simplexml_load_file("events.xml");
	date_default_timezone_set("Europe/London");
	
	$maxTweets = 6;
	$maxPastEvents = 7;
	$inFuture = 60 * 60 * 24 * 90; // number of seconds in the future to include events for
?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Mike Dilger - Follow</title>
	<meta http-equiv="content-Type" content="text/html; charset=iso-8859-1" />
	<meta name="copyright" content="" />
	<meta name="content-Language" content="english" /> 
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<meta name="author" content="Jonathan Holvey" />

	<?php include("resources.php"); ?>
	<style type="text/css">
		#header .image.left {
			background-image: url('images/banner_05.jpg');
		}
		#header .image.right {
			background-image: url('images/banner_04.jpg');
		}
		#followLink {
			color:#D16F24 !important;
			cursor:default;
		}
	</style>
</head>
<body>
	<?php include("header.php"); ?>
	<div id="content">
		<div class="half left">
			<div style="float:right"><a href=" <?php echo $xmlTweets["link"] ?> "><i>@DilgerTV</i> on twitter</a></div>
			<div class="heading">twitter</div>
			<?php
				$count = 0;
				foreach ($xmlTweets as $tweet) {
					echo createTweet($tweet);
					$count ++;
					if ($count == $maxTweets)
						break;
				}
			?>
			<a href=" <?php echo $xmlTweets["link"] ?> ">follow me on twitter</a>
			
			</div>
		<div class="half right">
			<div class="heading">coming up</div>
			<?php
				$eventFound = false;
				foreach ($xmlEvents as $event) {
					if ($event["endTime"] >= time() && $event["startTime"] <= time() + $inFuture) {
						echo createEvent($event);
						$eventFound = true;
					}
				}
				if (!$eventFound)
					echo "<div class=\"event\">(no upcoming events)</div>";
			?>
			<div class="heading">past events</div>
			<?php
				$events = 0;
				foreach ($xmlEvents as $event) {
					$events ++;
				}
				$count = 0;
				for ($i = $events; $i >= 0; $i--) {
					$event = $xmlEvents -> event[$i - 1];
					if ($event["endTime"] < time()) {
						echo createEvent($event);
						$count ++;
					}
					if ($count == $maxPastEvents)
						break;
				}
			?>
		</div>
	</div>
	<?php include("footer.php"); ?>
</body>
</html>
