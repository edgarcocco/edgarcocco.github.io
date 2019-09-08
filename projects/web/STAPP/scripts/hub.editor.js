
var featsIndex = 1;
$(function(){

	$("#hubusername").click(function(){
        $("#usernameTxtBox").show();
        $("#saveBtn").show();
        $(this).hide();
	});

    $("#saveBtn").click(function(){
		var usernameTxtBoxText = $("#usernameTxtBox").val();
		if(usernameTxtBoxText!="")
		{	
			var parameters = {
				"property" : "name",
				"value" : usernameTxtBoxText
			};
			$.ajax({
				  data: parameters,
				  type: "POST",
				  url: "hubdatahandler",
				  success: function(responseText){
					$("#hubusername").text($("#usernameTxtBox").val());
				   }
			});
		}
		$(this).hide();
		$("#usernameTxtBox").hide();
		$("#hubusername").show();
	});

	$("#inputimage").bind('change',function(event){
		var path = URL.createObjectURL(event.target.files[0]);
		$(".addpicthumb").remove();
		$(".thumblabel").remove();
		$(".previewImage").append('<img id="imgprev" class="" width="160" height="160"></img>\
									<p>\
									<form enctype="multipart/form-data" action="" method="post" \
													id="previewForm">\
									<button type="submit" class="btn btn-info my-2 text-left uploadbtn"\
											name="imagesubmit">Upload</button>\
									</form>\
									</p>');
		$(".previewImage").removeClass("d-flex");
		$("#imgprev").attr("src", path);
		$(this).prependTo("#previewForm");
	});

	$("#addstatbtn").click(function(){
		if($(".statRow").length){
			return;
		}
		$("#statbody1").append(appendStatInputs("", "", "new_stat"));
	});

	$("#addlinkbtn").click(function(){
		if($(".linkRow").length){
			return;
		}
		$("#linksbody1").append(appendLinkInputs("", "new_link"));
	});

	$("#editbiobtn").click(function(){
		if($(".bioInputContainer").length){
			return;
		}

		$(".biop").css("display", "none");
		$(".biocontainer").append('<div class="bioInputContainer"><textarea id="biographyInput" rows="10" cols="80">'+$(".biop").text()+'</textarea><button class="btn btn-success" id="saveBioBtn" onclick="acceptBioChanges()">Save</button></div>');
	});
	$("#increaseFeats").click(function(){
		featsIndex++;
		$(".featContainer").append('<input class="form-control" type="text" id="planFeat'+featsIndex+'" name="planFeat'+featsIndex+'"/>');
		$("#featsCount").attr("value", featsIndex);
	});
	$("#decreaseFeats").click(function(){
		if(featsIndex > 1){
			$("#planFeat"+featsIndex).remove();
			featsIndex--;
			$("#featsCount").attr("value", featsIndex);
		}
	});
	$("#plansubmit").click(function(){
		var planId = $("#planId").val();
		var planTitle = $("#planTitle").val();
		var planPrice = $("#planPrice").val();
		var featsCount = featsIndex;
		var feats = "";
		var frequency = $("input:radio[name ='frequency']:checked").val();
		var cycles = $("#cyclesInput").val();
		if(cycles == "")
			cycles = 1;
		var i;
		for(i = 1; i < featsCount+1; i++){
			if(i > 1)
			{
				feats+= ";";
			}
			feats += $("#planFeat"+i).val();
		}
		var parameters = {
			"property" : "plan",
			"id" : planId,
			"title" : planTitle,
			"feats" : feats,
			"price" : planPrice,
			"frequency" : frequency,
			"cycles" : cycles
		};
		$.ajax({
		  data: parameters,
		  type: "POST",
		  url: "hubdatahandler",
		  success: function(responseText){
			  if(responseText == "queryS"){
				  location.reload(true);
			  }
		   }
		});
	});
	
	$('#planModal').on('hidden.bs.modal', function (e) {
		featsIndex=1;
		$("#planId").val("");
		$("#planTitle").val("");
		$(".featContainer").empty();
		$(".featContainer").append('<input class="form-control" type="text" id="planFeat'+featsIndex+'" name="planFeat'+featsIndex+'" />');
		$(".featContainer").append('<input class="form-control" type="hidden" id="featsCount" name="featsCount" value="1" />');
		$("planPrice").val('1.00')
		$("#cyclesInput").val("");
	});

	$('input[type=radio][name=frequency]').change(function(){
		if(this.value == "D"){
			$("#cyclesInput").prop("disabled", false);
			$("#cyclesInput").attr("placeholder", "Days");

		}
		else{
			$("#cyclesInput").prop("disabled", true);
			$("#cyclesInput").attr("placeholder", "");

		}
	});
	$('input[type=radio][name=hub_visibility]').change(function(){
		var parameters = {
			"property" : "hub_visibility",
			"value" : this.value
		};
		$.ajax({
			   data: parameters,
			   type: "POST",
			   url: "hubdatahandler",
			   success: function(responseText){
			   }
		});
	});

});

var lastSelectedStatIndex=0;
var lastSelectedLinkIndex=0;

function thumbSelected(thumb){
	var imgPath = thumb.getAttribute("src");
	$("nopictureadvice").remove();
	//$(".largepic").css("background-image", "url(" + imgPath +")");
	$("#imgpreview").attr("src", imgPath);
}

function acceptBioChanges(){
	var bio = $("#biographyInput").val();
	var parameters = {
			"property" : "biography",
			"value" : bio
	};
	$.ajax({
		   data: parameters,
		   type: "POST",
		   url: "hubdatahandler",
		   success: function(responseText){
			   if(responseText == 'queryS'){
				   removeBioInputs();
			   }
		   }
	});

}

function removeBioInputs(){
	$(".bioInputContainer").remove();
	$(".biocontainer").load(" .biop");
	//location.reload();
}

function appendStatInputs(title, body, property){
	var statRowDiv = ' <div class="row statRow">\
							<div class="col">\
								<label for="titleinput">Title</label>\
								<input type="text" class="form-control" id="titleinput" value="'+title+'" />\
							</div>\
							<div class="col">\
								<label for="titlebody">Body</label>\
								<input type="text" class="form-control" id="bodyinput" value="'+body+'"/>\
							</div>\
						</div>\
						<div class="row my-2">\
							<div class="col">\
								<button class="btn btn-success" id="statacceptbtn" onclick="acceptStatChanges(\''+property+'\')">\
									Accept\
								</button>\
							</div>\
							<div class="col">\
								<button class="btn btn-danger" id="statdeclinebtn" onclick="removeStatInputs()">\
									Decline\
								</button>\
							</div>\
						</div>';
	return statRowDiv;
}

function removeStatInputs(){
	//$(".statRow").remove();
	$("#statbody1").load(" #statbody2");
	//location.reload();
}

function showStatSettingsBtn(index){
	$("#statEditBtn" + index).show();
	$("#statDeleteBtn" + index).show();
}

function hideStatSettingsBtn(index){
	$("#statEditBtn" + index).hide();
	$("#statDeleteBtn" + index).hide();
}
function editStat(index){
	var stat_title = $("#stat_title"+index).val();
	var stat_body = $("#stat_body"+index).val();
	$("#statInfo"+index).hide();
	$("#statbody2").prepend(appendStatInputs(stat_title, stat_body, "edit_stat"));
	lastSelectedStatIndex = index;
}

function acceptStatChanges(property){
	var title = $("#titleinput").val();
	var body = $("#bodyinput").val();
	var parameters = {
			"property" : property,
			"value" : lastSelectedStatIndex + ";" + title + ";" + body
	};
	$.ajax({
		   data: parameters,
		   type: "POST",
		   url: "hubdatahandler",
		   success: function(responseText){
			   if(responseText == 'queryS'){
				   removeStatInputs();
			   }
		   }
	});
}

function deleteStatDialog(index){
	var parameters = {
		"property" : "deleteStat",
		"value" : index
	};
	$.ajax({
		   data: parameters,
		   type: "POST",
		   url: "hubdatahandler",
		   success: function(responseText){
			   if(responseText == 'queryS'){
				   location.reload(true);
			   }
		   }
	});
}
function appendLinkInputs(link, property){
	var linkRowDiv = '<div class="row linkRow">\
									<div class="col form-group">\
										<label for="titleinput">Link</label>\
										<input type="text" placeholder="eg: http://instagram/myinstagram" class="form-control" id="linkinput" value="'+link+'" />\
										<div class="invalid-feedback">\
											Please double check your URL Format.\
										</div>\
									</div>\
							  	</div>\
								<div class="row my-2">\
							  		<div class="col">\
							  			<button class="btn btn-success" id="linkacceptbtn" onclick="acceptLinkChanges(\''+property+'\')">\
							  				Accept\
							  			</button>\
							  		</div>\
							  		<div class="col">\
										<button class="btn btn-danger" id="linkdeclinebtn" onclick="removeLinkInputs()">\
							  				Decline\
							  			</button>\
							  		</div>\
								</div>';
	return linkRowDiv;
}

function showLinkSettingsBtn(index){
	$("#linkEditBtn" + index).show();
	$("#linkDeleteBtn" + index).show();
}
function hideLinkSettingsBtn(index){
	$("#linkEditBtn" + index).hide();
	$("#linkDeleteBtn" + index).hide();
}

function editLink(index){
	var link = $("#linkval"+index).val();
	alert(link + index);
	$("#linkInfo"+index).hide();
	$("#linksbody2").prepend(appendLinkInputs(link, "edit_link"));
	lastSelectedLinkIndex = index;
}

function acceptLinkChanges(property){
	var link = $("#linkinput").val();
	if(link.match(/([A-z]+\:\/\/)?(([A-z-0-9]+)?\.([A-z]+))/) == null){
		$("#linkinput").addClass("is-invalid");
		return;
	}
	var parameters = {
		"property" : property,
		"value" : lastSelectedLinkIndex + ";" + link
	};

	$.ajax({
		   data: parameters,
		   type: "POST",
		   url: "hubdatahandler",
		   success: function(responseText){
			   if(responseText == 'queryS'){
				   removeLinkInputs();
			   }
		   }
	});
}

function removeLinkInputs(){
	//$(".linkRow").remove();
	$("#linksbody1").load(" #linksbody2");
	//location.reload();
}

function deleteLinkDialog(index){
	var parameters = {
		"property" : "deleteLink",
		"value" : index
	};
	$.ajax({
		   data: parameters,
		   type: "POST",
		   url: "hubdatahandler",
		   success: function(responseText){
			   if(responseText == 'queryS'){
				   location.reload(true);
			   }
		   }
	});
}

function showDeleteImgBtn(index){
	$("#deleteimgbtn" + index).show();
}
function hideDeleteImgBtn(index){
	$("#deleteimgbtn" + index).hide();
}
function deleteImgDialog(index){
	var parameters = {
		"property" : "deleteImg",
		"value" : index
	};

	$.ajax({
		   data: parameters,
		   type: "POST",
		   url: "hubdatahandler",
		   success: function(responseText){
			   if(responseText == 'queryS'){
				   location.reload(true);
			   }
		   }
	});
}

function editPlan(index)
{
	var input_plan_id = $("#input_plan_id"+index).val();
	var input_plan_title = $("#input_plan_title"+index).val();	
	var input_plan_feats = $("#input_plan_feats"+index).val().split(";");
	var input_plan_price = $("#input_plan_price"+index).val();
	var input_plan_frequency = $("#input_plan_frequency"+index).val();
	var input_plan_cycles = $("#input_plan_cycles"+index).val();
	featsIndex=1;

	//IMPORTANT
	//we need to set plan id in order to edit
	$("#planId").val(input_plan_id);
	$("#planTitle").val(input_plan_title);
	$(".featContainer").empty();
	$(".featContainer").append('<input class="form-control" type="hidden" id="featsCount" name="featsCount" value="1" />');
	for(var i = 0; i < input_plan_feats.length; i++){
		
		$(".featContainer").append('<input class="form-control" type="text" id="planFeat'+featsIndex+'" name="planFeat'+featsIndex+'" value="'+input_plan_feats[i]+'"/>');
		$("#featsCount").attr("value", featsIndex);
		if(i+1<input_plan_feats.length){
		featsIndex++;
		}
	}
	$("#featsCount").attr("value", featsIndex);
	$("planPrice").val(input_plan_price)
	$("input[name=frequency][value="+input_plan_frequency+"]").prop('checked', true);
	if(input_plan_frequency == "D")
	{
		$("#cyclesInput").prop("disabled", false);
		$("#cyclesInput").attr("placeholder", "Days");
	}
	else
	{
		$("#cyclesInput").prop("disabled", true);
		$("#cyclesInput").attr("placeholder", "");
	}
	$("#cyclesInput").val(input_plan_cycles);
}

function deletePlan(index)
{
	var parameters = {
		"property" : "delete_plan",
		"value" : index
	};

	$.ajax({
		   data: parameters,
		   type: "POST",
		   url: "hubdatahandler",
		   success: function(responseText){
			   if(responseText == 'queryS'){
				   location.reload(true);
			   }
		   }
	});

}
