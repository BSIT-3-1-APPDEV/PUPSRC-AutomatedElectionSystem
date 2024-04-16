<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="icon" type="image/x-icon" href="images/resc/ivote-favicon.png">
	<title>Admin Creation</title>

	<!-- Icons -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
	<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

	<!-- UPON USE, REMOVE/CHANGE THE '../../' -->
    <link rel="stylesheet" href="styles/styles.css" />
	<link rel="stylesheet" href="styles/admin-creation.css" />
	<link rel="stylesheet" href="../vendor/node_modules/bootstrap/dist/css/bootstrap.min.css" />

</head>

<body>

	<!---------- SIDEBAR + HEADER START ------------>
	<?php include_once __DIR__ . '/includes/components/sidebar.php'; ?>
	<!---------- SIDEBAR + HEADER END ------------>





<div class="main">
  <h1>Create an Admin Account</h1>
        <div class="row justify-content-center">
            <div class="col-md-10 card-box">
                    <div class="card-box">
                        <div class="row">
                            <div class="col-12">
                                <h2 class="form-title">Personal Information</h2>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-12 col-sm-4 mx-auto">
                                <div class="form-group local-forms">
                                    <label for="lname" class="login-danger">Last Name </label>
                                    <input type="text" id="lname" name="lname" placeholder="Enter Last Name">
                                </div>
                            </div>
                            <div class="col-12 col-sm-4 mx-auto">
                                <div class="form-group local-forms">
                                    <label for="fname" class="login-danger">First Name </label>
                                    <input type="text" id="fname" name="fname" placeholder="Enter First Name">
                                </div>
                            </div>
                            <div class="col-12 col-sm-3 mx-auto">
                                <div class="form-group local-forms">
                                    <label for="mname" class="login-danger">Middle Name </label>
                                    <input type="text" id="mname" name="mname" placeholder="Enter Middle Name">
                                </div>
                            </div>
                            <div class="col-12 col-sm-1 mx-auto">
                                <div class="form-group local-forms">
                                    <label for="suffix" class="login-danger">Suffix </label>
                                    <select id="suffix" name="suffix">
                                        <option value="suffix">Suffix</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <h2 class="form-title">Admin Account Credentials</h2>
                            </div>
                            <div class="col-12 col-sm-12 mx-auto">
                                <div class="form-group local-forms">
                                    <label for="email" class="login-danger">Email </label>
                                    <input type="email" id="email" name="email" placeholder="Email">
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 mx-auto">
                                <div class="form-group local-forms">
                                    <label for="password" class="login-danger">Password </label>
                                    <input type="password" id="password" name="password" placeholder="Password">
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 mx-auto">
                                <div class="form-group local-forms">
                                    <label for="cpassword" class="login-danger">Confirm Password </label>
                                    <input type="password" id="cpassword" name="cpassword" placeholder="Confirm Password">
                                </div>
                            </div>
                            <div class="d-flex justify-content-center">
                                <div class="d-flex flex-column">
                                    <button type="submit" class="button-create">Create</button>
                                    <button style="background: none; border: none; padding-top: 10px; padding-bottom: 20px; font-weight: bold; color: #909090; text-decoration: underline;">
                                    Reset Form
                                    </button>

                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




	<!-- UPON USE, REMOVE/CHANGE THE '../../' -->
	<script src="vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
	<script src="scripts/script.js"></script>
	<script src="scripts/feather.js"></script>
	<script src="scripts/viewport.js"></script>
	<script src="scripts/configuration.js"></script>

</body>

</html>