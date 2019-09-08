<?php

	require_once('Constants/getPath.php');
	require_once('Constants/header.php');
?>
<script src="redirectSketch.js"></script>
<meta http-equiv="refresh" content="5;url=account/login.php">
</head>

<body>
	<div class="container d-flex my-5 justify-content-center flex-wrap">
		<div id="canvasHolder"> </div>
		<?php if(isset($_GET['reason']) && $_GET['reason'] == 'login'): ?>
			<div class="text-center">
				<p class="lead">Oops, you need to be logged in to proceed to checkout!, <br /> Redirecting in 5... or <a href="account/login.php">Click Here</a></p>
			</div>
		<?php elseif(isset($_GET['reason']) && $_GET['reason'] == 'checkoutError'): ?>
			<div class="text-center">
				<p class="lead">Something went wrong during checkout!<br /> Redirecting in 5... or <a href="account/login.php">Click Here</a></p>
			</div>
		<?php elseif(isset($_GET['reason']) && $_GET['reason'] == 'plan'): ?>
			<div class="text-center">
				<p class="lead">You already have bought a plan!<br /> Redirecting in 5... or <a href="user/profile.php">Click Here</a></p>
			</div>
		<?php else: ?>
			<div class="text-center">
				<p class="lead">Redirecting in 5... or <a href="http://localhost/STAPP1/account/login.php">Click Here</a></p>
			</div>
		<?php endif; ?>
	</div>
</body>
</html>
