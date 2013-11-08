function checkUserName(userName, callback) {
    var url = "phplib/dispatcher.php";
    var data = {"method": "checkusername", "name": userName};
    $.post(url, data, callback);
}

function checkUserPassword(userName, userPassword, callback) {
    var url = "phplib/dispatcher.php";
    var data = {"method": "checkpassword", "name": userName, "password": userPassword};
    $.post(url, data, callback);
}
