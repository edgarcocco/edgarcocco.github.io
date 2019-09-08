<?php 
	session_start();
	require_once '../Constants/getPath.php';
	require_once '../Constants/connectvars.php';
	require_once "GoogleLogin.php";
	if(isset($_SESSION['user_id'])) {
		
		//$_SESSION = array();
		unset($_SESSION['user_id']);

		if(isset($_COOKIE[session_name()])) {
			setcookie(session_name(), "", time() - 3600, '/');
		}

		session_destroy();
	}

	if(isset($_SESSION['access_token'])){
		$gClient->revokeToken($_SESSION['access_token']);
		unset($_SESSION['access_token']);
		session_destroy();
	}

	setcookie('user_id', $row['user_id'], time() - 3600, '/');
	setcookie('username', $row['username'], time() - 3600, '/');
	$home_url = $rootUrl;
	header('Location: ' . $home_url);
?>
