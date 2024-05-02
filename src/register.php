<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/x-icon" href="images/resc/ivote-favicon.png">

    <link rel="stylesheet" href="styles/core.css" />
    <link rel="stylesheet" href="../vendor/node_modules/bootstrap/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="styles/dist/register.css">
    <title>Login</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg" id="login-navbar">
        <div class="container-fluid d-flex justify-content-center align-items-center">
            <a href="landing-page.php"><img src="images/resc/ivote-icon-2.png" id="ivote-logo-landing-header"
                    alt="ivote-logo"></a>
        </div>
    </nav>


    <div class="container-fluid">
        <div class="row mt-5">
            <div class="col-md-6">
                <form action="#">
                    <div class="row">
                        <div class="col-12 header-register">
                            <p class="fs-2 fw-bold main-red spacing-6">Get Started</p>
                            <p class="fs-7 fw-semibold main-blue spacing-6">Sign up to start voting</p>
                        </div>
                    </div>


                    <!-- Email Address -->
                    <div class="row pt-3">
                        <div class="col-12 d-flex justify-content-end">
                            <div class="form-group col-7">
                                <label class="fs-8 spacing-3">Email Address<span class="asterisk fw-medium">*</span></label>
                                <input type="text" class="form-control pt-2 bg-primary text-black" name="email" id="email" 
                                    placeholder="Email Address">
                            </div>
                        </div>
                    </div>


                    <!-- Select Organization -->
                    <div class="row pt-2">
                        <div class="col-12   d-flex justify-content-end">
                            <div class="col-7  ">
                                <label class="fs-8 spacing-3">Organization<span class="asterisk fw-medium">*</span></label>
                                <select class="form-select form-control bg-primary text-black"
                                    style="color: red background-color: blue;" name="org" id="org">
                                    <option selected hidden>Select Organization</option>
                                    <option value="acap">ACAP</option>
                                    <option value="aeces">AECES</option>
                                    <option value="elite">GIVE</option>
                                    <option value="jehra">JEHRA</option>
                                    <option value="jmap">JMAP</option>
                                    <option value="jpia">JPIA</option>
                                    <option value="piie">PIIE</option>
                                </select>
                            </div>
                        </div>
                    </div>


                    <!--Password -->
                    <div class="row pt-2">
                        <div class="col-12   d-flex justify-content-end">
                            <div class="col-7">
                                <div class="form-group">
                                    <label class="fs-8 spacing-3">Password <span class="asterisk fw-medium">*</span></label>
                                    <input type="password" class="form-control pt-2 bg-primary text-black"
                                        name="password" id="password" placeholder="Password">
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Re-type Password -->
                    <div class="row pt-2">
                        <div class="col-12   d-flex justify-content-end">
                            <div class="col-7">
                                <div class="form-group">
                                    <label class="fs-8 spacing-3">Re-type password <span class="asterisk fw-medium">*</span></label>
                                    <input type="password" class="form-control pt-2 bg-primary text-black"
                                        id="retype-pass" name="retype-pass" placeholder="Re-type password">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- COR -->
                    <div class="row pt-2">
                        <div class="col-12 d-flex justify-content-end">
                            <div class="col-7">
                                <div class="form-group">
                                    <label class="fs-8 spacing-3">Certificate of Registration<span class="asterisk fw-medium"> *</span></label>
                                    <input class="form-control form-control-sm pl-2" style="background-color:#EDEDED"
                                        type="file" name="cor" id="cor">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Select Organization -->
                    <div class="row pt-4">
                        <div class="col-12 d-flex justify-content-end">
                            <div class="col-7">
                                <button class="btn btn-primary px-sm-5 py-sm-1-5 btn-sm fw-bold fs-6 spacing-6 w-100"
                                    type="submit" id="sign-up" name="sign-up" value="approve">Sign Up</button>
                                <p class="pt-2 fs-7 spacing-8 main-blue-200 text-center">Already have an account? Go to
                                    <a href="landing-page.php" class="fw-bold main-blue underline">iVOTE</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-md-6 d-flex align-items-center">
                <div class="register-img-container">
                    <img src="images/resc/voting.png" alt="ivote-register" class="register-img"
                        style="margin-left: 50px">
                </div>
            </div>
        </div>
    </div>

    <?php include_once __DIR__ . '/includes/components/all-footer.php'; ?>
    <script src="../vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>


    <!-- BACK-END SELECTORS (name & id):

    Email Address: email

    Select Organization: org

    Password: password

    Re-type password: retype-pass

    Certificate of Reg: cor

    Submit Button: sign-up    -->

</body>

</html>