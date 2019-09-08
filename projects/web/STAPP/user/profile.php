<?php
session_start();
require_once "../Constants/getPath.php";
require_once "../Constants/header.php";
require_once "../Constants/connectvars.php";
require_once "../PHPScripts/functions.php";

if(!isset($_SESSION['user_id']))
{
    header('Location: ../');
	return;
}

$row = getRow($_SESSION['user_id']);
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
					<p class="lead" style="font-size: 32px">Account Info</p>
				</div>

				<div class="col border rounded">
					<div class="row">
						<div class="col">
							<p><b>First Name</b></p>
						</div>
						<div class="col">
							<p class="justify-content-end"><?php echo $row['first_name']; ?></p>
						</div>
					</div>

					<div class="row">
						<div class="col">
							<p><b>Last Name</b></p>
						</div>
						<div class="col">
							<p class="justify-content-end"><?php echo $row['last_name']; ?></p>
						</div>
					</div>

					<div class="row">
						<div class="col">
							<p><b>Username</b></p>
						</div>
						<div class="col">
							<p class="justify-content-end"><?php echo $row['username']; ?></p>
						</div>
					</div>

					<div class="row">
						<div class="col">
							<p><b>Email</b></p>
						</div>
						<div class="col">
							<p class="justify-content-end"><?php echo $row['email']; ?></p>
						</div>
					</div>

					<div class="row">
						<div class="col">
							<p><b>Phone Number</b></p>
						</div>
						<div class="col">
							<p class="justify-content-end"><?php echo $row['phone_number']; ?></p>
						</div>
					</div>
					<a class="btn btn-light border py-1 my-2" href="edit_profile.php" role="button">Edit</a>
				</div>
			</div>
		</div>
	</div>
</div>

<?php require_once '../Constants/fullfooter.php'; ?>
</body>

</html>
