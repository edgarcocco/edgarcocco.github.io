<?php
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	require_once '../Constants/getPath.php';

	session_start();
	if(isset($_SESSION['user_id']) || !isset($_GET['auth'])){
		$home_url = $rootUrl;
		header('Location: ' . $home_url);
		return;
	}
	require_once('../Constants/header.php');
	require_once('../PHPScripts/functions.php');
	require('../Constants/phpmailerAuth.php');

	$auth = $_GET['auth'];
	$result = "";
	$color = "";
	$isValid = false;
	$isSuccess=false;

	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$query = "SELECT * FROM password_reset WHERE auth='$auth' AND valid=1";
	$data = mysqli_query($dbc, $query);
	if(mysqli_num_rows($data) == 1) {
		$row = mysqli_fetch_array($data);
		$isValid=true;

		if(isset($_POST['submit'])){
			$email = $row['email'];
			$password = $_POST['password'];
			$passwordConfirm = $_POST['passwordConfirm'];
			if($password == $passwordConfirm){
				$query = "UPDATE stapp_user SET password=SHA('$password') WHERE email='$email'";
				mysqli_query($dbc, $query);

				$query = "UPDATE password_reset SET valid=0 WHERE email='$email'";
				mysqli_query($dbc, $query);
				$isSuccess=true;
			}
			else
			{
				$result = "Password doesn't match!";
			}
		}
	}
?>
<link href="../css/signin.css" rel="stylesheet">
</head>

<body>
	<?php if($isSuccess == false) { ?>
	<div class="container text-center">
		<img class="mb-4" src="..\images\stapp_nocolors.png" alt="STAPP Logo" width="72" height="72">
		<div class="align-items-center">
		<?php if(!empty($error_msg)) printf('<div class="alert alert-danger" role="alert">%s</div>', $error_msg); ?>
	  	<h1 class="h3 mb-3 font-weight-normal">New Password</h1>
		<p class="text-center" style="color: <?php echo $color ?>" ><?php echo $result ?></p>
		<?php if($isValid == true){ ?>
		<form class="form-signin border rounded" method="post" action="?auth=<?php echo $auth ?>">
		  <p>Please specify your new password</p>
		  <label for="inputPassword" class="sr-only">Password</label>
		  <input type="password" id="password" name="password" class="form-control my-3" placeholder="New Password" required>
		  <label for="passwordConfirm" class="sr-only">Confirm Password</label>
		  <input type="password" id="passwordConfirm" name="passwordConfirm" class="form-control my-3" placeholder="Confirm Password" required>
		  <button class="btn btn-success btn-block" type="submit" name="submit">Change Password!</button>
	  	</form>
		<?php }
			else { ?>
			<p>This authorization token is not valid!</p>
		<?php } ?>
		</div>
	</div>
	<?php }
		else
		{ 
			echo '<p>well done!</p>';	
		}
?>
	
</body>
</html>
