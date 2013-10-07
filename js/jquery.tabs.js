// Tab settings

$(function() {
	var tabContainers = $('div.box > div.content');
	tabContainers.hide().filter(':first').show();
	
	$('div.box > div.nav2 > ul a').click(function () {
		tabContainers.hide();
		tabContainers.filter(this.hash).css('display', '');	
		tabContainers.filter(this.hash).fadeIn();
		$('div.box > div.nav2 > ul li').removeClass('active');
		$(this).parent().addClass('active');
		return false;
	});
	$('div.box div.content').filter(':first').css('display', 'none');
});
