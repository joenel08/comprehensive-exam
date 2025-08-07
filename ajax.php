<?php
ob_start();
date_default_timezone_set("Asia/Manila");

$action = $_GET['action'];
include 'admin_class.php';
$crud = new Action();
if($action == 'login'){
	$login = $crud->login();
	if($login)
		echo $login;
}
if($action == 'login2'){
	$login = $crud->login2();
	if($login)
		echo $login;
}
if($action == 'logout'){
	$logout = $crud->logout();
	if($logout)
		echo $logout;
}
if($action == 'logout2'){
	$logout = $crud->logout2();
	if($logout)
		echo $logout;
}

if($action == 'signup'){
	$save = $crud->signup();
	if($save)
		echo $save;
}
if($action == 'save_user'){
	$save = $crud->save_user();
	if($save)
		echo $save;
}
if($action == 'update_user'){
	$save = $crud->update_user();
	if($save)
		echo $save;
}
if($action == 'delete_user'){
	$save = $crud->delete_user();
	if($save)
		echo $save;
}

if($action == 'make_default'){
	$save = $crud->make_default();
	if($save)
		echo $save;
}

if($action == 'save_academic'){
	$save = $crud->save_academic();
	if($save)
		echo $save;
}
if($action == 'delete_academic'){
	$save = $crud->delete_academic();
	if($save)
		echo $save;
}


if($action == 'save_program'){
	$save = $crud->save_program();
	if($save)
		echo $save;
}
if($action == 'delete_program'){
	$save = $crud->delete_program();
	if($save)
		echo $save;
}
if($action == 'get_programs'){
	$save = $crud->get_programs();
	if($save)
		echo $save;
}



if($action == 'save_student'){
	$save = $crud->save_student();
	if($save)
		echo $save;
}
if($action == 'delete_student'){
	$save = $crud->delete_student();
	if($save)
		echo $save;
}


if($action == 'accept_student'){
	$save = $crud->accept_student();
	if($save)
		echo $save;
}

if($action == 'decline_student'){
	$save = $crud->decline_student();
	if($save)
		echo $save;
}



if($action == 'get_student_documents'){
	$save = $crud->get_student_documents();
	if($save)
		echo json_encode($save);
}

if($action == 'save_documents_status'){
	$save = $crud->save_documents_status();
	if($save)
		echo $save;
}

if($action == 'request_check'){
	$save = $crud->request_check();
	if($save)
		echo $save;
}
if($action == 'update_payment_status'){
	$save = $crud->update_payment_status();
	if($save)
		echo $save;
}

if($action == 'save_exam'){
	$save = $crud->save_exam();
	if($save)
		echo $save;
}



if($action == 'delete_exam'){
	$save = $crud->delete_exam();
	if($save)
		echo $save;
}

if($action == 'update_dean_verdict'){
	$save = $crud->update_dean_verdict();
	if($save)
		echo $save;
}


if($action == 'save_subject'){
	$save = $crud->save_subject();
	if($save)
		echo $save;
}


if($action == 'save_grade_status'){
	$save = $crud->save_grade_status();
	if($save)
		echo $save;
}
if($action == 'save_application'){
	$save = $crud->save_application();
	if($save)
		echo $save;
}
if($action == 'update_subject_status'){
	$save = $crud->update_subject_status();
	if($save)
		echo $save;
}

if($action == 'save_subject_grades'){
	$save = $crud->save_subject_grades();
	if($save)
		echo $save;
}

if($action == 'save_user_dean'){
	$save = $crud->save_user_dean();
	if($save)
		echo $save;
}

if($action == 'save_user_bao'){
	$save = $crud->save_user_bao();
	if($save)
		echo $save;
}

ob_end_flush();
?>
