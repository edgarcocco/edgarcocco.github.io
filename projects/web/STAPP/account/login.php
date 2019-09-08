<?php
	session_start();
	require_once '../Constants/getPath.php';
    require_once('../Constants/connectvars.php');
	require_once('../PHPScripts/functions.php');
	require_once('GoogleLogin.php');

	if(isset($_SESSION['access_token']) || isset($_SESSION['user_id'])){
		$home_url = $rootUrl;
		header('Location: ' . $home_url);
		return;
	}

	$loginURL = $gClient->createAuthUrl();

    $error_msg = "";
    //if the user isnt logged in, try to log them in.
    if(!isset($_SESSION['user_id'])) {
        //see if they submitted log in data. [this part handles the login data]
        if(isset($_POST['submit'])){
            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            // Grab the user-entered
            $user_email = mysqli_real_escape_string($dbc, trim($_POST['email']));
            $user_password = mysqli_real_escape_string($dbc, trim($_POST['password']));
            if(!empty($user_email) && !empty($user_password)) {
                $query = "SELECT * FROM stapp_user WHERE email = '$user_email' AND " .
                    "password = SHA('$user_password')";
                $data = mysqli_query($dbc, $query);

                if(mysqli_num_rows($data) == 1) {
                    $row = mysqli_fetch_array($data);
                    $_SESSION['user_id'] = $row['user_id'];
                    $_SESSION['username'] = $row['username'];
					setcookie('user_id', $row['user_id'], time() + (60 * 60 * 24 * 30), '/');
					setcookie('username', $row['username'], time() + (60 * 60 * 24 * 30), '/');
                    $home_url = $rootUrl;
                    header('Location: ' . $home_url);
					return;
                }
                else {
                    //The username and pass are incorrect.
                    $error_msg = 'Sorry, you must enter a valid username and password to log in.';
                }
            }
            else {
                // The username/password weren't entered so set an error message.
                $error_msg = 'Sorry, you must enter your username and password to log in.';
            }
        }
	}
require_once('../Constants/header.php');
?>
<link href="../css/signin.css" rel="stylesheet">
<link href="../css/buttons-si.css" rel="stylesheet">
</head>

<body class="container text-center">
    <img class="mb-4" src="..\images\stapp_nocolors.png" alt="STAPP Logo" width="72" height="72">
    <div class="align-items-center">
	<?php if(!empty($error_msg)) printf('<div class="alert alert-danger" role="alert">%s</div>', $error_msg); ?>
  	<h1 class="h3 mb-3 font-weight-normal">Sign in to STAPP</h1>
    <form class="form-signin" method="post" action="">
      <label for="email" class="sr-only">Email address</label>
      <input type="email" id="email" name="email" class="form-control" placeholder="Email address" required autofocus>

      <label for="inputPassword" class="sr-only">Password</label>
      <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
	  <div class="d-flex justify-content-between">
		<label>
          <input type="checkbox" value="remember-me"> Remember me
        </label>
		<a href="resetpassword.php">Forgot Password?</a>
	  </div>
	<button class="btn btn-lg btn-secondary btn-block" type="submit" name="submit">Sign in</button>
	<div class="alert alert-secondary mb-0 mt-3">
		New user? Create a <a class="alert-link" href="register.php">New Account</a>
	</div>
    </form>
	</div>
	 <span>or... </span>
	<div class="container my-3">
	  <button class="btn-si btn-google" onclick="window.location = '<?php echo $loginURL ?>';" height="46">Sign in with Google</button>
	</div>
	</body>
</html>
