var currentMousePosition = 0;
var lastMousePosition = 0;
var isMovingLeft = false;
var isMovingRight = false;
var isMousePressed = false;
var scrollBy = 0;


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

  var columns = document.querySelectorAll(".col-7");
  var columnsWidth = 0;
  for(var i = 0; i < columns.length-2; i++)
    columnsWidth += columns[i].offsetWidth;

  window.onmousemove = function(event) {
    currentMousePosition = event.clientX;

    scrollBy = currentMousePosition - lastMousePosition;
    if(isMousePressed) {
      var scrollableDiv = document.querySelector("#scrollable-div");
      var leftValue = parseInt(scrollableDiv.style.left.split('p')[0]);
      var scrollTo = leftValue + scrollBy;
      if(Object.is(leftValue, NaN))
        leftValue = -1;

      if(scrollTo > 0)
        return;
      if(scrollTo < (columnsWidth * -1))
        return;

      scrollableDiv.style.left = (leftValue + scrollBy) + "px";
    }

    lastMousePosition = currentMousePosition;
  }
};

