import App from "../components/App.js";
import Alerte from "../components/Alerte.js";
import ModaleAction from "../components/ModaleAction.js";

(function () {
    new App();

    // Alertes
    const alerte = document.querySelector(".alerte");
    if (alerte) {
        new Alerte(alerte);
    }

    // Supprimer user
    const btnsSupprimerUser = document.querySelectorAll("[data-js-action='supprimerUser']");
    if (btnsSupprimerUser) {
        btnsSupprimerUser.forEach((btn) => {
            btn.addEventListener("click", afficherModaleSuppressionUser);
        });
    }

    // Deconnexion modale
    const btnLogout = document.querySelector("[data-js-action='afficherModaleDeconnexion']");
    if (btnLogout) {
        btnLogout.addEventListener("click", afficherModaleDeconnexion);
    }
})();

/**
 * Fonction pour afficher la confirmation de la suppression d'un utilisateur.
 */
function afficherModaleSuppressionUser(event) {
    const declencheur = event.currentTarget;

    // Extraire les informations utilisateur à partir des attributs de données
    const userID = declencheur.dataset.jsUserId;
    const userName = declencheur.dataset.jsName;

    // Créer et afficher la modale à l’aide de la classe ModaleAction
    new ModaleAction(userID, userName, "supprimerUser", "supprimer", "utilisateur");

    // Fermez le menu déroulant s’il était ouvert
    const dropdownCheckbox = declencheur.closest(".menu-deroulant").querySelector("[type='checkbox']");
    if (dropdownCheckbox) dropdownCheckbox.checked = false;
}

/**
 * Fonction permettant d’afficher la modale de confirmation de la déconnexion.
 */
function afficherModaleDeconnexion(event) {
    const declencheur = event.currentTarget;

    // Extraire les informations utilisateur à partir des attributs de données
    const userID = declencheur.dataset.jsUserId;
    const userName = declencheur.dataset.jsName;

    // Créer et afficher la modale à l’aide de la classe ModaleAction
    new ModaleAction(userID, userName, "deconnexionUser", "deconnexion", "user");

    // Fermez le menu déroulant s’il était ouvert
    const dropdownCheckbox = declencheur.closest(".menu-deroulant").querySelector("[type='checkbox']");
    if (dropdownCheckbox) dropdownCheckbox.checked = false;
}
