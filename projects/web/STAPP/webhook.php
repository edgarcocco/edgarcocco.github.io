<?php 

require_once 'Constants/getPath.php';
require 'phpmailer/vendor/autoload.php';

//PATH FOR THE CONSOLE TO FIND OUT PROJECT
$rootPath = "../htdocs/STAPP/";
require_once 'PHPScripts/functions.php';

use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\Exception;

/*
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD','rc25014111');
define('DB_NAME','stappdb');
*/
//define('DB_HOST', 'localhost');
//define('DB_USER', 'Edo');
//define('DB_PASSWORD','legit2013');
//define('DB_NAME','stappdb');

$hub_subscribers = get_hub_subscribers();
while($row = mysqli_fetch_array($hub_subscribers))
{
	$subscriber_id = $row['id'];
	$now = time();
	$next_cycle_date = strtotime($row['next_cycle_date']);
	$social_email_address = $row['social_email_address'];
	$notified = $row['next_cycle_notified'];
	$timeleft = $next_cycle_date - $now;
	if($timeleft < 0)
	{
		if($notified == 0)
		{
			$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
			try {
				//Server settings
				$mail->SMTPDebug = 2;                                 // Enable verbose debug output
				$mail->isSMTP();                                      // Set mailer to use SMTP
				$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
				$mail->SMTPAuth = true;                               // Enable SMTP authentication
				$mail->Username = 'rolle.cocco.enterprises@gmail.com';                 // SMTP username
				$mail->Password = 'Danjito1';                           // SMTP password
				$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
				$mail->Port = 587;                                    // TCP port to connect to

				//Recipients
				$mail->setFrom('rolle.cocco.enterprises@gmail.com', 'STAPP');
				$mail->addAddress($social_email_address, 'user');     // Add a recipient

				$body = 'Your subscription has ended! Please renew';

				//Content
				$mail->isHTML(true);                                  // Set email format to HTML
				$mail->Subject = '';
				$mail->Body    = $body;

				if (!$mail->send()) {
					$mail->ErrorInfo;
				}
				$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
				$query = "UPDATE hub_plan_subscribers SET next_cycle_notified=1 WHERE id={$subscriber_id}";
				mysqli_query($dbc, $query);
				mysqli_close($dbc);
			} catch (Exception $e) {
				echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
			}
		}
	}
}

$stapp_users = get_all_user_rows();
$google_users = get_all_google_rows();
while($row = mysqli_fetch_array($stapp_users))
{
	$user_id = $row['user_id'];
	$expired = checkPlanExpired($user_id);
	$user_plan = getPlanRow($user_id);
	if($expired == 1 && $user_plan['expired_notified'] == 0)
	{
		$body = 'Your STAPP Plan Subscription has ended please renew by going to <a href="https://stappapp.com/pricing">Pricing</a>';
		send_email(0, $row['email'], "Your Subscription with STAPP has ended!",$body);
		$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		$query = "UPDATE user_plan SET expired_notified=1 WHERE user_id=$user_id";
		mysqli_query($dbc, $query);
		mysqli_close($dbc);
	}
}

while($row = mysqli_fetch_array($google_users))
{
	$user_id = $row['user_id'];
	$expired = checkPlanExpired($user_id);
	$user_plan = getPlanRow($user_id);
	if($expired == 1 && $user_plan['expired_notified'] == 0)
	{
		$body = 'Your STAPP Plan Subscription has ended please renew by going to <a href="https://stappapp.com/pricing">Pricing</a>';
		send_email(0, $row['email'], "Your Subscription with STAPP has ended!",$body);
		$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		$query = "UPDATE user_plan SET expired_notified=1 WHERE user_id=$user_id";
		mysqli_query($dbc, $query);
		mysqli_close($dbc);
	}
}

?>
