import Alerte from "../components/Alerte.js";
import ModaleAction from "../components/ModaleAction.js";

(function () {
    let menuOuvert = null;

    const menusHTML = document.querySelectorAll(
        ".menu-deroulant > [type='checkbox']"
    );

    for (const menu of menusHTML) {
        menu.addEventListener("change", checkMenu);
    }

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

    function checkMenu(event) {
        const trigger = event.target;

        if (trigger.checked) {
            if (menuOuvert !== null) {
                menuOuvert.checked = false;
            }
            menuOuvert = trigger;
        } else {
            menuOuvert = null;
        }
    }

	function afficherModaleSupressionCellier(event) {
		menuOuvert = null;

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



})();

