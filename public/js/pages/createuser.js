import App from "../components/App.js";
import Alerte from "../components/Alerte.js";

(function () {
    new App();

    const alerte = document.querySelector(".alerte");

    if (alerte) {
        new Alerte(alerte);
    }

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

    const registerForm = document.querySelector("form");
    if (registerForm) {
        registerForm.addEventListener("submit", function (event) {
            const password = document.querySelector("[name='password']").value;
            const passwordConfirmation = document.querySelector("[name='password_confirmation']").value;
        
    });
}
})();