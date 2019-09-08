
<div class="list-group">
<?php

	$accountFiles = array('profile', 'plan');
	$accountMenus = array('Account Info', 'Plan Info');

	$hubFiles = array('hubmanager', 'hubearnings');
	$hubMenus = array('Manager', 'Earnings');

	//Account Info Menu Handler
	echo '<span class="list-group-item list-group-item-dark">
			Account
		  </span>';
	for($i = 0; $i < count($accountMenus); $i++){
		$select = "";
		if($accountFiles[$i] == $base_file){
			$select = "list-group-item-secondary active";
		}	
		echo '<a href="'.$accountFiles[$i].'" class="list-group-item list-group-item-action '.$select.'">';
		echo $accountMenus[$i];
		echo '</a>';
	}

	echo '<span class="list-group-item list-group-item-dark">
			HUB
		  </span>';
	for($i = 0; $i < count($hubMenus); $i++){
		$select = "";
		if($hubFiles[$i] == $base_file){
			$select = "list-group-item-secondary active";
		}	
		echo '<a href="'.$hubFiles[$i].'" class="list-group-item list-group-item-action '.$select.'">';
		echo $hubMenus[$i];
		echo '</a>';
	}
?>

</div>
