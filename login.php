<?php
session_start();
include ('./db_connect.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">

	<title>Login | Comprehensive Exam </title>
	<?php include ('header.php'); ?>
	<?php
	global $message;
	if (isset($_SESSION['login_id'])) {
		header("location:index.php?page=home");
	}
	?>
</head>
<style>
	body {
		width: 80%;
		margin: auto;
	}

	.logo {
		width: 30%;
	}


	.card {
		margin: auto;
		min-height: 47vh !important;
		width: 60vh !important;
	}

	.custom-radio {
		display: none;
	}

	.custom-radio-label {
		position: relative;
		padding-left: 30px;
		cursor: pointer;
		font-weight: 400;
		color: #495057;
		margin-bottom: 10px;
		user-select: none;
	}

	.custom-radio-label::before {
		content: '';
		position: absolute;
		top: 50%;
		left: 0;
		transform: translateY(-50%);
		width: 20px;
		height: 20px;
		border: 2px solid #adb5bd;
		background-color: #fff;
		box-sizing: border-box;
		transition: background-color 0.2s, border-color 0.2s;
	}

	.custom-radio:checked+.custom-radio-label::before {
		background-color: #007bff;
		border-color: #007bff;
	}

	.custom-radio-label::after {
		content: '';
		position: absolute;
		top: 50%;
		left: 8px;
		width: 5px;
		height: 8px;
		border: solid #fff;
		border-width: 0 2px 2px 0;
		transform: translateY(-50%) rotate(45deg) scale(0);
		opacity: 0;
		transition: transform 0.2s ease-in-out, opacity 0.2s;
	}

	.custom-radio:checked+.custom-radio-label::after {
		transform: translateY(-50%) rotate(45deg) scale(1);
		opacity: 1;
	}


	/* Large desktop */
	@media (min-width: 1200px) {

		.nav a,
		a strong,
		.navbar-brand span {
			font-size: 25px;
		}

		.form-group,
		h2 strong,
		a,
		button {
			font-size: 20px;
		}




	}

	@media (min-width: 980px) and (max-width: 1199px) {

		.nav a,
		a strong,
		.navbar-brand span {
			font-size: 25px;
		}

		.form-group,
		h2 strong,
		a,
		button {
			font-size: 20px;
		}



	}

	/* Portrait tablet to landscape and desktop */
	@media (min-width: 768px) and (max-width: 979px) {

		.form-group,
		h2 strong,
		a,
		button {
			font-size: 16px;
		}


	}

	/* Landscape phone to portrait tablet */
	@media (max-width: 767px) {

		.form-group,
		h2 strong,
		a,
		button {
			font-size: 16px;
		}


	}

	/* Landscape phones and down */
	@media (max-width: 480px) {
		h1 {
			font-size: 35px;
			margin-top: -50px;
		}

		p {
			font-size: 16px;
		}

	}

	.p {
		font-size: 18px;
	}

	label {
		font-size: 16px;
	}
</style>

<body class="bg-lightblue">
	<div class="row no-gutters ">
		<div class="col-md-7 vh-100 d-flex ">
			<div class="mx-5 offer-txt justify-content-center align-self-center">
				<div class="d-flex align-items-center">
					<i class="far fa-file-archive fa-4x" aria-hidden="true"></i>
					<div class="">
						<h1 class="ml-3 p-0 m-0">
							THEMASYS
						</h1>
						<h3 class="ml-3 p-0 m-0">A Thesis Management System</h3>
					</div>

				</div>
				<hr class="border-white">

				<h4 class="">Lorem ipsum dolor sit, amet consectetur adipisicing elit.</h4>
				<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas voluptate quidem possimus aliquid odit
					quod, earum ratione sapiente perferendis provident excepturi quia laboriosam? Earum itaque
					consectetur voluptas, maiores quis distinctio?</p>


			</div>
		</div>


		<div class="col-md-5 d-flex">
			
			<div class="card">
			
				<div class="card-body">
				<span id="status-alert"></span>
					<form id="login-form" action="" method="POST"
						class="offer-txt justify-content-center align-self-center">
						<h2 class="text1-lightblue"><b>Sign in</b></h2>
						<div class="lignblue"></div>
						<p class="text-secondary font-weight-normal mt-2">Enter your account details to continue</p>
						<hr>

						<div class="d-flex">
							<div class="form-check mr-3 align-items-center">
								<input type="radio" name="login" id="admin" class="form-check-input custom-radio"
									value="1">
								<label for="admin"
									class="text-secondary font-weight-normal mt-2 form-check-label custom-radio-label">
									Admin
								</label>
							</div>
							<div class="form-check mr-3">
								<input type="radio" name="login" id="faculty" class="form-check-input custom-radio"
									value="2">
								<label for="faculty"
									class="text-secondary font-weight-normal mt-2 form-check-label custom-radio-label">
									Faculty
								</label>
							</div>
							<div class="form-check mr-3">
								<input type="radio" name="login" id="student" class="form-check-input custom-radio"
									value="3">
								<label for="student"
									class="text-secondary font-weight-normal mt-2 form-check-label custom-radio-label">
									Student
								</label>
							</div>
						</div>
						<div class="form-group mt-3">
							<input type="text" id="email" name="email" class="shadow-sm form-control"
								placeholder="Enter Email">
							<div class="input-group input-focus mt-3">
								<input type="password" class="border-right-0 form-control shadow-sm"
									placeholder="Enter Password" name="password" id="password">
								<div class="input-group-prepend passs">
									<span class="input-group-text border-left-0 shadow-sm bg-white">
										<i id="mata" class="text-secondary fa fa-eye-slash" onclick="myFunction()"></i>
									</span>
								</div>
							</div>
							<div class="d-flex align-items-center mt-3">


								
									<a href="registration.php" class="h6 text1-lightblue">Student Sign-up</a>
								
								<!-- <div class="ml-auto text-right"> -->

									<a href="" class="h6 text-right ml-auto text1-lightblue">Forgot Password?</a>
								<!-- </div> -->
							</div>
							<button type="submit"
								class="btn btn-md btn-block btn-flat mt-3 border-0 btn-lightblue text-white">Login now</button>
						</div>
					</form>



				</div>
			</div>
		</div>
	</div>


	<?php
	include ('footer.php');
	?>


	<script>
		function myFunction() {
			var x = document.getElementById("password");

			if (x.type === "password") {
				x.type = "text";
				$('#mata').removeClass('fa fa-eye-slash');
				$('#mata').addClass('fa fa-eye');
			} else {
				x.type = "password";
				$('#mata').removeClass('fa fa-eye');
				$('#mata').addClass('fa fa-eye-slash');
			}
		}
	</script>
	<script>
    $('#login-form').submit(function (e) {
        e.preventDefault();
        start_load();

        // Reset previous alerts
        $('#status-alert').html('');
        $('#login-form button[type="button"]').attr('disabled', true).html('Logging in...');
        
        const email = $('#email').val().trim();
        const password = $('#password').val().trim();
        const loginType = $('input[name="login"]:checked').val();

        // Validation checks
        if (!email || !password || !loginType) {
            let alertMessage = '<div class="alert alert-danger">';
            if (!email) alertMessage += '<p>Email is required.</p>';
            if (!password) alertMessage += '<p>Password is required.</p>';
            if (!loginType) alertMessage += '<p>Please select a login type (Admin, Faculty, or Student).</p>';
            alertMessage += '</div>';

            $('#status-alert').html(alertMessage);
            $('#login-form button[type="button"]').removeAttr('disabled').html('Login');
            end_load();
            return; // Stop further execution
        }

        $.ajax({
            url: 'ajax.php?action=login',
            method: 'POST',
            data: $(this).serialize(),
            error: err => {
                console.log(err);
                $('#login-form button[type="button"]').removeAttr('disabled').html('Login');
            },
            success: function (resp) {
                if (resp == 1) {
                    alert_toast('Successfully Logged-in! Redirecting...');
                    location.href = 'index.php?page=home';
                } else if (resp == 2) {
                    alert_toast("Incorrect Details", 'error');
                    $('#login-form button[type="button"]').removeAttr('disabled').html('Login');
                    end_load();
                } else if (resp == 3) {
                    $('#status-alert').append('<div class="alert alert-danger">Account not verified by thesis adviser. Please wait, thank you!</div>');
                    end_load();
                }
            }
        });
    });
</script>


</body>

</html>