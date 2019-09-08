var canvas;

var carousel;
var firstSlide=true;
var secondSlide=false;
var thirdSlide=false;

var customerLogo;
var snapchatLogo;
var twitterLogo;
var instagramLogo;
var tumblrLogo;
var noCommercialLogo;
var creativityLogo;
function preload(){
	customerLogo = loadImage('images/customer-96.png');
	snapchatLogo = loadImage('images/snapchat-528.png');
	twitterLogo = loadImage('images/twitter-528.png');
	instagramLogo = loadImage('images/instagram-528.png');
	tumblrLogo = loadImage('images/tumblr-528.png');
	noCommercialLogo = loadImage('images/no-commercial-96.png');
	creativityLogo = loadImage('images/creativity-96.png');
}

function setup() {
	canvas = createCanvas(800,400);
	canvas.parent('firstSketch');
	canvas.class('d-block');
	canvas.addClass('w-100');

	carousel = document.getElementById('carousel-indicators');
}

var startAnimation=false;
function draw()	{
	if(isScrolledIntoView(carousel)){
		startAnimation=true;
	}

	if(startAnimation){
		increaseTint();
	}
	else
	{
		decreaseTint();
	}

	if(firstSlide == true){
		background(34);
		var width = 128;
		var height = 128;
		var x = (canvas.width - width*4)/2;
		var y = (canvas.height - height) /2;
		var margin = 10;

		tint(255, snapchatTint);
		image(snapchatLogo, x, y, width, height);
		image(customerLogo, (canvas.width-96)/2, 0, 96, 96);

		tint(255, twitterTint);
		image(twitterLogo, x + 128 + margin, y, width, height);

		tint(255, instagramTint);
		image(instagramLogo,x + 256 + margin*2, y, width, height);

		tint(255, tumblrTint);
		image(tumblrLogo, x + 384 + margin*3, y, width, height);
	}
	else if(secondSlide == true){
		background(34);
		tint(255,255);
		var x = (canvas.width - 96)/2;
		var y = (canvas.height - 96)/2;
		image(noCommercialLogo, x - canvas.width/4, y);
		image(creativityLogo, x + canvas.width/4, y);
	}
	else if(thirdSlide == true){
		background(34);
	}

	assignSlide($('.carousel-indicators > .active').index());
}

var snapchatTint = 0;
var twitterTint=0;
var instagramTint=0;
var tumblrTint=0;
function increaseTint(){
	var value = 10;
	if(snapchatTint < 256){
		snapchatTint += value;
	}
	else if(twitterTint < 256){
		twitterTint += value;
	}
	else if(instagramTint < 256){
		instagramTint += value;
	}
	else if(tumblrTint < 256){
		tumblrTint += value;
	}
}
function decreaseTint(){
	var value = 10;
	if(tumblrTint > 0){
		tumblrTint -= value;
	}
	else if(instagramTint > 0){
		instagramTint -= value;
	}
	else if(twitterTint > 0){
		twitterTint -= value;
	}
	else if(snapchatTint > 0){
		snapchatTint -= value;
	}
}

function assignSlide(n){
	setDrawsOff();
	hideCanvas(n);
	switch(n) {
		case 0:
			canvas.parent('firstSketch')
			firstSlide=true;
			break;
		case 1:
			canvas.parent('secondSketch')
			secondSlide=true;
			break;
		case 2:
			canvas.parent('thirdSketch')
			thirdSlide=true;
			break;
		default:
			canvas.parent('firstSketch')
			firstSlide=true;
			break;
	}
}

function hideCanvas(n){
	showCanvas();
	switch(n) {
		case 0:
			document.getElementById("firstCanvas").style.display = "none";
			break;
		case 1:
			document.getElementById("secondCanvas").style.display = "none";
			break;
		case 2:
			//document.getElementById("thirdCanvas").style.display = "none";
			break;
	}
}

function showCanvas() {
	document.getElementById("firstCanvas").style.display="block";
	document.getElementById("secondCanvas").style.display="block";
	//document.getElementById("thirdCanvas").style.display="block";
}

function setDrawsOff(){
	firstSlide=false;
	secondSlide=false;
	thirdSlide=false;
}


