$(function(){
});

function thumbSelected(thumb){
	var imgPath = thumb.getAttribute("src");
	$("nopictureadvice").remove();
	//$(".largepic").css("background-image", "url(" + imgPath +")");
	$("#imgpreview").attr("src", imgPath);
}

