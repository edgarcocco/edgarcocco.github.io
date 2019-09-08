<?php
session_start();
require_once '../Constants/getPath.php';
require_once '../Constants/header.php';
require_once('../PHPScripts/functions.php');
?>
<link rel="stylesheet" href="../css/pricing.css">
</head>
<body>

    <?php require_once '../Constants/navigation.php'; ?>

    <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
      <h1 class="display-4">Pricing</h1>
      <p class="lead">These are our current offers.</p>
    </div>

    <div class="container">
      <div class="card-deck mb-3 text-center" style="max-width:23rem; float: none; margin: 0 auto;">
        <div class="card mb-4 box-shadow">
          <div class="card-header">
            <h4 class="my-0 font-weight-normal">Regular HUB</h4>
          </div>
          <div class="card-body">
            <h1 class="card-title pricing-card-title">$10 <small class="text-muted">/ mo</small></h1>
            <ul class="list-unstyled mt-3 mb-4">
              <li>HUB Creation</li>
              <li>Create Billing Plans</li>
              <li>STAPP Client Access</li>
            </ul>
            <form action="../Checkout/plan.php?plan=regular" method="POST">
                <button type="submit" class="btn btn-lg btn-block btn-outline-primary" >Start Now!</button>
            </form>
		  </div>
          </div>
        </div>
      </div>

	<div class="fixed-bottom">
    <?php require_once '../Constants/fullfooter.php'; ?>
	</div>
  </body>
</html>
