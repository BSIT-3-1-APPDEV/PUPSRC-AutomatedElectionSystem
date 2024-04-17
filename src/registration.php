<?php
// require('./includes/session-handler.php');
// require('./includes/classes/db-config.php');
// require('./includes/classes/db-connector.php');
?>
<?php
$servername="localhost";
$username="root";
$password="";
$database_name="voters-registration";

$conn = mysqli_connect($servername,$username,$password,$database_name);

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width" />
    <link rel="stylesheet" href="./styles/registration.css" />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap"
    />
  </head>
  <body>
    <div class="registration-w-footer3">
      <header class="rectangle-parent16">
        <div class="header-logo">
          <img class="logo-icon7" alt="" src="./styles/reg-images/i-Vote-PUPSRC-AES4.png" />
        </div>
      </header>
      <section class="sign-up-call-to-action-wrapper">
        <div class="sign-up-call-to-action">
          <form id="formId"class="email-input-parent" method="post" enctype="multipart/form-data">
            <div class="email-input1">
              <div class="password-input-field">
                <div class="get-started-parent2">
                  <h2 class="get-started4">Get Started</h2>
                  <div class="sign-up-to4">Sign up to start voting</div>
                </div>
              </div>
              <div class="sign-in-button">
                <div class="frame-parent20">
                  <select class="elite-parent" name="org">
                    <option selected value="">Select your organization</option>
                    <option value="ELITE">ELITE</option>
                    <option value="GIVE">GIVE</option>
                    <option value="AECES">AECES</option>
                  </select>
                  <input
                    name="email"
                    class="email"
                    placeholder="Email Address"
                    type="text"
                  />
                </div>
              </div>
              <input
                id="password"
                name="password"
                class="password-input-child"
                placeholder="Password"
                type="password"
              />
              <input
                id="retype-password"
                name="retype-password"
                class="retype-password-input-item"
                placeholder="Re-type password"
                type="password"
              />
              <!-- <label for="retype-password"><span id="password-error" style="color: red;"></span></label> -->
            </div>
            <div class="upload-cor">
              <div class="upload-cor-child"></div>
              <!-- <button id="file-btn" class="rectangle-parent17">
                <div class="frame-child32"></div>
                <div class="upload-file4">Upload File</div>
              </button>
              <div class="upload-your-cor-wrapper2">
                <div class="upload-your-cor4">Upload your COR</div>
              </div> -->
              <input type="file" id="file" name="cor" class="upload-your-cor4">
            </div>
            <div class="registration-form-container1">
              <button class="signup-btn" value="Sign Up">
                <img
                  class="signup-btn-child"
                  alt=""
                  src="./styles/reg-images/rectangle-10.svg"
                />

                <div class="sign-up4" id="sign-up">Sign Up</div>
              </button>
              <input type="submit" id="submit" hidden>
              <div class="privacy-policy-terms-condition">
                <div class="contact-us-message">
                  <div class="already-have-an4">Already have an account?</div>
                  <a class="sign-in4" href="./index.php">i-Vote</a>
                </div>
              </div>
            </div>
          </form>
          <div class="voting-wrapper1">
            <img
              class="voting-icon4"
              loading="lazy"
              alt=""
              src="./styles/reg-images/voting@2x.png"
            />
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
  <script>
    const form = document.querySelector('formId'); 
     form.addEventListener('submit', function(event) { 
       event.preventDefault(); 
     }); 
   </script>
  <script>
  const passwordInput = document.getElementById('password');
  const retypePasswordInput = document.getElementById('retype-password');
  const passwordError = document.getElementById('password-error');

  function validatePassword() {
    if (passwordInput.value !== retypePasswordInput.value) {
      passwordError.textContent = "Passwords do not match";
    } else {
      passwordError.textContent = "";
    }
  }

  retypePasswordInput.addEventListener('input', validatePassword);
</script>
<script>
  var password = document.getElementById("password")
  , confirm_password = document.getElementById("retype-password");

function validatePassword(){
  if(password.value != confirm_password.value) {
    confirm_password.setCustomValidity("Passwords Don't Match");
  } else {
    confirm_password.setCustomValidity('');
  }
}
password.onchange = validatePassword;
confirm_password.onkeyup = validatePassword;
</script>
  <script>
  const file = document.getElementById("file");
  const fileBtn = document.getElementById("file-btn");
  const fileTxt = document.getElementById("custom-txt");
fileBtn.addEventListener("click", function() {
  file.click();
});
// file.addEventListener("change", function() {
//   if (file.value) {
//     fileTxt.innerHTML = file.value.match(
//       /[\/\\]([\w\d\s\.\-\(\)]+)$/
//     )[1];
//   } else {
//     fileTxt.innerHTML = "No file chosen, yet.";
//   }
// });
  </script>
  <script type="text/javascript">
  const sign_up_btn = document.getElementById("sign-up");
  const submit = document.getElementById("submit");
sign_up_btn.addEventListener("click", function() {
  submit.click();
});
</script>
<?php
if(($_SERVER["REQUEST_METHOD"] == "POST")){

  //retrieve data
  $organization = $_POST["org"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  //file name
  $pname = $_FILES["cor"]["name"];

  //temporary file name to store file
  $tname = $_FILES["cor"]["tmp_name"];

  //upload directory path
  $uploads_dir = 'files';

  //TO move the uploaded file to specific location
  move_uploaded_file($tname, $uploads_dir.'/'.$pname);

  #sql query to insert into database
  $sql = "INSERT into voters(organization,email,password,cor) VALUES('$organization','$email','$password','$pname')";

  if(mysqli_query($conn,$sql)){

      echo "File Sucessfully uploaded";
      }
      else{
          echo "Error";
      }  
}
?>
</html>