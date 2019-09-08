<?php
require_once 'auth.php';
require_once '../Constants/header.php';
require_once '../PHPScripts/functions.php';

$ticketsRow = getTicketData();
?>
</head>
<body>

<div class="container m-5 border rounded">
<?php
echo '<h1 class="text-center">Listing all Tickets</h1>';
echo '<table class="table table-striped">';
echo '<tr>';
echo '<th scope="col">Ticket ID</th>';
echo '<th scope="col">Username</th>';
echo '<th scope="col">Email</th>';
echo '<th scope="col">Department</th>';
echo '<th scope="col">Message</th>';
echo '<th scope="col">State</th>';
echo '<th scope="col"></th>';
echo '</tr>';
while($ticket = mysqli_fetch_array($ticketsRow))
{
	$userRow = getRow($ticket['user_id']);
	echo '<tr>';
	echo '<td>' . $ticket['id'] . '</td>';
	echo '<td>' . $userRow['username'] . '</td>';
	echo '<td>' . $userRow['email'] . '</td>';
	echo '<td>' . $ticket['department'] . '</td>';
	echo '<td><span style="overflow: hidden; white-space:nowrap; text-overflow:ellipsis; width:150px; display:inline-block">' . $ticket['message'] . '</span></td>';
	echo '<td>' . $ticket['state'] . '</td>';
	echo '<td>
		<div class="dropdown">
		  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			Options
		  </button>
		  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
			<a class="dropdown-item" href="openticket?ticketid='.$ticket['id'].'">Open Ticket</a>
			<div class="dropdown-divider"></div>
			<a class="dropdown-item text-danger" href="closeticket.php?ticketid='.$ticket['id'].'">Close Ticket</a>
		  </div>
		</div>
		</td>';
	echo '</tr>';
}
echo '</table>';
echo '<a href="index.php">Go back</a>';

?>
</div>

</body>
