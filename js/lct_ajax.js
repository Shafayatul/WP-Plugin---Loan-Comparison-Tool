jQuery(document).ready(function(){


	// Ajax for make primary address -- my account page
	jQuery("#lct-search").click(function(){
		jQuery.ajax({ //ajax request
			url: lctAjax.ajaxurl,
			type: "POST",
			data: {
				'action':'lct_data_search',
				'amount' : jQuery('input[name=amount]').val(),
				'state' : jQuery('#state :selected').val(),
				'purpose' : jQuery('#purpose :selected').val(),
				'security' : lctAjax.ajax_nonce,
			},
			success:function(data) {  //result
				console.log(data);
				jQuery('.demo-ouput').html(data);
			},
			error: function(errorThrown){
				console.log(errorThrown);
			}
		}); 		
	}); 		
	jQuery("#compare-selected").click(function(){
		var checkedId = '';
		jQuery('.checked-lender').each(function(){
			if (jQuery(this).is(":checked")) {
				checkedId = jQuery(this).attr('id')+','+checkedId;
			}
		});
		window.location.replace("/selected-compare/?ids="+checkedId);
/*		jQuery.ajax({ //ajax request
			url: lctAjax.ajaxurl,
			type: "POST",
			data: {
				'action':'lct_data_search',
				'amount' : jQuery('input[name=amount]').val(),
				'state' : jQuery('#state :selected').val(),
				'purpose' : jQuery('#purpose :selected').val(),
				'security' : lctAjax.ajax_nonce,
			},
			success:function(data) {  //result
				console.log(data);
				jQuery('.demo-ouput').html(data);
			},
			error: function(errorThrown){
				console.log(errorThrown);
			}
		}); */		
	}); 		


});