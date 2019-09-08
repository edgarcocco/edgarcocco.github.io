<?php
session_start();
require '../Checkout/app/start.php';
require_once "../Constants/getPath.php";
require_once "../Constants/header.php";
require_once "../Constants/connectvars.php";
require_once "../PHPScripts/functions.php";

if(!isset($_SESSION['user_id']))
{
    header('Location: ../');
	return;
}
$row = getRow($_SESSION['user_id']);
$user_id = $row['user_id'];
$username = $row['username'];
/* if paypal review succeed lets use this
if(isset($_GET['code']))
{
	$code = $_GET['code'];
	$plogin = new PPIntegratedLogin($apiContext);
	$bearerToken = $plogin->getBearerAccessToken($code);
	$userinfo = $plogin->getUserInfo($bearerToken);
	$paypalEmail = $userinfo["email"];
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$query = "INSERT INTO paypal_credentials (user_id, paypal_email, confirmed)
				VALUES('$user_id', '$paypalEmail',1)";
	mysqli_query($dbc, $query);
	header('Location: hubmanager.php');
}
 */
if(isset($_POST['submit']))
{
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	
	$query = "INSERT INTO hub (user_id, username, visibility) VALUES ($user_id, '$username', 0)";
	mysqli_query($dbc, $query);

	$hub_id = get_hub_id($row['user_id']);

	$query = "INSERT INTO hub_info (hub_id, name, biography) VALUES ($hub_id, 'MyName', 'Tell us something about you')";
	mysqli_query($dbc, $query);

	$query = "INSERT INTO hub_stats (hub_id) VALUES ($user_id)";
	mysqli_query($dbc, $query);

	$query = "INSERT INTO hub_plans (hub_id) VALUES ($user_id)";
	mysqli_query($dbc, $query);

	mysqli_close($dbc);
	mkdir('../hub_images/' . $row['username'] . '/', 0777, true);
}
$hub_id = get_hub_id($user_id);
$user_paypal_creds = get_paypal_credentials($user_id);

?>

</head>

<body>
<?php require_once '../Constants/navigation.php'; ?>

<div class="container">
	<div class="row">
		<div class="col-lg-3 my-5 border-right">
			<?php 
				$base_file = basename(__FILE__, '.php'); 
				require_once '../Constants/settings_navigation.php';
			?>
		</div>
		<div class="col ml-5">
			<div class="row my-5">
				<div class="col-lg-4">
					<p class="lead" style="font-size: 32px">My HUB</p>
				</div>
				<div class="col">
					<?php		
						if(empty($hub_id)){
						echo '<p>You currently dont have a hub<p>';
						echo '<form method="post" action="">';
						echo '<button type="submit" name="submit" class="btn btn-success">Create a HUB</button>';
						echo '</form>';
						}
						else
						{
							echo '<p class="lead">You have a HUB, you can start editing your HUB at anytime by clicking here</p>';
							echo '<a href="hubeditor" class="btn btn-light mr-2 border rounded">Edit HUB</a>';
							echo '<a href="../hub/'.$username.'" class="btn btn-light border rounded">Preview</a>';
							if($user_paypal_creds == NULL){
								echo '<p class="lead alert alert-info my-2" style="color: black">Start receiving money by linking your <a href="hubearnings">earning account</a> to STAPP </p>';
							}
						}
					?>
					<!--<span id='lippButton'></span>
					<script src='https://www.paypalobjects.com/js/external/api.js'>
					</script>
					<script>
					paypal.use( ['login'], function (login) {
					  login.render ({
						"appid":"AVnTaQJiUKoKWyAPfWo5RJmfzp9LFLP9ANUSVYAABSPIJjwIUzuWLuGikdSNvsT3mFH6ETwth05tFgh8",
						//"appid":"AYgACbU5lk6vlvX6WeL9FLR732eK8SnPtxUx8TYaoCb9hz93oWzszFkbIu7aw2dHX6GSZctX5HSZzDxZ",
						"authend":"sandbox",
						"scopes":"openid email",
						"containerid":"lippButton",
						"locale":"en-us",
						"returnurl":"http://localhost/STAPP/pplogin/"
					  });
					});
					</script>
					-->
					<?php //}else{ echo '<p class="my-1 lead alert alert-success">Your paypal account has been linked!</p>';}}?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php require_once '../Constants/fullfooter.php'; ?>
</body>

</html>
