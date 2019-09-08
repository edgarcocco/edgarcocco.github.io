<?php

session_start();
require_once '../../Constants/getPath.php';
require_once '../../Constants/connectvars.php';
require_once '../../Constants/header.php';
require_once '../../PHPScripts/functions.php';

//you need to be logged in to send a ticket!!
if(!isset($_SESSION['user_id']))
{
	header('Location: ../support.php?error_code=1');
}
/*
if(isset($_SESSION['ticket_limit_date']))
{
	if(time() > $_SESSION['ticket_limit_date'])
	{
		unset($_SESSION['ticket_limit']);
	}
}
if(!isset($_SESSION['ticket_limit']))
{
	$_SESSION['ticket_limit_date'] = time() + (60 * 60 * 24);
	$_SESSION['ticket_limit'] = 0;
}
 */

if(!ticketLimitCountExist($_SESSION['user_id']))
	createTodayTicketLimitCount($_SESSION['user_id']);

if(getTicketLimitCount($_SESSION['user_id']) > 1)
	header('Location: ../support.php?error_code=3');

if(isset($_GET['ticket'])){
	$ticket = $_GET['ticket'];
}


$error_msg = "";
$success = false;
if(isset($_POST['sendticket'])){
	//add ticket info to database
	//
	if(!empty($_POST['ticketmessage']))
	{
		$user_id = $_SESSION['user_id'];
		$userRow = getRow($user_id);
		$username = $userRow['username'];
		$category ="";
		if(!isset($_POST['category']))
			$category="";
		else
			$category = $_POST['category'];


		$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

		$ticketMessage = mysqli_real_escape_string($dbc, trim($_POST['ticketmessage']));
		$query = "INSERT INTO tickets (user_id, department, category, message, state, created_date) VALUES('$user_id', '$ticket','$category', '$ticketMessage', 'open', NOW())";
		mysqli_query($dbc, $query);
		$last_id = $dbc->insert_id;

		$query = "INSERT INTO ticket_updates (ticket_id, last_updated, last_answer_by) 
			VALUES('$last_id', NOW(), '$username')";
		mysqli_query($dbc, $query);
		mysqli_close($dbc);
		$success=true;

		//$_SESSION['ticket_limit'] = $_SESSION['ticket_limit'] + 1;
		//increase ticket limit for today date on a specific user!
		increaseTicketLimitCount($_SESSION['user_id']);
	}
	else
	{
		echo 'Ticket Message cannot be empty!';
	}
}



?>
</head>

<body>
<?php
require_once '../../Constants/navigation.php';

if($success){
	echo '<div class="container">';
	echo '<div class="alert alert-info text-center m-5">';
	echo '<h1> Ticket #'. $last_id . ' </h1>';
	echo '<p>Ticket successfully created, our support team will answer your ticket within 24 hours</p>';
	echo '</div>';
	echo '</div>';
}
else if(isset($ticket)){
?>

<div class="container m-5 py-2 w-50 border rounded">
<h3>
Ticket Details
</h3>

<p class="lead">Specify your problem and we will be able to help you.</p>
<div class="alert-info p-1 my-2"><p>Fields with asterisk (<span class="text-danger">*</span>) are mandatory.</p></div>
<form action="?ticket=<?php echo $ticket; ?>" method="post">
<?php 
	if($ticket == 'account')
	{
		echo '<h4>Account Administration Department</h4>';
	}
	else if($ticket == 'paymentOptions')
	{
		echo '<h4>Payments Department</h4>';
		echo '<div style="display:block">';
		echo '<p class="pr-5 py-1" style="display: inline;">Category<span class="text-danger">*</span> </p>';
		echo '<select name="category" class="py-1 my-2">
			  	<option value="Payment">Payment</option>
				<option value="Purchase">Purchase</option>
				<option value="Refund">Refund</option>
			  </select>';
		echo '</div>';
	}
	else if($ticket == 'support')
	{
		echo '<h4>Support Department</h4>';
	}
?>
<textarea name="ticketmessage" cols="25" rows="15" class="w-100"></textarea><br />
<button class="btn" type="submit" name="sendticket">Send Ticket</button>
</form>
</div>
<?php
}
else{
?>

<div class="container m-5">
	<h3>Choose a department</h3>
	<form action="" method="get">
		<label>
			<input type="radio" name="ticket" value="account" id="account"></input>
			Account administration
		</label><br />
		<label>
			<input type="radio" name="ticket" value="paymentOptions" id="paymentOptions"></input>
			Payments/purchases/refunds
		</label><br />
		<label>
			<input type="radio" name="ticket" value="support" id="support"></input>
			Support
		</label><br />
		<button class="btn" type="submit" name="submit" >Next</button>
	</form>
</div>

<?php
}
require_once '../../Constants/fullfooter.php';
?>
</body>
</html>

