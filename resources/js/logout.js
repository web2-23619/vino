document.addEventListener("DOMContentLoaded", () => {
    const logoutLink = document.querySelector(".logout-link");
    if (logoutLink) {
        logoutLink.addEventListener("click", function (e) {
            e.preventDefault();
            document.getElementById("logout-form").submit();
        });
    }
});
