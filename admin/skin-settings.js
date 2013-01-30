jQuery(document).ready(function($) {
	// Image
	$('.skin-img').click(function(){
		$(this).parent().parent().find('.skin-img').removeClass('skin-img-selected');
		$(this).addClass('skin-img-selected');
	});
	$('.skin-img').show();
	$('.skin-option-input').hide();

	// Details
	$('.skin-detail').click(function(){
		$(this).parent().find('.skin-description').toggleClass("skin-description-display");
	});
});