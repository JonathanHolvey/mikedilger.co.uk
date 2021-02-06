<!DOCTYPE html>
<?php
	$maxTweets = 6;
	$maxEvents = 8;

	$tweetCache = json_decode(file_get_contents("/tmp/cache/tweets.json"), true);
	$eventCache = json_decode(file_get_contents("/tmp/cache/events.json"), true);

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
			<a class="action-link floating" href="https://twitter.com/DilgerTV">follow <i>@DilgerTV</i> on twitter</a>
			<h1>twitter</h1>
			<div class="tweets" data-max-items="<?= $maxTweets ?>">
				<?php
					$tweets = array_slice($tweetCache["tweets"], 0, $maxTweets);
					foreach ($tweets as $tweet) {
						include("templates/tweet.php");
					}
				?>
			</div>
			<a class="action-link" href="https://twitter.com/DilgerTV">follow me on twitter</a>
		</div>
		<div class="half right">
			<h1>coming up</h1>
			<div class="future-events" data-max-items="<?= $maxEvents ?>">
				<?php
					foreach ($futureEvents as $event)
						include("templates/event.php");
					if (count($futureEvents) == 0)
						echo("<div class=\"event placeholder\">(No upcoming events)</div>");
				?>
			</div>
			<div class="past-events">
				<h1>past events</h1>
				<?php
					foreach (array_slice(array_reverse($pastEvents), 0, $maxEvents) as $event)
						include("templates/event.php");
				?>
			</div>
		</div>
	</div>
	<?php include("templates/footer.php"); ?>
	<?php include("templates/defer.php"); ?>
	<script>getUpdates();</script>
</body>
</html>
