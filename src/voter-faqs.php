<?php
include_once str_replace('/', DIRECTORY_SEPARATOR, 'includes/classes/file-utils.php');
require_once FileUtils::normalizeFilePath('includes/session-handler.php');
include_once FileUtils::normalizeFilePath('includes/error-reporting.php');

if (isset($_SESSION['voter_id']) && (isset($_SESSION['role'])) && ($_SESSION['role'] == 'student_voter')) {
    include FileUtils::normalizeFilePath('includes/session-exchange.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Fontawesome Link for Icons -->
  <link rel="stylesheet" href="../vendor/node_modules/bootstrap/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="styles/voter-faqs.css">
  <link rel="stylesheet" href="styles/loader.css">
  <link rel="stylesheet" href="<?php echo '../src/styles/orgs/' . $org_acronym . '.css'; ?>">
  <link rel="icon" href="images/resc/ivote-favicon.png" type="image/x-icon">
  <title>FAQs</title>

  <!-- Montserrat Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <!-- Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

  <!-- Bootstrap JavaScript -->
  <script src="../vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <!-- Custom JavaScript -->
  <script src="scripts/voter-faqs.js" defer></script>
  <script src="scripts/loader.js" defer></script>

</head>

<body>

<?php
include_once FileUtils::normalizeFilePath(__DIR__ . '/includes/components/loader.html'); 
include_once FileUtils::normalizeFilePath(__DIR__ . '/includes/components/topnavbar.php'); 
?>


  <section class="faqs-section">
    <!-- Voter FAQs Question and Answer -->

    <div class="container">
        <div class="row mt-2 mb-5">
            <div class="col-lg-5 faqs">
                <img src="images/resc/faqs.png" alt="FAQs Image" class="faqs-img mt-5">
            </div>
            <div class="col-lg-7 faqs-body">
                <div class="faqs-title"><span class="hello-text">Frequently Asked</span> Questions</div>
                <table class="table table-borderless">
                    <!-- FAQs Accordion -->
                    <div class="accordion accordion-flush" id="accordion">

                    <!-- Load faqs from json file here -->

                    </div>
                    <!-- End of Accordion -->

                    <!-- Pagination -->
                    <ul class="pagination mt-4" id="pagination">

                    </ul>
                </table>
            </div>
        </div>
    </div>
  </section>

  <?php include_once FileUtils::normalizeFilePath(__DIR__ . '/includes/components/footer.php'); ?>

</body>

</html>
<?php
} else {
    header("Location: voter-login");
}
?>