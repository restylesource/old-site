$(document).ready(function() {
	$.ajax({
	    url: "/ajax/browser-not-supported.php",
	    success: function (data) { $('body').append(data); },
	    dataType: 'html'
	});
});