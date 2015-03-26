$('#sidebar').affix({
	offset: {
		top: $("#content").offset().top
	}
});

$("body").scrollspy({
	target: '#sidecol'
});

$("body").on("load", function() {
	$("body").scrollspy("refresh");
});