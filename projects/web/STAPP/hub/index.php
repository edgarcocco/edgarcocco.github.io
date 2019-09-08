<?php
	session_start();
	
	require_once '../Constants/getPath.php';
	require_once '../Constants/header.php';
	require_once '../PHPScripts/functions.php';
	require '../Checkout/app/start.php';
	require '../stripe/vendor/stripe/stripe-php/init.php';
	use PayPal\Api\Amount;
	use PayPal\Api\Details;
	use PayPal\Api\Item;
	use PayPal\Api\ItemList;
	use PayPal\Api\Payee;
	use PayPal\Api\Payer;
	use PayPal\Api\Payment;
	use PayPal\Api\RedirectUrls;
	use PayPal\Api\Transaction;

	\Stripe\Stripe::setApiKey($stripe_secret_key);

	if(isset($_GET['usr'])){
		$user_hub = $_GET['usr'];
	}
	else
		header('Location: ../index');

	define('HUB_UPLOADPATH', '../hub_images/' . $user_hub . '/');
	if(isset($_SESSION['username'])){
		// this is our account hub id if there is any HUB!.
		$user_hub_id = get_hub_id($_SESSION['user_id']);
	}
	
	
	$hub = get_hub($user_hub);
	$hub_id = get_hub_id($user_hub);
	$hub_info = get_hub_info($hub_id);
	$hub_stats = get_hub_stats($hub_id);
	$hub_plans = get_hub_plans($hub_id);
	$hub_links = get_hub_links($hub_id);
	$userRow = getRowByUsername($user_hub);
	// this is the HUB owner user id
	$user_id = $userRow['user_id'];
	$hub_owner_plan = getPlanRow($userRow['user_id']);
	$receiver_email = get_paypal_credentials($hub['user_id'])['paypal_email'];
	if(isset($_POST['paypalSubmit']) || isset($_POST['stripeToken']))
	{
		$planCount = 0;
		$planIndex = $_POST['planIndex'];
		$social_username = $_POST['social_username'];
		$social_email_address = $_POST['social_email_address'];
		while($planRow = mysqli_fetch_array($hub_plans))
		{
			if($planCount == $planIndex)
			{
				$hub_plan_id = $planRow['id'];
				$planTitle = $planRow['plan_title'];
				$price = $planRow['price'];

				$return_url = $rootUrl."Checkout/hubpayment.php?hub_plan_id=".$hub_plan_id."&social_username=".$social_username."&social_email_address=".$social_email_address;
			
				// if user wants to check out with paypal
				if(isset($_POST['paypalSubmit'])){
					$payer = new Payer();
					$payer->setPaymentMethod("paypal");

					$planItem = new Item();
					$planItem->setName($planTitle)
						->setCurrency("USD")
						->setQuantity(1)
						->setSku($hub_plan_id)
						->setPrice($price);
					$itemList = new ItemList();
					$itemList->setItems(array($planItem));

					$amount = new Amount();
					$amount->setCurrency("USD")
						->setTotal($price);

					$payee = new Payee();
					$payee->setEmail($receiver_email);

					$transaction = new Transaction();
					$transaction->setAmount($amount)
						->setItemList($itemList)
						->setDescription("HUB Plan")
						->setPayee($payee)
						->setInvoiceNumber(uniqid());

					$redirectUrls = new RedirectUrls();
					$redirectUrls->setReturnUrl($return_url."&payment_platform=paypal")
						->setCancelUrl($rootUrl);

					$payment = new Payment();
					$payment->setIntent("sale")
						->setPayer($payer)
						->setRedirectUrls($redirectUrls)
						->setTransactions(array($transaction));

					$request = clone $payment;
					try{
						$payment->create($apiContext);
						$approvalUrl = $payment->getApprovalLink();
						header('Location: '.$approvalUrl);
					}
					catch(Exception $ex){
						var_dump(json_decode($ex->getData()));
					}
				}
				// or else user wants to check out with stripe
				// we check if we get a stripeToken
				if(isset($_POST['stripeToken'])){
					$token = $_POST['stripeToken'];
					$hub_stripe_credentials = get_stripe_credentials($user_id);
					try{
						$charge = \Stripe\Charge::create([
							'amount' => $price * 100,//this is in cents
							'currency' => 'usd',
							'description' => $planTitle . ', '.$userRow['username'].'\'s HUB Plan',
							'source' => $token,
							'destination' => [
								"account" => $hub_stripe_credentials['stripe_user_id'],
								],
						]);
						$payment_id = uniqid();
						$token = $_POST['stripeToken'];
						$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
						$query = "INSERT INTO transaction_stripe(user_id, payment_id, complete, date)
							VALUES ($user_id, '$payment_id', 0, NOW())";
						mysqli_query($dbc, $query);
						mysqli_close();
					}
					catch(Exception $e){
						$message = $e->getMessage();
						returnErrorWithMessage($message);
					}
					header('Location: '.$return_url."&payment_platform=stripe&paymentId={$payment_id}");
				}
			}
			$planCount++;
		}
		
	}
?>
<link rel="stylesheet" href="../css/hubstyle.css"></link>
<script src="../scripts/hub.js"></script>
</head>

<body>
	<?php require_once '../Constants/navigation.php'; 
	
	 if($hub['visibility'] == 0 && $hub['user_id'] != $_SESSION['user_id']){
		 echo '<div class="container text-center m-5" >
			 	<img src="../images/exclamation_sign.png" />
			 	<p class="lead" style="font-size: 36px">The HUB you are trying to access is private! Come back soon!</p>
			   </div>';
	 } else {?>
	<div class="container py-5" style="min-height: 512px">
		<div class="d-flex justify-content-between">
			<?php
				 if($hub['visibility'] == 0)
				 {
					 echo '<span class="alert alert-warning">This HUB is <b>private</b> preventing all users to view, only HUB administrator is allowed to view, Edit the HUB to change this.</span>';
				 }
				 else
					 echo '<span></span>';
			?>
			<?php
			if(isset($user_hub_id)){
				if($hub_id == $user_hub_id){
					echo '<a href="../user/hubeditor" class="btn btn-light border rounded">Edit HUB</a>';
				}
			}
			?>
		</div>
		<div class="row">
			<div class="col-lg-4 col-md-6 col-xs-6">
				<div class="row">
					<div class="col">
						<?php
						if(!empty($hub_info['pictures']))
						{
							$pictures = explode(';', $hub_info['pictures']);
							echo '<div class="border rounded largepic" style="">';
							echo '<img id="imgpreview" src="'.HUB_UPLOADPATH .$pictures[0].'"></img>';
							echo '</div>';
						}
						else{
							echo '<div class="border rounded largepic" style="">';
							echo '<p class="text-muted lead" id="nopicadvice" style="">No picture added</p>';
							echo '</div>';
						}
						?>
					</div>
				</div>
				<div class="row ">
					<div class="col mt-2">
						<div class="card border-secondary" style="width:20rem">
						  <div class="card-header d-flex justify-content-between py-1">
							  <span class="my-2">Stats</span>
						  </div>
						  <div class="card-body text-secondary" id="statbody1">
							<div id="statbody2">
							<?php
								//loop through each hub stats and display them here
								if(mysqli_num_rows($hub_stats) > 0){
								while($row = mysqli_fetch_array($hub_stats))
								{
									$stat_id = $row['id'];
									echo '
											<div class="d-flex justify-content-around">
												<p class="card-text"><b>'.$row['stat_title'].': </b>'.$row['stat_body'].'</p>
												<button class="btn btn-danger" style="display:none" id="statDeleteBtn'.$stat_id.'" onclick="deleteStatDialog('.$stat_id.')">Del</button>
											</div>';
								}
								}
							?>
							</div>
						  </div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-4 col-md-5 col-xs-6">
				<div class="row">
					<div class="col ">
						<p class="display-4 text-muted hubusername"><?php echo $hub_info['name'] ?></p>
					</div>
				</div>
				<div class="row mb-5" id="thumbrow">
					<div class="col " id="thumbcol">
						<?php
						$pictures = explode(";", $hub_info['pictures']);
							$picturesLength = count($pictures);
							$countForRow=0;
							$i=0;
							echo '<div class="d-flex fleximg">';

							for($picturesLength=$picturesLength; $picturesLength > 0; $picturesLength--)
							{
								if(empty($pictures[$i]))
									continue;
								if($countForRow==4)
								{
									$countForRow=0;
									echo '</div>';
									echo '<div class="d-flex fleximg">';
								}
								echo '<div class="p-2 smallpic">';
								echo '<img class="" onclick="thumbSelected(this)" src="' . HUB_UPLOADPATH . $pictures[$i] . '"></img>';
								echo '</div>';
								$countForRow++;
								$i++;
							}
							echo '</div>';
						?>
						<!--<div class="p-2 smallpic" id="previewImage">
						</div>-->
					</div>
				</div>
				<div class="row">
					<div class="col">
						<div class="card border-secondary mt-1 ml-1" id="biocard">
						  <div class="card-header d-flex justify-content-between py-1">
							  <span class="my-2">Biography</span>
						  </div>
						  <div class="card-body text-secondary">
							<div class="biocontainer">
								<p class="biop">
									<?php
										echo $hub_info['biography'];
									?>
								</p>
							</div>
						  </div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col">
						<div class="card border-secondary mt-1 ml-1" style="width:20rem">
							<div class="card-header d-flex justify-content-between py-1">
								<span class="my-2">Links</span>
							</div>
							<div class="card-body text-secondary" id="linksbody1">
								<div id="linksbody2">
									<?php
									//loop through each hub stats and display them here
									if(mysqli_num_rows($hub_links) > 0){
										while($row = mysqli_fetch_array($hub_links)){
											echo '<p><a href="'.$row['link'].'">'.get_url_title($row['link']).'</a></p>';
										}
									}
									?>
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
		<hr />
	<?php //style="max-width:17rem; min-height:20rem; opacity: 0.7;"?>
		<div class="container">
			<div class="text-center">
				<h1>Plans</h1>
			</div>

			<div class="d-flex justify-content-center" style="position: relative; overflow: hidden;">
				<div id="planCarousel" class="carousel slide" data-ride="carousel" >
				  <div class="carousel-inner">
					  <?php
						$cardCount=0;
						while($plan = mysqli_fetch_array($hub_plans))
						{

							$plan_title = $plan['plan_title'];
							$frequency = $plan['frequency'];
							if($cardCount<1)
								echo '<div class="carousel-item active">';
							else
								echo '<div class="carousel-item">';
							$cardCount++;
							echo '<div class="card" style="min-width:17rem; min-height:20rem">';

							echo '<div class="card-header text-center"><b>';
							echo $plan_title;
							echo '</b> / <span class="text-muted">';
							if($frequency == 'D')
								echo 'daily';
							else if($frequency == 'M')
								echo 'monthly';
							else if($frequency == 'Y')
								echo 'yearly';
							echo '</span></div>';
							$feats = explode(';', $plan['feats']);

							echo '<div class="card-body">';
							echo '<ul>';
							for($i = 0; $i < count($feats); $i++){
								if(empty($feats[$i]))
									continue;
								echo '<li>';
								echo $feats[$i];
								echo '</li>';
							}
							echo '</ul>';
							echo '</div>';
							$price = $plan['price'];
							echo '<div class="card-footer text-center text-muted">';
							echo '<p>Starting at <b>' . $price . '$USD</b></p>';

							if(!empty($receiver_email)){
							echo '
							<button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#planModal'.$cardCount.'">
								Buy Plan
							</button>';
							}
							else{
								echo '<p><small>This user can\'t receive payments</small></p>';
							}

							echo '</div>';

							echo '</div>';
							echo '</div>';
						}
						?>
				  </div>
				  <a class="carousel-control-prev" href="#planCarousel" role="button" data-slide="prev">
					<span class="carousel-control-prev-icon" aria-hidden="true"></span>
					<span class="sr-only">Previous</span>
				  </a>
				  <a class="carousel-control-next" href="#planCarousel" role="button" data-slide="next">
					<span class="carousel-control-next-icon" aria-hidden="true"></span>
					<span class="sr-only">Next</span>
				  </a>
				</div>
				
			</div>
			<?php 
				if($cardCount>0){
					if(isset($user_hub_id) && $hub_id == $user_hub_id && empty($receiver_email)){
						echo '<p class="text-center"><small>Your visitors won\'t be able to buy your plans! please go to <a href="../user/hubearnings">Earnings</a> and link your Payments Account.</small></p>';
					}
				}
			?>
			<?php
				$hub_modal_plans = get_hub_plans($hub_id);
				$modalCount = 0;
				$planIndex = 0;
				while($modalPlan = mysqli_fetch_array($hub_modal_plans))
				{
					$modalCount++;
					$modalPlanFeats = explode(';', $modalPlan['feats']);
					$popoverFeatsList = "";
					$popoverFeatsList .= '<ul>';
					$plan_price = $modalPlan['price'];
					for($i = 0; $i < count($modalPlanFeats); $i++){
						if(empty($modalPlanFeats[$i]))
							continue;
						$popoverFeatsList .= '<li>';
						$popoverFeatsList .= $modalPlanFeats[$i];
						$popoverFeatsList .= '</li>';
					}
					$popoverFeatsList .= '</ul>';

					echo '<!-- BEGIN MODAL -->
						<div class="modal fade" id="planModal'.$modalCount.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
						  <div class="modal-dialog modal-dialog-centered" role="document">
							<div class="modal-content">
							  <div class="modal-header">
								<h5 class="modal-title" id="exampleModalLongTitle">Plan Subscription</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								  <span aria-hidden="true">&times;</span>
								</button>
							  </div>
							  <div class="modal-body">
								<div class="w-50" style="margin: 0 auto;">
									<div class="row my-2">
										<h3 style="margin: 0 auto;">'. $modalPlan['plan_title'] .'</h3>
										<button type="button" style="background: none; border:none; padding: 0px;"
												data-toggle="popover"
												title="'.$modalPlan['plan_title'].' Features" data-html="true"
												data-content="'.$popoverFeatsList.'">
											<img src="../images/exclamation.png" width="16" height="16" style="margin:auto auto;" />
										</button>

									</div>
									<div class="row my-2">
										<h3 style="margin: 0 auto;">'. $modalPlan['price'] . '$</h3>
									</div>
									<div class="row">
										  <input type="radio" id="paypalRadio" name="payment_platform" class="" onchange="changePaymentPlatform(0, '.$planIndex.')">
										  <label class="" for="paypalRadio">Paypal</label>
										  <input type="radio" id="stripeRadio" name="payment_platform" class="" onchange="changePaymentPlatform(1, '.$planIndex.', '.$plan_price.')">
										  <label class="" for="stripeRadio">Stripe</label>
									</div>
									<div class="formContainer'.$planIndex.'">
									</div>
									  
									<small>You will be taken to the payment site
											to proceed with the payment and then complete the
											HUB Plan Proccess.</small>
								</div>
							  </div>
							  <div class="modal-footer">

							  </div>
							</div>
						  </div>
						</div>
						<!--End modal -->';
					$planIndex++;
				}
				?>
			</div>
		</div>
	</div>

	<?php
			}
			require_once '../Constants/fullfooter.php';
		?>
	<script>
		var paypalRadio = document.getElementById('paypalRadio');
		var stripeRadio = document.getElementById('stripeRadio');
		function changePaymentPlatform(paymentPlatform, planIndex, planPrice){
			// 0 paypal
			// 1 stripe
			if(paymentPlatform == 0){
				$(".stripePlatform").remove();
				$(".formContainer"+planIndex).append('<form action="" method="post" class="paypalPlatform">\
					<div class="row my-2"> \
						<input class="form-control" type="text" name="social_username" placeholder="Snapchat username" /> \
					</div> \
					<div class="row my-2"> \
						<input class="form-control" type="text" name="social_email_address" placeholder="Snapchat email address" /> \
					</div> \
					<div class="row my-2"> \
						<button type="submit" name="paypalSubmit" style="background:none; border: none; padding:0px; cursor:pointer"> \
							<img src="https://www.paypalobjects.com/webstatic/en_US/i/buttons/checkout-logo-large.png" alt="Check out with PayPal" /> \
						</button> \
					</div> \
					<input type="hidden" name="planIndex" value="'+planIndex+'" /> \
				</form>');
			}
			if(paymentPlatform == 1){
					$(".paypalPlatform").remove();
					$(".formContainer"+planIndex).append('<form action="" method="post" class="stripePlatform">\
					<div class="row my-2"> \
						<input class="form-control" type="text" name="social_username" placeholder="Snapchat username" /> \
					</div> \
					<div class="row my-2"> \
						<input class="form-control" type="text" name="social_email_address" placeholder="Snapchat email address" /> \
					</div> \
					<div class="row my-2"> \
						<script \
						src="https://checkout.stripe.com/checkout.js"\
						class="stripe-button" \
						data-key="<?php echo $stripe_publishable_key ?>" \
						data-amount="'+(planPrice*100)+'" \
						data-name="STAPP" \
						data-description="STAPP User HUB Plan" \
						data-image="https://stripe.com/img/documentation/checkout/marketplace.png" \
						data-locale="auto" \
						data-zip-code="true"></scr'+'ipt> \
					</div> \
					<input type="hidden" name="planIndex" value="'+planIndex+'" /> \
					</form>');
			}
		}
		$('[data-toggle="popover"]').popover();
		$('[data-toggle="popover"]').click(function(){
			$(this).blur();	
		});
	</script>
</body>
</html>

