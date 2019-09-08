<?php
use PayPal\Api\Agreement;
use PayPal\Api\AgreementStateDescriptor;

session_start();
require_once "../Constants/getPath.php";
require_once "../Constants/header.php";
require_once "../Constants/connectvars.php";
require_once "../PHPScripts/functions.php";
require_once "../Checkout/app/start.php";
if(!isset($_SESSION['user_id']))
{
    header('Location: ../');
	return;
}

$planRow = getPlanRow($_SESSION['user_id']);

if(isset($_POST['cancelSubmit']))
{
	$agreementStateDescriptor = new AgreementStateDescriptor();
	$agreementStateDescriptor->setNote("Suspending Regular Plan Agreement");

	$createdAgreement = new Agreement();
	$createdAgreement->setId($planRow['agreement_id']);

	try{
		$agreementId = $planRow['agreement_id'];
		$createdAgreement->cancel($agreementStateDescriptor, $apiContext);

		$agreement = Agreement::get($createdAgreement->getId(), $apiContext);

		$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		$query = "UPDATE user_plan SET cancelled = 1 WHERE agreement_id = '$agreementId'";
		mysqli_query($dbc, $query);
		mysqli_close($dbc);
	}
	catch(Exception $ex){
		echo $ex;
	}
}
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

		<div class="col">
			<div class="row my-5">
				<div class="col-lg-4">
					<p class="lead" style="font-size: 32px">Plan Info</p>
				</div>
				<div class="col border rounded">
					<?php if(!empty($planRow['current_plan'])){
					$now = time();
					$expiration_date = strtotime($planRow['plan_expiration']);
					$datediff = $expiration_date - $now;
					$canRenew = $now > $expiration_date;
					$daysLeft =  round($datediff / (60 * 60 * 24));
					 ?>
					<div class="row">
						<div class="col">
							<p><b>Current Plan</b></p>
						</div>
						<div class="col">
							<p class="justify-content-end">
								<b>
								<?php
									$first_upper = strtoupper($planRow['current_plan'][0]);
									$planRow['current_plan'][0] = $first_upper;
									if($canRenew == 1)
										echo 'No plan';
									else
										echo $planRow['current_plan'];
								?>
								</b>
							</p>
						</div>
					</div>

					<div class="row">
						<div class="col">
							<p><b>Plan Expiration</b></p>
						</div>
						<div class="col">
							<?php
							$alert = 'alert-info';
							if ($planRow['cancelled'] == 1)
								$alert = 'alert-danger';
							else if($daysLeft <= 5 && $daysLeft >= 1)
								$alert = 'alert-warning';
							else if($daysLeft == 0)
								$alert = 'alert-danger';
							?>
								<p class="justify-content-end alert <?php echo $alert; ?>">
								<b>
								<?php
									if($canRenew == 0){
										echo $daysLeft . ' Days Left';
										echo '<br/>';
										echo $planRow['plan_expiration'];
									}
									else
										echo 'No expiration date';

									if($planRow['cancelled'] == 1){
										if($canRenew == 0) {
										echo '<p>Your current plan has been cancelled,
										but your paid month is still active!</p>';
										echo '<p>You can re-buy a new plan after your
													paid month is over</p>';
										}
										else
										{
											echo '<p><a class="btn btn-info" href="../pricing/">See Pricing</a></p>';
										}
									}
								?>
								</b>
								</p>
						</div>
					</div>
					<?php 
					//START PLAN OPTION CONDITION
					if($planRow['cancelled'] == 0 && $planRow['current_plan'] != 'Trial') { ?>
				<!--	<div class="row">
						<div class="col">
							<p><b>Plan Options</b></p>
						</div>
						<div class="col">

							<button type="button" class="btn btn-danger mx-2 mb-2" data-toggle="modal" data-target="#cancelModal">
							Cancel Plan
							</button>

							<!-- Modal -->
							<div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="CancelModal" aria-hidden="true">
							  <div class="modal-dialog modal-dialog-centered" role="document">
								<div class="modal-content">
								  <div class="modal-header">
									<h5 class="modal-title" id="exampleModalLongTitle">Cancel Plan</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									  <span aria-hidden="true">&times;</span>
									</button>
								  </div>
								  <div class="modal-body">
									Do you want to cancel your plan?
								  </div>
								  <div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
									<form action="" method="post">
										<button type="submit" name="cancelSubmit" class="btn btn-primary">Yes</button>
									</form>
								  </div>
								</div>
							  </div>
							</div>

						</div>
					</div>
					<?php //END PLAN OPTION CONDITION
						} ?>
					<!--
					<div class="row">
						<div class="col">
							<p><b>Auto Renew</b></p>
						</div>
						<div class="col">
							<p>Enabled</p>
						</div>
					</div>

					<div class="row">
						<div class="col">
							<a class="btn btn-light border py-1 my-2" href="edit_plan.php">Edit</a>
						</div>
					</div>
					-->


					<?php } else { ?>
					<div class="row">
						<p>No plan</p>
					</div>
					<?php }?>
			</div>
		</div>
		</div>
	</div>
	</div>
	<?php require_once '../Constants/fullfooter.php'; ?>
</body>


