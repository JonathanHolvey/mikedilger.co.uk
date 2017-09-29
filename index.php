<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Mike Dilger</title>
	<meta http-equiv="content-Type" content="text/html; charset=iso-8859-1" />
	<meta name="copyright" content="" />
	<meta name="content-Language" content="english" /> 
	<meta name="description" content="Mike Dilger, current and past TV presenting projects, pictures, background information and contact details." />
	<meta name="keywords" content="Mike Dilger, Michael Dilger, mike, dilger, naturalist, botanist, birder, ornithologist, presenter, natural history, BBC, Channel 5, wildlife, Nature's Calendar, Hands on Nature, Springwatch, Inside Out, Nature of Britain, Bristol, Natural History Unit, tv, television" />
	<meta name="created" content="December 2006" />
	<meta name="updated" content="August 2010" />
	<meta name="author" content="Jonathan Holvey" />
	<meta name="google-site-verification" content="9SDLuvd4cL9pf6-MXQ4DQT6UUCRVAXtqKM2vlhPlZss" />

	<?php include("templates/resources.php"); ?>
	<style type="text/css">
		header .image.left {
			background-image :url('images/banner_02.jpg');
		}
		header .image.right {
			background-image :url('images/banner_11.jpg');
		}
		#home-link {
			color:#D16F24 !important;
			cursor:default;
		}
	</style>
</head>
<body>
	<?php include("templates/header.php"); ?>
	<div class="content">
		<div class="third left">
			<h1>about me</h1>
			<p>I am an ecologist, natural history presenter and writer. I have a life-long passion for British and tropical flora and fauna, of which I have profound experience and encyclopaedic knowledge.</p>
			<p>I am committed to bringing the beauty of the natural world to a broader audience with enthusiasm and insight, rather than sensation or gimmicks.</p>
			<div class="play-link"><a href="showreel">watch my showreel</a></div>
			<div style="clear:both"><a href="updates">find out what I'm up to</a></div>
		</div>
		<div class="third middle">
			<img src="images/photo_wall.jpg" alt="" class="noTitle"/>
		</div>
		<div class="third right">
			<h1>twitter</h1>
			<?php
				$tweetCache = json_decode(file_get_contents("cache/tweets.json"), true);
				$tweets = array_slice($tweetCache["tweets"], 0, 1);
				include("templates/tweets.php");
			?>
			<div style="margin:-10px 0 10px 0"><?php echo "<a href=\"" . $xmlTweets["link"] . "\">follow <i>@DilgerTV</i> on twitter</a>" ?></div>
			<h1>coming up</h1>
			<?php
				$eventCache = json_decode(file_get_contents("cache/events.json"), true);
				$futureEvents = array();
				foreach ($eventCache["events"] as $event) {
					if ($event["end"] > time())
						$futureEvents[] = $event;
				}
				$events = array_slice(array_reverse($futureEvents), 0, 1);
				if (count($events) > 0)
					include("templates/events.php");
				else
					echo("[No up-coming events]<br/>")
			?>
			<a href="follow">see more updates</a>
		</div>
	</div>
	<?php include("templates/footer.php"); ?>
	<?php include("templates/defer.php"); ?>
</body>
</html>
