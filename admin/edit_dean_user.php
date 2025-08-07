<?php
include 'db_connect.php';
$qry = $conn->query("SELECT * FROM dean_users where id = ".$_GET['id'])->fetch_array();
foreach($qry as $k => $v){
	$$k = $v;
}
include 'new_dean_user.php';
?>