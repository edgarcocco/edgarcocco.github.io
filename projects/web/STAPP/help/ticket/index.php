<?php 

session_start();
require_once '../../Constants/getPath.php';
require_once '../../Constants/header.php';
require_once '../../PHPScripts/functions.php';

if(!isset($_SESSION['user_id']))
{
	header('Location: ../support.php?error_code=1');
}
$ticketsRow = getTickets($_SESSION['user_id']);
?>
</html>

<body>
<?php require_once '../../Constants/navigation.php'; ?>
	<div class="row">
		<div class="col-lg-3 mx-1">
			<h3 class="my-1 font-weight-light alert alert-secondary">Navigation</h3>
			<div class="list-group">
				<a href="../support" class="list-group-item list-group-item-action ">
					Support
				</a>
				<a href="../faq" class="list-group-item list-group-item-action ">
					FAQ
				</a>
				<a href="" class="list-group-item list-group-item-action list-group-item-secondary active">
					Ticket
				</a>
				<a href="../tutorials" class="list-group-item list-group-item-action ">
					Tutorials
				</a>
			</div>
		</div>
		<div class="col my-5">
			<h1>View Tickets</h1>
			<hr />
			<div class="d-flex justify-content-end">
				<a class="btn btn-primary my-3" href="send.php">
					Send Ticket
				</a>
			</div>
			<?php
			echo '<table class="table table-striped">';
			echo '<tr>';
			echo '<th scope="col">Ticket ID</th>';
			echo '<th scope="col">Last Updated</th>';
			echo '<th scope="col">Last Answer by</th>';
			echo '<th scope="col">Department</th>';
			echo '<th scope="col">State</th>';
			echo '<th scope="col"></th>';
			echo '</tr>';
			while($ticket = mysqli_fetch_array($ticketsRow))
			{
				$userRow = getRow($ticket['user_id']);
				$ticketUpdate = getLastTicketUpdate($ticket['id']);
				$department = $ticket['department'];
				echo '<tr>';
				echo '<td>' . $ticket['id'] . '</td>';
				echo '<td>' . $ticketUpdate['last_updated'] . '</td>';
				echo '<td>' . $ticketUpdate['last_answer_by'] . '</td>';
				echo '<td>';
				if($department == 'account')
				{
					echo 'Account';
				}
				else if($department == 'paymentOptions')
				{
					echo 'Payment Issue';
				}
				else if($department == 'support')
				{
					echo 'User Support';
				}
				echo '</td>';
				$ticketState = $ticket['state'];
				$ticketState[0] = strtoupper($ticketState[0]);
				$text_mode = "";
				if($ticket['state'] == 'open')
					$text_mode="text-success";
				else
					$text_mode="text-danger";
				echo '<td class="' . $text_mode . '">' . $ticketState .'</td>';
				echo '<td>';
				echo '<form class="" method="get" action="viewticket">';
				echo '<input type="hidden" name="ticketId" id="ticketId" value="'. $ticket['id'] . '"></input>';
				echo '<button type="submit" name="submit" class="btn">Open Ticket</button>';
				echo '</form>';
				echo '</td>';
				echo '</tr>';
			}
			echo '</table>';
			?>
		</div>

	</div>
<?php require_once '../../Constants/fullfooter.php'; ?>
</body>

