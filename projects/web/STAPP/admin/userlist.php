<?php
$requiredRank='Owner';
require_once 'auth.php';
require_once '../Constants/header.php';
?>
</head>

<body>
<?php
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$users_display = '';
if(isset($_GET['users_display']))
	$users_display = $_GET['users_display'];
$table = 'stapp_user';
if($users_display == 'stapp')
	$table = 'stapp_user';
else if($users_display == 'google')
	$table = 'google_user';


$query = "SELECT * FROM {$table}";
$data = mysqli_query($dbc, $query);

echo '<div class="container">';
echo '<h1 class="text-center">Listing All Users</h1>';
echo '<table class="table table-striped">';
echo '<tr>';
echo '<th scope="col">ID</th>';
echo '<th scope="col">Username</th>';
echo '<th scope="col">Email</th>';
echo '<th scope="col">Plan</th>';
echo '<th scope="col">Account from</th>';
echo '<th scope="col">Options</th>';
echo '</tr>';
$modal = 0;
while($row = mysqli_fetch_array($data))
{

	$planRow = getPlanRow($row['user_id']);
	$belong = 'stapp';
	if($row['user_id'] > PHP_INT_MAX)
		$belong = 'google';
	echo '<tr>';
	echo "<td> {$row['user_id']} </td>";
	echo "<td> {$row['username']} </td>";
	echo "<td> {$row['email']} </td>";
	echo "<td> {$planRow['current_plan']} </td>";
	if($belong == 'stapp')
		echo '<td class="" width="12%"><img src="../images/stapp_nocolors.png" width="30" height="30" /></td>';
	else
		echo '<td class="" width="12%"><img src="../images/g-plus-icon.png" width="30" height="30" /></td>';

	echo '<td>
		<div class="dropdown">
		  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			Options
		  </button>
		  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
			<a class="dropdown-item" href="edit_subscription?user_id='.$row['user_id'].'">Edit Subscription</a>
			<div class="dropdown-divider"></div>
			<!-- Button trigger modal -->
			<button class="dropdown-item" style="cursor:pointer" type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal' . $modal . '">
			Delete
		</button>
		  </div>
		</div>
		<!-- Modal -->
		<div class="modal fade" id="modal'.$modal.'" tabindex="-1" role="dialog" aria-labelledby="ModalLabel'.$modal.'" aria-hidden="true">
		  <div class="modal-dialog" role="document">
			<div class="modal-content">
			  <div class="modal-header">
				<h5 class="modal-title" id="ModalLabel'.$modal.'">Delete user</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">&times;</span>
				</button>
			  </div>
			  <div class="modal-body">
				Are you sure you want to delete this user?
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<a class="btn btn-primary" href="delete_user.php?id=' . $row['user_id']  . '&from='.$belong.'">Delete</a>			  
			  </div>
			</div>
		  </div>
		</div>
		</td>';
	echo '</tr>';
	$modal += 1;
}
echo '</table>';
echo '<a href="index.php">Go back</a>';
echo '</div>';
?>
</body>
</html>
