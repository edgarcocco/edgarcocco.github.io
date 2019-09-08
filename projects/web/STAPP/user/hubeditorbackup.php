<?php 
require_once '../Constants/getPath.php';
require_once '../Constants/header.php';
?>
<link rel="stylesheet" href="../css/hubstyle.css"></link>
<script src="../scripts/hubscript.js"></script>
</head>

<body>
<?php require_once '../Constants/navigation.php'; ?>

<div class="mx-5 py-5" style="min-height: 512px">
	<div class="container">
		<div class="border rounded largepic">
		<p class="text-muted lead" style="">No picture added</p>
		</div>
		<div class="" style="display: inline-block; position:absolute; left: 29rem; ">
			<div class="row">
				<div class="col">
					<p class="display-4 text-muted hubusername" id="hubusername">username</p>
					<input style="display:none; width: 240px; height: 67px;" class="form-control my-2 px-3 py-2" type="text" id="usernameTxtBox" placeholder="Username"></input>
					<button style="display:none" class="btn" id="saveBtn" >Save</button>
				</div>
			</div>
			<div class="row my-5">
				<div class="col-lg-auto">
					<div class="border rounded smallpic">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<hr />

<div class="mx-5 py-5" style="min-height: 512px">
	<div class="container pl-5">
		<div class="row aboutmecards">
			<div class="col-lg-3">
				<div class="card border-secondary mb-3" style="max-width: 18rem;">
				  <div class="card-header">Stats</div>
				  <div class="card-body text-secondary">
					<p class="card-text"><b>Age:</b>18</p>
					<p class="card-text"><b>Country:</b>DO</p>
				  </div>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="card border-secondary mb-3" style="max-width: 18rem;">
				  <div class="card-header">Biography</div>
				  <div class="card-body text-secondary">
					<p>This wild held an auto biography, tell us a bit of you and what kind of contents
						you upload on your social media and so on... </p>
				  </div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php require_once '../Constants/fullfooter.php'; ?>
</body>
</html>
