<?php 

session_start();
require_once '../../Constants/getPath.php';
require_once '../../Constants/header.php';
require_once '../../PHPScripts/functions.php';

if(!isset($_SESSION['user_id']))
{
	header('Location: ../support.php?error_code=1');
}

if(!isset($_GET['ticketId']))
{
	header('Location: ../support.php');
}

$userRow = getRow($_SESSION['user_id']);
if(isset($_POST['submit']) )
{
	if(!empty($_POST['replymessage'])){
	$username = $userRow['username'];	
	$replyMessage = $_POST['replymessage'];
	$ticketId = $_GET['ticketId'];
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$query = "INSERT INTO ticket_answer (ticket_id, is_mod, answerby, answer, created_date)
				VALUES ('$ticketId', 0, '$username', '$replyMessage', NOW())";
	$result = mysqli_query($dbc, $query);
	$query = "INSERT INTO ticket_updates (ticket_id, last_updated, last_answer_by) 
						VALUES('$ticketId', NOW(), '$username')";
	mysqli_query($dbc, $query);
	mysqli_close($dbc);
	header("Refresh:0");
	}
	else
	{
		$errorMsg = "Reply Message cannot be empty!";
	}
}


$ticketsRow = getTickets($_SESSION['user_id']);
$ticketRow = 0;
while($ticketRow = mysqli_fetch_array($ticketsRow))
{
	if($ticketRow['id'] == $_GET['ticketId'])
	{
		break;
	}
}

if($_SESSION['user_id'] != $ticketRow['user_id'])
{
	header('Location: ../support.php?error_code=2');
}

$ticketAnswerData = getTicketAnswers($ticketRow['id']);
?>
</html>

<body>
<?php require_once '../../Constants/navigation.php'; ?>

<div class="container">
	<div class="row">
		<div class="col-lg-3 my-5">
			<div class="list-group">
				<a href="../support.php" class="list-group-item list-group-item-action ">
					Support
				</a>
				<a href="../faq.php" class="list-group-item list-group-item-action ">
					FAQ
				</a>
				<a href="index.php" class="list-group-item list-group-item-action list-group-item-secondary active">
					Ticket
				</a>
			</div>
		</div>
		<div class="col">
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

			echo '<p><b>Category: </b>' . $ticketRow['category'] . '</p>';
			$ticketState = $ticketRow['state'];
			$ticketState[0] = strtoupper($ticketState[0]);
			echo '<p><b>State:</b> ' . $ticketState . '</p>';
			echo '<p><b>Creation date:</b> ' . $ticketRow['created_date'] . '</p>';

			echo '<p><b>Message:</b></p>';

			echo '<div class="p-1" style="background: #FEFEFE">';
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

			echo '</div>';

			if(isset($errorMsg)){
				echo '<p class="alert alert-danger my-4">'.$errorMsg.'</p>';
			}
			if($ticketRow['state'] == 'open')
			{
				echo '<p class="lead">Reply:</p>';
		?>
		<form action="?ticketId=<?php echo $ticketRow['id']; ?>" method="post">
			<textarea name="replymessage" cols="25" rows="10" class="w-100"></textarea>
			<button class="btn" type="submit" name="submit">Send Reply</button>
		</form>
		<?php
			}
		?>
		</div>
		</div>
	</div>
</div>

<?php require_once '../../Constants/fullfooter.php'; ?>
</body>
