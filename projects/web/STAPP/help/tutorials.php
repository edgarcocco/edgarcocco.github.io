<?php 
	require_once '../Constants/getPath.php';
	require_once '../Constants/header.php';
?>
<style>
html{
	overflow-x: hidden;
}
</style>
</head>

<body>
	<?php require_once '../Constants/navigation.php'; ?>
<div>
	<div class="row my-2"><!-- startrow-->

		<div class="col-lg-3 mx-1"><!-- startcol-->
			<h3 class="my-1 font-weight-light alert alert-secondary">Navigation</h3>
			<div class="list-group">
				<a href="support.php" class="list-group-item list-group-item-action ">
					Support
				</a>
				<a href="faq" class="list-group-item list-group-item-action">
					FAQ
				</a>
				<a href="ticket/" class="list-group-item list-group-item-action ">
					Ticket
				</a>
				<a href="" class="list-group-item list-group-item-action list-group-item-secondary active">
					Tutorials
				</a>
			</div>
		</div><!-- endcol-->

		<div class="col"><!--startcol -->
			<h1>STAPP Tutorials</h1>
				<p>
				  <a class="btn btn-secondary" data-toggle="collapse" href="#getStartedVideo" role="button" aria-expanded="false" aria-controls="getStartedVideo">
					How to get started
				  </a>
				</p>

				<div class="collapse" style="" id="getStartedVideo">
					<div class="card card-body m-2">
						<p>In this tutorial you will learn how to:</p>
						<ul>
							<li>Get started.</li>
							<li>Client Installation.</li>
							<li>Client Login</li>
						</ul>
						<video width="640" height="360" controls>
							<source src="videotutorials/STAPP_how_to_get_started.mp4"  type="video/mp4" />
							<!--<source src="__VIDEO__.OGV"  type="video/ogg" />-->
							<!--<object width="640" height="360" type="application/x-shockwave-flash" data="__FLASH__.SWF">
								<param name="movie" value="__FLASH__.SWF" />
								<param name="flashvars" value="autostart=true&amp;controlbar=over&amp;image=__POSTER__.JPG&amp;file=__VIDEO__.MP4" />
								<img src="__VIDEO__.JPG" width="640" height="360" alt="__TITLE__"
									 title="No video playback capabilities, please download the video below" />
							</object>-->
						</video>
						<p>	<strong>Download Video:</strong>
							<a href="videotutorials/STAPP_how_to_get_started.mp4">"MP4"</a>
						</p>	
					</div>
				</div>

				<p>
				  <a class="btn btn-secondary" data-toggle="collapse" href="#clientFeaturesVideo" role="button" aria-expanded="false" aria-controls="clientFeaturesVideo">
				  	How to use all client features
				  </a>
				</p>

				<div class="collapse" style="" id="clientFeaturesVideo">
					<div class="card card-body m-2">
						<p>In this tutorial you will learn how to:</p>
						<ul>
							<li>Use all STAPP Client Features</li>
						</ul>
						<video width="640" height="360" controls>
							<source src="videotutorials/STAPP_How_to_use_all_client_features.mp4"  type="video/mp4" />
							<!--<source src="__VIDEO__.OGV"  type="video/ogg" />-->
							<!--<object width="640" height="360" type="application/x-shockwave-flash" data="__FLASH__.SWF">
								<param name="movie" value="__FLASH__.SWF" />
								<param name="flashvars" value="autostart=true&amp;controlbar=over&amp;image=__POSTER__.JPG&amp;file=__VIDEO__.MP4" />
								<img src="__VIDEO__.JPG" width="640" height="360" alt="__TITLE__"
									 title="No video playback capabilities, please download the video below" />
							</object>-->
						</video>
						<p>	<strong>Download Video:</strong>
							<a href="videotutorials/STAPP_How_to_use_all_client_features.MP4">"MP4"</a>
						</p>	
					</div>
				</div>

				<p>
				  <a class="btn btn-secondary" data-toggle="collapse" href="#ticketReportsVideo" role="button" aria-expanded="false" aria-controls="ticketReportsVideo">
				  	How to send ticket reports
				  </a>
				</p>

				<div class="collapse" style="" id="ticketReportsVideo">
					<div class="card card-body m-2">
						<p>In this tutorial you will learn how to:</p>
						<ul>
							<li>Get support</li>
							<li>Send and view your tickets</li>
						</ul>
						<video width="640" height="360" controls>
							<source src="videotutorials/STAPP_how_to_send_ticket_reports.mp4"  type="video/mp4" />
							<!--<source src="__VIDEO__.OGV"  type="video/ogg" />-->
							<!--<object width="640" height="360" type="application/x-shockwave-flash" data="__FLASH__.SWF">
								<param name="movie" value="__FLASH__.SWF" />
								<param name="flashvars" value="autostart=true&amp;controlbar=over&amp;image=__POSTER__.JPG&amp;file=__VIDEO__.MP4" />
								<img src="__VIDEO__.JPG" width="640" height="360" alt="__TITLE__"
									 title="No video playback capabilities, please download the video below" />
							</object>-->
						</video>
						<p>	<strong>Download Video:</strong>
							<a href="videotutorials/STAPP_how_to_send_ticket_reports.mp4">"MP4"</a>
						</p>	
					</div>
				</div>

				
		</div><!--endcol -->

	</div> <!--endrow-->

	<?php require_once '../Constants/fullfooter.php'; ?>
</body>

</html>
