/******
       echotable
       several functions about printing the table
       Author: lishunyang(pkuuuuu@pku.edu.cn)
 ******/

/*****
      echoFilterTable
      colored filter table!
      warning: there must be a table component who's id is 'table'
*****/
function echoFilterTable(res) {
    if (!res) {
	document.getElementById("hint").innerHTML = "未知错误，请稍后再试";
	return;
    }
    var r = JSON.parse(res);
    if (r.status != 0) {
	alert(r.status);
	return;
    }

    var table = document.getElementById("table");
    table.style.display = ""; // display table
    var row;
    var cell;
    var i, j;
    var mclr = "#F0F0F0"; // mouse move color
    var bgclr = "#FFFFFF"; // background color
    var sclr = "#37c350";// selected color

    for (i in r.table.data) {
	row = table.insertRow(i)
	j = 0;
	for (j in r.table.data[i]) {
	    cell = row.insertCell(j);
	    cell.innerHTML = r.table.data[i][j];
	    //cell.style.display = (0==j ? "none" : ""); // do not show 'id', DEBUG INFO
	    j++;
	}
	cell = row.insertCell(j);
	cell.innerHTML = "0";
	//cell.style.display="none"; // do not show 'selected', DEBUG INFO
	row.onmouseover = function(){
	    this.style.backgroundColor = mclr;
	};
	row.onmouseout = function(){
	    if (this.cells[j].innerHTML == "1")
		this.style.backgroundColor = sclr;
	    else
		this.style.backgroundColor = bgclr;
	};
	row.onclick = function(){
	    if (this.cells[j].innerHTML == "1") {
		this.cells[j].innerHTML = "0";
		this.style.backgroundColor = mclr;
	    }
	    else {
		this.cells[j].innerHTML = "1";
		this.style.backgroundColor = sclr;
	    }
	};
    }

    // 插入表头
    // TODO： 这里调用table.insertRow函数其实并没有区分哪个是表头，待解决
    row = table.insertRow(0);
    j = 0;
    for (j in r.table.head) {
	cell = row.insertCell(j);
	cell.innerHTML = r.table.head[j];
	//cell.style.display = (0 == j ? "none" : ""); // do not show 'id', DEBUG INFO
	j++;
    }
    cell = row.insertCell(j);
    cell.innerHTML = "-";
    //cell.style.display="none"; // do not show 'selected', DEBUG INFO
}


/******
       echoResultTable
       warning: you must have a table component which has id 'table' in your html file!
******/
function echoResultTable(res) {
    if (!res) {
	document.getElementById("hint").innerHTML = "未知错误，请稍后再试";
	return;
    }
    var r = JSON.parse(res);
    if (r.status != 0) {
	alert(r.status);
	return;
    }

    var table = document.getElementById("table");
    table.style.display = ""; // display table
    var row;
    var cell;
    var i, j;

    for (i in r.table.data) {
	row = table.insertRow(i)
	for (j in r.table.data[i]) {
	    cell = row.insertCell(j);
	    cell.innerHTML = r.table.data[i][j];
	    j++;
	}
    }

    // 插入表头
    // TODO： 这里调用table.insertRow函数其实并没有区分哪个是表头，待解决
    row = table.insertRow(0);
    j = 0;
    for (j in r.table.head) {
	cell = row.insertCell(j);
	cell.innerHTML = r.table.head[j];
	j++;
    }
}
