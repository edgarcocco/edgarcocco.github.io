<?php
session_start();
require_once 'auth.php';
require_once '../Constants/getPath.php';
require_once '../Constants/connectvars.php';

if(!isset($_GET['ticketid'])){
	header('Location: index.php');
}

$ticketId = $_GET['ticketid'];

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$query = "UPDATE tickets SET state='closed' WHERE id = '$ticketId'";
mysqli_query($dbc, $query);

header('Location: ticketlist.php');
?>
