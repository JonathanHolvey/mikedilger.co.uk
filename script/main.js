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