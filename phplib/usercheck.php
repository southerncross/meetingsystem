<?php
include "config.php";

function checkusername($name) {
  if (DEBUG_MODE)
    $fp = fopen('./usercheck.log', 'w'); // DEBUG
  $res = array();

  if (strlen($name) == 0) {
    $res['status'] = -ERR_INPUT_FORMAT;
    $res['msg'] = 'empty user name';
    if (DEBUG_MODE)
      fwrite($fp, "empty\n"); // DEBUG
    return $res;
  }

  $conn = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
  if (!$conn) {
    $res['status'] = -ERR_DB_CONNECTION;
    if (DEBUG_MODE)
      fwrite($fp, "connection err"); // DEBUG
    return $res;
  }


  mysql_select_db(DB_NAME, $conn);
  $query = "SELECT * FROM person WHERE id = ".$name;
  $result = mysql_query($query);
  if ($result && mysql_num_rows($result) != 0) {
    $res['status'] = 0;
    $res['msg'] = "ok";
  }
  else {
    $res['status'] = -ERR_USER_EXIST;
    $res['msg'] = 'user doesn\'t exist';
  }
  mysql_close($conn);
  
  if (DEBUG_MODE) {
    fwrite($fp, "normal\n".$res['msg']); // DEBUG
    fclose($fp);
  }
  return $res;
}

function checkpassword($name, $password) {
  if (DEBUG_MODE)
    $fp = fopen('./usercheck.log', 'w'); // DEBUG
  $res = array();

  if (strlen($name) == 0) {
    $res['status'] = -ERR_INPUT_FORMAT;
    $res['msg'] = 'empty user name';
    return $res;
  }

  $conn = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
  if (!$conn) {
    $res['status'] = -ERR_DB_CONNECTION;
    if (DEBUG_MODE)
      fwrite($fp, "connection err"); // DEBUG
    return $res;
  }

  // TODO: doesn't check password actually.
  mysql_select_db(DB_NAME, $conn);
  $query = "SELECT * FROM person WHERE id = ".$name;
  $result = mysql_query($query);
  if ($result && mysql_num_rows($result) != 0) {
    $person = mysql_fetch_array($result);
    $res['msg'] = $person['name'];
    $res['status'] = 0;
  }
  else {
    $res['msg'] = 'user doesn\'t exist';
    $res['status'] = -ERR_USER_EXIST;
  }
  mysql_close($conn);
  
  if (DEBUG_MODE) {
    fwrite($fp, "normal\n".$res['msg']); // DEBUG
    fclose($fp);
  }
  return $res;
}
?>
