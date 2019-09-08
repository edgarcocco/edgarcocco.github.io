<?php

use PayPal\Api\Agreement;
use PayPal\Api\Patch;
use PayPal\Api\PatchRequest;

session_start();
require_once "../Constants/getPath.php";
require_once "../Constants/header.php";
require_once "../Constants/connectvars.php";
require_once "../PHPScripts/functions.php";
require_once "../Checkout/app/start.php";
if(!$_SESSION['user_id'])
{
    header('Location: ../');
}


$userRow = getRow($_SESSION['user_id']);
$userPlan = getPlanRow($_SESSION['user_id']);
if(isset($_POST['submit']))
{
	$agreementId = $userPlan['agreement_id'];
	$patch = new Patch();

	$patch->setOp('replace')
		->setPath('/')
		->setValue(json_decode('{
					"auto_bill_amount": ""
				}'));
	$patchRequest = new PatchRequest();
	$patchRequest->addPatch($patch);

	try{
		$agreement = Agreement::get($agreementId, $apiContext);
		$agreement->update($patchRequest, $apiContext);
	} catch(Exception $ex){
		echo $ex->getCode();
		echo $ex->getData();
	}
	echo '<pre>';
	echo var_dump($agreement);
	echo '</pre>';
}
?>
</head>

<body>
<?php require_once "../Constants/navigation.php";?>

<div class="container my-5">
	<?php
	if(!empty($error_msg)){
		echo '<div class="alert alert-danger">';
		echo $error_msg;
		echo '</div>';
	}
	?>
	<form method="POST" action="">
		<div class="row">
			<div class="col">
				<p class="lead"><b>Plan Info</b></p>
			</div>
			<div class="col border rounded">
				<div class="row">
					<div class="col">
						<p>Auto renew</p>
					</div>
					<div class="col">
						<div class="custom-control custom-radio custom-control-inline">
						<input oninput="enableSaveBtn()" type="radio" id="enableAutoRenew" name="autorenew" class="custom-control-input">
						<label class="custom-control-label" for="enableAutoRenew">Enable</label>
						</div>
						<div class="custom-control custom-radio custom-control-inline">
						<input  oninput="enableSaveBtn()" type="radio" id="disableAutoRenew" name="autorenew" class="custom-control-input">
						<label class="custom-control-label" for="disableAutoRenew">Disable</label>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="my-2">
			<div class="d-inline-flex">
				<a class="btn btn-outline-success mb-2 mx-2" href="profile.php">Go Back</a>
			</div>
			<div class="d-inline-flex" style="float: right">
				<button class="btn btn-info mb-2" type="submit" name="submit" id="saveBtn" disabled>Save Info</button>
			</div>
		</div>
	</form>
</div>
<script type="text/javascript">
	var radio1 = document.getElementById('enableAutoRenew');
	var radio2 = document.getElementById('disableAutoRenew');
	var isEnabled = "<?php echo $userPlan['auto_renew']; ?>";
	
	if(isEnabled == 1)
		radio1.checked = true;
	else
		radio2.checked = true;

	function enableSaveBtn(){
	var saveBtn = document.getElementById("saveBtn");
	saveBtn.removeAttribute("disabled");
	}
</script>
<?php require_once "../Constants/fullfooter.php";?>
</body>
