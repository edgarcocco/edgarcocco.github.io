<?php

require_once 'auth.php';
require_once '../Constants/header.php';
require_once '../Constants/connectvars.php';
require_once '../PHPScripts/functions.php';


?>

</head>

<body>
	<div class="container">
		<?php
			$hubs = get_all_hubs();
			echo '<h1 class="text-center">Listing all HUBs</h1>';
			echo '<table class="table table-hover">';
			echo '<tr>';
			echo '<th scope="col">ID</th>';
			echo '<th scope="col">Username</th>';
			echo '<th scope="col">Visibility</th>';
			echo '<th scope="col">More</th>';
			echo '</tr>';
			$hub_index = 0;
			while($row = mysqli_fetch_array($hubs))
			{
				echo '<tr style="" data-toggle="collapse" data-target="#hub_collapse'.$hub_index.'" aria-expanded="false" aria-controls="hub_collapse'.$hub_index.'">';
				echo "<td> {$row['id']} </td>";
				echo "<td> {$row['username']} </td>";
				if($row['visibility'])
				{
					echo "<td>Public</td>";
				}
				else
				{
					echo "<td>Private</td>";
				}

				echo '<td>
						<a class="btn btn-secondary" href="view_hub?id='.$row['id'].'">View More HUB Info</a>
					  </td>';
				/*echo '<td>
					<div class="dropdown">
					  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						More
					  </button>
					  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						<a class="dropdown-item" href="view_hub?id='.$row['id'].'">View HUB Info</a>
						<div class="dropdown-divider"></div>
						<!-- Button trigger modal -->
						<button class="dropdown-item" style="cursor:pointer" type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal' . $hub_index. '">
						Delete
						</button>
					  </div>
					</div>
					<!-- Modal -->
					<div class="modal fade" id="modal'.$hub_index.'" tabindex="-1" role="dialog" aria-labelledby="ModalLabel'.$hub_index.'" aria-hidden="true">
					  <div class="modal-dialog" role="document">
						<div class="modal-content">
						  <div class="modal-header">
							<h5 class="modal-title" id="ModalLabel'.$hub_index.'">Delete user</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							  <span aria-hidden="true">&times;</span>
							</button>
						  </div>
						  <div class="modal-body">
							Are you sure you want to delete this user?
						  </div>
						  <div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							<a class="btn btn-primary" href="">Delete</a>
						  </div>
						</div>
					  </div>
					</div>
					</td>';*/
				echo '</tr>';
				$hub_index += 1;
			}
			echo '</table>';
			echo '<a href="index.php">Go back</a>';
		?>
	</div>
</body>

</html>
