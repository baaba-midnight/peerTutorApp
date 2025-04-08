document.addEventListener("DOMContentLoaded", function () {
    // Get current page URL
    let currentPage = window.location.pathname.split("/").pop();

    console.log("Current page:", currentPage);

    // Select all nav links
    let navLinks = document.querySelectorAll(".nav-link");

    navLinks.forEach(link => {
        console.log("Link href:", link.getAttribute("href").split("/").pop());
        if (link.getAttribute("href").split("/").pop() === currentPage) {
            link.classList.add("active");
        }
    });
});
