<?php 
	require_once 'auth.php';
	require_once '../Constants/getPath.php';
	require_once '../Constants/header.php';
	require_once '../PHPScripts/functions.php';

	if(!isset($_GET['id']))
	{
		header('Location: index.php');
		return;
	}

	$success = false;
	$id = $_GET['id'];
	$user_from = $_GET['from'];

	if($user_from == 'stapp')
	{
		fullAccountDelete($id);
	}
	else if($user_from == 'google')
	{
		fullAccountDelete($id);
	}
?>
</head>
<body>
<?php 
	echo '<p class="lead">User deleted successfully <a href="userlist.php?users_display='.$user_from. '">Return to User List</a></p>';
?>
</body>
</html>
