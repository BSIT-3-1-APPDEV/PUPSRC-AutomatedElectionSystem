<?php
require_once 'includes/classes/db-connector.php'; 
require_once 'includes/session-handler.php';

$org_name = $_SESSION['organization'] ?? '';

include 'includes/organization-list.php';

$org_full_name = $org_full_names[$org_name];

// Check if the user is already logged in
if(isset($_SESSION['voter_id'])) {
    // Check if the 'role' key exists in the session
    if(isset($_SESSION['role'])) {
        // Redirect based on role
        if($_SESSION['role'] == 'Student Voter') {
            header("Location: ballot-forms.php");
        }
        elseif($_SESSION['role'] == 'Committee Member') {
            header("Location: admindashboard.php");
        }
        exit();
    } else {
        // If 'role' key does not exist, handle the error or redirect to an appropriate page
        echo "Role not found in session.";
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Fontawesome Link for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Online Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Akronim&family=Anton&family=Aoboshi+One&family=Audiowide&family=Black+Han+Sans&family=Braah+One&family=Bungee+Outline&family=Hammersmith+One&family=Krona+One&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="styles/dist/landing.css">
    <link rel="stylesheet" href="styles/orgs/<?php echo $org_name; ?>.css">
    <title>Login</title>
</head>

<body class="login-body" id="<?php echo strtoupper($org_name) ;?>-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col login-left-section">
                <div class="organization-names">
                    <img src="images/logos/<?php echo $org_name; ?>.png" class="img-fluid login-logo" alt="<?php echo strtoupper($org_name) . ' ' ; ?>Logo">
                    <p><?php echo strtoupper($org_full_name); ?></p>
                    <h1 class="login-AES">AUTOMATED ELECTION SYSTEM</h1>

                    <div class="login-wave-footer" id="<?php echo strtoupper($org_name) ;?>-wave">
                        <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                            <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25" class="shape-fill"></path>
                            <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5" class="shape-fill"></path>
                            <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" class="shape-fill"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="col login-right-section">
                <div>
                    <form action="voter-login-inc.php" method="post" class="login-form needs-validation" novalidate>
                        <h1 class="login-account">Login Account</h1>
                        <p>Sign in your account</p>
                    
                        <div class="col-md-12 mt-4 mb-3">
                            <input type="email" class="form-control" id="Email" name="email" placeholder="Email Address" required pattern="[a-zA-Z0-9._%+-]+@gmail\.com$">
                        </div>

                    
                        <div class="col-md-12 mb-2">
                            <div class="input-group">
                                <input type="password" class="form-control" name="password" placeholder="Password" id="Password" required>
                                <button class="btn btn-secondary" type="button" id="password-toggle">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    
                        <a href="#">Forgot Password</a>
                    
                        <div class="d-grid gap-2 mt-5 mb-4">
                            <button class="btn btn-primary" name="sign_in" type="submit">Sign In</button>
                        </div>
                    
                        <p>Don't have an account? <a href="#" id="<?php echo strtolower($org_name) ;?>SignUP">Sign Up</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Example starter JavaScript for disabling form submissions if there are invalid fields
(() => {
  'use strict'

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  const forms = document.querySelectorAll('.needs-validation')

  // Loop over them and prevent submission
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }

      form.classList.add('was-validated')
    }, false)
  })
})()
    </script>

<!-- Updated script for password toggle -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const togglePassword = document.querySelector("#password-toggle");
        const passwordInput = document.querySelector("#Password");
        const eyeIcon = togglePassword.querySelector("i");
    
        togglePassword.addEventListener("click", function () {
            const type =
                passwordInput.getAttribute("type") === "password"
                    ? "text"
                    : "password";
            passwordInput.setAttribute("type", type);
    
            // Toggle eye icon classes
            eyeIcon.classList.toggle("fa-eye-slash");
            eyeIcon.classList.toggle("fa-eye");
        });
    });
    </script>

</body>
</html>
