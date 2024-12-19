import App from "../components/App.js";
import Alerte from "../components/Alerte.js";
import ModaleAction from "../components/ModaleAction.js";

(function () {
    new App();

    const alerte = document.querySelector(".alerte");

    if (alerte) {
        new Alerte(alerte);
    }

    const btnsModaleConfirmation = document.querySelectorAll(
        "[data-js-action='afficherModaleConfirmation']"
    );

    if (btnsModaleConfirmation) {
        for (const btn of btnsModaleConfirmation) {
            btn.addEventListener("click", afficherModaleSupressionCellier);
        }
    }
})();

function afficherModaleSupressionCellier(event) {
    const declencheur = event.target;
    const cellierID = declencheur.dataset.jsCellier;
    const cellierNom = declencheur.dataset.jsName;
    const elToChange = declencheur.closest("article");

    const dropdown = elToChange.querySelector(".menu-deroulant > input");
    dropdown.checked = false;

    const modale = new ModaleAction(
        cellierID,
        cellierNom,
        "supprimerCellier",
        "supprimer",
        "cellier",
        elToChange
    );
}
