const indexPUPSRC = document.getElementById("index-PUPSRC");

function updateText() {
  if (window.innerWidth <= 768) {
    indexPUPSRC.textContent = "PUP SANTA ROSA CAMPUS";
  } else {
    indexPUPSRC.textContent =
      "Polytechnic University of the Philippines - Santa Rosa Campus";
  }
}

window.addEventListener("load", updateText);
window.addEventListener("resize", updateText);
