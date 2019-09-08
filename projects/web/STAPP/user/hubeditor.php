<?php

session_start();
require_once '../Constants/getPath.php';
require_once '../Constants/header.php';
require_once '../PHPScripts/functions.php';


$row = getRow($_SESSION['user_id']);
$user_id = $row['user_id'];
$hub = get_hub($row['user_id']);
$hub_id = $hub['id'];
$hub_links = get_hub_links($hub_id);
$hub_owner_plan = getPlanRow($userRow['user_id']);

if(empty($hub_id))
{
	header('Location: ../');
}


if(isset($_POST['imagesubmit'])){
	//echo var_dump($_FILES['inputimage']);
	$screenshot =  time() . $_FILES['inputimage']['name'];
	$screenshot_type = $_FILES['inputimage']['type'];
	$screenshot_size = $_FILES['inputimage']['size'];
	if(count($_FILES) > 0){
		if(($screenshot_type == 'image/gif') || ($screenshot_type == 'image/jpeg') ||
			($screenshot_type == 'image/pjpeg') || ($screenshot_type == 'image/png') &&
			($screenshot_size > 0) && ($screenshot_size < ST_MAXFILESIZE))
		{
			make_non_exist_dir('../hub_images/' . $row['username'] . '/');
			$target = ST_UPLOADPATH . $screenshot;
			$source_img = $_FILES['inputimage']['tmp_name'];
			$newScreenshot = pathinfo($screenshot, PATHINFO_FILENAME) .'.jpg';
			if(move_uploaded_file($source_img, $target));
			{

				//start the compression, and convert to a jpg
				$destination_img = ST_UPLOADPATH . $newScreenshot;
				$d = compress($target, $destination_img, 100);
				$img = resize_image($destination_img, 512, 512);
				imagejpeg($img, $destination_img, 100);
				if($screenshot_type == 'image/png' || $screenshot_type == 'image/gif'
				|| $screenshot_type == 'image/pjpeg'){
					unlink($target);
				}
				//echo var_dump($img);
				$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
				$hub_info = get_hub_info($hub_id);

				$uploadedPictures = "";
				if(!empty($hub_info['pictures']))
				{
					$uploadedPictures = explode(';', $hub_info['pictures']);

					$picturesAppended = "";
					for($i = 0; $i < count($uploadedPictures); $i++)
					{
						if(empty($uploadedPictures[$i]))
							continue;
						$picturesAppended = $picturesAppended . $uploadedPictures[$i] . ';';
					}
					$picturesAppended = $picturesAppended . $newScreenshot;
					$query = "UPDATE hub_info SET pictures='$picturesAppended' WHERE hub_id='$hub_id'";
					mysqli_query($dbc, $query);
				}
				else
				{
					$query = "UPDATE hub_info SET pictures='$newScreenshot' WHERE hub_id='$hub_id'";
					mysqli_query($dbc, $query);
				}
				mysqli_close($dbc);
				header('Location: redirector.php?from=hubeditor');
			}
		}
	}
}

$hub_info = get_hub_info($hub_id);
$hub_stats = get_hub_stats($hub_id);
$hub_plans = get_hub_plans($hub_id);
?>
<link rel="stylesheet" href="../css/hubstyle.css"></link>

<script src="../scripts/hub.editor.js"></script>

</head>

<body>
  <?php require_once '../Constants/navigation.php'; ?>

  <div class="container py-5" style="min-height: 512px">

    <div class="row">
      <div class="col-lg-4 col-md-6 col-xs-6">
        <div class="row">
          <div class="col">
            <?php
					if(!empty($hub_info['pictures']))
					{
						$pictures = explode(';', $hub_info['pictures']);
						echo '<div class="border rounded largepic" style="">';
						echo '<img id="imgpreview" src="'.ST_UPLOADPATH .$pictures[0].'"></img>';
						echo '</div>';
					}
					else{
						echo '<div class="border rounded largepic" style="">';
						echo '<p class="text-muted lead" id="nopicadvice" style="">No picture added</p>';
						echo '</div>';
					}
					?>
          </div>
        </div>
        <div class="row ">
          <div class="col mt-2">
            <div class="card border-secondary" style="width:20rem">
              <div class="card-header d-flex justify-content-between py-1">
				<span class="my-2">Stats
				<img src="../images/exclamation_i16x16.png" data-toggle="tooltip" data-placement="right" title="Let the people know some about you, e.g, Age, Height etc... "/>
				</span>
					
                <button class="btn" style="text-align: right;" id="addstatbtn">Add</button>
			
              </div>
              <div class="card-body text-secondary" id="statbody1">
                <div id="statbody2">
                  <?php
					//loop through each hub stats and display them here
					if(mysqli_num_rows($hub_stats) > 0){
						while($row = mysqli_fetch_array($hub_stats))
						{
							$stat_id = $row['id'];
							echo '
								<div class="d-flex justify-content-between" onmouseover="showStatSettingsBtn('.$stat_id.')" onmouseout="hideStatSettingsBtn('.$stat_id.')">
									<p id="statInfo'.$stat_id.'" class="card-text"><b>'.$row['stat_title'].': </b>'.$row['stat_body'].'</p>
									<div>
										<button class="btn btn-success" style="display:none" id="statEditBtn'.$stat_id.'" onclick="editStat('.$stat_id.')">Edit</button>
										<button class="btn btn-danger" style="display:none" id="statDeleteBtn'.$stat_id.'" onclick="deleteStatDialog('.$stat_id.')">Delete</button>
									</div>
									<input type="hidden" id="stat_title'.$stat_id.'" value="'.$row['stat_title'].'">
									<input type="hidden" id="stat_body'.$stat_id.'" value="'.$row['stat_body'].'">
								</div>';
						}
					}
				  ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-5 col-xs-6">
        <div class="row">
          <div class="col ">
            <p class="display-4 text-muted hubusername" id="hubusername">
              <?php echo $hub_info['name'] ?>
			  <img src="../images/exclamation_i16x16.png" data-toggle="tooltip" data-placement="right" title="Click your name to edit it!"/>
            </p>
            <div class="form-row">
              <div class="col">
                <input style="display:none;" class="form-control" type="text" name="usernameTxtBox" id="usernameTxtBox" placeholder="Username"></input>
              </div>
              <div class="col">
                <button type="submit" name="submitUsername" style="display:none" class="btn" id="saveBtn">Save</button>
              </div>
            </div>
          </div>
        </div>
        <div class="row mb-5" id="thumbrow">
          <div class="col " id="thumbcol">
            <?php
					$hub_info = get_hub_info($hub_id);
					$pictures = explode(";", $hub_info['pictures']);
					if(empty($pictures[0])){
						echo '<div class="d-flex fleximg previewImage">';
						echo'<label class="p-2 thumblabel">
								<div class="border rounded addpicthumb">
								</div>
								<input accept="image/*" type="file" name="inputimage" id="inputimage" style="cursor:pointer" hidden></input>
							</label>';
						echo '</div>';
					}
					else{
						$picturesLength = count($pictures);
						$countForRow=0;
						$i=0;
						echo '<div class="d-flex fleximg">';

						for($picturesLength=$picturesLength; $picturesLength > 0; $picturesLength--)
						{
							if(empty($pictures[$i]))
								continue;
							if($countForRow==4)
							{
								$countForRow=0;
								echo '</div>';
								echo '<div class="d-flex fleximg">';
							}
							echo '<div class="p-2 smallpic" onmouseover="showDeleteImgBtn('.$i.')" onmouseout="hideDeleteImgBtn('.$i.')">';
							echo '<img class="" onclick="thumbSelected(this)" src="' . ST_UPLOADPATH . $pictures[$i] . '"></img>';
							//echo '<img class="" onclick="thumbSelected(this)" src="../hub/'. $userRow['username'] . '/images/compressedimg.jpg"></img>';

							echo '</div>';
							echo '<img id="deleteimgbtn'.$i.'" onmouseover="showDeleteImgBtn('.$i.')" onmouseout="hideDeleteImgBtn('.$i.')" onclick="deleteImgDialog('.$i.')" src="../images/close.png" style="width: 20px; height: 20px; border: none; cursor:pointer; display: none; margin:0px;" ></img>';
							$countForRow++;
							$i++;
						}

						if($countForRow<4)
						{
							echo '<div class="previewImage">';
							echo '<label class="p-2 thumblabel">
									<div class="border rounded addpicthumb">
									</div>
									<input accept="image/*" type="file" name="inputimage" id="inputimage" style="cursor:pointer" hidden></input>
								</label>';
							echo '</div>';

							echo '</div>';
						}

						if($countForRow == 4){
						echo '</div>';
						echo '<div class="row">';
						echo '<div class="col previewImage">
								<label class="p-2 thumblabel">
									<div class="border rounded addpicthumb">
									</div>
									<input accept="image/*" type="file" name="inputimage" id="inputimage" style="cursor:pointer" hidden></input>
								</label>
							</div>';
						echo '</div>';
						}
					}
					?>
            <!--<div class="p-2 smallpic" id="previewImage">
					</div>-->
          </div>
        </div>
        <div class="row">
          <div class="col">
            <div class="card border-secondary mt-1 ml-1" id="biocard">
              <div class="card-header d-flex justify-content-between py-1">
				<span class="my-2">Biography
				<img src="../images/exclamation_i16x16.png" data-toggle="tooltip" data-placement="right" title="Users would like to hear a story from you, feel free to tell us anything!"/>
				</span>
                <button class="btn" style="text-align: right;" id="editbiobtn">Edit Bio</button>
              </div>
              <div class="card-body text-secondary">
                <div class="biocontainer">
                  <p class="biop"><?php echo $hub_info['biography']; ?></p>
                </div>
              </div>
            </div>
          </div>
        </div>

		<div class="row">
			<div class="col">
				<div class="card border-secondary mt-1 ml-1" style="width:20rem">
					<div class="card-header d-flex justify-content-between py-1">
						<span class="my-2">My Links
						<img src="../images/exclamation_i16x16.png" data-toggle="tooltip" data-placement="right" title="Share some links like social media, or any website of your liking!"/>
						</span>
						<button class="btn" style="text-align: right;" id="addlinkbtn">Add</button>
					</div>
					<div class="card-body text-secondary" id="linksbody1">
						<div id="linksbody2">
							<?php
							//loop through each hub link and display them here
							if(mysqli_num_rows($hub_links) > 0){
								$count = 0;
								while($row = mysqli_fetch_array($hub_links)){
									$link_id = $row['id'];
									if($count>0){
										echo '<hr />';
									}
									echo '<div class="d-flex justify-content-between" onmouseover="showLinkSettingsBtn('.$link_id.')" onmouseout="hideLinkSettingsBtn('.$link_id.')">';
									echo '<p id="linkInfo'.$link_id.'"><a href="'.$row['link'].'" id="linkInfo'.$link_id.'">'.get_url_title($row['link']).'</a></p>';
									echo '<button class="btn btn-success" style="display:none" id="linkEditBtn'.$link_id.'" onclick="editLink('.$link_id.')">Edit</button>';
									echo '<button class="btn btn-danger" style="display:none" id="linkDeleteBtn'.$link_id.'" onclick="deleteLinkDialog('.$link_id.')">Delete</button>';
									echo '<input type="hidden" id="linkval'.$link_id.'" value="'.$row['link'].'"/>';
									echo '</div>';
									
									$count++;
								}
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>

      </div>



    </div>
    <hr />
    <div class="container">
      <div class="text-center">
        <h2>Plans</h2>
      </div>

      <div class="d-flex justify-content-center" style="position: relative; overflow: hidden;">
        <div id="planCarousel" class="carousel slide" data-ride="carousel" >
          <div class="carousel-inner">
            <?php
				$hub_plans = get_hub_plans($hub_id);

				$activeCount=0;
				while($plan = mysqli_fetch_array($hub_plans))
				{
					$plan_id = $plan['id'];
					$plan_title = $plan['plan_title'];
					$feats = $plan['feats'];
					$price = $plan['price'];
					$frequency = $plan['frequency'];
					$cycles = $plan['cycles'];
					if($activeCount<1)
						echo '<div class="carousel-item active">';
					else
						echo '<div class="carousel-item">';
					$activeCount++;
					echo '<div class="card" style="min-width:17rem; min-height:20rem">';

					echo '<div class="card-header text-center"><b>';
					echo $plan_title;
					echo '</b> / <span class="text-muted">';
					if($frequency == 'D')
						echo 'daily';
					else if($frequency == 'M')
						echo 'monthly';
					else if($frequency == 'Y')
						echo 'yearly';
					echo '</span></div>';
					$feats_list = explode(';', $feats);
					echo '<div class="card-body">';
					echo '<ul>';
					for($i = 0; $i < count($feats_list); $i++){
						if(empty($feats_list[$i]))
							continue;
						echo '<li>';
						echo $feats_list[$i];
						echo '</li>';
					}
					echo '</ul>';
					echo '</div>';
					
					echo '<div class="card-footer text-center text-muted">';
					echo '<p>Starting at <b>' . $price . '$USD</b></p>';
					//echo '<button class="btn btn-outline-secondary">Buy now PREVIEW</button>';
					echo '<button class="btn btn-outline-secondary mx-2" onclick="editPlan('.$plan_id.')" data-toggle="modal" data-target="#planModal">Edit Plan</button>';
					echo '<button class="btn btn-outline-danger" onclick="deletePlan('.$plan_id.')">Delete Plan</button>';

					echo '<input type="hidden" id="input_plan_id'.$plan_id.'" value="'.$plan_id.'"/>';
					echo '<input type="hidden" id="input_plan_title'.$plan_id.'" value="'.$plan_title.'"/>';
					echo '<input type="hidden" id="input_plan_feats'.$plan_id.'" value="'.$feats.'"/>';
					echo '<input type="hidden" id="input_plan_price'.$plan_id.'" value="'.$price.'"/>';
					echo '<input type="hidden" id="input_plan_frequency'.$plan_id.'" value="'.$frequency.'"/>';
					echo '<input type="hidden" id="input_plan_cycles'.$plan_id.'" value="'.$cycles.'"/>';
					echo '</div>';

					echo '</div>';
					echo '</div>';
				}
			?>
          </div>
          <a class="carousel-control-prev" href="#planCarousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
          </a>
          <a class="carousel-control-next" href="#planCarousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
          </a>
        </div>
      </div>
    </div>

    <div class="mt-5 text-center">
      <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#planModal">
        New Plan
      </button>

      <div class="modal fade" id="planModal" tabindex="-1" role="dialog" aria-labelledby="planModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="planModalLongTitle">Define a new Plan</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
				<div class="main-form">
				  <div class="row">
					<div class="col-2">
					  Title
					</div>
					<div class="col col-offset-4">
					  <input class="form-control" type="text" name="planTitle" id="planTitle"/>
					</div>
				  </div>
				  <div class="row my-2">
					<div class="col-2">
					  Feats
					  <img style="display:inline" src="../images/exclamation_i16x16.png" data-toggle="tooltip" data-placement="right" title="Add some features you are offerings to your users" width="16" height="16"/>
					</div>
					<div class="col-4 featContainer">
					  <input class="form-control" type="text" id="planFeat1" name="planFeat1" />
					  <input class="form-control" type="hidden" id="featsCount" name="featsCount" value="1" />
					</div>
					<div class="col">
					  <button class="btn btn-outline-success" style="display: inline;" id="increaseFeats">+</button>
					  <button class="btn btn-outline-danger" style="display: inline;" id="decreaseFeats">-</button>
					</div>
				  </div>
				  <div class="row mt-2">
					<div class="col-2">
					  Price
					</div>
					<div class="col-4">
					  <input class="form-control" type="number" value="1.00" name="planPrice" id="planPrice" step=".01"/>
					</div>
					<div class="col-1 mx-0 my-1">
					  USD
					</div>
				  </div>
				  <div class="row mt-2">
					<div class="col-2">
						  Expiration
						  <img src="../images/exclamation_i16x16.png" data-toggle="tooltip" data-placement="right" title="This is how long a user can stay on your plan" width="16" height="16"/>
					</div>
					<div class="col-7 xs-offset-2 col-sm-6 col-lg-6 col-xs-3">
						<div class="d-flex justify-content-around">
							<label>
								<input type="radio" name="frequency" value="D"/>
								Daily
							</label>
							<label>
								<input type="radio" name="frequency" value="M"/>
								Monthly
							</label>
							<label>
								<input type="radio" name="frequency" value="Y"/>
								Yearly
							</label>
						</div>
					</div>
				  </div>
				  <div class="row my-2">
					<div class="col-2">
					</div>
					<div class="col-4">
						<input id="cyclesInput" type="number" class="form-control" disabled>
					</div>
				  </div>
				</div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" id="modalclosebtn" data-dismiss="modal">Close</button>
              <button type="button" name="plansubmit" id="plansubmit" data-dismiss="modal" class="btn btn-primary">Save changes</button>
			  <?php //Setting this id meanst we going to update?>
			  <input type="hidden" id="planId" value="" />
            </div>
          </div>
        </div>
      </div>
    </div>
    <hr />


	<h2 class="text-center">Visibility</h2>

	<?php 
		$public_input_tag = "";
		echo '<div class="container d-flex my-2">';
				if($hub_owner_plan['current_plan'] == 'regular'){
					$public_input_tag = '<label><input name="hub_visibility" type="radio" value="1" ' . ($hub['visibility'] == 1 ? "checked" : "") . ' />Public</label>';
				}
				else{
					echo '<span class="alert alert-warning text-center">You can share your HUB and start monetizing it after purchasing a <b>STAPP</b> Plan! <a href="../pricing/">See Pricing</a></span>';
					$public_input_tag = '<label class="text-muted"><input type="radio" disabled />Public (Only paid users)</label>';
				}
		echo '</div>';
		
		echo '<div class="container d-flex flex-column">';
		echo '<label><input name="hub_visibility" type="radio"'; 
				if($hub['visibility'] == 0) echo 'checked';
		echo ' value="0" /> Private</label>';
		echo $public_input_tag;
		echo '</div>';
	?>

    <div class="row">
      <div class="col-lg-4 col-md-5 col-xs-6">
	      <?php 
			  echo '<div style="margin: 0 auto">';
	          echo '<a href="../hub/'. $_SESSION['username'] .'" class="btn btn-dark">Preview</a>';
			  echo '</div>';
	      ?>
      </div>
    </div>

  </div>


  <?php require_once '../Constants/fullfooter.php'; ?>
  <script>
		 $(function () {
		  $('[data-toggle="tooltip"]').tooltip()
		})
  </script>
</body>
</html>


<!--/*
	echo ' <div class="col-2 smallpic">';
	echo '<img class="" style="margin: 0;" src="'.$pictures[0].'"></img>';
	echo '</div>';
	echo ' <div class="col-2 smallpic">';
	echo '<img class="" src="'.$pictures[0].'"></img>';
	echo '</div>';
	echo ' <div class="col-2 smallpic">';
	echo '<img class="" src="'.$pictures[0].'"></img>';
	echo '</div>';
	echo ' <div class="col-4 mr-0 smallpic">';
	echo '<img class="" src="'.$pictures[0].'"></img>';
	echo '</div>';*/-->



		 

