<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/includes/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('includes/session-handler.php');
require_once FileUtils::normalizeFilePath('includes/classes/session-manager.php');
include_once FileUtils::normalizeFilePath('includes/organization-list.php');

SessionManager::checkUserRoleAndRedirect();
session_destroy();

if (isset($_SESSION['error_message'])) {
  $error_message = $_SESSION['error_message'];
  unset($_SESSION['error_message']); // Unset the error message from the session once displayed
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Fontawesome Link for Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styles/dist/landing.css">
  <link rel="stylesheet" href="styles/loader.css" />
  <link rel="icon" href="images/resc/ivote-favicon.png" type="image/x-icon">
  <title>iVote</title>

  <!-- Montserrat Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

  <link rel="icon" href="images/resc/ivote-favicon.png" type="image/x-icon">
</head>

<body id="index-body">

  <?php include_once FileUtils::normalizeFilePath(__DIR__ . '/includes/components/loader.html'); ?>

  <nav class="navbar navbar-expand-lg fixed-top" id="mainNav">
    <div class="container">
      <!-- <a class="navbar-brand" href="#">Your Brand</a> -->
      <img src="images/resc/ivote-icon-2.png" id="ivote-logo-landing-header" alt="ivote-logo">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item fw-medium">
            <a class="nav-link" href="landing-page.php">Home</a>
          </li>
          <li class="nav-item fw-medium">
            <a class="nav-link" href="about-us.php">About Us</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="register.php">Register</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Parallax section -->
  <section class="parallax">
    <div class="container">
    <!-- Displays error message -->
    <?php if (isset($error_message)) : ?>
        <div class="text-danger alert alert-warning alert-dismissible fade show" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle flex-shrink-0 me-2" viewBox="0 0 16 16">
                <path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.15.15 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.2.2 0 0 1-.054.06.1.1 0 0 1-.066.017H1.146a.1.1 0 0 1-.066-.017.2.2 0 0 1-.054-.06.18.18 0 0 1 .002-.183L7.884 2.073a.15.15 0 0 1 .054-.057m1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767z" />
                <path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z" />
            </svg>
            <div class="d-flex align-items-center">
                <span class="pe-1"><?php echo $error_message; ?></span>
                <button type="button" class="btn-close text-danger" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>
      <div class="row">
        <div class="col text-center text-white">
          <img src="images/resc/iVOTE4.png" class="img-fluid ivote-logo" alt="iVote Logo">
          <h5 id="index-PUPSRC" class="text-truncate mt-3">Polytechnic University of the Philippines -
            Santa Rosa Campus</h5>
          <h1 class="" id="index-AES">AUTOMATED ELECTION SYSTEM</h1>
          <a href="#organizations" type="button" class="btn btn-primary fw-bold index-button" id="">Select Organization</a>
        </div>
      </div>
      <div class="index-wave-footer" id="organizations">
        <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
          <path d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z" class="shape-fill"></path>
        </svg>
      </div>
    </div>
  </section>

  <!-- Normal section -->
  <section class="organizations">
    <form action="includes/classes/landing-page-controller.php" method="post">
      <div class="container-fluid">
        <h2 class="landing-organization-title"><span class="hello-text">Hello,</span> Isko’t Iska!</h2>
        <p class="landing-organization-subtitle">- Select your Organization - </p>

        <div class="container-fluid">
          <div class="row justify-content-center text-center">
            <div class="col-md-3 mb-4">
              <button type="submit" name="submit_btn" value="<?php echo $org_acronyms['sco']; ?>" class="landing-page-org-card" id="SCO-landing-logo">
                <img src="images/logos/sco.png" alt="SCO Logo" class="landing-page-logo-size">
                <h5 class="fw-bold pt-2 text-capitalize"><?php echo $org_full_names['sco']; ?></h5>
              </button>
            </div>
          </div>
        </div>

        <div class="container-fluid">
          <div class="row justify-content-center text-center">
            <div class="col-md-3 mb-4" id="index-ACAP">
              <button type="submit" name="submit_btn" value="<?php echo $org_acronyms['acap']; ?>" class="landing-page-org-card" id="ACAP-landing-logo">
                <img src="images/logos/acap.png" alt="ACAP Logo" class="landing-page-logo-size">
                <h5 class="fw-bold pt-2 text-uppercase"><?php echo $org_acronyms['acap']; ?></h5>
              </button>
            </div>

            <div class="col-md-3 mb-4" id="index-AECES">
              <button type="submit" name="submit_btn" value="<?php echo $org_acronyms['aeces']; ?>" class="landing-page-org-card" id="AECES-landing-logo">
                <img src="images/logos/aeces.png" alt="AECES Logo" class="landing-page-logo-size">
                <h5 class="fw-bold pt-2 text-uppercase"><?php echo $org_acronyms['aeces']; ?></h5>
              </button>
            </div>

            <div class="col-md-3 mb-4" id="index-AECES">
              <button type="submit" name="submit_btn" value="<?php echo $org_acronyms['elite']; ?>" class="landing-page-org-card" id="ELITE-landing-logo">
                <img src="images/logos/elite.png" alt="ELITE Logo" class="landing-page-logo-size">
                <h5 class="fw-bold pt-2 text-uppercase"><?php echo $org_acronyms['elite']; ?></h5>
              </button>
            </div>
          </div>
        </div>

        <div class="container-fluid">
          <div class="row justify-content-center text-center">
            <div class="col-md-3 mb-4" id="index-ACAP">
              <button type="submit" name="submit_btn" value="<?php echo $org_acronyms['give']; ?>" class="landing-page-org-card" id="GIVE-landing-logo">
                <img src="images/logos/give.png" alt="GIVE Logo" class="landing-page-logo-size">
                <h5 class="fw-bold pt-2 text-uppercase"><?php echo $org_acronyms['give']; ?></h5>
              </button>
            </div>
            <div class="col-md-3 mb-4" id="index-JEHRA">
              <button type="submit" name="submit_btn" value="<?php echo $org_acronyms['jehra']; ?>" class="landing-page-org-card" id="JEHRA-landing-logo">
                <img src="images/logos/jehra.png" alt="JEHRA Logo" class="landing-page-logo-size">
                <h5 class="fw-bold pt-2 text-uppercase"><?php echo $org_acronyms['jehra']; ?></h5>
              </button>
            </div>

            <div class="col-md-3 mb-4" id="index-JMAP">
              <button type="submit" name="submit_btn" value="<?php echo $org_acronyms['jmap']; ?>" class="landing-page-org-card" id="JMAP-landing-logo">
                <img src="images/logos/jmap.png" alt="JMAP Logo" class="landing-page-logo-size">
                <h5 class="fw-bold pt-2 text-uppercase"><?php echo $org_acronyms['jmap']; ?></h5>
              </button>
            </div>

          </div>
        </div>

        <div class="container-fluid ">
          <div class="row justify-content-center text-center">
            <div class="col-md-3 mb-4" id="index-JPIA">
              <button type="submit" name="submit_btn" value="<?php echo $org_acronyms['jpia']; ?>" class="landing-page-org-card" id="JPIA-landing-logo">
                <img src="images/logos/jpia.png" alt="JPIA Logo" class="landing-page-logo-size">
                <h5 class="fw-bold pt-2 text-uppercase"><?php echo $org_acronyms['jpia']; ?></h5>
              </button>
            </div>
            <div class="col-md-3 mb-4" id="index-PIIE">
              <button type="submit" name="submit_btn" value="<?php echo $org_acronyms['piie']; ?>" class="landing-page-org-card" id="PIIE-landing-logo">
                <img src="images/logos/piie.png" alt="PIIE Logo" class="landing-page-logo-size">
                <h5 class="fw-bold pt-2 text-uppercase"><?php echo $org_acronyms['piie']; ?></h5>
              </button>
            </div>
          </div>
        </div>
      </div>
      </div>
    </form>
  </section>

  <footer class="footer">
    <div class="custom-shape-divider-top-1713266907">
      <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
        <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25" class="shape-fill"></path>
        <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5" class="shape-fill"></path>
        <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" class="shape-fill"></path>
      </svg>
    </div>
    <div class="container-fluid footer-body">
      <div class="row">
        <div class="col-md-6 footer-left pt-xl-4 px-xl-5 d-flex justify-content-center flex-column d-flex">
          <div>
            <img src="images/resc/iVOTE4.png" class="img-fluid ivote-logo" id="footer" alt="iVote Logo">
            <p>iVOTE is an Automated Election System (AES) for the student<br>organizations of the PUP Santa Rosa Campus.</p>
            <p class="credits-footer" id="credits"><span class="hello-text">© 2024 BSIT 3-1.</span> All Rights Reserved</p>
            <div class="vertical-line"></div>
          </div>
        </div>

        <div class="col-md-3 footer-middle">
          <div class="row">
            <p class="credits-footer">Visit</p>
            <div class="col-md-3">
              <a href="https://www.facebook.com/thepupsrcstudentcouncil">SCO</a>
              <a href="https://www.facebook.com/ACAPpage">ACAP</a>
              <a href="https://www.facebook.com/OfficialAECES">AECES</a>
            </div>
            <div class="col-md-3">
              <a href="https://www.facebook.com/ELITE.PUPSRC">ELITE</a>
              <a href="https://www.facebook.com/educgive">GIVE<br></a>
              <a href="https://www.facebook.com/PUPSRCJEHRA">JEHRA</a>
            </div>
            <div class="col-md-3">
              <a href="https://www.facebook.com/JMAPPUPSRCOfficial">JMAP</a>
              <a href="https://www.facebook.com/JPIA.PUPSRC">JPIA <br></a>
              <a href="https://www.facebook.com/piiepup">PIIE</a>

            </div>
          </div>
        </div>
        <div class="col-md-3 footer-right">
          <div>
            <p class="credits-footer">Contact Us</p>
            <p>Email us at <a href="mailto:ivote-pupsrc@gmail.com" class="ivote-email">ivote-pupsrc@gmail.com</a></p>

            <p><a href="#" class="footer-about-us"> FAQs | </a><a href="#" class="footer-about-us">Our Story</a></p></a></p>
            <a href="https://twitter.com/iVOTEpupsrc"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAACXBIWXMAAAsTAAALEwEAmpwYAAADw0lEQVR4nO2ZXYgURxDHW+NXjBqN8cFPJCQvCUIwoijIIQgBFX1Ilng33XtJ1HsIEUEFUYRmqsZvQyKC5lDwKS8GjaAoKvEDQhDZ3LlVc55KiDFRk5D4rZh46kqvu+uonNO3N7M7hP3BwjLb2zX/nqrq6hohatSoUeP/QS7XQwEdUMi5F36AHiqgGVGbl8jzijYk8PGFm871LXuytNc6UgFfDRWDdGme9l+LSoRy+X2F3FEQ0d6whoZ0e1IJnA4XwjmJ/E0UItLQ9o4Cvl5YoL+l9t8UUSGRvrURo5A/6o4dxzs9XCJdeLwwdFe5NFlESb0+87pE+sPiqVxzdHZUOTZS2h8gkVqKceegXy/iwAF/tpWLAR00iaJLInbufEkC7wksyFIRJwp5h52L+Qu6OO/mQOLYJuLG0ecGKaBfw1My37YNUoW0OJDKD9Tpo71EJXDc7FSJ9CA8XugH4zIvFAE8SwHfL4z3U2szr1ZEROkGkL6ycjGgJZ3N0Qj0nnlyxX3oY+TRFRWRvwl9vp9ZQYvA/7cBsuOe/3/rWAX8Z0HErUbPf1dUC8fLjpfA9yxcrCWl/T5PxRkyFWLpvsmGotpIYNdy1wczvqk501sCHy5dd+lzkQTq9NFeCumkhZgO6bZNksBfB8RtFEnCWeW/nS8nwuPl5pPvvFvrXE+RNBTQEstazDyJjNxw6hWRRLTO9ZTIx2yEpMH/UCSZRpNSkW6EuxhfjOR8EScO8HxLF9shko5E2msVKx5/IJLMXN0+QiH9ZyHmr09WtQwTSUUhr7XOYMB7RBJR6C8IpNk7lmLSIkmkXZ5WdCkJ9LM5j0ig30LFmEaD9seI5OzufK0g4p96aHvLXFdI0/N9r/By//uuHo8j59nOh0SeEvxdAW2xixlaKKpF6osfX1ZIJwqr+lAiOc+OadKZ/hL4bHjpQndNT6sqZYlC/i5QFC7rbKzpTxWPtGF1WFNzpndFhSjgTQEf3x42XgKvsSwqV1ayFFkUcIkjwRNgZ5gxCumUhZgO5dGE+EV4NLPkJkBtjbp1sO1/TQzYnF1Uft7z/WIUkR1f6nwAXS4n/0ug5XYuRutjEZF/xYD8e3HXTnv+xLLPLsDHLYQ8UC7VRSrCdD4kcLZkwMvO6c58DZreMG0gi13/l0/XtQ+MRIRJhwr5UNQbl3L5M8visjmqV2BbA3vFlyIqcrkeEni/jRjHo5ndsuUgrwg85n1hvdxyzi4S6YpFFrusVp8eWpYR5VGq1KwG/imuzocpayyz2K447NeoUaOGqAqPAOgfysMaeqR8AAAAAElFTkSuQmCC" class="connect-with-us-footer"></a>
            <a href="https://www.facebook.com/profile.php?id=61558930417110"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAACXBIWXMAAAsTAAALEwEAmpwYAAADaklEQVR4nO2Yv08UURDHF39Q+CPGWFn4I1FLbew1ahTFwiieRm7eoRbE+A/YaJabOQGVRguNiQ2xUNAGFWPUxNgZQwI3s4D2doKCioAFa94ChnC3x+7tu9s14ZtMcsXezPvsvDc7byxrWctKtlK2s04h1yuSdiB+rkg+KZRvgPJH29zvYYX8DFDaMtn8Mf0fKxFy3Rq9IIXSDchTisQNY0A8qUi6gJyj2pcVh1SOU4qEwy7e33hAoZyqGsA5HNylSN6aAyiw12A7OysKATlpUChjFYRwvS2H/ENhvtE8gevWKJKWSgOoQms3d3ZctwZI7sUA4XqGfNcIjC6VsUHQPxiKBKFIzsYOQXPnhjgdoTrxT0OLmFTID3TJ9vy2Dm1KtfdtaGzjjU12//ZGzO8Gko7Sfng8Q7KjnGwYKbGA8iadG9q8VDwgvhDghbwKBZFB57SZTEjvfvvdqiAxg4AoEjeT45NhSm3kLzYgj+gtFPTlBQUB5P5AVczrncwc0NagEGFAlLas1C3pUDeAJkAy13mPX4wm5L2AcseLNW/EH4P750clIS7eGF5fThdbJNB4qrt7pR+EIp6O5B/ld7Pdt6ZENrz7hIFtxQO+MYg7jWSc5Ig/CEm7iSCA8t4vBqB8qfgZhNmbnQmQl759m74tmsl6jz8IymcjICS9xfynbKfWUDZcfW0ulZHR/wVEEX/1BYlcTaqaEZ4yCqJbBt38LbRS05HFz84bEN8MmZHpEltLvod+M1k+bBkQID8OmZERX2feLCo+kP5QsZEH/Z2R9MYBYtvuCiCZMFZ+9UcmDpBGGtxm9PqbzvHx0A6zcln3TwvNdy7lujWLn/V6ryxfKiNunS+IbsQUyq/QTqtcfoFkAm4NrC2ZZiB+knQQRdJlLSXdVSYdJJ3NHwo4kGMnuSDse0UokMrlTyQWBLk+MEjUcVClQCDsOEhLD868qXhSQFDGlO1stcqRbggV8kzsIMgzaeQzVhQBCcYOQtJimZBCuR0XCJB0WCYFyFeCbjMjIOjFMpOJApicNAS5s0QFAeLR4DPeKJ0qyovKgXDPeZItVrU02ykXH3OWB8If9NzZiksZyu8Dkod6TBoWZPY7xZ3ah5UUpWynFsg5CCRXdWEo9kzz/b7VCvmpQr6WycoB/Z/qr3RZy7LC6C+bcabimCLC+AAAAABJRU5ErkJggg==" class="connect-with-us-footer" alt="Connect with us"></a>
            <a href="https://www.instagram.com/ivotepupsrc/"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAACXBIWXMAAAsTAAALEwEAmpwYAAADoElEQVR4nO2aS08UQRCA56BeJD7ueDAqmngxCh6MaKJ4Bk046HbvevIPKHicbPXwiifEg2gA/4MRo8simHgjcadqUeSMJ18RCHoRTM3OLASZ2Xm5DQmVVEII3V1f16NrujGMXdkV/ZIB+6QAvC8VTQigjxJoWSpaS0WBlnlOAVQQirp5rdQBpIXNErCYmtGh4fCdUHQhMcCd4Zm9EvCRBFytO4SqwvDaQ5fNN3tiQdzqw8NC4aQ2APUPUJFtiuOJ+oeSqqlvO83yvtAgTjjpN3ptKxVAD6Mktr6cUDVDbDUHeC6MN7ZjSK1t8kohEIJrt1YDFb3ImHYjqwAaD/rbmzB7whfEPey0gWRMu9Gz5baiI8EhRl0BIFTYMSCKXgeBfEoeHvhNKBqRlt3utDQPSvtZ3famQwKNSqDvW44FGmcABwLwZY215oJCazE2BNCKUGhlzPkDgYloGEZn/8xBCdjDY2JvGOCi7wLxJ6WFjSVR5PGaVPiUd81pLKsNIT6RCtuq61nYzGPjrmukCcKGeLEte0tNAmi6dvjRlFd13Aq1oBcEaMXzRFbZl/xi3y+XMnm71YHJU4tQ+EsbCOeE54koEBthhFk+7q7drwWEjeCkdXIiRDgFzDPJc+TM94eibkZKIDSyntjxIDzN5O2r7vpj9Q8ty26vjHGqUyIQqWjYyTMLr9cfpLfU5I6ZSwrCpTlOr2ekAdJplhtcjywl9wgu8VydZrlhp4P8dDxizh/Y2aGl8IO20BKAHTym0nYkBAF67KwPdKP+HgEadUOrLblHylfcTXmm90BUNBUbBLDoHYhC0Y/6e6RiRE8FpHRUKvySqEUBHIg6PjUQp9GzsNlJ1LzdyoaFHgv4VaryRR6btcrntTaNclMbz7sb6nYSsJhVdGz9kxY/x1nbSBOkCpOnFm8e7p247eCyWjlncEkCznJ18hLb80RcCBkEkuRTl0NDAPVx0ho1pNLp4oAA/P1fPnVFCpcPbis+xg1grgdPcQfAyj/z77jERq1OMvrlg97rIBnNI6/8QRR16zZQhvY83vUF4QsB7QaqaL2er/D7oHYjVU31v2X0JGeVzwiFf7aBsWtbKdsW6lnB9cqQboOlnwINGmGFHx63Y4gJoOlIT28s/PC4zR59JsIctr6e4Tc7nU9xgvMVaDD28/RGycLsaaHwuYZQKmQs+6yRtjjnDFAXlz/nXzhSuXjwFJe4yeQTWyi6F/i0tiu7YtRN/gIfvSaMXEnrtwAAAABJRU5ErkJggg==" class="connect-with-us-footer"></a>
          </div>
        </div>
        <p class="credits-footer" id="credits-two"><span class="hello-text">© 2024 BSIT 3-1.</span> All Rights Reserved</p>
      </div>
    </div>
  </footer>

  <!-- Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="scripts/loader.js"></script>

  <!-- JavaScript for dynamic text change PUPSRC -->
  <script>
    const indexPUPSRC = document.getElementById('index-PUPSRC');

    function updateText() {
      if (window.innerWidth <= 768) {
        indexPUPSRC.textContent = 'PUP SANTA ROSA CAMPUS';
      } else {
        indexPUPSRC.textContent = 'Polytechnic University of the Philippines - Santa Rosa Campus';
      }
    }

    window.addEventListener('load', updateText);
    window.addEventListener('resize', updateText);
  </script>
</body>

</html>