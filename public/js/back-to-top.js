document.addEventListener("DOMContentLoaded", function () {
    const backToTopBtn = document.getElementById("btn-back-to-top");

    window.addEventListener("scroll", () => {
        if (window.scrollY > 200) {
            backToTopBtn.style.display = "flex";
        } else {
            backToTopBtn.style.display = "none";
        }
    });

    backToTopBtn.addEventListener("click", () => {
        window.scrollTo({ top: 0, behavior: "smooth" });
    });
});
