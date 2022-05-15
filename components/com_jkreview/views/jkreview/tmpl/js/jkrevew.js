jQuery(document).ready(function(){
	
	jQuery('.rating').map(function(){
		jQuery(this).starRating({
			initialRating: jQuery(this).find('input').val(),
			starSize: 15,
			ratedColor: '#ffb700',
			activeColor: '#ffb700',
			useGradient: false
		});
		jQuery(this).starRating('setReadOnly', true);
	}); 

	jQuery('.totalRating').map(function(){
		jQuery(this).starRating({
			initialRating: jQuery(this).find('input').val(),
			starSize: 35,
			ratedColor: '#FFD800',
			activeColor: '#FFD800',
			useGradient: false
		});
		jQuery(this).starRating('setReadOnly', true);
	}); 
	
	jQuery('#rating').starRating({
		initialRating: 3.5,
		starSize: 15,
		ratedColor: '#ffb700',
		useGradient: false,
		activeColor: '#ffb700',
		disableAfterRate: false,
		callback: function(currentRating){
			jQuery('input[name="rating"]').val(currentRating);
		}
	});
	
	jQuery('input[name="tоkеnсsrf"]').attr("name","tokencsrf");
	jQuery('input[name="tokencsrf"]').val(jQuery.cookie('tokencsrf'));

});