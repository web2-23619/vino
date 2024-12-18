import App from "../components/App.js";

(function () {
    new App();

    const btnLogout = document.querySelector("[data-js-action='logout']");

    btnLogout.addEventListener("click", logout);

})();

async function logout() {
    const token = localStorage.getItem("token");

    if (token) {
        // Make the logout API request to revoke the token
        const response = await fetch("/api/logout", {
            method: "POST",
            headers: {
                Authorization: `Bearer ${token}`, // Send the token in the header to authenticate the request
                "Content-Type": "application/json",
            },
        });

        const data = await response.json();
        if (response.ok) {
            // If successful, remove the token from localStorage
            localStorage.removeItem("token");
        } else {
            alert("Error logging out");
        }
    } else {
        alert("No token found");
    }
}
