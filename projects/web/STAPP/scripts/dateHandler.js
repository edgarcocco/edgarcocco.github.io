var month = new Array();
var date = new Date();

function removeOptions(selectbox)
{
    var i;
    for(i = selectbox.options.length - 1 ; i >= 0 ; i--)
    {
        selectbox.remove(i);
    }
}

function getDaysInMonth(m, y) {
    console.log(m);
   return /8|3|5|10/.test(--m)?30:m==1?(!(y%4)&&y%100)||!(y%400)?29:28:31;

}

function populateDay(month){
    var min = 1,
        max = (month == null) ? 31 : getDaysInMonth(parseInt(month)+1, date.getFullYear()),
    selectDay = document.getElementById('selectDay');
    removeOptions(selectDay);
    for(var i = min; i <=max; i++){
        var opt = document.createElement('option');
        opt.value = i;
        opt.innerHTML = i;
        if(i == 0) opt.setAttribute("selected", "");
        selectDay.appendChild(opt);
    }
}

function populateMonthArray()	{
	month[0] = "January";
	month[1] = "February";
	month[2] = "March";
	month[3] = "April";
	month[4] = "May";
	month[5] = "June";
	month[6] = "July";
	month[7] = "August";
	month[8] = "September";
	month[9] = "October";
	month[10] = "November";
	month[11] = "December";
}

function populateMonth(){
	populateMonthArray();
    var min = 0,
        max = 11,
    selectMonth = document.getElementById('selectMonth');

	for (var i = min; i<=max; i++)
    {
        var opt = document.createElement('option');
        opt.value = i;
        opt.innerHTML = i;
        opt.text = month[i];
        if(i == 0) opt.setAttribute("selected", "");
        selectMonth.appendChild(opt);
	}
    selectMonth.setAttribute("onchange", "populateDay(selectMonth.options[selectMonth.selectedIndex].value)");
}

function populateYear() {
    var min = 1900,
        max = 2000,
    selectYear = document.getElementById('selectYear');

	for (var i = min; i<=max; i++)
    {
        var opt = document.createElement('option');
        opt.value = i;
        opt.innerHTML = i;
        if(i == max) opt.setAttribute("selected", "");
        selectYear.appendChild(opt);
	}

}

function populateSelect(){
    populateDay();
    populateMonth();
    populateYear();
}

populateSelect();
