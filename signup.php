<?php session_start();
include('./db_connect.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Sign-up | Comprehensive Examination </title>
    <?php include('header.php'); ?>
    <?php
    global $message;
    if (isset($_SESSION['login_id'])) {
        header("location:index.php?page=home");
    }
    ?>

    <style>
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

        .register-box {
            width: 500px !important;
        }

        @media (max-width: 576px) {

            .register-box {
                margin-top: .5rem;
                width: 90% !important;
            }
        }
    </style>
</head>

<body class="register-page">
    <div class="register-box">

        <!-- /.login-logo -->
        <div class="card card-outline card-success">
            <div class="card-header text-center">

                <!-- <a href="../../index2.html" class="h1"><b>Citi</b>Care</a> -->
                <img src="assets/img/spup_logo.png" width="30%" alt="">

            </div>
            <div class="card-body">
                <span id="status-alert"></span>
                <div class="text-center">
                    <h3 href="" class="h3 text-center">Application for Comprehensive Examination</h3>
                </div>
                <p class="login-box-msg mt-3">Please fill-up the necessary details:</p>

                <form id="signup-form" action="" method="POST">
                    <div id="alert-message" class="alert alert-success d-none" role="alert"></div>


                    <!-- School ID (Required) -->
                    <div class="row">
                        <div class="col border-right border-secondary">
                            <label class="mt-2">User Information:</label>
                            <div class="input-group mb-3">
                                <input type="text" id="school_id" name="school_id" class="form-control"
                                    placeholder="School ID" required>
                            </div>

                            <!-- Lastname (Required) -->
                            <div class="input-group mb-3">
                                <input type="text" id="lastname" name="lastname" class="form-control"
                                    placeholder="Last Name" required>
                            </div>

                            <!-- Firstname (Required) -->
                            <div class="input-group mb-3">
                                <input type="text" id="firstname" name="firstname" class="form-control"
                                    placeholder="First Name" required>
                            </div>

                            <!-- Middle Name (Optional) -->
                            <div class="input-group mb-3">
                                <input type="text" id="middlename" name="middlename" class="form-control"
                                    placeholder="Middle Name">
                            </div>

                            <!-- Extension Name (Optional) -->
                            <div class="input-group mb-2">
                                <input type="text" id="extname" name="extname" class="form-control"
                                    placeholder="Extension Name (e.g., Jr., Sr.)">
                            </div>
                            <div class="form-group ">
                                <label for="marital_status" class="font-weight-normal text-muted text-sm">&nbsp;&nbsp;Marital Status</label>
                                <select name="marital_status" id="marital_status" class="form-control" required>
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Widowed">Widowed</option>
                                    <option value="Separated">Separated</option>
                                    <option value="Divorced">Divorced</option>
                                </select>

                            </div>
                        </div>

                        <div class="col-sm">
                            <label class="mt-2">System Information:</label>
                            <!-- Level (Masteral/Doctoral) -->
                            <div class="input-group mb-3">
                                <select name="level" id="level" class="form-control" required>
                                    <option value="" disabled selected>Select Level</option>
                                    <option value="masteral">Masteral</option>
                                    <option value="doctorate">Doctoral</option>
                                </select>
                            </div>

                            <!-- Course/Program (Select Options) -->
                            <div class="input-group mb-3">
                                <select name="program" id="program" class="form-control" required>
                                    <option value="" disabled selected>Select Course/Program</option>
                                    <!-- Options will be populated dynamically -->
                                </select>
                            </div>



                            <!-- Mobile Number -->
                            <div class="input-group mb-3">
                                <input type="text" id="mobile_number" name="mobile_number" class="form-control"
                                    placeholder="Mobile Number">
                            </div>

                            <!-- Email Address -->
                            <div class="input-group mb-3">
                                <input type="email" id="email" name="email" class="form-control"
                                    placeholder="Email Address" required>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-envelope"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="input-group mb-3">
                                <input type="password" name="password" id="password" class="form-control"
                                    placeholder="Password" required>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span id="mata" class="fas fa-lock" onclick="myFunction()"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input type="password" id="password1" name="cpass" class="form-control"
                                    placeholder="Confirm Password">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span id="mata1" class="fas fa-lock" onclick="myFunction1()"></span>
                                    </div>
                                </div>
                            </div>
                            <small id="password-match-message" class="form-text m-0"></small>

                        </div>
                    </div>



                    <div class="social-auth-links text-center mt-2 mb-3">
                        <button type="submit"
                            class="btn btn-md btn-block btn-flat mt-3 border-0 btn-success text-white">
                            Register Now
                        </button>
                        <p class="text-muted mt-2">Already have an account? <a href="signin.php"
                                class="text-success">Sign-in Now</a></p>


                    </div>
                </form>


            </div>
            <!-- /.card-body -->
        </div>
    </div>
    <!-- /.login-box -->

    <?php
    include('footer.php');
    ?>
    <script>
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('password1');
        const message = document.getElementById('password-match-message');

        function checkPasswordMatch() {
            if (confirmPassword.value === "") {
                message.textContent = "";
                message.className = "form-text";
                return;
            }

            if (password.value === confirmPassword.value) {
                message.textContent = "✅ Passwords match";
                message.className = "form-text text-success";
            } else {
                message.textContent = "❌ Passwords do not match";
                message.className = "form-text text-danger";
            }
        }

        password.addEventListener('input', checkPasswordMatch);
        confirmPassword.addEventListener('input', checkPasswordMatch);
    </script>

    <script>
        function myFunction() {
            var x = document.getElementById("password");

            if (x.type === "password") {
                x.type = "text";
                $('#mata').removeClass('fas fa-lock');
                $('#mata').addClass('fas fa-unlock-alt');
            } else {
                x.type = "password";
                $('#mata').removeClass('fas fa-unlock-alt');
                $('#mata').addClass('fas fa-lock');
            }
        }

        function myFunction1() {
            var x = document.getElementById("password1");

            if (x.type === "password") {
                x.type = "text";
                $('#mata1').removeClass('fas fa-lock');
                $('#mata1').addClass('fas fa-unlock-alt');
            } else {
                x.type = "password";
                $('#mata1').removeClass('fas fa-unlock-alt');
                $('#mata1').addClass('fas fa-lock');
            }
        }
    </script>

    <script>
        $('#level').on('change', function () {
            var level = $(this).val();

            $.ajax({
                url: 'ajax.php?action=get_programs',
                type: 'POST',
                data: { level: level },
                success: function (data) {
                    $('#program').html(data);
                }
            });
        });
    </script>

    <script>
        $('#signup-form').on('submit', function (e) {
            e.preventDefault();
            start_load()

            if ($('[name="password"]').val() != '' && $('[name="cpass"]').val() != '') {
                if ($('#pass_match').attr('data-status') != 1) {
                    if ($("[name='password']").val() != '') {
                        $('[name="password"],[name="cpass"]').addClass("border-danger")
                        end_load()
                        return false;
                    }
                }
            }
            $.ajax({
                url: 'ajax.php?action=save_student',
                type: 'POST',
                data: $(this).serialize(),
                success: function (response) {

                    $('#alert-message')
                        .removeClass('d-none')
                        .html("✅ <strong>Registration successful!</strong> Please go to the registrar's office to submit the following documents. You may access your account to view the documents needed!");

                    // Reset the form
                    $('#signup-form')[0].reset();
                    end_load()
                },
                error: function () {
                    alert("An error occurred. Please try again.");
                    end_load()
                }
            });
        });
    </script>

</body>

</html>