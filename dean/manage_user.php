<?php
include('../db_connect.php');
session_start();
if (isset($_GET['id'])) {

	$user = $conn->query("SELECT * FROM bao_users
	where id =" . $_GET['id']);
	foreach ($user->fetch_array() as $k => $v) {
		$meta[$k] = $v;
	}
}
?>
<div class="container-fluid">
	<div id="msg"></div>

	<form action="" id="manage-user">
		<input type="hidden" name="id" value="<?php echo isset($meta['id']) ? $meta['id'] : '' ?>">

		<div class="row">
			

			<div class="col-sm">
				<div class="form-group">
					<label for="name">School ID</label>
					<input type="text" name="school_id" id="school_id" class="form-control"
						value="<?php echo isset($meta['school_id']) ? $meta['school_id'] : '' ?>" required>
				</div>
			</div>

		
		</div>



		<div class="row">
			<div class="col-sm">
				<div class="form-group">
					<label for="name">Ext. Name</label>
					<input type="text" name="extname" id="extname" class="form-control w-100"
						value="<?php echo isset($meta['extname']) ? $meta['extname'] : '' ?>" required>
				</div>
			</div>

			<div class="col-sm">
				<div class="form-group">
					<label for="name">First Name</label>
					<input type="text" name="firstname" id="firstname" class="form-control w-100"
						value="<?php echo isset($meta['firstname']) ? $meta['firstname'] : '' ?>" required>
				</div>
			</div>

			<div class="col-sm">
				<div class="form-group">
					<label for="name">Middle Name <span class="text-sm text-muted text-italic">(optional)</span></label>
					<input type="text" name="middlename" id="middlename" class="form-control w-100"
						value="<?php echo isset($meta['middlename']) ? $meta['middlename'] : '' ?>">
				</div>
			</div>

			<div class="col-sm">
				<div class="form-group">
					<label for="name">Last Name</label>
					<input type="text" name="lastname" id="lastname" class="form-control"
						value="<?php echo isset($meta['lastname']) ? $meta['lastname'] : '' ?>" required>
				</div>
			</div>
		</div>

		<!-- <div class="row">
			<div class="col">
				<div class="form-group">
					<label for="">Phone Number</label>
					<input type="text" name="mobile_number" id="mobile_number" class="form-control"
						value="<?php echo isset($meta['mobile_number']) ? $meta['mobile_number'] : '' ?>" required>
				</div>
			</div>

			<div class="col">
				<div class="form-group">
					<label for="">Marital Status</label>
					<select name="marital_status" id="marital_status" class="form-control" required>
						<option value="Single" <?= (isset($meta['marital_status']) && $meta['marital_status'] == 'Single') ? 'selected' : '' ?>>Single</option>
						<option value="Married" <?= (isset($meta['marital_status']) && $meta['marital_status'] == 'Married') ? 'selected' : '' ?>>Married</option>
						<option value="Widowed" <?= (isset($meta['marital_status']) && $meta['marital_status'] == 'Widowed') ? 'selected' : '' ?>>Widowed</option>
						<option value="Separated" <?= (isset($meta['marital_status']) && $meta['marital_status'] == 'Separated') ? 'selected' : '' ?>>Separated</option>
						<option value="Divorced" <?= (isset($meta['marital_status']) && $meta['marital_status'] == 'Divorced') ? 'selected' : '' ?>>Divorced</option>
					</select>
				</div>
			</div>
		</div> -->


		<div class="form-group">
			<label for="email">Email</label>
			<input type="text" name="email" id="email" class="form-control"
				value="<?php echo isset($meta['email']) ? $meta['email'] : '' ?>" required autocomplete="off">
		</div>
		<div class="form-group">
			<label for="password">Password</label>
			<input type="password" name="password" id="password" class="form-control" value="" autocomplete="off">
			<small><i>Leave this blank if you dont want to change the password.</i></small>
		</div>



	</form>
</div>
<style>
	img#cimg {
		height: 15vh;
		width: 15vh;
		object-fit: cover;
		border-radius: 100% 100%;
	}
</style>
<script>
	function displayImg(input, _this) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				$('#cimg').attr('src', e.target.result);
			}

			reader.readAsDataURL(input.files[0]);
		}
	}
	$('#manage-user').submit(function (e) {
		e.preventDefault();
		start_load()
		$.ajax({
			url: 'ajax.php?action=save_student',
			data: new FormData($(this)[0]),
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			type: 'POST',
			success: function (resp) {
				if (resp == 1) {
					alert_toast("Data successfully saved", 'success')
					setTimeout(function () {
						location.reload()
					}, 1500)
				} else {
					$('#msg').html('<div class="alert alert-danger">Email already exist!</div>')
					end_load()
				}
			}
		})
	})

</script>