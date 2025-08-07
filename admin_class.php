<?php
session_start();
ini_set('display_errors', 1);
date_default_timezone_set("Asia/Manila");
require __DIR__ . '/vendor/autoload.php';
class Action
{
	private $db;

	public function __construct()
	{
		ob_start();
		include 'db_connect.php';

		$this->db = $conn;
	}
	function __destruct()
	{
		$this->db->close();
		ob_end_flush();
	}

	function login()
	{
		extract($_POST);

		// List of tables and corresponding user types/view folders
		$tables = [
			'users' => 'admin',
			'student_list' => 'student',
			'bao_users' => 'bao',
			'dean_users' => 'dean'
		];

		foreach ($tables as $table => $folder) {
			// Query current table for email and password match
			$qry = $this->db->query("SELECT *, CONCAT(firstname, ' ', lastname) as name 
                                 FROM $table 
                                 WHERE email = '$email' AND password = '" . md5($password) . "'");

			if ($qry && $qry->num_rows > 0) {
				$row = $qry->fetch_assoc();

				// Check verification if the field exists
				if (!isset($row['isVerified']) || $row['isVerified'] == 1) {
					foreach ($row as $key => $value) {
						if ($key != 'password' && !is_numeric($key)) {
							$_SESSION['login_' . $key] = $value;
						}
					}

					$_SESSION['login_view_folder'] = $folder . '/';
					$_SESSION['login_table'] = $table;

					// Load academic info if table is 'users'
					// if ($table == 'users' || ) {
					$academic = $this->db->query("SELECT * FROM academic_list WHERE is_default = 1");
					if ($academic && $academic->num_rows > 0) {
						foreach ($academic->fetch_assoc() as $k => $v) {
							if (!is_numeric($k)) {
								$_SESSION['academic'][$k] = $v;
							}
						}
					}
					// }

					return 1; // Successful login
				} else {
					return 3; // Account found but not verified
				}
			}
		}

		return 2; // No match found in any table
	}

	function logout()
	{
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:signin.php");
	}

	function save_user()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'cpass', 'password')) && !is_numeric($k)) {
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		if (!empty($password)) {
			$data .= ", password=md5('$password') ";

		}
		$check = $this->db->query("SELECT * FROM users where email ='$email' " . (!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if ($check > 0) {
			return 2;
			exit;
		}
		if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
			$data .= ", avatar = '$fname' ";

		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO users set $data");
		} else {
			$save = $this->db->query("UPDATE users set $data where id = $id");
		}

		if ($save) {
			return 1;
		}
	}

	function save_user_dean()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'cpass', 'password')) && !is_numeric($k)) {
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		if (!empty($password)) {
			$data .= ", password=md5('$password') ";

		}
		$check = $this->db->query("SELECT * FROM dean_users where email ='$email' " . (!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if ($check > 0) {
			return 2;
			exit;
		}
		if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
			$data .= ", avatar = '$fname' ";

		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO dean_users set $data");
		} else {
			$save = $this->db->query("UPDATE dean_users set $data where id = $id");
		}

		if ($save) {
			return 1;
		}
	}

		function save_user_bao()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'cpass', 'password')) && !is_numeric($k)) {
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		if (!empty($password)) {
			$data .= ", password=md5('$password') ";

		}
		$check = $this->db->query("SELECT * FROM bao_users where email ='$email' " . (!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if ($check > 0) {
			return 2;
			exit;
		}
		if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
			$data .= ", avatar = '$fname' ";

		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO bao_users set $data");
		} else {
			$save = $this->db->query("UPDATE bao_users set $data where id = $id");
		}

		if ($save) {
			return 1;
		}
	}
	function signup()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'cpass')) && !is_numeric($k)) {
				if ($k == 'password') {
					if (empty($v))
						continue;
					$v = md5($v);

				}
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}

		$check = $this->db->query("SELECT * FROM users where email ='$email' " . (!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if ($check > 0) {
			return 2;
			exit;
		}
		if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
			$data .= ", avatar = '$fname' ";

		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO users set $data");

		} else {
			$save = $this->db->query("UPDATE users set $data where id = $id");
		}

		if ($save) {
			if (empty($id))
				$id = $this->db->insert_id;
			foreach ($_POST as $key => $value) {
				if (!in_array($key, array('id', 'cpass', 'password')) && !is_numeric($key))
					$_SESSION['login_' . $key] = $value;
			}
			$_SESSION['login_id'] = $id;
			if (isset($_FILES['img']) && !empty($_FILES['img']['tmp_name']))
				$_SESSION['login_avatar'] = $fname;
			return 1;
		}
	}

	function update_user()
	{
		extract($_POST);
		$data = "";
		$type = array("", "users", "faculty_list", "student_list");
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'cpass', 'table', 'password')) && !is_numeric($k)) {

				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		$check = $this->db->query("SELECT * FROM {$type[$_SESSION['login_type']]} where email ='$email' " . (!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if ($check > 0) {
			return 2;
			exit;
		}
		if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
			$data .= ", avatar = '$fname' ";

		}
		if (!empty($password))
			$data .= " ,password=md5('$password') ";
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO {$type[$_SESSION['login_type']]} set $data");
		} else {
			echo "UPDATE {$type[$_SESSION['login_type']]} set $data where id = $id";
			$save = $this->db->query("UPDATE {$type[$_SESSION['login_type']]} set $data where id = $id");
		}

		if ($save) {
			foreach ($_POST as $key => $value) {
				if ($key != 'password' && !is_numeric($key))
					$_SESSION['login_' . $key] = $value;
			}
			if (isset($_FILES['img']) && !empty($_FILES['img']['tmp_name']))
				$_SESSION['login_avatar'] = $fname;
			return 1;
		}
	}
	function delete_user()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = " . $id);
		if ($delete)
			return 1;
	}
	function save_system_settings()
	{
		extract($_POST);
		$data = '';
		foreach ($_POST as $k => $v) {
			if (!is_numeric($k)) {
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		if ($_FILES['cover']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['cover']['name'];
			$move = move_uploaded_file($_FILES['cover']['tmp_name'], '../assets/uploads/' . $fname);
			$data .= ", cover_img = '$fname' ";

		}
		$chk = $this->db->query("SELECT * FROM system_settings");
		if ($chk->num_rows > 0) {
			$save = $this->db->query("UPDATE system_settings set $data where id =" . $chk->fetch_array()['id']);
		} else {
			$save = $this->db->query("INSERT INTO system_settings set $data");
		}
		if ($save) {
			foreach ($_POST as $k => $v) {
				if (!is_numeric($k)) {
					$_SESSION['system'][$k] = $v;
				}
			}
			if ($_FILES['cover']['tmp_name'] != '') {
				$_SESSION['system']['cover_img'] = $fname;
			}
			return 1;
		}
	}
	function save_image()
	{
		extract($_FILES['file']);
		if (!empty($tmp_name)) {
			$fname = strtotime(date("Y-m-d H:i")) . "_" . (str_replace(" ", "-", $name));
			$move = move_uploaded_file($tmp_name, 'assets/uploads/' . $fname);
			$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https' ? 'https' : 'http';
			$hostName = $_SERVER['HTTP_HOST'];
			$path = explode('/', $_SERVER['PHP_SELF']);
			$currentPath = '/' . $path[1];
			if ($move) {
				return $protocol . '://' . $hostName . $currentPath . '/assets/uploads/' . $fname;
			}
		}
	}


	function save_academic()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'user_ids')) && !is_numeric($k)) {
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		$chk = $this->db->query("SELECT * FROM academic_list where (" . str_replace(",", 'and', $data) . ") and id != '{$id}' ")->num_rows;
		if ($chk > 0) {
			return 2;
		}
		$hasDefault = $this->db->query("SELECT * FROM academic_list where is_default = 1")->num_rows;
		if ($hasDefault == 0) {
			$data .= " , is_default = 1 ";
		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO academic_list set $data");
		} else {
			$save = $this->db->query("UPDATE academic_list set $data where id = $id");
		}
		if ($save) {
			return 1;
		}
	}
	function delete_academic()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM academic_list where id = $id");
		if ($delete) {
			return 1;
		}
	}
	function make_default()
	{
		extract($_POST);
		$update = $this->db->query("UPDATE academic_list set is_default = 0");
		$update1 = $this->db->query("UPDATE academic_list set is_default = 1 where id = $id");
		$qry = $this->db->query("SELECT * FROM academic_list where id = $id")->fetch_array();
		if ($update && $update1) {
			foreach ($qry as $k => $v) {
				if (!is_numeric($k))
					$_SESSION['academic'][$k] = $v;
			}

			return 1;
		}
	}

	function save_program()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {

			if (empty($data)) {
				$data .= " $k='$v' ";
			} else {
				$data .= ", $k='$v' ";
			}

		}

		if (empty($program_id)) {
			$save = $this->db->query("INSERT INTO program set $data");
		} else {
			$save = $this->db->query("UPDATE program set $data where program_id = $program_id");
		}
		if ($save) {
			return 1;
		}
	}

	function delete_program()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM program where program_id = $program_id");
		if ($delete) {
			return 1;
		}
	}

	function get_programs()
	{
		extract($_POST);

		$level = $_POST['level'];

		$stmt = $this->db->prepare("SELECT program_id, program_abbrv, programFullDesc FROM program WHERE level = ?");
		$stmt->bind_param("s", $level);
		$stmt->execute();
		$result = $stmt->get_result();


		while ($row = $result->fetch_assoc()) {
			echo '<option value="' . $row['program_id'] . '">' . $row['program_abbrv'] . ' - ' . $row['programFullDesc'] . '</option>';
		}
	}
	function save_student()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'cpass', 'password')) && !is_numeric($k)) {
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		if (!empty($password)) {
			$data .= ", password=md5('$password') ";

		}
		$check = $this->db->query("SELECT * FROM student_list where email ='$email' " . (!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if ($check > 0) {
			return 2;
			exit;
		}
		if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
			$data .= ", avatar = '$fname' ";

		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO student_list set $data");
		} else {
			$save = $this->db->query("UPDATE student_list set $data where id = $id");
			// Fetch updated user data and update session
			$user = $this->db->query("SELECT * FROM student_list WHERE id = $id")->fetch_array();
			foreach ($user as $key => $value) {
				if ($key != 'password' && !is_numeric($key)) {
					$_SESSION['login_' . $key] = $value;
				}
			}
		}

		if ($save) {
			return 1;
		}
	}
	function delete_student()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM student_list where id = " . $id);
		if ($delete)
			return 1;
	}


	function accept_student()
	{
		extract($_POST);
		$delete = $this->db->query("UPDATE student_list SET isVerified = 1 where id = " . $id);
		if ($delete)
			return 1;
	}

	function decline_student()
	{
		extract($_POST);
		$delete = $this->db->query("UPDATE student_list SET isVerified = 2 where id = " . $id);
		if ($delete)
			return 1;
	}

	function save_documents_status()
	{
		$student_id = isset($_POST['student_id']) ? intval($_POST['student_id']) : 0;
		$tor = isset($_POST['tor']) ? 1 : 0;
		$birth_cert = isset($_POST['birth_cert']) ? 1 : 0;
		$grades = isset($_POST['grades']) ? 1 : 0;
		$marriage_cert = isset($_POST['marriage_cert']) ? 1 : 0;
		$verdict = isset($_POST['approved']) ? $_POST['approved'] : 'Pending';
		$notes = isset($_POST['notes']) ? addslashes($_POST['notes']) : '';

		if ($student_id <= 0) {
			return json_encode(['status' => 'error', 'message' => 'Invalid student ID.']);
		}

		$query = "INSERT INTO student_documents 
                (student_id, tor, birth_cert, grades, marriage_cert, verdict, notes)
              VALUES 
                ($student_id, $tor, $birth_cert, $grades, $marriage_cert, '$verdict', '$notes')
              ON DUPLICATE KEY UPDATE 
                tor = VALUES(tor),
                birth_cert = VALUES(birth_cert),
                grades = VALUES(grades),
                marriage_cert = VALUES(marriage_cert),
                verdict = VALUES(verdict),
                notes = VALUES(notes)";

		$save = $this->db->query($query);
		if ($save) {
			return json_encode(['status' => 'success', 'message' => 'Document status saved.']);
		} else {
			return json_encode([
				'status' => 'error',
				'message' => 'Failed to save document status.',
				'error' => $this->db->error
			]);
		}
	}

	function get_student_documents()
	{
		$student_id = isset($_POST['student_id']) ? intval($_POST['student_id']) : 0;

		if ($student_id <= 0) {
			return ['success' => false, 'message' => 'Invalid student ID'];
		}

		$qry = $this->db->query("SELECT * FROM student_documents WHERE student_id = $student_id");
		if ($qry && $qry->num_rows > 0) {
			$row = $qry->fetch_assoc();
			return ['success' => true, 'data' => $row];
		} else {
			return ['success' => true, 'data' => null]; // No data yet, just return empty
		}
	}

	function request_check()
	{
		extract($_POST);

		// Construct query
		$query = "INSERT INTO paymentstatus (student_id, payment_status) VALUES ('$id', 'pending')";
		$save = $this->db->query($query);

		if ($save) {
			return json_encode(['status' => 'success', 'message' => 'Request successfully sent!.']);
		} else {
			return json_encode([
				'status' => 'error',
				'message' => 'Failed to sent a request.',
				'error' => $this->db->error
			]);
		}
	}

	function save_signature()
	{
		$faculty_id = $_POST['faculty_id'];
		$signatureData = $_POST['signature']; // Base64-encoded image

		// Remove the base64 image prefix (if present)
		$signatureData = str_replace('data:image/png;base64,', '', $signatureData);
		$signatureData = base64_decode($signatureData); // Decode the base64 data to binary

		// Generate a unique file name for the signature
		$fileName = $faculty_id . '_signature_' . time() . '.png';
		$filePath = 'assets/uploads/' . $fileName; // Path where image will be stored

		// Save the signature image to the server
		if (file_put_contents($filePath, $signatureData)) {
			// Update the database with the signature file path
			$stmt = $this->db->prepare("UPDATE `faculty_list` SET `signature` = ? WHERE `id` = ?");
			$stmt->bind_param('si', $fileName, $faculty_id); // 'si' means string and integer
			$stmt->execute();

			// Check if the update was successful
			if ($stmt->affected_rows > 0) {
				echo json_encode(['success' => true]);
			} else {
				echo json_encode(['success' => false]);
			}
		} else {
			echo json_encode(['success' => false]);
		}
	}


	function update_payment_status()
	{
		$student_id = $_POST['student_id'];
		$status = $_POST['payment_status'];
		$notes = $_POST['payment_notes'];

		// Check if record exists
		$check = $this->db->query("SELECT * FROM paymentstatus WHERE student_id = '$student_id'");
		if ($check->num_rows > 0) {
			// Update
			$this->db->query("UPDATE paymentstatus SET payment_status = '$status', payment_notes = '$notes', date_updated = NOW() WHERE student_id = '$student_id'");
		} else {
			// Insert
			$this->db->query("INSERT INTO paymentstatus (student_id, payment_status, payment_notes, date_updated) VALUES ('$student_id', '$status', '$notes', NOW())");
		}

		echo json_encode(['status' => 'success', 'message' => 'Payment status updated successfully']);
		exit;
	}

	function save_exam()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (empty($data)) {
				$data .= " $k='$v' ";
			} else {
				$data .= ", $k='$v' ";
			}

		}

		$data .= ", academic_id = '" . $_SESSION['academic']['id'] . "'";

		if (empty($e_id)) {
			$save = $this->db->query("INSERT INTO exam_list set $data");
		} else {
			$save = $this->db->query("UPDATE exam_list set $data where e_id = $e_id");
		}

		if ($save) {
			return 1;
		}
	}

	function delete_exam()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM exam_list where e_id = " . $exam_id);
		if ($delete)
			return 1;
	}

	function update_dean_verdict()
	{
		$student_id = $_POST['student_id'];
		$academic_id = $_SESSION['academic']['id'];

		$verdict = $this->db->real_escape_string($_POST['verdict']);
		$approval_notes = $this->db->real_escape_string($_POST['approval_notes']);
		$date = date('Y-m-d H:i:s');

		$check = $this->db->query("SELECT * FROM dean_approval WHERE student_id = '$student_id'");

		if ($check->num_rows > 0) {
			// Update existing
			$this->db->query("UPDATE dean_approval SET 
            verdict = '$verdict',
            approval_notes = '$approval_notes',
            date_of_approval = '$date'
            WHERE student_id = '$student_id'");
		} else {
			// Insert new
			$this->db->query("INSERT INTO dean_approval (academic_id, student_id, verdict, approval_notes, date_of_approval)
            VALUES ('$academic_id','$student_id', '$verdict', '$approval_notes', '$date')");
		}

		echo json_encode(['status' => 'success', 'message' => 'Verdict Successfully Saved!']);
		exit;
	}


	function save_subject()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (empty($data)) {
				$data .= " $k='$v' ";
			} else {
				$data .= ", $k='$v' ";
			}

		}


		if (empty($sub_id)) {
			$save = $this->db->query("INSERT INTO subject_list set $data");
		} else {
			$save = $this->db->query("UPDATE subject_list set $data where sub_id = $sub_id");
		}

		if ($save) {
			return 1;
		}
	}

	function save_grade_status()
	{
		$academic_id = $_SESSION['academic']['id'];
		$student_id = $_POST['student_id'];
		$grade_status = $_POST['grade_status'];
		$remarks = $_POST['remarks'];

		// Check if this student already has a grade status record
		$stmt_select = $this->db->prepare("SELECT sg_id FROM student_grade_status WHERE student_id = ? and academic_id = ?");
		if (!$stmt_select) {
			echo "Prepare failed: (" . $this->db->errno . ") " . $this->db->error;
			exit;
		}
		$stmt_select->bind_param("ii", $student_id, $academic_id);
		$stmt_select->execute();
		$stmt_select->store_result();

		if ($stmt_select->num_rows > 0) {
			// Record exists, so update it.
			$stmt_update = $this->db->prepare("UPDATE student_grade_status SET grade_status = ?, remarks = ?, date_marked = NOW() WHERE student_id = ?");
			if (!$stmt_update) {
				echo "Prepare failed: (" . $this->db->errno . ") " . $this->db->error;
				exit;
			}
			$stmt_update->bind_param("ssi", $grade_status, $remarks, $student_id);
			$stmt_update->execute();

			if ($stmt_update->affected_rows > 0) {
				return 1;
			} else {
				return 2;
			}
			$stmt_update->close();
		} else {
			// No record exists, so insert a new one.
			$stmt_insert = $this->db->prepare("INSERT INTO student_grade_status (academic_id, student_id, grade_status, remarks, date_marked) VALUES (?, ?, ?, ?, NOW())");
			if (!$stmt_insert) {
				echo "Prepare failed: (" . $this->db->errno . ") " . $this->db->error;
				exit;
			}
			$stmt_insert->bind_param("iiss", $academic_id, $student_id, $grade_status, $remarks);
			$stmt_insert->execute();

			if ($stmt_insert->affected_rows > 0) {
				return 1;
			} else {
				return 2;
			}
			$stmt_insert->close();
		}
		$stmt_select->close();

	}

	function save_application()
	{
		$student_id = $_SESSION['login_id'];
		$academic_id = $_SESSION['academic']['id'];
		$level = $_SESSION['login_level'];

		// Define subject count limits based on level
		$limitConfig = [
			'core' => $level === 'doctorate' ? 3 : 2,
			'major' => $level === 'doctorate' ? 6 : 4,
			'cognate' => 1
		];

		$inserted = 0;

		foreach (['core', 'major', 'cognate'] as $type) {
			$subjects = $_POST[$type . '_subjects'] ?? [];

			if (empty($subjects))
				continue;

			// 1. Count already applied subjects of this type
			$check = $this->db->query("
            SELECT COUNT(*) as count 
            FROM application_form af
            INNER JOIN subject_list sl ON sl.sub_id = af.subject_ids
            WHERE af.student_id = $student_id AND sl.subject_type = '$type'
        ");

			if (!$check) {
				echo "SQL Error: " . $this->db->error;
				exit;
			}

			$existingCount = $check->fetch_assoc()['count'] ?? 0;
			$newCount = count($subjects);

			// 2. Enforce type-specific limit
			if (($existingCount + $newCount) > $limitConfig[$type]) {
				echo "You can only apply for {$limitConfig[$type]} $type subjects. You already submitted $existingCount.";
				exit;
			}

			foreach ($subjects as $subject_id) {
				$subject_id = intval($subject_id);

				// 3. Check if this subject_id is already applied
				$dupe = $this->db->query("SELECT 1 FROM application_form 
                WHERE student_id = $student_id AND subject_ids = $subject_id");

				if ($dupe && $dupe->num_rows == 0) {
					// 4. Insert subject
					$this->db->query("INSERT INTO application_form 
                    (academic_id, student_id, level, subject_ids) 
                    VALUES 
                    ('$academic_id', '$student_id', '$level', '$subject_id')");
					$inserted++;
				}
			}
		}

		echo "$inserted subject(s) successfully applied.";
	}



	function update_subject_status()
	{
		$af_ids = $_POST['af_ids'];
		$status = $_POST['status'];

		if (!empty($af_ids)) {
			$ids = implode(",", array_map('intval', $af_ids)); // sanitize
			$this->db->query("UPDATE application_form SET subject_status = '$status' WHERE af_id IN ($ids)");
			echo "Subject(s) successfully updated to '$status'.";
		} else {
			echo "No subjects selected.";
		}
		exit;
	}




	function save_subject_grades()
	{
		$af_ids = $_POST['af_id'];
		$grades = $_POST['grade'];
		$student_id = $_POST['student_id'];
		$academic_id = $_SESSION['academic']['id'];
		$remarks = $_POST['remarks'];

		$total = 0;
		$count = 0;
		$inserted = 0;

		for ($i = 0; $i < count($af_ids); $i++) {
			$af_id = intval($af_ids[$i]);
			$grade = floatval($grades[$i]);

			if ($grade <= 0)
				continue; // skip invalid

			$total += $grade;
			$count++;



			// Check for duplicates
			$check = $this->db->query("SELECT * FROM subject_grades WHERE af_id = '$af_id'");
			if ($check->num_rows == 0) {
				$this->db->query("INSERT INTO subject_grades (academic_id, student_id, af_id, grade) 
                VALUES ('$academic_id','$student_id','$af_id', '$grade')");
				$inserted++;
			}
		}

		// Compute average and final status
		if ($count > 0) {
			$average = $total / $count;
			$final_status = ($average >= 80) ? 'Passed' : 'Failed';


			// Save to student_grade_status
			$exists = $this->db->query("SELECT * FROM student_grade_status WHERE student_id = '$student_id'");
			if ($exists->num_rows == 0) {
				$this->db->query("INSERT INTO student_grade_status (grade_status, student_id, remarks, date_marked)
                VALUES ('$final_status', '$student_id', '$remarks', NOW())");
			} else {
				$this->db->query("UPDATE student_grade_status SET 
                grade_status = '$final_status', 
                remarks = '$remarks', 
                date_marked = NOW()
                WHERE student_id = '$student_id'");
			}
		}

		echo json_encode(['status' => 'success', 'message' => "$inserted grades submitted"]);

	}

}