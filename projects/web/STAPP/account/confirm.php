<?php
session_start();
require_once '../Constants/getPath.php';
require_once '../Constants/header.php';
require_once '../Constants/connectvars.php';

$is_confirmed = false;
if(isset($_GET['id']) && isset($_GET['code'])){
	$user_id = $_GET['id'];
	$confirm_code = $_GET['code'];
	$confirmed_account = get_confirmed_account($user_id);
	if($confirm_code == $confirmed_account['confirm_code'])
	{
		$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		$query = "UPDATE confirmed_accounts SET is_confirmed=1 WHERE user_id='$user_id'";
		mysqli_query($dbc, $query);
		$is_confirmed=true;
	}
}
else if($is_confirmed == false)
{
	header('Location: ../index.php');
}

?>

</head>

<body>

<?php require_once '../Constants/navigation.php'; ?>

<div class="text-center">
	<p><img src="../images/tick_icon.png"></img></p>
	<p class="lead">You have successfully confirmed your STAPP Account</p>
	<a href="../user/profile.php">Go to my profile.</a>
</div>

</body>

</html>
