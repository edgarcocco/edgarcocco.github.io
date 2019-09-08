	<?php 

		session_start();
		require_once 'Constants/getPath.php';
		require_once 'Constants/header.php';
		require_once 'PHPScripts/functions.php';
		require_once 'Checkout/app/start.php';
	?>

    <link href="css/style.css" rel="stylesheet">
	<script type="application/ld+json">	
	{
	  "@context": "http://schema.org",
	  "@type": "Business",
	  "url": "https://www.stappapp.com",
	  "logo": "https://www.stappapp.com/images/stapp_nocolors.png"
	}
	</script>
  </head>

  <body>
    <?php require_once('Constants/navigation.php'); ?>

    <div class="px-2 p-md-5 headline">
      <div class="py-5">
		<div class="row">
			<div class="col-4">
				<h1 class="display-4 font-weight-normal main-title">STAPP</h1>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-10 col-lg-6">
				<hr style="border-color: #FFFFFF22;"/>
				<p class="lead">The Subscription App, provides user with the tools necessary to help earn money and keep track of your subscribers on your favourite social media!</p>
			</div>
		</div>

		<div class="row">
			<div class="col">
				<a class="btn btn-dark mr-3 p-3" href="STAPP_Setup.exe" download="STAPP_Setup.exe">Download now for free!</a>
			</div>
		</div>
      </div>
    </div>

	<hr class="divider"/>

	<div class="container my-3">
		<div class="row">
			<div class="col-md-7">
				<p class="h1">What is STAPP</p>
				<p>
					<span class="ml-5"><i>The Subscription APP</i></span> 
					is the ideal program to accurately keep track of your current subscribers.
					This is a discreet app for personal and commercial use, helping the user
					stay organized and ahead in their business.
				</p>

				<h2>Get yourself Up and Running</h2>
				<p class="">
					<span class="ml-5">As easy as 1,2,3 STAPP friendly user interface give users an easy and</span>
					understandable usage with friendly eye material design concept. <br/>
				</p>
				<p>
					STAPP supports <b>Online</b> &amp; <b>Offline</b> use, while keeping the information stored from the last user logged in.
				</p>
				<?php 
					if(!isset($_SESSION['user_id']))
						//echo '<a class="btn btn-outline-info px-5 py-2" href="account/register.php">Sign Up & Start 14-Days Trial</a>'
				?>
				
				<a class="btn btn-success" href="pricing/">See Pricing</a> 
				<a class="btn btn-info px-5 py-2 my-2" href="help/tutorials">Watch The Tutorials</a>
			</div>
			<div class="col-md-5">
				<img class="image img-fluid mx-auto" src="images/stapp_materialdesign.png" />
			</div>
			</p>
		</div>
	</div> 
	<div class="whystapp px-3 mb-5">
		<div class="text-center">
		<h1 class="display-3 py-5">Why use<span style="font-family: 'Russo One', Verdana"> STAPP</span>?</h1>
		</div>
		<div class="container">
			<div class="row">
				<div class="col-sm-8 offset-md-2">
				<ul>
					<li>
						<h1 class="">Save <span style="font-weight:100">Time</span></h1>
						<p>
						STAPP accurately allows you to keep track of your current subscribers 
						from the date you've added them. 
						</p>
					</li>
					<li>
						<h1 class="">Stay ontop of <span style="font-weight:100">your game</span></h1>
						<p>
						The program automatically notifies you when one of your user's subscription has 
						ended, giving you the options to either keep them on the expired list or renew their 
						subscription with one easy click!	
						</p>
					</li>
					<li>
						<h1 class="">Easy to use</h1>
						<p>
						Why hand right lists or throw on a pair of glasses to read through your typed subscribers 
						when you can simply add your subs one time and have them backed up on an online storage?
						</p>
					</li>
				</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="px-5 mb-5">
		<div class="text-center">
		<h1 class="display-3 py-5">Who uses<span style="font-family: 'Russo One', Verdana"> STAPP</span>?</h1>
		</div>
		<div class="container w-75">
			<div id="carousel" class="carousel slide" data-ride="carousel" data-interval="false">
			  <ol id="carousel-indicators" class="carousel-indicators">
				<li data-target="#carousel" data-slide-to="0" class="active"></li>
				<li data-target="#carousel" data-slide-to="1"></li>
				<!--<li data-target="#carousel" data-slide-to="2"></li>-->
			  </ol>
			  <div class="carousel-inner">
				<div id="firstSketch" class="carousel-item active">
					<canvas id="firstCanvas" class="w-100" width="500" height="235" style="background-color: #22"></canvas>
					<div class="carousel-caption d-none d-md-block">
						<p>
						STAPP is a professional app mainly used by Snapchat users and similar platforms to keep track of the 
					user's subscribers.
						</p>
					</div>
				</div>
				<div id="secondSketch" class="carousel-item">
					<canvas id="secondCanvas" class="w-100" width="500" height="235" style="background-color: #22"></canvas>
					<div class="carousel-caption d-none d-md-block">
						<p>
						 However it can also be used for non-commercial use and applied however the user 
						 see's fit! Show us how far your creativity can go with the app!
						</p>
					</div>
				</div>
				<!--
				<div id="thirdSketch" class="carousel-item">
					<canvas id="thirdCanvas" class="w-100" width="500" height="235" style="background-color: #22"></canvas>
					<div class="carousel-caption d-none d-md-block">
						<p>
						STAPP is a professional app mainly used by Snapchat and similar platforms to keep track of the 
					user's subscribers. However it can also be used for non-commercial use and applied however the user 
					see's fit! Show us how far your creativity can go with the app!
						</p>
					</div>
				</div>-->
			  </div>
			  <a class="carousel-control-prev" href="#carousel" role="button" data-slide="prev">
				<span class="carousel-control-prev-icon" aria-hidden="true"></span>
				<span class="sr-only">Previous</span>
			  </a>
			  <a class="carousel-control-next" href="#carousel" role="button" data-slide="next">
				<span class="carousel-control-next-icon" aria-hidden="true"></span>
				<span class="sr-only">Next</span>
			  </a>
			</div>
		</div>
	</div>

	<?php require_once('Constants/fullfooter.php'); ?>
	<script src="scripts/indexSketch.js"></script>
    </body>
</html>
