<?php
	require_once "../Constants/getPath.php";
	require_once "../GoogleAPI/vendor/autoload.php";

	if($debug == true)
		$redirectUri = "http://stappapplocalhost.com/account/g-callback.php";
	else
		$redirectUri = "https://stappapp.com/account/g-callback.php";

	$gClient = new Google_Client();
	$gClient->setClientId("308328508339-ajuvo4mves0kbkvoc64r3854r9globsi.apps.googleusercontent.com");
	$gClient->setClientSecret("ErLWUfSQSvfyCWLwLnFqxr0U");
	$gClient->setApplicationName("STAPP | The Subscription App");
	$gClient->setRedirectUri($redirectUri);
	$gClient->addScope("https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email");
?>
