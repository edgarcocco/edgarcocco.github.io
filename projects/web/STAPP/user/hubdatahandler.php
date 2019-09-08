<?php
session_start();
require_once '../Constants/getPath.php';
//require_once '../Constants/header.php';
require_once '../Constants/connectvars.php';
require_once '../PHPScripts/functions.php';

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$property = $_POST['property'];
$value = "";
if(isset($_POST['value']))
	$value = mysqli_real_escape_string($dbc, $_POST['value']);
$userRow = getRow($_SESSION['user_id']);
$hub_id = get_hub_id($_SESSION['user_id']);
$hub_info = get_hub_info($hub_id);
define('ST_UPLOADPATH', '../hub_images/' . $userRow['username'] . '/');

if($property == "name")
{
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$query = "UPDATE hub_info SET name = '$value' WHERE hub_id='$hub_id'";
	mysqli_query($dbc, $query);
	echo 'queryS';
	mysqli_close($dbc);
}
if($property == "picture")
{
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$query = "UPDATE hub_info SET pictures = '$value' WHERE hub_id='$hub_id'";
	mysqli_query($dbc, $query);
	echo 'queryS';
	mysqli_close($dbc);
}
if($property == "deleteImg")
{
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$pictures = explode(';', $hub_info['pictures']);
	$index = $value;
	$newPictures = "";
	for($i = 0; $i < count($pictures); $i++)
	{
		if($i == $index || empty($pictures[$i]))
			continue;
		$newPictures = $newPictures . $pictures[$i] . ";";
	}
	//echo $newPictures;
	$query = "UPDATE hub_info SET pictures='$newPictures' WHERE hub_id='$hub_id'";
	mysqli_query($dbc, $query);
	unlink(ST_UPLOADPATH . $pictures[$index]);
	mysqli_close($dbc);
	echo 'queryS';
}
if($property == "new_stat")
{
	$values = explode(';', $value);
	$stat_id = $values[0];
	$title = $values[1];
	$body = $values[2];
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$query = "INSERT INTO hub_stats (hub_id, stat_title, stat_body) 
				VALUES ($hub_id, '$title', '$body')";

	mysqli_query($dbc, $query);
	mysqli_close($dbc);
	echo 'queryS';
}

if($property == "edit_stat")
{
	$values = explode(';', $value);

	$stat_id = $values[0];
	$title = $values[1];
	$body = $values[2];

	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$query = "UPDATE hub_stats SET stat_title='$title', stat_body='$body' WHERE id='$stat_id'";

	mysqli_query($dbc, $query);
	mysqli_close($dbc);
	echo 'queryS';

}

if($property == "deleteStat")
{
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$index = $value;
	$query = "DELETE FROM hub_stats WHERE id=$index";
	mysqli_query($dbc, $query);
	mysqli_close($dbc);
	echo 'queryS';
}

if($property == "biography")
{
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$bio = $value;
	$query = "UPDATE hub_info SET biography='$bio' WHERE hub_id=$hub_id";
	mysqli_query($dbc, $query);
	mysqli_close($dbc);
	echo 'queryS';
}

if($property == "new_link")
{
	$values = explode(';', $value);

	$link_id = $values[0];
	$link = $values[1];

	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$query = "INSERT INTO hub_links (hub_id,link) VALUES ($hub_id, '$link')";
	mysqli_query($dbc, $query);
	mysqli_close($dbc);
	echo 'queryS';

}
if($property == "edit_link")
{
	$values = explode(';', $value);

	$link_id= $values[0];
	$link = $values[1];

	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$query = "UPDATE hub_links SET link='$link' WHERE id=$link_id";
	mysqli_query($dbc, $query);
	mysqli_close($dbc);
	echo 'queryS';

}
if($property == "deleteLink")
{
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$index = $value;
	$query = "DELETE FROM hub_links WHERE id='$index'";
	mysqli_query($dbc, $query);
	mysqli_close($dbc);
	echo 'queryS';
}

if($property == "plan")
{
	$title = $_POST['title'];
	$feats = $_POST['feats'];
	$price = $_POST['price'];
	$frequency = $_POST['frequency'];
	$cycles = $_POST['cycles'];
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	if(empty($_POST['id'])){
	$query = "INSERT INTO hub_plans (hub_id, plan_title, feats, price, frequency, cycles) 
				VALUES ($hub_id, '$title', '$feats', ${price}, '$frequency', ${cycles})";
		//echo 'insert';
	}
	else{
		$id = $_POST['id'];
		$query = "UPDATE hub_plans SET plan_title='$title', feats='$feats', price=${price}, frequency='$frequency', cycles=${cycles} WHERE id={$id}";
		//echo 'update';
	}

//	echo $query;
	mysqli_query($dbc, $query);
	mysqli_close($dbc);
	echo 'queryS';
}

if($property == "delete_plan")
{
	$plan_id = $value;
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$query = "DELETE FROM hub_plans WHERE id={$plan_id}";
	mysqli_query($dbc, $query);
	mysqli_close($dbc);
	echo 'queryS';
}

if($property == "hub_visibility")
{
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$query = "UPDATE hub SET visibility = '$value' WHERE id=$hub_id";
	mysqli_query($dbc, $query);
	mysqli_close($dbc);
	echo 'queryS';
}



$_POST = array();
?>
