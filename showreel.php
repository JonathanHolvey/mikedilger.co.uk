<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Showreel - Mike Dilger</title>
	<meta http-equiv="content-Type" content="text/html; charset=iso-8859-1" />
	<meta name="copyright" content="" />
	<meta name="content-Language" content="english" /> 
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<meta name="author" content="Jonathan Holvey" />

	<?php include("templates/resources.php"); ?>
	<style type="text/css">
		header .image.left {
			background-image: url('images/banner_12.jpg');
		}
		header .image.right {
			background-image: url('images/banner_24.jpg');
		}
	</style>
</head>
<body>
	<?php include("templates/header.php"); ?>
	<div class="content">
		<iframe style="width:800px;height:475px" src="https://www.youtube.com/embed/kmQrAsXBRV4?rel=0&amp;autoplay=1&amp;controls=0&amp;showinfo=0"" frameborder="0"></iframe>
	</div>
	<?php include("templates/footer.php"); ?>
	<?php include("templates/defer.php"); ?>
	<script>
		// make embedded youtube videos responsive
		var $videos = $("iframe[src*='//www.youtube.com']"),
		$container = $(".content");
		$videos.each(function() {
			$(this)
				.data("aspectRatio", $(this).height() / $(this).width())
				// remove hard coded width and height
				.removeAttr("height")
				.removeAttr("width");
			console.log($(this).width);
		});

		$(window).resize(function() {
			// resize all videos according to their aspect ratio
			 $videos.each(function() {
				var width = $container.width() - $(this).outerWidth(true) + $(this).width();
				var $el = $(this);
				$el
					.width(width)
					.height(width * $el.data("aspectRatio"));
			});
		}).resize();
	</script>
</body>
</html>