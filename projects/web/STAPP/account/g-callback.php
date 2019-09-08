<?php
	session_start();
	require_once "GoogleLogin.php";
	require_once "../Constants/connectvars.php";
	require_once "../PHPScripts/functions.php";

	if(isset($_SESSION['access_token'])){
		$gClient->setAccessToken($_SESSION['access_token']);
	}
	else if(isset($_GET['code'])) {
		$token = $gClient->fetchAccessTokenWithAuthCode($_GET['code']);
		$_SESSION['access_token'] = $token;
	} else {
		header('Location: login.php');
		exit();
	}

	try{
		$oAuth = new Google_Service_Oauth2($gClient);
		$userData = $oAuth->userinfo_v2_me->get();
		$google_id = $userData['id'];
		$google_email = $userData['email'];
	}
	catch(Exception $e){
		$gClient->revokeToken($_SESSION['access_token']);
		unset($_SESSION['access_token']);
		echo 'Timeout check your internet connection!';
		return;
	//	header('Location: Register.php?error=1');
	}
	/*
	if(emailExists($userData['email'])){
		$gClient->revokeToken($_SESSION['access_token']);
		unset($_SESSION['access_token']);
		header('Location: register.php?error=1');
		exit();
	}*/

	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$query = "SELECT * FROM stapp_user WHERE email='$google_email'";
	$data = mysqli_query($dbc, $query);

	// if the is no email linked to this account, create a new account with it
	// and log this user in
	if(mysqli_num_rows($data) == 0) {
		$first_name = $userData['givenName'];
		$last_name = $userData['familyName'];
		$query = "INSERT INTO stapp_user (username,first_name, last_name, email, join_date)
					VALUES('', '$first_name', '$last_name', '$google_email', NOW())";
		mysqli_query($dbc, $query);

		$query = "SELECT * FROM stapp_user WHERE email = '$google_email'";
		$data = mysqli_query($dbc, $query);
		$row = mysqli_fetch_array($data);
		$_SESSION['user_id'] = $row['user_id'];
		setcookie('user_id', $row['user_id'], time() + (60 * 60 * 24 * 30), '/');

		mysqli_close($dbc);
		$home_url = $rootUrl;
		header('Location: ' . $home_url);
		return;

	}
	else{
		// else just log this user in!
		$row = mysqli_fetch_array($data);
		$_SESSION['user_id'] = $row['user_id'];
		setcookie('user_id', $row['user_id'], time() + (60 * 60 * 24 * 30), '/');
		if(!empty($row['username'])){
			$_SESSION['username'] = $row['username'];
			setcookie('username', $row['username'], time() + (60 * 60 * 24 * 30), '/');
		}

		$home_url = $rootUrl;
		header('Location: ' . $home_url);
		return;
	}

	//header('Location: ../index.php');
	//exit();
?>
