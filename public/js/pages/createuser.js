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

 
})();

