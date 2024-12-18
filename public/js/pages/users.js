import App from "../components/App.js";
import Alerte from "../components/Alerte.js";
import ModaleAction from "../components/ModaleAction.js";

(function () {
    new App();

    const alerte = document.querySelector(".alerte");
    if (alerte) {
        new Alerte(alerte);
    }

    const btnsSupprimerUser = document.querySelectorAll(
        "[data-js-action='supprimerUser']"
    );

    if (btnsSupprimerUser) {
        for (const btn of btnsSupprimerUser) {
            btn.addEventListener("click", afficherModaleSuppressionUser);
        }
    }
})();

/**
 * Function to display the confirmation modal for deleting a user.
 */
function afficherModaleSuppressionUser(event) {
    const declencheur = event.target;

    // Extract user information from data attributes
    const userID = declencheur.dataset.jsUserId;
    const userName = declencheur.dataset.jsName;

    // Create and display the modal using ModaleAction class
    new ModaleAction(userID, userName, "supprimerUser", "supprimer", "user");

    // Close the dropdown menu if it was open
    const dropdownCheckbox = declencheur.closest(".menu-deroulant").querySelector("[type='checkbox']");
    if (dropdownCheckbox) dropdownCheckbox.checked = false;
}
