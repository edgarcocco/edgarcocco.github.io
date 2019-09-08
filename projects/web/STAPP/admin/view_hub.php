<?php
use PayPal\Api\Agreement;
use PayPal\Api\AgreementDetails;

require "../Checkout/app/start.php";

$requiredRank = "Owner";
require_once 'auth.php';
require_once '../Constants/header.php';
require_once '../Constants/connectvars.php';
$hub_id = "";
if(isset($_GET['id']))
	$hub_id = $_GET['id'];
else
	header('Location: index.php');

?>


</head>

<body>
<div class="border rounded w-50 p-3 my-5" style="margin: 0 auto;">
	<h1>View HUB Info</h1>
	<?php
		$hub = get_hub_by_id($hub_id);
		$hub_info = get_hub_info($hub_id);
		//not fetched
		$hub_stats = get_hub_stats($hub_id);
		$hub_plans = get_hub_plans($hub_id);
		//--
		$userRow = getRow($hub['user_id']);
	?>

	<table class="table">
	  <thead>
		<tr>
		</tr>
	  </thead>
	  <tbody>
		<tr>
		  <th scope="row">HUB ID: </th>
		  <td><?php echo $hub['id'] ?></td>
		</tr>
		<tr>
		  <th scope="row">Username: </th>
		  <td><?php echo $hub['username'] ?></td>
		</tr>
		<tr>
		  <th scope="row">Email: </th>
		  <td><?php echo $userRow['email'] ?></td>
		</tr>
		<tr>
		  <th scope="row">HUB Name: </th>
		  <td><?php echo $hub_info['name'] ?></td>
		</tr>
		<tr>
		  <th scope="row">Pictures (RAW Data): </th>
		  <td><?php echo $hub_info['pictures'] ?></td>
		</tr>
	  </tbody>
	</table>
	<small>See more...</small>
	<p>
	  <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapsePlan" aria-expanded="false" aria-controls="collapsePlan">
		Detailed Plan Info
	  </button>
	</p>
	<div class="collapse my-2" id="collapsePlan">
	  <div class="card card-body">
		<div class="">
			<?php
				$hub_plan_purchases = get_hub_plan_purchases($hub_id);
				$totalEarned = 0.00;
				while($hub_plan_row = mysqli_fetch_array($hub_plan_purchases)){
					$totalEarned += $hub_plan_row['amount'];
				}
				echo '<h1>Total Earned: '.$totalEarned.'$</h1>';
				while($hub_plan_row = mysqli_fetch_array($hub_plans))
				{
					echo '<h2>'.$hub_plan_row['plan_title'].'</h2>';
					echo '<p class="lead">Price: '.$hub_plan_row['price'] . '</p>';
					$hub_plan_purchases = get_hub_plan_purchases_by_plan($hub_plan_row['id']);
					$totalEarned = 0.00;
					echo '<p class="lead">Purchases: '. mysqli_num_rows($hub_plan_purchases).'</p>';;
					while($hub_plan_purchases_row = mysqli_fetch_array($hub_plan_purchases)){
						$totalEarned += floatval($hub_plan_purchases_row['amount']);
					}
					echo '<p class="lead">Earned: ' . $totalEarned . '$</p>';
				}
			?>
		</div>
	  </div>
	</div>
	<p>
	<a class="btn btn-info" href="index.php">Go Back </a>
	</p>
</div>
</body>
</html>
