jQuery(document).ready(function(){


	// make bootstrap model work
	jQuery(".email-shortlist").click(function(){
	  jQuery('#myModal').modal('show');
	});


	// lct-send-email
	// jQuery(".lct-send-email").click(function(){
	jQuery(document).on('click', ".lct-send-email", function(){
		var email = jQuery("#lct-email"). val();
		var name = jQuery("#lct-name"). val();
		var ids = jQuery("#hidden-ids"). val();
		jQuery.ajax({ //ajax request
			url: lctAjax.ajaxurl,
			type: "POST",
			data: {
				'action':'lct_send_email',
				'email': email,
				'name': name,
				'ids': ids,
				'security' : lctAjax.ajax_nonce,
			},
			success:function(data) {  //result
				console.log(data);
				if (data=='done') {
					jQuery('.alert-success').show();
					jQuery('.alert-danger').hide();
				}else{
					jQuery('.alert-success').hide();
					jQuery('.alert-danger').show();	
				}
			},
			error: function(errorThrown){
				console.log(errorThrown);
			}
		}); 		
	});

	// Ajax for make primary address -- my account page
	
	jQuery(".lct-filter").click(function(e){
		e.preventDefault();
		var ReverseMortgage = jQuery("input[name='ReverseMortgage']:checked"). val();
		var NoDoc = jQuery("input[name='NoDoc']:checked"). val();
		var LowDoc = jQuery("input[name='LowDoc']:checked"). val();
		var Equity = jQuery("input[name='Equity']:checked"). val();
		var CreditImpaired = jQuery("input[name='CreditImpaired']:checked"). val();
		var FixedRate = jQuery("input[name='FixedRate']:checked"). val();
		var FixedPeriod = jQuery("input[name='FixedPeriod']:checked"). val();
		jQuery.ajax({ //ajax request
			url: lctAjax.ajaxurl,
			type: "POST",
			data: {
				'action':'lct_data_search',
				'amount' : jQuery('#hidden-amount').val(),
				'state' : jQuery('#hidden-state').val(),
				'purpose' : jQuery('#hidden-purpose').val(),
				'ReverseMortgage': ReverseMortgage,
				'NoDoc': NoDoc,
				'LowDoc': LowDoc,
				'Equity': Equity,
				'CreditImpaired': CreditImpaired,
				'FixedRate': FixedRate,
				'FixedPeriod': FixedPeriod,
				'security' : lctAjax.ajax_nonce,
			},
			success:function(data) {  //result
				jQuery('.search-result').html(data);
			},
			error: function(errorThrown){
				console.log(errorThrown);
			}
		}); 		
	}); 		
	jQuery("#compare-selected").click(function(){
		var checkedId = '';
		var selectCount=0;
		jQuery('.checked-lender').each(function(){
			if (jQuery(this).is(":checked")) {
				selectCount++;
				checkedId = jQuery(this).attr('id')+','+checkedId;
			}
		});
		if (selectCount == 0) {
			alert('Please select atleast 2 product to compare.');
		}else if (selectCount<2) {
			alert('You have to select 2-3 product to compare.');
		}else if(selectCount>3){
			alert('You can compare maximum 3 products.');
		}else{
			window.location.replace("/selected-compare/?ids="+checkedId);
		}	
	}); 		


});