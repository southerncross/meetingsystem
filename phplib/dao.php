<?php

/**********************
 后台php脚本
 同mysql交互，返回表格内容
 wrote by: lishunyang
 **********************/

include "config.php";

function getdata($name, $filter = null) {
  $res = array();
  $query = "";
  $flag = false;

  $conn = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
  if (!$conn) {
    $res['status'] = -ERR_DB_CONNECTION;
    return $res;
  }
  mysql_select_db(DB_NAME, $conn);

  /** TABLE Meeting **/
  if (0 == strcmp($name, 'meeting')) {
    $res = getmeeting($conn, $filter);
    $flag = true;
  }

  /** TABLE Person **/
  if (0 == strcmp($name, 'person')) {
    $res = getperson($conn, $filter);
    $flag = true;
  }

  /** TABLE SignINAll **/
  if (0 == strcmp($name, 'signin_all')) {
    $res = getsigninall($conn, $filter);
    $flag = true;
  }

  /** TABLE SignINMe **/
  if (0 == strcmp($name, 'signin_me')) {
    $res = getsigninme($conn, $filter);
    $flag = true;
  }

  mysql_close($conn);

  if (!$flag)
    $res['status'] = -ERR_NAME_MATCH;

  return $res; // 注意，这里没有用json编码，是为了可以被其他php脚本直接使用
}

function getmeeting($conn, $filter) {
  $res['status'] = 0;
  if (null == $filter)
    $query = "SELECT * FROM meeting ORDER BY meeting.starttime";
  else {
    // TODO
  }
  // meeting
  $result = mysql_query($query);
  $meeting = array();
  while ($item = mysql_fetch_array($result))
    array_push($meeting, $item);
  // field
  $query = "SELECT * FROM field ORDER BY id";
  $result = mysql_query($query);
  $field = array();
  while ($item = mysql_fetch_array($result))
    $field[$item['id']] = $item['name'];

  $res['table'] = array();
  $res['table']['head'] = array('id', '会议名称', '地点', '开始时间', '结束时间');
  $res['table']['data'] = array();
  $i = 0;
  foreach ($meeting as $m) {
    if (array_key_exists($m['fieldID'], $field))
      $fieldname = $field[$m['fieldID']];
    else
      $fieldname = "-";
    $res['table']['data'][$i] = array($m['id'],
				      $m['description'], 
				      $fieldname,
				      $m['starttime'],
				      $m['endtime']);
    $i++;
  }
  
  return $res;
}

function getperson($conn, $filter) {
  $res['status'] = 0;
  if (null == $filter)
    $query = "SELECT * FROM person";
  else {
    // TODO
  }

  // person
  $result = mysql_query($query);
  $person = array();
  while ($item = mysql_fetch_array($result))
    array_push($person, $item);

  // card
  $query = "SELECT * FROM card";
  $result = mysql_query($query);
  $card = array();
  while ($item = mysql_fetch_array($result))
    $card[$item['idperson']] = $item['id'];

  $res['table'] = array();
  $res['table']['head'] = array('姓名', '年级', '部门', '卡片ID');
  $res['table']['data'] = array();
  $i = 0;
  foreach ($person as $p) {
    $res['table']['data'][$i] = array($p['name'], 
				      $p['grade'],
				      $p['school'],
				      $card[$p['id']]);
    $i++;
  }

  return $res;
}

function getsigninall($conn, $filter) {
  $res['status'] = 0;
  if (null == $filter)
    $query = "SELECT * FROM meeting ORDER BY id";
  else {
    // TODO
  }

  // meeting information
  $result = mysql_query($query);
  $meeting = array();
  while ($item = mysql_fetch_array($result))
    array_push($meeting, $item);

  // person information
  $query = "SELECT * FROM person ORDER BY id";
  $result = mysql_query($query);
  $person = array();
  while ($item = mysql_fetch_array($result))
    array_push($person, $item);

  // signin information
  $query = "SELECT idperson, idmeeting, arrived, signintime FROM person, meeting, signINRecord WHERE signINRecord.idperson = person.id AND signINRecord.idmeeting = meeting.id ORDER BY idperson, idmeeting";
  $result = mysql_query($query);
  $signin = array();
  while ($item = mysql_fetch_array($result))
    array_push($signin, $item);

  // head
  $i = 0;
  $res['table'] = array();
  $res['table']['head'] = array();
  array_push($res['table']['head'][$i], "");
  foreach ($meeting as $m) {
    array_push($res['table']['head'][$i], $m['description']);
  }
  //body
  $res['table']['data'] = array();
  $i = 0;
  $j = 0;
  foreach ($person as $p) {
    $res['table']['data'][$i] = array();
    array_push($res['table']['data'][$i], $p['name']);
    foreach ($meeting as $m) {
      if ($signin[$j]['idperson'] != $p['id']) {
	array_push($res['table']['data'][$i], "");
	continue;
      }
      if ($signin[$j]['idmeeting'] != $m['id']) {
	array_push($res['table']['data'][$i], "");
	continue;
      }
      array_push($res['table']['data'][$i], $signin[$j]['arrived']);
      $j++;
    }
    $i++;
  }
  return $res;
}

function getsigninme($conn, $filter) {
  $res['status'] = 0;

  $pid = $filter['pid'];

  // meeting
  $query = "SELECT * FROM meeting ORDER BY id";
  $result = mysql_query($query);
  $meeting = array();
  while ($item = mysql_fetch_array($result))
    array_push($meeting, $item);

  // signin
  $query = "SELECT * FROM signINRecord WHERE idperson=".$pid." ORDER BY idmeeting";
  $result = mysql_query($query);
  $signin = array();
  while ($item = mysql_fetch_array($result))
    array_push($signin, $item);

  $i = 0;
  $j = 0;
  $res['table']['head'] = array("会议名称", "开始时间", "结束时间", "是否签到");
  $res['table']['data'] = array();
  foreach($meeting as $m) {
    $res['table']['data'][$i] = array($m['description'],
				      $m['starttime'],
				      $m['endtime']);
    if ($signin[$j]['idmeeting'] != $m['id'])
      array_push($res['table']['data'][$i], '-');
    else {
      if (0 == $signin[$j]['arrived'])
	array_push($res['table']['data'][$i], "未完整签到");
      else
	array_push($res['table']['data'][$i], "已签到");
      $j++;
    }
    $i++;
  }

  return $res;
}
?>