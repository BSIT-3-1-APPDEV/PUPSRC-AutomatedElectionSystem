
<?php

 require('./includes/classes/db-connector.php');

 if ($_SERVER["REQUEST_METHOD"] == "POST") {

  //database connection
  $conn = DatabaseConnection::connect();

  //retrieve data
  $organization = $_POST["organization"];
  $email = $_POST['email'];
  $password = $_POST['password'];

  //password encryption using argon2
  $hashed_password = password_hash($password, PASSWORD_ARGON2I);

  //file name
  $pname = $_FILES["cor"]["name"];

  //temporary file name to store file
  $tname = $_FILES["cor"]["tmp_name"];

  //upload directory path
  $uploads_dir = 'file-cor';

  //TO move the uploaded file to specific location
  move_uploaded_file($tname, $uploads_dir.'/'.$pname);

  //sql query to insert into database
  $sql = "INSERT into voters(organization,email,password,cor) VALUES('$organization','$email','$hashed_password','$pname')";

  //checking registration is successful or not
  if(mysqli_query($conn,$sql)){

      echo "Registration successful!";
    }
    else{
      echo "Error: " . $sql . "<br>" . $conn->error;
    }
  
  $conn->close();
}
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width" />

    <!-- stylesheets -->
    <link rel="stylesheet" href="./styles/registration.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap"/>

    <!-- Boostrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- Fontawesome Link for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">

    <title>Registration</title>
  </head>
  <body>
    <div class="registration-w-footer3">
      <header class="rectangle-parent16">
        <div class="header-logo">
          <img class="logo-icon7" alt="" src="./styles/reg-images/ivote-logo.png" />
        </div>
      </header>
      <section class="sign-up-call-to-action-wrapper">
        <div class="sign-up-call-to-action">
          <form id="formId" class="email-input-parent was-validated" method="post" enctype="multipart/form-data">
            <div class="email-input1">
              <div class="password-input-field">
                <div class="get-started-parent2">
                  <h2 class="get-started4">Get Started</h2>
                  <div class="sign-up-to4">Sign up to start voting</div>
                </div>
              </div>
              <div class="sign-in-button">
              <div class="frame-parent20">

              <div class="col-md-12 mb-2">
                   <input 
                   class="form-control"
                    name="email"
                    placeholder="Email Address"
                    type="text"
                    required
                  />
                  <div class="invalid-feedback">you must enter your email or webmail</div>
              </div>
                
        
                  <div class="col-md-12 mb-2">

                   <select class="form-select" name="organization" id="organization" required>
                    <option selected value="">Select your organization</option>
                    <option value="acap">ACAP</option>
                    <option value="aeces">AECES</option>
                    <option value="elite">ELITE</option>
                    <option value="give">GIVE</option>
                    <option value="jehra">JEHRA</option>
                    <option value="jmap">JMAP</option>
                    <option value="jpia">JPIA</option>
                    <option value="piie">PIIE</option>
                  </select>
                  <div class="invalid-feedback">you must pick your organization</div>
                  </div>
                </div>
                </div>
              <div class="col-md-12 mb-2">
    <div class="input-group">
        <input type="password" class="form-control" name="password" placeholder="Password" id="password" required>
        <button class="btn btn-secondary" type="button" id="password-toggle" style="border-top-right-radius: 5px; border-bottom-right-radius: 5px;">
            <i class="fas fa-eye"></i>
        </button>
        <div class="invalid-feedback">you must create your password</div>
    </div>
</div>
<div class="col-md-12 mb-2">
    <div class="input-group">
        <input type="password" class="form-control" name="confirm_password" placeholder="Re-type Password" id="confirmPassword" required>
        <button class="btn btn-secondary" type="button" id="password-toggle" style="border-top-right-radius: 5px; border-bottom-right-radius: 5px;">
            <i class="fas fa-eye"></i>
        </button>
        <div class="invalid-feedback">you must retype your password</div>
    </div>
    <div class="password-mismatch-message invalid-feedback" style="display: none;">Your password does not matched</div>
</div>
            <div class="mb-3">
  <label for="cor" class="form-label">Upload your COR</label>
  <input type="file" class="form-control form-control-lg" aria-label="file example" id="cor" accept="images/*, .pdf" required>
  <div class="invalid-feedback">You must upload your COR</div>
</div>
            </div>
            <div class="registration-form-container1">
              <button class="signup-btn" id="signup-button"><div class="sign-up4" id="sign-up">Sign Up</div></button>
              <input type="submit" id="submit" hidden>
              </form>
                <div class="contact-us-message">
                  <div class="already-have-an4">Already have an account? go to&nbsp;</div>
                  <a class="sign-in4" href="_blank">i-Vote</a>
                </div>
            </div>
          <div class="voting-wrapper1">
            <img
              class="voting-icon4"
              loading="lazy"
              alt=""
              src="./styles/reg-images/voting@2x.png"
            />
          </div>
        </div>
        </div>
      </div>
      </section>
      <section class="wave-parent1">
        <img class="wave-icon3" loading="lazy" alt="" src="./styles/reg-images/wave.svg" />

        <footer class="rectangle-parent18">
          <div class="frame-child33"></div>
          <div class="automated-election-system-desc">
            <div class="logo-parent4">
              <img
                class="logo-icon8"
                loading="lazy"
                alt=""
                src="./styles/reg-images/i-Vote4.png"
              />

              <div class="about-us-label">
                <div class="automated-election-system-container3">
                  <p class="automated-election-system3">
                  iVOTE is an Automated Election System (AES) for the student<br>
                  organizations of the PUP Santa Rosa Campus.</p>
                </div>
              </div>
            <div class="bsit-3-1-all-rights-reserved-frame">
              <div class="bsit-3-1-all-container3">
                <span>© 2024 BSIT 3-1. </span>
                <span class="all-rights-reserved3">All Rights Reserved</span>
              </div>
            </div>
          </div>
          </div>
          <div class="voting-label">
            <div class="frame-parent21">
              <div class="student-i-d-input-wrapper">
                <div class="student-i-d-input">
                  <div class="student-i-d-input-child"></div>
                  <div class="privacy-policy-terms-container3">
                    <p class="privacy-policy3">Privacy Policy</p>
                    <p class="terms-conditions3">Terms & Conditions</p>
                    <p class="blank-line3">&nbsp;</p>
                    <p class="about-us3">About Us | Our Story</p>
                    <p class="feedback3">Feedback</p>
                  </div>
                </div>
              </div>
              <div class="frame-parent22">
                <div class="connect-with-us-frame">
                  <b class="connect-with-us3">Connect with us</b>
                </div>
                <div class="sign-up-btn-parent">
                  <div class="sign-up-btn">
                    <div class="already-hav-account-link">
                      <img
                        class="vector-icon7"
                        alt=""
                        src="./styles/reg-images/vector-2.svg"
                      />
                    </div>
                    <img
                      class="vector-icon8"
                      alt=""
                      src="./styles/reg-images/vector-3.svg"
                    />

                    <div class="connect-with-us-msg">
                      <img
                        class="connect-with-us-msg-child"
                        loading="lazy"
                        alt=""
                        src="./styles/reg-images/group-32.svg"
                      />
                    </div>
                    <div class="not-applicable">
                      <img
                        class="message-area-icon"
                        alt=""
                        src="./styles/reg-images/vector-4.svg"
                      />
                    </div>
                  </div>
                  <div class="infotech-mail">
                    <div class="message-us-at-container3">
                      <span class="message-us-at3">Message us at</span>
                      <b> </b>
                      <span class="infotechgmailcom3">infotech@gmail.com</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </footer>
      </section>
    </div>
  </body>
  <!--password mismatch-->
  <script>
    document.addEventListener("DOMContentLoaded", function () {
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('confirmPassword');
        const mismatchMessage = document.querySelector('.password-mismatch-message');
        const signUpButton = document.getElementById('signup-button');

        function validatePasswords() {
            if (passwordInput.value !== confirmPasswordInput.value) {
                mismatchMessage.style.display = 'block'; // Display mismatch message
                signUpButton.disabled = true; // Disable sign-up button
            } else {
                mismatchMessage.style.display = 'none'; // Hide mismatch message
                signUpButton.disabled = false; // Enable sign-up button
            }
        }

        passwordInput.addEventListener('input', validatePasswords);
        confirmPasswordInput.addEventListener('input', validatePasswords);
    });
</script>
  <!--toggle password-->
  <script>
    document.addEventListener("DOMContentLoaded", function () {
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('confirmPassword');
        const toggleButtons = document.querySelectorAll('#password-toggle');

        function togglePasswordVisibility(inputField, icon) {
            const type = inputField.getAttribute('type') === 'password' ? 'text' : 'password';
            inputField.setAttribute('type', type);
            // Toggle eye icon classes
            icon.classList.toggle("fa-eye-slash");
            icon.classList.toggle("fa-eye");
        }

        toggleButtons.forEach(function(toggleButton) {
            toggleButton.addEventListener('click', function() {
                const targetInputId = this.previousElementSibling.getAttribute('id');
                const targetInput = document.getElementById(targetInputId);
                togglePasswordVisibility(targetInput, this.querySelector("i"));
            });
        });
    });
</script>
  <!--submit button click-->
  <script>
  const sign_up_btn = document.getElementById("sign-up");
  const submit = document.getElementById("submit");
sign_up_btn.addEventListener("click", function() {
  submit.click();
});
</script>
</html>