import App from "../components/App.js";
import Alerte from "../components/Alerte.js";
import ModaleAction from "../components/ModaleAction.js";

(function () {
    new App();

    const alerte = document.querySelector(".alerte");

    if (alerte) {
        new Alerte(alerte);
    }

    const btnsSupprimerCellier = document.querySelectorAll(
        "[data-js-action='supprimerCellier']"
    );

    if (btnsSupprimerCellier) {
        for (const btn of btnsSupprimerCellier) {
            btn.addEventListener("click", afficherModaleSupressionCellier);
        }
    }
})();

function afficherModaleSupressionCellier(event) {
    const declencheur = event.target;
    const cellierID = declencheur.dataset.jsCellier;
    const cellierNom = declencheur.dataset.jsName;

    const modale = new ModaleAction(cellierID, cellierNom, "supprimerCellier", "supprimer", "cellier");

	const articleHTML = declencheur.closest("article");
	const dropdown = articleHTML.querySelector("[type='checkbox']")
	console.log(dropdown);
	dropdown.checked = false;
}
