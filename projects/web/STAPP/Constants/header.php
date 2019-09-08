<!DOCTYPE html>
<?php
date_default_timezone_set('America/New_York');

if($debug == true)
{
	$absolute_path = 'http://stappapp.localhost/';
}
else 
{
	$absolute_path = $rootUrl;
}
$return_path = '';
$cases = 0;
$loop = true;
while($loop)
{
	$directories = getcwd();
	for($i = 0; $i < $cases; $i++)
		$directories = dirname($directories);

	$directories = scandir($directories);

	foreach($directories as $s)
	{
		if($s == 'Constants')
		{
			$loop = false;
			break;
		}
	}
	if($loop){
		$return_path = '../' . $return_path;
		$cases++;
	}
}

require_once(__DIR__ . '/../PHPScripts/functions.php');

if(isset($_SESSION['user_id']))
{
	$userRow = getRow($_SESSION['user_id']);

	if(empty($userRow['username']))
	{
		if(!isset($personal_flag))
			header('Location: ' . $return_path . 'account/personalinfo');
	}

	define('ST_UPLOADPATH', '../hub_images/' . $userRow['username'] . '/');
	define('ST_MAXFILESIZE', 4000000);
}
if(!isset($_SESSION['user_id']))
{
	if(isset($_COOKIE['user_id']) && isset($_COOKIE['username']))
	{
		$_SESSION['user_id'] = $_COOKIE['user_id'];
		$_SESSION['username'] = $_COOKIE['username'];
	}
}

?>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<!--<meta name="viewport" content="user-scalable = yes">-->
        <?php printf('<link href="%sSTAPP_Logotype.ico" rel="icon" />', $return_path);?>
        <title>STAPP | The Subscription App</title>
        
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
		<script src="https://apis.google.com/js/platform.js" async defer></script>
		
        <?php
            printf('<script src="%sscripts/bootstrap.js"></script>', $return_path);
			//printf('<script src="%sscripts/bootstrap.min.js"></script>', $return_path);
			printf('<script src="%sscripts/p5.js"></script>', $return_path);
			printf('<script src="%sscripts/p5.dom.js"></script>', $return_path);
			printf('<script src="%sscripts/functions.js"></script>', $return_path);
        ?>
		<?php
            printf('<link rel="stylesheet" href="%scss/bootstrap.css">', $return_path);
        ?>
		<link href="https://fonts.googleapis.com/css?family=Russo+One" rel="stylesheet">
