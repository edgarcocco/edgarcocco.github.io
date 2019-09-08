    <?php
		if(!isset($_SESSION)){
			session_start();
		}
		require_once($return_path . 'PHPScripts/functions.php');

		if(!isset($_SESSION['user_id'])){
		?>
   <nav class="navbar navbar-expand-md navbar-dark" style="background-color: #222;">
<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
    <div class="navbar-collapse collapse w-100 order-1 order-md-0 dual-collapse2">
		<!-- left side -->
        <ul class="navbar-nav mr-auto">
        </ul>
    </div>
	<!-- center side -->
    <div class="mx-auto order-0">
		<a class="navbar-brand mx-auto" href="<?php printf("%s", $absolute_path); ?>">
		<img src="<?php printf("%s/images/transparent_stapp.png", $absolute_path);?>" width="44" height="44"/>
		</a>
    </div>
    <div class="navbar-collapse collapse w-100 order-3 dual-collapse2" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto mr-5">
			<li class="nav-item">
				<a class="nav-link" download="STAPP_Setup.exe" href="<?php printf("%sSTAPP_Setup.exe", $absolute_path);?>">Download</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="<?php printf("%spricing/", $absolute_path);?>">Pricing</a>
			</li>
            <li class="nav-item">
				<a class="nav-link text-light" href="<?php printf("%saccount/register.php", $absolute_path);?>">Sign up</a>
            </li>
			<li class="nav-item">
				<span class="navbar-text">or</span>
			</li>
			<li class="nav-item">
				<a class="nav-link text-light" href="<?php printf("%saccount/login.php", $absolute_path); ?>">Sign in</a>
			</li>
		</ul>
    </div>
</nav>
<?php
	}
	else {
		$confirmed_account = get_confirmed_account($_SESSION['user_id']);
		$planRow = getPlanRow($_SESSION['user_id']);
?>
<nav class="navbar navbar-expand-lg navbar-light navbar-dark" style="background-color: #222;">
<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
<div class="navbar-collapse collapse w-100 order-1 order-md-0 dual-collapse2">
		<!-- left side -->
        <ul class="navbar-nav mr-auto">
        </ul>
    </div>
	<!-- center side -->
    <div class="mx-auto order-0">
		<a class="navbar-brand mx-auto" href="<?php printf("%s", $absolute_path) ?>">
			<?php printf('<img src="%simages/transparent_stapp.png" width="44" height="44"/>', $return_path); ?>
		</a>
    </div>
  <div class="navbar-collapse collapse w-100 order-3 dual-collapse2" id="navbarSupportedContent">
    <ul class="navbar-nav ml-auto">
		<li class="nav-item">
			<a class="nav-link text-light" download="STAPP_Setup.exe" href="<?php printf("%sSTAPP_Setup.exe", $absolute_path);?>">Download</a>
		</li>
		<li class="nav-item">
			<a class="nav-link text-light" href="<?php printf("%spricing/", $absolute_path);?>">Pricing</a>
		</li>

      <li class="nav-item dropdown mr-5">
		<a class="nav-link dropdown-toggle text-light" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			Hello, <?php echo getUserName($_SESSION['user_id']); ?>
		</a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="<?php printf("%suser/profile.php", $absolute_path); ?>">Profile</a>
		  <a href="<?php printf("%suser/plan.php", $absolute_path); ?>" class="dropdown-item">
		  Plan: 
			<span class="text-success">
				<?php if($planRow['current_plan'] == 'trial') echo 'Trial';
				else if($planRow['current_plan'] == 'regular') echo 'Regular';
				?>
			</span>
		  </a>
          <div class="dropdown-divider"></div>
		  <a href="<?php printf("%shelp/support.php", $absolute_path); ?>" class="dropdown-item">Support</a>
          <a class="dropdown-item text-danger" href="<?php printf("%saccount/logout.php", $absolute_path); ?>">Logout</a>
        </div>
      </li>
	 <li class="nav-item">
	 <!--<form post="GET" action="<?php printf("%saccount/logout.php", $absolute_path); ?>">
	 <button class="btn btn-danger" type="submit" href=>
			Logout
	 </button>
	 </form>-->
	</li>
    </ul>
	</div>
  </nav>

  <?php
			if(!$confirmed_account['is_confirmed']){
			echo '<div class="alert alert-warning mb-0 text-center">
				Your account is not confirmed, please check your email for confirmation link!	
				</div>';
			}
		}
	?>
</ul>
