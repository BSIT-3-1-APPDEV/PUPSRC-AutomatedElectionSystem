$(window).on("load", function () {
  // Remove preloader after a delay
  setTimeout(function () {
    $(".loader").fadeOut("slow");
    $(".loader-wrapper").fadeOut("slow");
  });
  // console.log("Page finished loading. Preloader stopped."); // for testing only
});
