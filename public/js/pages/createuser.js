import App from "../components/App.js";
import Alerte from "../components/Alerte.js";

(function () {
    new App();

    if (!document.querySelector("template#alerte")) {
        const template = document.createElement("template");
        template.id = "alerte";
        template.innerHTML = `
            <div class="alerte">
                <p></p>
                <button data-js-action="fermer">x</button>
            </div>
        `;
        document.body.prepend(template);
    }

    document.querySelector("form").addEventListener("submit", register);
})();

async function register(event) {
    event.preventDefault();

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
        const username = document.querySelector("[name='username']").value;
        const email = document.querySelector("[name='email']").value;
        const password = document.querySelector("[name='password']").value;
        const passwordConfirmation = document.querySelector("[name='password_confirmation']").value;

        const response = await fetch(`${App.instance.baseURL}/api/register`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
            body: JSON.stringify({
                username,
                email,
                password,
                password_confirmation: passwordConfirmation,
            }),
        });

        const data = await response.json();
        //console.log("Registration response:", data);

        //console.log(data);

        if (response.ok) {
            localStorage.setItem("token", data.token);
            //console.log("Redirecting to:", data.redirect);
            window.location.href = data.redirect; 
            //window.location.href = `${App.instance.baseURL}/profile`;
        } else {
            // Display error message
            new Alerte(null, data.message || "Une erreur s'est produite", "erreur");
        }
    } catch (error) {
        console.error("Unexpected Error:", error);
        new Alerte(null, "Une erreur s'est produite", "erreur");
    }
}
