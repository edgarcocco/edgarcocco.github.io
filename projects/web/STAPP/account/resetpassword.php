<?php
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	session_start();
	require_once '../Constants/getPath.php';
	require_once('../Constants/header.php');
	require_once('../PHPScripts/functions.php');
	require('../Constants/phpmailerAuth.php');

	if(isset($_SESSION['user_id'])){
		$home_url = $rootUrl;
		header('Location: ' . $home_url);
		return;
	}
	$result="";
	$color = "black";
	if(isset($_POST['submit']) && isset($_POST['email'])){
		$email = $_POST['email'];
		if(!emailExists($email)){
			$result = 'This email is not registered.';
			$color = 'red';
		}
		else{
			$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

			$query = "SELECT * FROM password_reset WHERE email = '$email' AND valid=1";
			$data = mysqli_query($dbc, $query);
			if(mysqli_num_rows($data) == 1) {
				$result = 'An email was already sent to this account.';
				$color = 'red';
			}
			else
			{
				$auth = hash("sha256",uniqid());
				$query = "INSERT INTO password_reset (email, auth, valid)" .
					"VALUES ('$email', '$auth', 1)";
				mysqli_query($dbc, $query);

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
					$mail->setFrom('rolle.cocco.enterprises@gmail.com', 'STAPPSupport');
					$mail->addAddress($email, 'user');     // Add a recipient


					if($debug == true)
						$body = '<a href="http://localhost/STAPP1/account/newpassword.php?auth=' . $auth . '">Click this link to reset your password</a>';
					else
						$body = '<a href="https://stappapp.com/account/newpassword.php?auth=' . $auth . '">Click this link to reset your password</a>';

					//Content
					$mail->isHTML(true);                                  // Set email format to HTML
					$mail->Subject = 'Password reset!';
					$mail->Body    = $body;
					$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

					if (!$mail->send()) {
						$mail->ErrorInfo;
					}
					else{
						$result = 'Email has been sent, reset password email is valid within 24 hours.';
						$color = 'green';
					}
				} catch (Exception $e) {
					echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
				}
			}
		}
	}
?>
<link href="../css/signin.css" rel="stylesheet">
</head>

<body>
	<div class="container text-center">
		<img class="mb-4" src="..\images\stapp_nocolors.png" alt="STAPP Logo" width="72" height="72">
		<div class="align-items-center">
		<?php if(!empty($error_msg)) printf('<div class="alert alert-danger" role="alert">%s</div>', $error_msg); ?>
	  	<h1 class="h3 mb-3 font-weight-normal">Reset Password</h1>
		<p class="text-center" style="color: <?php echo $color ?>" ><?php echo $result ?></p>
		<form class="form-signin border rounded" method="post" action="">
		  <p>Enter your email address in order to send you a link to reset password.</p>
		  <label for="email" class="sr-only">Email address</label>
		  <input type="email" id="email" name="email" class="form-control mb-3" placeholder="Email address" required autofocus>
		  <button class="btn btn-success btn-block" type="submit" name="submit">Send reset password email</button>
		</form>
		</div>
	</div>
</body>
</html>



