<?php
include "dao.php";

session_start();

// TODO session check

if (isset($_POST['filter']))
  $res = getdata($_POST['name'], $_POST['filter']);
else
  $res = getdata($_POST['name']);

echo json_encode($res);
?>