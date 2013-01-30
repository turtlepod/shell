// Mobile Menu
jQuery(document).ready(function($){

	// Primary Menu
	$("#menu-primary #menu-icon").click(function(){
		$("#menu-primary .wrap").fadeToggle();
		$(this).toggleClass("active");
	});
	// Secondary Menu
	$("#menu-secondary #menu-icon").click(function(){
		$("#menu-secondary .wrap").fadeToggle();
		$(this).toggleClass("active");
	});

});
