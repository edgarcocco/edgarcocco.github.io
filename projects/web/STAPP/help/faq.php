<?php

session_start();
require_once '../Constants/getPath.php';
require_once '../Constants/header.php';

?>
</head>
<body>
<?php require_once '../Constants/navigation.php'; ?>
	<div class="row">
		<div class="col-lg-3 mx-1">
			<h3 class="my-1 font-weight-light alert alert-secondary">Navigation</h3>
			<div class="list-group">
				<a href="support.php" class="list-group-item list-group-item-action ">
					Support
				</a>
				<a href="" class="list-group-item list-group-item-action list-group-item-secondary active">
					FAQ
				</a>
				<a href="ticket/" class="list-group-item list-group-item-action ">
					Ticket
				</a>
				<a href="tutorials" class="list-group-item list-group-item-action ">
					Tutorials
				</a>
			</div>
		</div>
		<div class="col">
			<div class="container">
				<h1> FAQs </h1>
				<p class="lead">These are our most frequently asked questions.</p>
				<div class="my-5 w-75">
					<div class="">
					<p class="alert alert-primary"><b>Where to Start?</b></p>
					<p class="alert alert-secondary">First start by creating a STAPP account (<a href="../account/register">Sign Up</a>), 
								as it is your first time using our service, we give users a 14 DAYS TRIAL
								after the TRIAL is over, users will need to subscribe for a plan.</p>
					</div>
				</div>
				<div class="w-75">
				<p class="lead">This page will be filled with most commonly asked questions about our service, if you didn't find this page helpful please send us a ticket.</p>
				</div>
			</div>
		</div>
	</div>

<?php require_once '../Constants/fullfooter.php'; ?>
</body>
</html>
