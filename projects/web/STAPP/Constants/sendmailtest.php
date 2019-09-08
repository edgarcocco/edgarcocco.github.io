
<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'getPath.php';
require_once 'header.php';
require_once 'connectvars.php';
require('phpmailerAuth.php');
require_once '../PHPScripts/functions.php';

$email = "blasterkid111@gmail.com";
$plan = "regular";

$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
	try {
		//Server settings
		$mail->SMTPDebug = 0;                                 // Enable verbose debug output
		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = 'rolle.cocco.enterprises@gmail.com';                 // SMTP username
		$mail->Password = '2501411125014111';                           // SMTP password
		$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
		$mail->Port = 587;                                    // TCP port to connect to

		//Recipients
		$mail->setFrom('rolle.cocco.enterprises@gmail.com', 'STAPP Invoice');
		$mail->addAddress($email, 'user');     // Add a recipient

		$mail->AddEmbeddedImage('../Images/stapp_nocolors.png', 'stapp_logo');

		if($plan == 'regular'){
			$body =
				'
				<a href="https://www.stappapp.com">
					<img src="cid:stapp_logo" style="display: block; margin: 0 auto;" />
				</a>
				
				<div style="width:50%; display: block; margin: 0 auto;">
					<h1 style="text-align: center;">STAPP Invoice</h1>
					<p><b>Order ID:</b>EC-16516516565</p>
					<p><b>Order Date:</b>Today</p>
					<p><b>Auto renew:</b> Yes</p>
					<p><b>Next auto renew date:</b> Next Month</p>
					<hr />
					<div style="margin-top: 10px; margin-bottom:10px">
						<span style="font-size: 16px"><b>Plan</b></span> 
						<span style="float: right; color:gray">Regular (Monthly Plan)</span>
					</div>
					<div style="margin-top: 10px; margin-bottom:10px">
						<span style="font-size: 16px"><b>Cost</b></span> 
						<span style="float: right; color:gray">10.00$ USD</span>
					</div>
					<div style="margin-top: 10px; margin-bottom:10px">
						<span style="font-size: 16px"><b>Fee</b></span> 
						<span style="float: right; color:gray">0.00$ USD</span>
					</div>
					<div style="margin-top: 10px; margin-bottom:10px">
						<span style="font-size: 22px"><b>Subtotal</b></span> 
						<span style="float: right; color:gray">10.00$ USD</span>
					</div>
					<hr />
					<div style="margin-top: 10px; margin-bottom:10px">
						<span style="font-size: 22px"><b>Total</b></span> 
						<span style="font-size: 22px; float: right; color:gray">10.00$ USD</span>
					</div>
				</div>
				' . '<p>#'.uniqid() .'</p>';
			$boughtPlan = 'Regular';
		}
		//Content
		$mail->isHTML(true);                                  // Set email format to HTML
		$mail->Subject = 'Your invoice for ' . $boughtPlan . '(Monthly Plan) #' . uniqid();
		$mail->Body    = $body;
		//$mail->AltBody = 'STAPP Invoice ';

		if (!$mail->send()) {
			$mail->ErrorInfo;
		}
		else{
			echo '<p class="text-success">Email has been sent.</p>';
		}
	} catch (Exception $e) {
		echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
	}

	
?>
