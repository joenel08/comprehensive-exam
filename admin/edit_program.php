<?php
include 'db_connect.php';
$qry = $conn->query("SELECT * FROM program where program_id = ".$_GET['program_id'])->fetch_array();
foreach($qry as $k => $v){
  $$k = $v;
}
include 'program_list.php';
?>