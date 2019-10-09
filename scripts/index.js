var columns = null;
var columnsWidth = 0;
var currentMousePosition = 0;
var lastMousePosition = 0;
var isMovingLeft = false;
var isMovingRight = false;
var isMousePressed = false;
var scrollBy = 0;
var scrollPosition = 0;


// arrowAnimation
var showDragAnimation = true;
var show = false;
var arrows = null;
var currentArrowIndex = 0;
var dragDiv = null;


function windowResized() {
  prepareColumns();
}

window.onload = function() {
  var scrollableDiv = document.querySelector("#scrollable-div");
  scrollableDiv.x = 0;
  scrollableDiv.onmousedown = function() {
    isMousePressed = true;
  };
  scrollableDiv.onmouseleave = function() {
    isMousePressed = false;
  }
  scrollableDiv.onmouseup = function() {
    isMousePressed = false;
  }
  
  window.onresize = windowResized;
  window.onmousemove = function(event) {
    currentMousePosition = event.clientX;

    scrollBy = currentMousePosition - lastMousePosition;
    if(isMousePressed) {
      var scrollableDiv = document.querySelector("#scrollable-div");
      var leftValue = parseInt(scrollableDiv.style.left.split('p')[0]);
      var scrollTo = leftValue + scrollBy;

      if(Object.is(scrollTo, NaN))
        scrollTo = -1;
      if(scrollTo > 0)
        scrollTo = 0;
      if(scrollTo < (columnsWidth * -1))
        scrollTo = columnsWidth * -1;

      scrollableDiv.style.left = (scrollTo) + "px";
      showDragAnimation=false;
      scrollPosition = (Math.abs(leftValue) / columnsWidth) * 100;
    }
    lastMousePosition = currentMousePosition;
  }

  prepareColumns();
  dragDiv = document.querySelector(".drag-div");
  arrows = document.querySelectorAll(".right-arrow")
  for(var i = 0; i < arrows.length; i++)
    arrows[i].style.opacity = 1;
  currentArrowIndex = arrows.length-1;
  setInterval(update, 1);
};

function prepareColumns() {
  columns = document.querySelectorAll(".scrollable-column");
  columnsWidth = 0;
  for(var i = 0; i < columns.length-1; i++)
    columnsWidth += columns[i].offsetWidth;
}

function update(){
  if(showDragAnimation)

    arrowAnimation();
  if(!showDragAnimation && dragDiv.style.opacity == "") 
    dragDiv.remove();

  divFadeIn();
}

function divFadeIn() {
  for(var i = 1; i < columns.length; i++) {
    var columnPosition = (100/columns.length) * i;

    if(scrollPosition > columnPosition){
      var increaseBy = 0.01;
      var opacity = parseFloat(columns[i].style.opacity);
      if(Object.is(opacity, NaN))
        opacity = increaseBy;
      columns[i].style.opacity =  Math.min(opacity + increaseBy, 1);
    }
  }
}

function arrowAnimation() {
  if(show == true){
    var opacity = parseFloat(arrows[currentArrowIndex].style.opacity);
    arrows[currentArrowIndex].style.opacity = opacity + 0.01;
    if(arrows[currentArrowIndex].style.opacity > 0.99){
      currentArrowIndex--;
    }
    if(currentArrowIndex < 0) {
      currentArrowIndex = 2;
      show = false;
    }

  }
  else{
    var opacity = parseFloat(arrows[currentArrowIndex].style.opacity);
    arrows[currentArrowIndex].style.opacity = opacity - 0.01;
    if(arrows[currentArrowIndex].style.opacity < 0.01)
      currentArrowIndex--;
    if(currentArrowIndex < 0) {
      currentArrowIndex=2;
      show=true;
    }
  }
}





