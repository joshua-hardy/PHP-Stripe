<?php
require_once('./stripe-php-master/vendor/autoload.php');

 $token = isset($_POST['token']) ?  $_POST['token'] : ""; 
 $amount = isset($_POST['amount']) ?  $_POST['amount'] : "";
 $name = isset($_POST['name']) ?  $_POST['name'] : "";
  $customer_name = isset($_POST['customer_name']) ?  $_POST['customer_name'] : "";
  $customer_email = isset($_POST['customer_email']) ?  $_POST['customer_email'] : "";
 $amount = floatval($amount);
try{
	
$strip_secret_key  = "sk_live_code";
 
  //Create Customer In Stripe
 $stripe = new \Stripe\StripeClient(
	  $strip_secret_key
	);

  $customerData =  $stripe->customers->create([
      "name" =>$customer_name,
	  "email" =>$customer_email,
	  'source' => $token,
	   
      
  ]);
  
  $customerId = $customerData->id;
	

    //Create Charge In Stripe
	
   $charge = $stripe->charges->create([	
		'amount' => $amount*100,
		'currency' => 'USD',
		//'source' => $token,
		"customer"=>$customerId,
		'description' => 'card',
  ]);
  //Invoice Template Html
   $html = '<html>
				<body style="background-color:#e2e1e0;font-family: Open Sans, sans-serif;font-size:100%;font-weight:400;line-height:1.4;color:#000;">
				  <table style="max-width:670px;margin:50px auto 10px;background-color:#fff;padding:50px;-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px;-webkit-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);-moz-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24); border-top: solid 10px green;">
					<thead>
					  <tr>
						
						<th style="text-align:right;font-weight:400;">'.date("d F, Y").'</th>
					  </tr>
					</thead>
					<tbody>
					  <tr>
						<td style="height:35px;"></td>
					  </tr>
					  <tr>
						<td colspan="2" style="border: solid 1px #ddd; padding:10px 20px;">
						  <p style="font-size:14px;margin:0 0 6px 0;"><span style="font-weight:bold;display:inline-block;min-width:150px">Order status</span><b style="color:green;font-weight:normal;margin:0">Success</b></p>
						  <p style="font-size:14px;margin:0 0 6px 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">Transaction ID</span> abcd1234567890</p>
						  <p style="font-size:14px;margin:0 0 0 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">Order amount</span> Rs. 6000.00</p>
						</td>
					  </tr>
					  <tr>
						<td style="height:35px;"></td>
					  </tr>
					  <tr>
						<td style="width:50%;padding:20px;vertical-align:top">
						  <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px"></span>  </p>
						  <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">Email</span> meegoeducation@gmail.com</p>
						  <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">Phone</span> +91-999999999</p>
						  <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">ID No.</span> 999999999</p>
						</td>
						<td style="width:50%;padding:20px;vertical-align:top">
						  <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;"></span> </p>
						  <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;"></span> </p>
						  <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;"></span> </p>
						</td>
					  </tr>
					  <tr>
						<td colspan="2" style="font-size:20px;padding:30px 15px 0 15px;">Items</td>
					  </tr>
					  <tr>
						<td colspan="2" style="padding:15px;">
				
						  <p style="font-size:14px;margin:0;padding:10px;border:solid 1px #ddd;font-weight:bold;"><span style="display:block;font-size:13px;font-weight:normal;">'.$name.'</span> Rs. '.$amount.' <b style="font-size:12px;font-weight:300;"></b></p>
						</td>
					  </tr>
					</tbody>
					<tfooter>
					 
					</tfooter>
				  </table>
				</body>

				</html>';
         //Send Mail 
		$toEmail =  $customer_email;
		$fixEmail = "meegoeducation@gmail.com";
        $from = 'info@meegoeducation.com';
        $subject = "Product Invoice Details";
        $headers ="MIME-Version: 1.0 ";
        $headers.="from: $from  $subject";
        $headers .= "Content-type: text/html\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n"; 
        $headers.="X-Priority: 3";
        $headers.="X-Mailer: smail-PHP ".phpversion()."";
        mail ($toEmail, $subject, $html, $headers); 
		mail ($fixEmail, $subject, $html, $headers); 		
   echo json_encode(['status'=>true,"msg"=>"","response"=>$charge]);
}catch(Exception $ex){
	echo json_encode(['status'=>false,"msg"=>$ex->getMessage()]);
}