 $(document).on("click",".buy_now",function() {
	  var $this = $(this);
	  $amount = $this.parents('.figure').find(".price").html();
	  $amount = parseFloat($amount.replace(/[^\d\.]/g, ''));
	  $(".pay_amount").val($amount);
	  
	  var $name = $this.parents(".figure").find("figcaption")
	  $(".product_name").val($name);

 });
 
 Stripe.setPublishableKey('pk_live_code');
            function stripeResponseHandler(status, response) {
                if (response.error) {
                    
                    $('.submit-button').removeAttr("disabled");
             
                    $(".payment-errors").html(response.error.message);
                } else {
                    var $amount = $(".pay_amount").val();
					var $name = $(".product_name").val();
					var customer_name = $(".name").val();
					var customer_email = $(".email").val();
                    var token = response['id'];
                     $.ajax({
					   type: "post",
					   url: "./stripe.php",
					   data: {token:token,amount:$amount,name:$name,customer_name:customer_name,customer_email:customer_email},
					   dataType: "json",
					   success: function (response) {
						 if(response.status){
						    
						      $(".payment-success").html("Payment Successfully");
							    $('#payment-form')[0].reset();
								$("#stripeModel").modal('hide')
						 }else{
						  $(".payment-errors").html(response.msg);
						 }
					   }
					 });
                       
                }
            }

            $(document).on("click",".submit-button",function() {
             
                   
				  var valid = cardValidation();

					if(valid == true) {
                   $(".payment-errors").html("");
                    Stripe.createToken({
                        number: $('.card-number').val(),
                        cvc: $('.card-cvc').val(),
                        exp_month: $('.card-expiry-month').val(),
                        exp_year: $('.card-expiry-year').val()
                    }, stripeResponseHandler);
                    return false; // submit from callback
					}
            
				
            });
			
			
			function cardValidation () {
				var valid = true;
				var name = $('.name').val();
			
				var cardNumber = $('.card-number').val();
				var month = $('.card-expiry-month').val();
				var year = $('.card-expiry-year').val();
				var cvc = $('.card-cvc').val();
				var email = $('.email').val();

				$("#error-message").html("").hide();

				if (name.trim() == "") {
					valid = false;
				}
				
				if (cardNumber.trim() == "") {
					   valid = false;
				}

				if (month.trim() == "") {
						valid = false;
				}
				if (year.trim() == "") {
					valid = false;
				}
				if (cvc.trim() == "") {
					valid = false;
				}
				if (email.trim() == ""){
					valid = false;
				}else{
					
						var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
						if (testEmail.test(email)){
							return true;
						}else{
							$(".payment-errors").html("Invalid Email Address");
							return false;
						}
					
				}

				if(valid == false) {
					$(".payment-errors").html("All Fields are required");
				}

				return valid;
			}
			
			function isNumber(evt) {
				evt = (evt) ? evt : window.event;
				var charCode = (evt.which) ? evt.which : evt.keyCode;
				if (charCode > 31 && (charCode < 48 || charCode > 57)) {
					return false;
				}
				return true;
			}