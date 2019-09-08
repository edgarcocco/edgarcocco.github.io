<?php 
session_start();
require_once '../Constants/getPath.php';
require_once '../Constants/connectvars.php';
require_once '../PHPScripts/functions.php';

$userRow = getRow($_SESSION['user_id']);
$pass = false;

if($userRow['email'] == 'blasterkid111@gmail.com')
{
	$pass = true;	
	$_SESSION['rank'] = "Owner";
}
if($userRow['email'] == 'genkishi101@gmail.com')
{
	$pass = true;
	$_SESSION['rank'] = "Owner";
}
if($userRow['email'] == 'jansonrolle101@gmail.com')
{
	$pass = true;
	$_SESSION['rank'] = "Owner";
}
if($pass == false)
{
	header('Location: ../account/login.php');
	return;
}

if(isset($requiredRank)){
	if($_SESSION['rank'] != $requiredRank)
	{
		header('Location: index.php?error=rank');
		return;
	}
}

?>
