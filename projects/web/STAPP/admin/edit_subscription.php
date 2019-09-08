<?php
use PayPal\Api\Agreement;
use PayPal\Api\AgreementDetails;

require "../Checkout/app/start.php";

$requiredRank = "Owner";
require_once 'auth.php';
require_once '../Constants/header.php';
require_once '../Constants/connectvars.php';
$user_id = "";
if(isset($_GET['user_id']))
	$user_id = $_GET['user_id'];
else
	header('Location: index.php');

$userRow = getRow($user_id);
$userPlan = getPlanRow($user_id);
//$agreement_check = Agreement::get($userPlan['agreement_id'], $apiContext);
//$agreement_details = $agreement_check->getAgreementDetails();

if(isset($_POST['submit']))
{
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$timeby = $_POST['timeSelect'];
	$quantity = $_POST['quantity'];
	$plan_expiration = $userPlan['plan_expiration'];
	if($timeby == 'day')
	{
		if($quantity > 31)
		{
			header('Location: edit_subscription.php?user_id='.$user_id. '&error=time');
		}
	}
	if($timeby == 'month')
	{
		if($quantity > 12)
		{
			header('Location: edit_subscription.php?user_id='.$user_id. '&error=time');
		}
	}
	if($timeby == 'year')
	{
		if($quantity < date('Y'))
		{
			header('Location: edit_subscription.php?user_id='.$user_id. '&error=time');
		}
	}
	$query = 'UPDATE user_plan 
					SET plan_expiration = DATE_ADD(\''.$plan_expiration.'\', INTERVAL '.$quantity.' ' . $timeby.') WHERE user_id = '.$user_id;
	mysqli_query($dbc, $query);	
	$userPlan = getPlanRow($user_id);

	$datePaypalFormatted = date("c", strtotime($userPlan['plan_expiration'])); 
	//$agreement_details->setNextBillingDate($datePaypalFormatted);
}

$renew_date = new DateTime($userPlan['plan_expiration']);

?>


</head>

<body>
<div class="border rounded w-50 p-3 my-5" style="margin: 0 auto;">
	<h1>Edit STAPP Subscription</h1>
	<?php
	$plan = $userPlan['current_plan'];
	$plan[0] = strtoupper($plan[0]);
	echo '<p class="alert alert-info">This user have acquired the <b>' . $plan . '</b> plan</p>';
	?>

	<table class="table">
	  <thead>
		<tr>
		</tr>
	  </thead>
	  <tbody>
		<tr>
		  <th scope="row">ID</th>
		  <td><?php echo $user_id; ?></td>
		</tr>
		<tr>
		  <th scope="row">Username</th>
		  <td><?php echo $userRow['username']; ?></td>
		</tr>
		<tr>
		  <th scope="row">Email</th>
		  <td><?php echo $userRow['email']; ?></td>
		</tr>
		<tr>
		  <th scope="row">Plan</th>
		  <td><?php echo $plan; ?></td>
		</tr>
		<tr>
		  <th scope="row">Next payment cycle</th>
		  <td><?php echo $renew_date->format('M d, Y'); ?></td>
		</tr>
	  </tbody>
	</table>
	<form action="?user_id=<?php echo $user_id; ?>" method="post">
	Change next payment cycle to:<br>
	<table>
	<tr>
		<td>
			<input class="form-control" type="number" name="quantity" min="1"/>
		</td>
		<td>
			<select class="form-control" name="timeSelect" id="timeSelect">
				<option value="day">Days</option>
				<option value="month">Month</option>
				<option value="year">Year</option>
			</select>
		</td>
		<td>
			<button class="btn" type="submit" name="submit">Submit</button>
		</td>
	</tr>
	</table>
	</form>
	
	<a class="btn btn-info" href="index.php">Go Back </a>
</div>
</body>
</html>
