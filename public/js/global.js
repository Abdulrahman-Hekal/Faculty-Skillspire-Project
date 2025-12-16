// Hamburger Menu Toggle
const hamburger = document.getElementById("hamburger");
const mobileMenu = document.getElementById("mobileMenu");

if (hamburger) {
  hamburger.addEventListener("click", function () {
    hamburger.classList.toggle("active");
    mobileMenu.classList.toggle("active");
  });

  // Close menu when a link is clicked
  const mobileNavLinks = document.querySelectorAll(".mobile-nav-link");
  mobileNavLinks.forEach((link) => {
    link.addEventListener("click", function () {
      hamburger.classList.remove("active");
      mobileMenu.classList.remove("active");
    });
  });

  // Close menu when clicking outside
  document.addEventListener("click", function (event) {
    const isClickInsideMenu = mobileMenu.contains(event.target);
    const isClickOnHamburger = hamburger.contains(event.target);

    if (
      !isClickInsideMenu &&
      !isClickOnHamburger &&
      mobileMenu.classList.contains("active")
    ) {
      hamburger.classList.remove("active");
      mobileMenu.classList.remove("active");
    }
  });
}

// Add active state to navigation links based on current page
document.addEventListener("DOMContentLoaded", function () {
  const currentPage = globalThis.location.href;
  const navLinks = document.querySelectorAll(".nav-link");

  navLinks.forEach((link) => {
    const href = link.getAttribute("href");
    if (href === currentPage || currentPage === href + "/") {
      link.classList.add("active");
    }
  });
});
