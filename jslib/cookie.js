function setCookie(c_name,value,expiredays)
{
    var exdate = new Date();
    exdate.setDate(exdate.getDate() + expiredays);
    document.cookie = c_name + "=" + escape(value) +
	((expiredays == null) ? "" : ";expires=" + exdate.toGMTString());
}

function getCookie(c_name)
{
    if (document.cookie.length > 0)
    {
	var c_start = document.cookie.indexOf(c_name + "=");
	if (c_start != -1)
	{ 
	    var c_start = c_start + c_name.length + 1;
	    var c_end = document.cookie.indexOf(";", c_start);
	    if (c_end == -1) c_end=document.cookie.length;
	    return unescape(document.cookie.substring(c_start,c_end));
	} 
    }
    return "";
}

function delCookie(c_name)//删除cookie
{
    var exdate = new Date();
    exdate.setTime(exdate.getTime() - 1);
    var val = getCookie(c_name);
    if (val != null) document.cookie = c_name + "=" + val 
	+ ";expires=" + exdate.toGMTString();
}
