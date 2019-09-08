<?php 
require_once '../Constants/getPath.php';
require_once '../Constants/header.php';
?>
</head>

<body>
<?php
require_once '../Constants/navigation.php';
?>


<div class="container my-5">
	<p class="lead text-center">This is STAPP Support Center use one of these options to get support.</p>
	<?php 
		if(isset($_GET['error_code']))
		{
			$error_code = $_GET['error_code'];
			$error_msg = "";
			if($error_code == 1)
			{
				$error_msg = 'You need to <a href="../account/login">log-in</a> in order to use or send Tickets';
			}
			if($error_code == 3)
			{
				$error_msg = 'You can\'t send 2 tickets in the same day, please wait 24 hours to be able to send tickets';
			}
			echo '<p class="alert alert-danger">'.$error_msg.'</p>';
		}
	?>

</div>
<div class="container my-5">
	<div class="m-3">
		<div class="row" style="height: 20rem">
			<a class="col-6 border rounded" href="faq.php" style="text-decoration: none; background-image: url('../images/news_icon.png'); background-repeat: no-repeat; background-position: center bottom;">
				<h1 class="text-center my-5"><i>FAQs</i></h1>
				<div class="text-dark w-50 text-center" style="margin: 0 auto;">
					<p>We highly recommend reading our FAQs
					   before using any other support options</p>
				</div>
			</a>
			<a class="col-6 border rounded" href="ticket/" style="text-decoration: none; background-image: url('../images/ticket_icon.png'); background-repeat: no-repeat; background-position: center bottom;">
				<h1 class="text-center my-5"><i>Tickets</i></h1>
				<div class="text-dark w-50 text-center" style="margin: 0 auto;">
					<p>Send us a ticket, and one of our team will be with you
					   shortly</p>
				</div>
			</a>
		</div>
		<div class="row" style="height: 20rem">
			<a class="col-6 border rounded" href="mailto:rolle.cocco.enterprises@gmail.com" style="text-decoration: none; background-image: url('../images/contact_us_icon.png'); background-repeat: no-repeat; background-position: center bottom;">
				<h1 class="text-center my-5"><i>Contact Us</i></h1>
				<div class="text-dark w-50 text-center" style="margin: 0 auto;">
					<p>Send us an e-mail</p>
				</div>
			</a>
			<a class="col-6 border rounded" href="tutorials" style="text-decoration: none; background-image: url('../images/tutorial_icon.png'); background-repeat: no-repeat; background-position: center bottom;">
				<h1 class="text-center my-5"><i>Tutorial</i></h1>
				<div class="text-dark w-50 text-center" style="margin: 0 auto;">
					<p>Watch the tutorials we have prepare for new users</p>
				</div>
			</a>
		</div>
	</div>
</div>

<?php
require_once '../Constants/fullfooter.php';
?>
</body>
</html>

