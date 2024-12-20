import App from "../components/App.js";
import Alerte from "../components/Alerte.js";

(function () {
	new App();

    const alerte = document.querySelector(".alerte");

    if (alerte) {
        new Alerte(alerte);
    }

    const btnsSupprimer = document.querySelectorAll(
        "[data-js-action='supprimer']"
    );

    for (const btn of btnsSupprimer) {
        btn.addEventListener("click", App.instance.removeBottleFromCellar);
    }
})();
