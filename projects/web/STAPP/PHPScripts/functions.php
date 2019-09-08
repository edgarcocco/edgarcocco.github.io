<?php
require $rootPath.'phpmailer/vendor/autoload.php';
require_once($rootPath . 'Constants/connectvars.php');
use PHPMailer\PHPMailer\PHPMailer;

function getUserId($email){
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	$query = "SELECT * FROM " . TABLE_NAME . " WHERE email = '$email'";
	$data = mysqli_query($dbc, $query);

	if(mysqli_num_rows($data) == 1)
	{
		$row = mysqli_fetch_array($data);
		return $row['user_id'];
	}

	$query = "SELECT * FROM google_user WHERE email = '$email'";
	$data = mysqli_query($dbc, $query);

	if(mysqli_num_rows($data) == 1)
	{
		$row = mysqli_fetch_array($data);
		return $row['google_id'];
	}

}

function getUserName($user_id){
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	$query = "SELECT username FROM " . TABLE_NAME . " WHERE user_id = $user_id";
	$data = mysqli_query($dbc, $query);

	if(mysqli_num_rows($data) == 1)
	{
		$row = mysqli_fetch_array($data);
		return $row['username'];
	}

	$query = "SELECT username FROM google_user WHERE user_id = $user_id";
	$data = mysqli_query($dbc, $query);

	if(mysqli_num_rows($data) == 1)
	{
		$row = mysqli_fetch_array($data);
		return $row['username'];
	}
}

function getRow($user_id){
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	$query = "SELECT * FROM " . TABLE_NAME . " WHERE user_id = $user_id";
	$data = mysqli_query($dbc, $query);

	if(mysqli_num_rows($data) == 1)
	{
		$row = mysqli_fetch_array($data);
		return $row;
	}

	$query = "SELECT * FROM google_user WHERE user_id = $user_id";
	$data = mysqli_query($dbc, $query);

	if(mysqli_num_rows($data) == 1)
	{
		$row = mysqli_fetch_array($data);
		return $row;
	}
}

function get_all_user_rows()
{
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$query = "SELECT * FROM stapp_user";
	$data = mysqli_query($dbc, $query);
	return $data;
}

function get_all_google_rows()
{
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$query = "SELECT * FROM google_user";
	$data = mysqli_query($dbc, $query);
	return $data;
}


function get_confirmed_account($user_id){
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$query = "SELECT * FROM confirmed_accounts WHERE user_id=$user_id";
	$data = mysqli_query($dbc, $query);
	if(mysqli_num_rows($data) > 0){
		return mysqli_fetch_array($data);
	}
	return null;
}

function getRowByUsername($username){
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	$query = "SELECT * FROM " . TABLE_NAME . " WHERE username = '$username'";
	$data = mysqli_query($dbc, $query);

	if(mysqli_num_rows($data) == 1)
	{
		$row = mysqli_fetch_array($data);
		return $row;
	}

	$query = "SELECT * FROM google_user WHERE username = '$username'";
	$data = mysqli_query($dbc, $query);

	if(mysqli_num_rows($data) == 1)
	{
		$row = mysqli_fetch_array($data);
		return $row;
	}
}


function userNameExists($username)
{
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	$query = "SELECT * FROM " . TABLE_NAME . " WHERE username = '$username'";
	$data = mysqli_query($dbc, $query);

	if(mysqli_num_rows($data) == 0)
	{
		mysqli_close($dbc);
		return false;
	}
	mysqli_close($dbc);
	return true;
}

function emailExists($email)
{
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	$query = "SELECT * FROM " . TABLE_NAME . " WHERE email = '$email'";
	$data = mysqli_query($dbc, $query);

	if(mysqli_num_rows($data) == 0)
	{
		mysqli_close($dbc);
		return false;
	}
	mysqli_close($dbc);
	return true;
}

function googleEmailExist($email){
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	$query = "SELECT * FROM google_user WHERE email = '$email'";
	$data = mysqli_query($dbc, $query);

	if(mysqli_num_rows($data) == 0) 
	{
		mysqli_close($dbc);
		return false;
	}
	mysqli_close($dbc);
	return true;

}

function getPlanRow($user_id){
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	$query = "SELECT * FROM user_plan WHERE user_id = $user_id";
	$data = mysqli_query($dbc, $query);

	if(mysqli_num_rows($data) == 1)
	{
		$row = mysqli_fetch_array($data);
		mysqli_close($dbc);
		return $row;
	}
}

function getTickets($user_id){
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	$query = "SELECT * FROM tickets WHERE user_id = $user_id";
	$data = mysqli_query($dbc, $query);
	mysqli_close($dbc);
	return $data;
}



function getTicketData(){
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	$query = "SELECT * FROM tickets";
	$data = mysqli_query($dbc, $query);
	mysqli_close($dbc);
	return $data;
}

function getTicketAnswers($ticket_id){
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	$query = "SELECT * FROM ticket_answer WHERE ticket_id=$ticket_id order by created_date asc";
	$data = mysqli_query($dbc, $query);
	mysqli_close($dbc);
	return $data;
}

function getLastTicketUpdate($ticket_id){
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	$query = "SELECT * FROM ticket_updates WHERE ticket_id=$ticket_id ORDER BY last_updated DESC LIMIT 1";
	$data = mysqli_query($dbc, $query);
	mysqli_close($dbc);
	return mysqli_fetch_array($data);
}
function deleteAllTickets($user_id){
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	$tickets = getTickets($user_id);

	$query = "DELETE FROM tickets WHERE user_id = $user_id";
	mysqli_query($dbc, $query);

	$query = "DELETE FROM ticket_limit WHERE user_id = $user_id";
	mysqli_query($dbc, $query);

	if(mysqli_num_rows($tickets) > 0)
	{
		while(mysqli_fetch_array($ticket))
		{
			$ticket_id = $ticket['ticket_id'];

			$query = "DELETE FROM ticket_answer WHERE ticket_id = $ticket_id";
			mysqli_query($dbc, $query);

			$query = "DELETE FROM ticket_updates WHERE ticket_id = $ticket_id";
			mysqli_query($dbc, $query);
		}
	}
	mysqli_close($dbc);
}
function userHasPlan($user_id){
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	$query = "SELECT * FROM user_plan WHERE user_id = $user_id";
	$data = mysqli_query($dbc, $query);
	mysqli_close($dbc);

	if(mysqli_num_rows($data) == 1) 
	{
		return true;
	}

	return false;
}

function userHasNonTrialPlan($user_id){
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	$query = "SELECT * FROM user_plan WHERE user_id = $user_id && current_plan <> 'trial'";
	$data = mysqli_query($dbc, $query);
	mysqli_close($dbc);

	if(mysqli_num_rows($data) == 1) 
	{
		return true;
	}

	return false;
}

function createTodayTicketLimitCount($user_id){
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	$todayPlusDay = time() + (60 * 60 * 24);
	$query = "INSERT INTO ticket_limit (user_id, count, count_time)
				VALUES ($user_id, 0, '$todayPlusDay');";
	mysqli_query($dbc, $query);
	mysqli_close($dbc);
}

function ticketLimitCountExist($user_id){
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	$query = "SELECT * FROM ticket_limit WHERE user_id = $user_id AND count_time > " . time() .";";

	$data = mysqli_query($dbc, $query);
	if(mysqli_num_rows($data) > 0)
		return true;
	else
		return false;
	mysqli_close($dbc);
}

function getTicketLimitCount($user_id){
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	$query = "SELECT MAX(count) AS Count FROM ticket_limit WHERE user_id=$user_id AND count_time > ".time().";";
	$data = mysqli_query($dbc, $query);
	$row = mysqli_fetch_array($data);
	mysqli_close($dbc);
	return $row['Count'];
}
function increaseTicketLimitCount($user_id)
{
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	$query = "SELECT MAX(count) AS Count FROM ticket_limit WHERE user_id=$user_id AND count_time > ".time().";";
	$data = mysqli_query($dbc, $query);
	$row = mysqli_fetch_array($data);
	$count = $row['Count'] + 1;

	$query = "UPDATE ticket_limit SET count = '$count' WHERE user_id=$user_id AND count_time > ".time().";";	
	mysqli_query($dbc,$query);

	mysqli_close($dbc);
}

function checkPlanExpired($user_id){
	date_default_timezone_set('America/New_York');

	$planRow = getPlanRow($user_id);
	$expirationDate = strtotime($planRow['plan_expiration']);

	$expired = $expirationDate < time();
	/* we used to renew the expiration date if the user expiration date has arrived now we just send him an email, asking him to pay again.
	if($expired == 1)
	{
		$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		$query = "UPDATE user_plan SET plan_expiration = DATE_ADD(NOW(), INTERVAL 1 MONTH) WHERE user_id = '$user_id' AND current_plan <> 'trial' AND cancelled <> 1";
		$result = mysqli_query($dbc, $query);
	}*/

	return $expired;
}

function fullAccountDelete($user_id){
	if($user_id < PHP_INT_MAX)
		remove_stapp_user($user_id);
	else
		remove_google_user($user_id);

	remove_user_plans($user_id);
}


function remove_stapp_user($user_id)
{
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	$userRow = getRow($user_id);
	$email = $userRow['email'];

	$query = "DELETE FROM stapp_user WHERE user_id = $user_id";
	mysqli_query($dbc, $query);

	$query = "DELETE FROM password_reset WHERE id = $user_id";
	mysqli_query($dbc, $query);

	$query = "DELETE FROM transaction_paypal WHERE user_id = $user_id";
	mysqli_query($dbc, $query);

	deleteAllTickets($user_id);

	mysqli_close($dbc);
}

function remove_google_user($user_id)
{
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	$query = "DELETE FROM google_user WHERE user_id = $user_id";
	$result = mysqli_query($dbc, $query);

	$query = "DELETE FROM password_reset WHERE id = $user_id";
	mysqli_query($dbc, $query);

	$query = "DELETE FROM transaction_paypal WHERE user_id = $user_id";
	mysqli_query($dbc, $query);

	deleteAllTickets($user_id);

	mysqli_close($dbc);
}

function remove_user_plans($user_id)
{
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	$query = "DELETE FROM user_plan WHERE user_id = $user_id";
	mysqli_query($dbc, $query);
	
	mysqli_close($dbc);
}

function get_hub()
{
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$user_id = 0;
	$username = "";
	if(ctype_digit(func_get_arg(0)[0]) || gettype(func_get_arg(0)) == 'integer')
	{
		$user_id = func_get_arg(0);
		$query = "SELECT * FROM hub WHERE user_id = $user_id";
		$data = mysqli_query($dbc, $query);
	}
	else if(gettype(func_get_arg(0)) == 'string')
	{
		$username = func_get_arg(0);
		$query = "SELECT * FROM hub WHERE username = '$username'";
		$data = mysqli_query($dbc, $query);
	}

	if(mysqli_num_rows($data) > 0)
	{
		$row = mysqli_fetch_array($data);
		mysqli_close($dbc);
		return $row;
	}
	mysqli_close($dbc);
}

function get_all_hubs(){
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	$query = "SELECT * FROM hub";
	$data = mysqli_query($dbc, $query);
	mysqli_close($dbc);
	return $data;
}

function get_hub_by_id($id){

	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	$query = "SELECT * FROM hub WHERE id = $id";
	$data = mysqli_query($dbc, $query);
	$row = mysqli_fetch_array($data);
	mysqli_close($dbc);

	return $row;
}


function get_hub_id()
{
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$user_id = 0;
	$username = "";

	if(ctype_digit(func_get_arg(0)[0]) || gettype(func_get_arg(0)) == 'integer')
	{
		$user_id = func_get_arg(0);
		$query = "SELECT id FROM hub WHERE user_id = $user_id";
	}
	else if(gettype(func_get_arg(0)) == 'string')
	{
		$username = func_get_arg(0);
		$query = "SELECT id FROM hub WHERE username='$username'";
	}

	$data = mysqli_query($dbc, $query);
	if(mysqli_num_rows($data) > 0)
	{
		$row = mysqli_fetch_array($data);
		mysqli_close($dbc);
		return $row['id'];
	}
	mysqli_close($dbc);
}

function get_hub_info($hub_id)
{
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	$query = "SELECT * FROM hub_info WHERE hub_id = $hub_id";
	$data = mysqli_query($dbc, $query);
	if(mysqli_num_rows($data) > 0)
	{
		$row = mysqli_fetch_array($data);
		mysqli_close($dbc);
		return $row;
	}
	mysqli_close($dbc);

}

function get_hub_stats($hub_id)
{
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	$query = "SELECT * FROM hub_stats WHERE hub_id = $hub_id";
	$data = mysqli_query($dbc, $query);

	mysqli_close($dbc);
	return $data;
}
function get_hub_plans($hub_id)
{
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	$query = "SELECT * FROM hub_plans WHERE hub_id = $hub_id";
	$data = mysqli_query($dbc, $query);

	mysqli_close($dbc);
	return $data;
}

function get_single_hub_plan($plan_id){
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	$query = "SELECT * FROM hub_plans WHERE id = $plan_id";
	$data = mysqli_query($dbc, $query);
	$row = NULL;
	if(mysqli_num_rows($data)>0){
		$row = mysqli_fetch_array($data);
	}
	mysqli_close($dbc);
	return $row;
}

function get_hub_links($hub_id)
{
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	$query = "SELECT * FROM hub_links WHERE hub_id = $hub_id";
	$data = mysqli_query($dbc, $query);

	mysqli_close($dbc);
	return $data;
}

function get_url_title($url)
{
	$pattern_replace = "/^(http:\/\/|https:\/\/)?(www\.)?/";
	$pattern_match = "/^(\w+)/";

  $extLessUrl = preg_replace($pattern_replace, '',$url);
  $title = preg_match($pattern_match, $extLessUrl, $matches);
	$matches[0][0] = strtoupper($matches[0][0]);
	return $matches[0];
}

function get_paypal_credentials($user_id)
{
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	$query = "SELECT * FROM paypal_credentials WHERE user_id = $user_id";
	$data = mysqli_query($dbc, $query);
	$row = NULL;
	if(mysqli_num_rows($data) > 0){
		$row = mysqli_fetch_array($data);
	}
	mysqli_close($dbc);
	return $row;
}

function get_stripe_credentials($user_id)
{
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	$query = "SELECT * FROM stripe_credentials WHERE user_id = $user_id";
	$data = mysqli_query($dbc, $query);
	$row = NULL;
	if(mysqli_num_rows($data) > 0){
		$row = mysqli_fetch_array($data);
	}
	mysqli_close($dbc);
	return $row;
}

function get_hub_subscribers()
{
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	$query = "SELECT * FROM hub_plan_subscribers";
	$data = mysqli_query($dbc, $query);

	mysqli_close($dbc);
	return $data;
}

function get_hub_plan_purchases($hub_id)
{
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	$query = "SELECT * FROM hub_plan_purchases where hub_id={$hub_id}";
	$data = mysqli_query($dbc, $query);

	mysqli_close($dbc);
	return $data;

}

function get_hub_plan_purchases_by_plan($plan_id)
{
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	$query = "SELECT * FROM hub_plan_purchases WHERE hub_plan_id={$plan_id}";
	$data = mysqli_query($dbc, $query);

	mysqli_close($dbc);
	return $data;
}

function is_paypal_credentials_confirmed($user_id){
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	$query = "SELECT * FROM paypal_credentials where user_id=$user_id";
	$data = mysqli_query($dbc, $query);

	$row = mysqli_fetch_array($data);

	mysqli_close($dbc);
	if($row['confirmed'] == 1){
		return 1;	
	}
	else{
		return 0;
	}


	return $data;

}

function containsDecimal( $value ) {
    if ( strpos( $value, "." ) !== false ) {
        return true;
    }
    return false;
}
function compress($source, $destination, $quality) {

    $info = getimagesize($source);

    if ($info['mime'] == 'image/jpeg') 
        $image = imagecreatefromjpeg($source);

    elseif ($info['mime'] == 'image/gif') 
        $image = imagecreatefromgif($source);

    elseif ($info['mime'] == 'image/png') 
        $image = imagecreatefrompng($source);

    imagejpeg($image, $destination, $quality);

    return $destination;
}

function resize_image($file, $w, $h, $crop=FALSE) {
    list($width, $height) = getimagesize($file);
    $r = $width / $height;
    if ($crop) {
        if ($width > $height) {
            $width = ceil($width-($width*abs($r-$w/$h)));
        } else {
            $height = ceil($height-($height*abs($r-$w/$h)));
        }
        $newwidth = $w;
        $newheight = $h;
    } else {
        if ($w/$h > $r) {
            $newwidth = $h*$r;
            $newheight = $h;
        } else {
            $newheight = $w/$r;
            $newwidth = $w;
        }
    }
    $src = imagecreatefromjpeg($file);
    $dst = imagecreatetruecolor($newwidth, $newheight);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

    return $dst;
}

function verify_paypal_email($email){
	$pa = new PaypalAdaptive();
	$response = $pa->executeSimplePayment("test", 1.00, $email);
	$emailId = $response['paymentInfoList']['paymentInfo'][0]['receiver']['accountId'];
	if($emailId != 0)
	{
		return true;
	}
	return false;
}

function str_contain_space($str)
{
	if ($str == trim($str) && strpos($str, ' ') !== false) {
		return true;
	}
	else
		return false;
}

function make_non_exist_dir($dir){
	if(!file_exists($dir)){
		mkdir($dir, 0777, true);
		return 1;
	}
	else
		return 0;
}

function send_email($debug, $address, $subject, $body){
	$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
	try {
		//Server settings
		$mail->SMTPDebug = $debug;                                 // Enable verbose debug output
		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = 'rolle.cocco.enterprises@gmail.com';                 // SMTP username
		$mail->Password = '2501411125014111';                           // SMTP password
		$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
		$mail->Port = 587;                                    // TCP port to connect to

		//Recipients
		$mail->setFrom('rolle.cocco.enterprises@gmail.com', 'STAPP');
		$mail->addAddress($address, 'user');     // Add a recipient

		$body = $body;

		//Content
		$mail->isHTML(true);                                  // Set email format to HTML
		$mail->Subject = $subject;
		$mail->Body    = $body;

		if (!$mail->send()) {
			$mail->ErrorInfo;
		}
	} catch (Exception $e) {
		echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
	}

}
//Edited by rezker (http://www.rezker.com)
function code_to_country( $code ){

    $code = strtoupper($code);

    $countryList = array(
        'AF' => 'Afghanistan',
        'AX' => 'Aland Islands',
        'AL' => 'Albania',
        'DZ' => 'Algeria',
        'AS' => 'American Samoa',
        'AD' => 'Andorra',
        'AO' => 'Angola',
        'AI' => 'Anguilla',
        'AQ' => 'Antarctica',
        'AG' => 'Antigua and Barbuda',
        'AR' => 'Argentina',
        'AM' => 'Armenia',
        'AW' => 'Aruba',
        'AU' => 'Australia',
        'AT' => 'Austria',
        'AZ' => 'Azerbaijan',
        'BS' => 'Bahamas the',
        'BH' => 'Bahrain',
        'BD' => 'Bangladesh',
        'BB' => 'Barbados',
        'BY' => 'Belarus',
        'BE' => 'Belgium',
        'BZ' => 'Belize',
        'BJ' => 'Benin',
        'BM' => 'Bermuda',
        'BT' => 'Bhutan',
        'BO' => 'Bolivia',
        'BA' => 'Bosnia and Herzegovina',
        'BW' => 'Botswana',
        'BV' => 'Bouvet Island (Bouvetoya)',
        'BR' => 'Brazil',
        'IO' => 'British Indian Ocean Territory (Chagos Archipelago)',
        'VG' => 'British Virgin Islands',
        'BN' => 'Brunei Darussalam',
        'BG' => 'Bulgaria',
        'BF' => 'Burkina Faso',
        'BI' => 'Burundi',
        'KH' => 'Cambodia',
        'CM' => 'Cameroon',
        'CA' => 'Canada',
        'CV' => 'Cape Verde',
        'KY' => 'Cayman Islands',
        'CF' => 'Central African Republic',
        'TD' => 'Chad',
        'CL' => 'Chile',
        'CN' => 'China',
        'CX' => 'Christmas Island',
        'CC' => 'Cocos (Keeling) Islands',
        'CO' => 'Colombia',
        'KM' => 'Comoros the',
        'CD' => 'Congo',
        'CG' => 'Congo the',
        'CK' => 'Cook Islands',
        'CR' => 'Costa Rica',
        'CI' => 'Cote d\'Ivoire',
        'HR' => 'Croatia',
        'CU' => 'Cuba',
        'CY' => 'Cyprus',
        'CZ' => 'Czech Republic',
        'DK' => 'Denmark',
        'DJ' => 'Djibouti',
        'DM' => 'Dominica',
        'DO' => 'Dominican Republic',
        'EC' => 'Ecuador',
        'EG' => 'Egypt',
        'SV' => 'El Salvador',
        'GQ' => 'Equatorial Guinea',
        'ER' => 'Eritrea',
        'EE' => 'Estonia',
        'ET' => 'Ethiopia',
        'FO' => 'Faroe Islands',
        'FK' => 'Falkland Islands (Malvinas)',
        'FJ' => 'Fiji the Fiji Islands',
        'FI' => 'Finland',
        'FR' => 'France, French Republic',
        'GF' => 'French Guiana',
        'PF' => 'French Polynesia',
        'TF' => 'French Southern Territories',
        'GA' => 'Gabon',
        'GM' => 'Gambia the',
        'GE' => 'Georgia',
        'DE' => 'Germany',
        'GH' => 'Ghana',
        'GI' => 'Gibraltar',
        'GR' => 'Greece',
        'GL' => 'Greenland',
        'GD' => 'Grenada',
        'GP' => 'Guadeloupe',
        'GU' => 'Guam',
        'GT' => 'Guatemala',
        'GG' => 'Guernsey',
        'GN' => 'Guinea',
        'GW' => 'Guinea-Bissau',
        'GY' => 'Guyana',
        'HT' => 'Haiti',
        'HM' => 'Heard Island and McDonald Islands',
        'VA' => 'Holy See (Vatican City State)',
        'HN' => 'Honduras',
        'HK' => 'Hong Kong',
        'HU' => 'Hungary',
        'IS' => 'Iceland',
        'IN' => 'India',
        'ID' => 'Indonesia',
        'IR' => 'Iran',
        'IQ' => 'Iraq',
        'IE' => 'Ireland',
        'IM' => 'Isle of Man',
        'IL' => 'Israel',
        'IT' => 'Italy',
        'JM' => 'Jamaica',
        'JP' => 'Japan',
        'JE' => 'Jersey',
        'JO' => 'Jordan',
        'KZ' => 'Kazakhstan',
        'KE' => 'Kenya',
        'KI' => 'Kiribati',
        'KP' => 'Korea',
        'KR' => 'Korea',
        'KW' => 'Kuwait',
        'KG' => 'Kyrgyz Republic',
        'LA' => 'Lao',
        'LV' => 'Latvia',
        'LB' => 'Lebanon',
        'LS' => 'Lesotho',
        'LR' => 'Liberia',
        'LY' => 'Libyan Arab Jamahiriya',
        'LI' => 'Liechtenstein',
        'LT' => 'Lithuania',
        'LU' => 'Luxembourg',
        'MO' => 'Macao',
        'MK' => 'Macedonia',
        'MG' => 'Madagascar',
        'MW' => 'Malawi',
        'MY' => 'Malaysia',
        'MV' => 'Maldives',
        'ML' => 'Mali',
        'MT' => 'Malta',
        'MH' => 'Marshall Islands',
        'MQ' => 'Martinique',
        'MR' => 'Mauritania',
        'MU' => 'Mauritius',
        'YT' => 'Mayotte',
        'MX' => 'Mexico',
        'FM' => 'Micronesia',
        'MD' => 'Moldova',
        'MC' => 'Monaco',
        'MN' => 'Mongolia',
        'ME' => 'Montenegro',
        'MS' => 'Montserrat',
        'MA' => 'Morocco',
        'MZ' => 'Mozambique',
        'MM' => 'Myanmar',
        'NA' => 'Namibia',
        'NR' => 'Nauru',
        'NP' => 'Nepal',
        'AN' => 'Netherlands Antilles',
        'NL' => 'Netherlands the',
        'NC' => 'New Caledonia',
        'NZ' => 'New Zealand',
        'NI' => 'Nicaragua',
        'NE' => 'Niger',
        'NG' => 'Nigeria',
        'NU' => 'Niue',
        'NF' => 'Norfolk Island',
        'MP' => 'Northern Mariana Islands',
        'NO' => 'Norway',
        'OM' => 'Oman',
        'PK' => 'Pakistan',
        'PW' => 'Palau',
        'PS' => 'Palestinian Territory',
        'PA' => 'Panama',
        'PG' => 'Papua New Guinea',
        'PY' => 'Paraguay',
        'PE' => 'Peru',
        'PH' => 'Philippines',
        'PN' => 'Pitcairn Islands',
        'PL' => 'Poland',
        'PT' => 'Portugal, Portuguese Republic',
        'PR' => 'Puerto Rico',
        'QA' => 'Qatar',
        'RE' => 'Reunion',
        'RO' => 'Romania',
        'RU' => 'Russian Federation',
        'RW' => 'Rwanda',
        'BL' => 'Saint Barthelemy',
        'SH' => 'Saint Helena',
        'KN' => 'Saint Kitts and Nevis',
        'LC' => 'Saint Lucia',
        'MF' => 'Saint Martin',
        'PM' => 'Saint Pierre and Miquelon',
        'VC' => 'Saint Vincent and the Grenadines',
        'WS' => 'Samoa',
        'SM' => 'San Marino',
        'ST' => 'Sao Tome and Principe',
        'SA' => 'Saudi Arabia',
        'SN' => 'Senegal',
        'RS' => 'Serbia',
        'SC' => 'Seychelles',
        'SL' => 'Sierra Leone',
        'SG' => 'Singapore',
        'SK' => 'Slovakia (Slovak Republic)',
        'SI' => 'Slovenia',
        'SB' => 'Solomon Islands',
        'SO' => 'Somalia, Somali Republic',
        'ZA' => 'South Africa',
        'GS' => 'South Georgia and the South Sandwich Islands',
        'ES' => 'Spain',
        'LK' => 'Sri Lanka',
        'SD' => 'Sudan',
        'SR' => 'Suriname',
        'SJ' => 'Svalbard & Jan Mayen Islands',
        'SZ' => 'Swaziland',
        'SE' => 'Sweden',
        'CH' => 'Switzerland, Swiss Confederation',
        'SY' => 'Syrian Arab Republic',
        'TW' => 'Taiwan',
        'TJ' => 'Tajikistan',
        'TZ' => 'Tanzania',
        'TH' => 'Thailand',
        'TL' => 'Timor-Leste',
        'TG' => 'Togo',
        'TK' => 'Tokelau',
        'TO' => 'Tonga',
        'TT' => 'Trinidad and Tobago',
        'TN' => 'Tunisia',
        'TR' => 'Turkey',
        'TM' => 'Turkmenistan',
        'TC' => 'Turks and Caicos Islands',
        'TV' => 'Tuvalu',
        'UG' => 'Uganda',
        'UA' => 'Ukraine',
        'AE' => 'United Arab Emirates',
        'GB' => 'United Kingdom',
        'US' => 'United States of America',
        'UM' => 'United States Minor Outlying Islands',
        'VI' => 'United States Virgin Islands',
        'UY' => 'Uruguay, Eastern Republic of',
        'UZ' => 'Uzbekistan',
        'VU' => 'Vanuatu',
        'VE' => 'Venezuela',
        'VN' => 'Vietnam',
        'WF' => 'Wallis and Futuna',
        'EH' => 'Western Sahara',
        'YE' => 'Yemen',
        'ZM' => 'Zambia',
        'ZW' => 'Zimbabwe'
    );

    if( !$countryList[$code] ) return $code;
    else return $countryList[$code];
    }

	?>
