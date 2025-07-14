<?php
include 'db_connect.php';
$qry = $conn->query("SELECT * FROM exam_list where e_id = ".$_GET['e_id'])->fetch_array();
foreach($qry as $k => $v){
	$$k = $v;
}
include 'exam_list.php';
?>