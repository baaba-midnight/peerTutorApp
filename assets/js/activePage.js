document.addEventListener("DOMContentLoaded", function () {
    // Get current page URL
    let currentPage = window.location.pathname.split("/").pop();

    // Select all nav links
    let navLinks = document.querySelectorAll(".nav-link");

    navLinks.forEach(link => {
        if (link.getAttribute("href").split("/").pop() === currentPage) {
            link.classList.add("active");
        }
    });
});
