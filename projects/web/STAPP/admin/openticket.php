<?php
require_once 'auth.php';
require_once '../Constants/header.php';
require_once '../Constants/connectvars.php';
require_once '../PHPScripts/functions.php';


$ticketId = $_GET['ticketid'];
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$query = "SELECT * FROM tickets WHERE id='$ticketId'";
$data = mysqli_query($dbc, $query);
$ticketRow = mysqli_fetch_array($data);
$userRow = getRow($ticketRow['user_id']);
$adminRow = getRow($_SESSION['user_id']);
$ticketAnswerData = getTicketAnswers($ticketId);
if(isset($_POST['submit']) )
{
	if(!empty($_POST['replymessage'])){
	$adminUsername = $adminRow['username'];	
	$replyMessage = $_POST['replymessage'];
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$query = "INSERT INTO ticket_answer (ticket_id, is_mod, answerby, answer, created_date)
				VALUES ('$ticketId', 1, '$adminUsername', '$replyMessage', NOW())";
	$result = mysqli_query($dbc, $query);
	$query = "INSERT INTO ticket_updates (ticket_id, last_updated, last_answer_by) 
						VALUES('$ticketId', NOW(), '$adminUsername')";
	mysqli_query($dbc, $query);
	mysqli_close($dbc);
	header("Refresh:0");
	}
	else
	{
		$errorMsg = "Reply Message cannot be empty!";
	}
}
?>

</head>

<body>

<div class="container border rounded w-75 my-5">

<?php
$department = $ticketRow['department'];

echo '<div class="m-3">';
echo '<h2 class="my-3">'.$userRow['username'].'\'s Ticket #'.$ticketRow['id'].'</h2>';

if($department == 'account')
{
	echo '<p><b>Department:</b> Account</p>';
}
else if($department == 'paymentOptions')
{
	echo '<p><b>Department:</b> Payment Issue</p>';
}
else if($department == 'support')
{
	echo '<p><b>Department:</b> User Support</p>';
}

$ticketRow['state'][0] = strtoupper($ticketRow['state'][0]);
echo '<p><b>Category: </b>' . $ticketRow['category'] . '</p>';
echo '<p><b>State:</b> ' . $ticketRow['state'] . '</p>';
echo '<p><b>Creation date:</b> ' . $ticketRow['created_date'] . '</p>';

echo '<p><b>Message:</b></p>';
echo '<div class="alert-primary px-3 rounded" style="margin-right:20%">';
echo $ticketRow['message'];
echo '<span class="d-flex justify-content-end m-0 p-0">'.$ticketRow['created_date'].'</span>';
echo '</div>';

if(mysqli_num_rows($ticketAnswerData) > 0){
	while($answerRow = mysqli_fetch_array($ticketAnswerData)){
	if($answerRow['is_mod'] == 1)
		echo '<div class="alert-primary my-4 px-3 rounded" style="margin-left:20%">';
	else
		echo '<div class="alert-primary my-4 px-3 rounded" style="margin-right:20%">';
	echo nl2br($answerRow['answer']);
	echo '<span class="d-flex justify-content-end m-0 p-0">'.$answerRow['created_date'].'</span>';
	echo '</div>';
	}
}

if(isset($errorMsg)){
	echo '<p class="alert alert-danger my-4">'.$errorMsg.'</p>';
}
echo '<p class="lead">Reply:</p>';
?>

<form action="?ticketid=<?php echo $_GET['ticketid'];?>" method="post">
	<textarea name="replymessage" cols="25" rows="10" class="w-100"></textarea>
	<button class="btn" type="submit" name="submit">Send Reply</button>
</form>

<a class="btn btn-info my-3" href="ticketlist">Go Back</a>
<div class="d-flex justify-content-end">
	<div class="">
		<p class="alert alert-info my-0 mt-3">
			Logged in as, <?php echo $userRow['username'] ?>
		</p>
	</div>
</div>

<?php
	echo '</div>';
?>

</div>

</body>
