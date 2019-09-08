<?php
session_start();

require_once '../Constants/getPath.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// error 500
// if you use "\" to reference a php script instead of "/"
require '../stripe/vendor/autoload.php';

require '../Checkout/app/start.php';
require_once '../Constants/connectvars.php';
require_once '../Constants/header.php';
require_once '../PHPScripts/functions.php';
require '../Constants/phpmailerAuth.php';

if(isset($_SESSION['user_id'])){
	$user_id = $_SESSION['user_id'];
	$user_paypal_creds = get_paypal_credentials($user_id);
	$user_stripe_creds = get_stripe_credentials($user_id);
}
if(!isset($_SESSION['user_id']) && !isset($_GET['id']))
{
	header('Location: ../index');
}

$error_msg = 0;
$restart_page = 0;
$show_confirm_succeed = 0;
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if(isset($_GET['confirm_code']) && isset($_GET['id'])){
	$cc = $_GET['confirm_code'];
	$user_id = $_GET['id'];
	$user_paypal_creds = get_paypal_credentials($user_id);
	if($user_paypal_creds['confirm_code'] == $cc)
	{
		if(!is_paypal_credentials_confirmed($user_id)){
			$query = "UPDATE paypal_credentials SET confirmed = 1 WHERE user_id='$user_id'";
		}
		else
		{
			if(!isset($_SESSION['user_id'])){
				header('Location: ../index');
			}
			else
			{
				header('Location: hubearnings');
			}
		}
		mysqli_query($dbc, $query);
		mysqli_close($dbc);
		$show_confirm_succeed=1;
	}
}
if(isset($_POST['paypalEmailSubmit']))
{
	$paypalEmail = $_POST['paypalEmail'];
	if(!filter_var($paypalEmail, FILTER_VALIDATE_EMAIL))
	{
		$error_msg = 1;
	}
	else
	{
		$confirm_code = uniqid();
		if($user_paypal_creds == NULL){
			$query = "INSERT INTO paypal_credentials (user_id, paypal_email, confirm_code,confirmed)
				VALUES('$user_id', '$paypalEmail', '$confirm_code', 0)";
			mysqli_query($dbc, $query);
			mysqli_close($dbc);
		}
		else
		{
			$query = "UPDATE paypal_credentials SET paypal_email = '$paypalEmail', confirm_code = '$confirm_code', confirmed = 0 WHERE user_id='$user_id'";
			mysqli_query($dbc, $query);
			mysqli_close($dbc);
		}
		$restart_page=1;
		$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
		try {
			//Server settings
			$mail->SMTPDebug = 0;                                 // Enable verbose debug output
			$mail->isSMTP();                                      // Set mailer to use SMTP
			$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
			$mail->Username = 'rolle.cocco.enterprises@gmail.com';                 // SMTP username
			$mail->Password = 'Danjito1';                           // SMTP password
			$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
			$mail->Port = 587;                                    // TCP port to connect to

			//Recipients
			$mail->setFrom('rolle.cocco.enterprises@gmail.com', 'STAPP Earnings Confirm');
			$mail->addAddress($paypalEmail, 'user');     // Add a recipient

			$body =
				'
				<a href="https://www.stappapp.com">
					<img src="cid:stapp_logo" style="display: block; margin: 0 auto;" />
				</a>

				<div style="width:75%; display: block; margin: 0 auto;">
					<p>To confirm that you are the owner of this earnings linked account please click this link:</p>
					<p></p>
					<a href="'.$rootUrl.'user/hubearnings?confirm_code='.$confirm_code.'&id='.$user_id.'">Confirm my earnings method</a>
				</div>
				';
			//Content
			$mail->isHTML(true);                                  // Set email format to HTML
			$mail->Subject = 'Confirm your STAPP earning method';
			$mail->Body    = $body;
			//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

			if (!$mail->send()) {
				$mail->ErrorInfo;
				echo 'error';
			}
		}catch (Exception $e) {
			echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
		 }
	}
}

if(isset($_GET['code'])){

	$code = $_GET['code'];
	$curl = curl_init();

	curl_setopt_array($curl, array(
		CURLOPT_URL => "https://connect.stripe.com/oauth/token",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => "client_secret={$stripe_secret_key}&code={$code}&grant_type=authorization_code",
		CURLOPT_HTTPHEADER => array(
			"cache-control: no-cache"
		),
	));

	$response = curl_exec($curl);
	$json_response = json_decode($response, true);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
		echo "cURL Error #:" . $err;
		die();
	}
	
	$stripe_user_id = $json_response['stripe_user_id'];
	
	$query = "INSERT INTO stripe_credentials (user_id, stripe_user_id) VALUES({$user_id}, '$stripe_user_id')";
	mysqli_query($dbc, $query);
	$restart_page = 1;
}
if($restart_page == 1)
{
	header('Location: hubearnings');
}
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.js"></script>
</head>

<body>
<?php 
	require_once '../Constants/navigation.php'; 
	if($show_confirm_succeed==1){
		echo '<div class="text-center">
				<p><img src="../images/tick_icon.png"></img></p>
				<p class="lead">You have successfully confirmed your Payment Account</p>
				<p class="lead">Please <a href="hubearnings">refresh this page</a></p>
			  </div>';
		require_once '../Constants/fullfooter.php';
		return;
	}
	if($user_paypal_creds['confirmed']==0 && $user_paypal_creds != NULL){
		echo '<div class="alert alert-warning text-center">
				An email has been sent to <b>'.$user_paypal_creds['paypal_email'].'</b> please confirm that is your PayPal Account!
			  </div>';
	}

	

?>
<div class="container">
	<div class="row">
		<div class="col-lg-3 my-5 border-right">
		<?php
			$base_file = basename(__FILE__, '.php');
			require_once '../Constants/settings_navigation.php';
		?>
		</div>
		<div class="col">
			<div class="row">
				<div class="col-lg-4 my-5 ">
					<p class="lead" style="font-size: 32px">Earnings</p>
				</div>
				<div class="col my-5">
					<p class="lead" style="font-size: 32px">Link</p>
					<p class="alert alert-info">To start receiving money, please link your account with one of these available methods</p>	
					<!-- Button trigger modal -->
					<?php
						if($user_paypal_creds == NULL){
							echo '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#paypalModal" style="border-radius: 0.50rem">
							  		Link with PayPal
								  </button>';
						}
						else{
							if($user_paypal_creds['confirmed']==0){
								echo '<div class="d-flex justify-content-between alert alert-secondary text-muted">
										Waiting PayPal Email Confirm
										<button type="button" class="btn btn-primary border rounded" data-toggle="modal" data-target="#paypalModal" style="border-radius: 0.50rem">
											Change Email
										</button>
									  </div>';
							}
							else
							{
								echo '<div class="alert alert-primary lead">
										PayPal Confirmed<br>
										<small>Account <b>'.$user_paypal_creds['paypal_email'].'</b> receiving HUB Payments</small>
									  </div>';

							}
						}
						if($user_stripe_creds == NULL){
							echo '<p class="my-3">';
							echo '<a href="https://connect.stripe.com/express/oauth/authorize?client_id='.$client_id.'&state='.uniqid().'">';
							echo '<img src="/images/StripeConnectbutton/light-on-dark.png" />'; 
							echo '</a>';
							echo '</p>';
						}
						else{
							echo '<div class="alert alert-primary lead">
										Stripe Confirmed<br>
									  </div>';
						}
					?>
					<!-- Modal -->
					<div class="modal fade" id="paypalModal" tabindex="-1" role="dialog" aria-labelledby="paypalModalTitle" aria-hidden="true">
						<div class="modal-dialog modal-dialog-centered" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="paypalModalLongTitle">Link PayPal Account</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<form action="" method="post">
									<div class="modal-body">
										<p class="alert alert-warning">Please ensure that you write the correct <b>PayPal Email Address</b> of your account to Receive Payments.</p>
										<input class="form-control" type="text" name="paypalEmail" placeholder="PayPal Email" />
									</div>
									<div class="modal-footer">
										<button type="submit" class="btn btn-primary" name="paypalEmailSubmit">Save</button>
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									</div>
								</form>
							</div>
						</div>
					</div>
					<!-- End Button trigger modal -->

				<!--<canvas id="myChart" style="max-width: 100%; max-height: 50%;"></canvas>-->
				</div>

			</div>

		</div>

	</div>

</div>
<?php require_once '../Constants/fullfooter.php'; ?>
<script>
/*var ctx = document.getElementById("myChart").getContext('2d');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ["Oct", "Nov", "Dec", "Jan"],
        datasets: [{
            label: '$ earned',
            data: [12, 19, 3, 3],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)'
            ],
            borderColor: [
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
});*/
</script>

</body>
</html>

