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
});

// create new tweet elements from AJAX response
function tweetUpdates(tweets) {
	var target = $(".tweets");
	tweets.forEach(function(tweet) {
		showItem(tweet.html, target);
	});
}

// create new event elements from AJAX response
function eventUpdates(events) {
	if (events.length > 0)
		$(".event.placeholder").hide();
	var target = $(".future-events");
	events.forEach(function(event) {
		showItem(event.html, target);
	});
}

// show new content and animate using CSS transitions
function showItem(item, target) {
	item = $(item);
	item.addClass("hidden").css("position", "absolute");
	target.prepend(item);
	var height = item.outerHeight();
	item.addClass("collapsed")
		.css("position", "")
		.height(height)
		.removeClass("collapsed");
	item.on("transitionend", function() {
		item.removeClass("hidden")
			.height("");
	});
}