jQuery(document).ready(function($) {

	// Image Options
	$('.skin-img').click(function(){
		$(this).parent().parent().find('.skin-img').removeClass('skin-img-selected');
		$(this).addClass('skin-img-selected');
	});
	$('.skin-img').show();
	$('.skin-option-input').hide();

});