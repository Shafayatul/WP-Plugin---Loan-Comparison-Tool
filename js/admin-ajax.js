jQuery(document).ready(function(){
	var split = location.search.replace('?', '').split('=')
	if(split[1]=='lender'){
		jQuery.ajax({
			url: ajaxurl,
			type: "POST",
			data: {
				'action':'lct_get_available_lender'
			},
			success:function(data) {  //result
				// console.log(data);
				jQuery('#post-body-content').append(data);
				jQuery('input[name="post_title"]').val(jQuery('#lender-new :selected').val());
				jQuery('#title-prompt-text').html('');
				jQuery('#title:first').focus();
			},
			error: function(errorThrown){
				console.log(errorThrown);
			}
		}); 		
	}
	if (split[1]=='product') {
		jQuery.ajax({
			url: ajaxurl,
			type: "POST",
			data: {
				'action':'lct_get_available_product'
			},
			success:function(data) {  //result
				jQuery('#titlewrap').append(data);
				jQuery('input[name="post_title"]').val(jQuery('#lender-new :selected').val());
				jQuery('#title-prompt-text').html('');
				jQuery('#title:first').focus();
			},
			error: function(errorThrown){
				console.log(errorThrown);
			}
		}); 	
	}

	jQuery(document).on('change', '#lender-new', function(){
		jQuery('input[name="post_title"]').val(jQuery('#lender-new :selected').val());
		jQuery('input[name="post_title"]').focus();
	});
	jQuery(document).on('change', '#product-new', function(){
		jQuery('input[name="post_title"]').val(jQuery('#product-new :selected').val());
		jQuery('input[name="post_title"]').focus();
	});

});