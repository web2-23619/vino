import App from "../components/App.js";
import Alerte from "../components/Alerte.js";
import ModaleAction from "../components/ModaleAction.js";
import Bottle from "../components/Bottle.js";

(function () {
    const appSingleton = new App();

    const alerte = document.querySelector(".alerte");

    if (alerte) {
        new Alerte(alerte);
    }

    document.addEventListener("fermerModale", function (event) {
        const bouteilles = document.querySelectorAll(".card_bottle");
        const nbBouteilles = bouteilles.length;

        if (nbBouteilles === 0) {
            displayNoContentMessage();
        }
    });

    showAll();
})();

function displayNoContentMessage() {
    const template = document.querySelector("template#noPurchase");
    let content = template.content.cloneNode(true);
    let sectionHTML = document.querySelector("main > section");
    sectionHTML.append(content);

    const boutonAjout = document.querySelector("footer > div");
    if (boutonAjout) {
        boutonAjout.remove();
    }
}

function displayAddBottleBtn(){
	    const template = document.querySelector("template#action-button");
        let content = template.content.cloneNode(true);
        let sectionHTML = document.querySelector("footer");
        sectionHTML.prepend(content);
}

async function showAll() {
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
    const response = await fetch(`${App.instance.baseURL}/api/afficher/achat`, {
        method: "get",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken, // Ajoute CSRF token
            Authorization: "Bearer " + localStorage.getItem("token"), // Ajoute le token
        },
    });

    const data = await response.json();

    const container = document.querySelector("[data-js-list]");
    const template = document.querySelector("template#bottle");

    if (!data.empty) {
        data.purchases.forEach((purchase) => {
            new Bottle(purchase, "purchase", template, container);
        });
		displayAddBottleBtn();
    } else {
        displayNoContentMessage();
    }
}
