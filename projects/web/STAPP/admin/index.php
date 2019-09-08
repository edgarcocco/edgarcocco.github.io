<?php
require_once 'auth.php';
require_once '../Constants/header.php';
$username = $userRow['username'];
?>
</head>

<body>

<div class="m-3 pl-3 pt-3 border rounded" style="width:40%">
	<h1 class="">STAPP Admin Lobby</h1>
	<div style="width: 300px">
		<div class="list-group">
		  <a href="userlist.php?user_display=stapp" class="list-group-item list-group-item-action list-group-item-secondary">
			List STAPP Registered Users
		  </a>
		  <a href="userlist.php?users_display=google" class="list-group-item list-group-item-action list-group-item-danger">
			List Google Registered Users
		  </a>
		  <a href="hub_list.php" class="list-group-item list-group-item-action list-group-item-primary">
			List All HUBs
		  </a>
		  <a href="ticketlist.php" class="list-group-item list-group-item-action list-group-item-primary">
			List Tickets
		  </a>
		</div>
	</div>
<?php 
	if(isset($_GET['error'])){
		if($_GET['error'] == 'rank'){
		echo'<p class="my-3 alert alert-danger">';
		echo 'You are not allowed to enter, your rank is too low.';
		echo '</p>';
		}
	}
?>
	<div class="d-flex justify-content-end">
		<div class="">
			<p class="alert alert-info my-0 mt-3">
				Logged in as <?php echo $username . ", " . $_SESSION['rank']; ?>
			</p>
		</div>
	</div>
</div>

</body>

</html>
