import App from "../components/App.js";
import Alerte from "../components/Alerte.js";

(function () {
    new App();

    const alerte = document.querySelector(".alerte");

    if (alerte) {
        new Alerte(alerte);
    }

    const loginFormHTML = document.querySelector("form");

    loginFormHTML.addEventListener("submit", login);
})();

async function login() {
    const email = document.querySelector("[name='email']").value;
    const password = document.querySelector("[name='password']").value;

    const response = await fetch("/api/login", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            email: email,
            password: password,
        }),
    });

    const data = await response.json();

    if (response.ok) {
        // sauvegarder token dans le local storage
        localStorage.setItem("token", data.token);
    } else {
        console.log("login fail");
    }
}
