$(document).ready(function() {
	// make play button clickable
	$(".playLink").click(function() {
		location.href = $(this).children("a").attr("href");
	});
	// make buttons clickable
	$(".button").click(function() {
		location.href = $(this).children("a").attr("href");
	});
	
	// load picture information and add title attributes to images
	var xmlPictures;
	$.ajax({
		type:"GET",
		url:"photos.xml",
		dataType:"xml",
		success:function(xml) {
			// for images
			$("img:not(.noTitle)").each(function() {
				var img = this;
				var src = $(img).attr("src").replace("images/","");
				$(xml).find("photo").each(function() {
					if ($(this).attr("src") == src) {
						$(img).attr("title",$(this).attr("title"));
						return false;
					}
				});
			});
			// for header backgrounds
			$("header .image").each(function() {
				var img = this;
				var back = $(img).css("backgroundImage");
				var src = back.substring(back.indexOf("/images/") + 8).replace("\")","").replace(")","");
				$(xml).find("photo").each(function() {
					if ($(this).attr("src") == src) {
						$(img).attr("title",$(this).attr("title"));
						return false;
					}
				});
			});
		}
	});
	
	// show extra content
	$(".showMore").click(function() {
		$(".more").show();
		$(this).hide();
		var offset = $(".more").offset();
		scrollTo(offset.left,offset.top);
	});

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
});