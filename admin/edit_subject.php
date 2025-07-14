<?php
include 'db_connect.php';
$qry = $conn->query("SELECT * FROM subject_list where sub_id = ".$_GET['sub_id'])->fetch_array();
foreach($qry as $k => $v){
	$$k = $v;
}
include 'subject_list.php';
?>