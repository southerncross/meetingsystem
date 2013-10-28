/************************
 ajaxtable.js ajax从后台获取数据模块， 用于html中table的显示
 author: lishunyang
*************************/

/** 
    gettable 以ajax方式同后台php交互，得到json编码的数据
    param: name 
    param: filter，
    param: callback，
    return: void
*/ 
function gettable(name, filter, callback) {
    if (0 == name.length)
	return false;

    var url = "phplib/echotable.php";
    var data = {"name": name, "filter": filter};
    $.post(url, data, callback);
}

/** 
    echotable 在html中填充表格
    param: res json格式的表格数据
    return: void
    notice: 要求html具有 id=hint的文本域，用于显示错误信息，id=table的表格，用于填充表格内容   */
function echotable(res) {
    if (!res) {
	document.getElementById("hint").innerHTML = "未知错误，请稍后再试";
	//$("#hint").html("未知错误，请稍后再试");
	return;
    }
    
    var r = JSON.parse(res);
    if (r.status != 0) {
	alert(r.status);
	return;
    }

    var table = document.getElementById("table");
    var row;
    var cell;
    var i, j;

    // TODO: 没有区分表头和表体
    for (i in r.data) {
	row = table.insertRow(i);
	j = 0;
	for (j in r.data[i]) {
	    cell = row.insertCell(j);
	    cell.innerHTML = r.data[i][j];
	    j++;
	}
    }
}

