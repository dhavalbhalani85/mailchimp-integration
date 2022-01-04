(function($){
	$('.subscribe_email').on('click', function( event ){
		$("#vet_funder_user_email_subscribe_form").trigger("reset");
		$('.user-email-subscribe').show();
		event.preventDefault();
		var offering_name = $(this).data('offering-list');
		var post_id = $(this).data('post-id');
		$('.loguser_user_subscribe_email').data('offering-list', offering_name);
		$('.loguser_user_subscribe_email').data('post-id', post_id);
		$('.user-email-subscribe').addClass('is-visible');
	});

	//close popup
	jQuery('.olive-popup').on('click', function( event ){
		if( jQuery(event.target).is('.olive-popup-close') || jQuery(event.target).is('.olive-popup') ) {
			event.preventDefault();
			jQuery(this).removeClass('is-visible');
		}
	});

	//close popup when clicking the esc keyboard button
	jQuery(document).keyup(function( event ){
		if( event.which == '27' ){
			jQuery( '.olive-popup' ).removeClass( 'is-visible' );
		}
	});

	/* ajax call*/
	$(document).ready(function() {
		var email_validation = $("#vet_funder_user_email_subscribe_form").validate({
			rules: {
			    subscriber_email: {required: true, email:true},
			},
			messages: {
			    subscriber_email: {required: 'Please Enter Email', email: "Please enter valid email."},
			}
		});

		
		$('.loguser_user_subscribe_email').click(function(){
		//$("#content").on("click",".loguser_user_subscribe_email", function(){
			
			$('.vt-subscribe-notify-bar').empty();
			var offering_name = $(this).data('offering-list');
			var post_id = $(this).data('post-id');
			
			if($("#vet_funder_user_email_subscribe_form").valid()){
				var ajax_url = vetfunder_localize_script.admin_url;
	 			var email = $("#subscriber_email").val();
	 			if(email !='' ){
	 				var sub_email = email;
	 			}else{
	 				var sub_email = '';
	 			}
			    $.ajax({
				    type:"POST",
				    url:ajax_url,
				    dataType: "json",
				    data:{action:"add_email_mailchimp_list",sub_email:sub_email, offering_name:offering_name,post_id:post_id},
				    success: function(response) {
				    	jQuery('.olive-popup').removeClass('is-visible');
			       		if(response.flag = 1 ){
			       			//$('Thank You For Subscribing Our Champaign').insertAfter( '.vt-subscribe-notify-bar' );
			       			setTimeout(function() {
							    $(".vt-subscribe-notify-bar").show();
							    $(".vt-subscribe-notify-bar").append('<i class="fa fa-check" aria-hidden="true"></i>'+response.msg);
							}, 500);
							setTimeout(function() {
							     $(".vt-subscribe-notify-bar").hide();
							}, 5500);
				        }else{
				        	setTimeout(function() {
							    $(".vt-subscribe-notify-bar").show();
							    $(".vt-subscribe-notify-bar").append('<i class="fas fa-exclamation-triangle" aria-hidden="true"></i>'+response.msg);
							}, 500);
							setTimeout(function() {
							     $(".vt-subscribe-notify-bar").hide();
							}, 5500);
				        }
				    }
		    	});
			}else{
				email_validation.focusInvalid();
			}

		});

	});
	
})(jQuery);