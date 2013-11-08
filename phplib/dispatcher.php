<?php
/***
    这一层的作用是将底层代发返回的php关联数组转化为json格式供前端js使用
 ***/

define('DEBUG_MODE', 1);

session_start();

// TODO session check

if (DEBUG_MODE)
  $fp = fopen('./dispatcher.log', 'w'); // DEBUG

if (!isset($_POST['method'])) {
  // exception method
  // TODO
  return;
}

if ($_POST['method'] == 'gettable') {
  include "dao.php";
  if (isset($_POST['filter']))
    $res = getdata($_POST['name'], $_POST['filter']);
  else
    $res = getdata($_POST['name']);
  echo json_encode($res);
  if (DEBUG_MODE) {
    fclose($fp);
  }
  return;
}

if ($_POST['method'] == 'checkusername') {
  include "usercheck.php";
  $res = checkusername($_POST['name']);
  echo json_encode($res);
  if (DEBUG_MODE) {
    fwrite($fp, "checkusername\n".$_POST['name']); // DEBUG
    fclose($fp);
  }
  return;
}

if ($_POST['method'] == 'checkpassword') {
  include "usercheck.php";
  $res = checkpassword($_POST['name'], $_POST['password']);
  echo json_encode($res);
  if (DEBUG_MODE) {
    fwrite($fp, "checkpassword\n".$_POST['name']." ".$_POST['password']); // DEBUG
    fclose($fp);
  }
  return;
}

$res = array();
$res['status'] = -ERR_NAME_MATCH;
echo json_encode($res);
if (DEBUG_MODE) {
  fwrite($fp, "not match"); // DEBUG
  fclose($fp);
}
return;
?>