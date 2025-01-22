import App from "../components/App.js";
import Alerte from "../components/Alerte.js";
import ModaleAction from "../components/ModaleAction.js";

(function () {
    new App();

    const alerte = document.querySelector(".alerte");

    if (alerte) {
        new Alerte(alerte);
    }

    const btnsReduire = document.querySelectorAll("[data-js-action='reduire']");
    const btnsAugmenter = document.querySelectorAll(
        "[data-js-action='augmenter']"
    );

    for (const btn of btnsReduire) {
        btn.addEventListener("click", (event) =>
            changeQuantity(event, "reduire")
        );
    }

    for (const btn of btnsAugmenter) {
        btn.addEventListener("click", (event) =>
            changeQuantity(event, "augmenter")
        );
    }

    const btnsModaleConfirmation = document.querySelectorAll(
        "[data-js-action='afficherModaleConfirmation']"
    );

    if (btnsModaleConfirmation) {
        for (const btn of btnsModaleConfirmation) {
            btn.addEventListener("click", afficherModaleSupressionAchat);
        }
    }

    document.addEventListener("fermerModale", function (event) {
        const bouteilles = document.querySelectorAll(".card_bottle");
        const nbBouteilles = bouteilles.length;

        if (nbBouteilles === 0) {
            const template = document.querySelector("template#noPurchase");
            let content = template.content.cloneNode(true);
            let sectionHTML = document.querySelector("main > section");
            sectionHTML.append(content);

            const boutonAjout = document.querySelector("footer > div");
            if (boutonAjout) {
                boutonAjout.remove();
            }
        }
    });
})();

async function changeQuantity(event, action) {
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
    const trigger = event.target;
    const purchaseItem = trigger.closest("article");
    const id = purchaseItem.dataset.jsId;
    const quantityElement = purchaseItem.querySelector(
        "[data-js-quantite='quantite']"
    );
    let currentQuantity = parseInt(quantityElement.textContent);

    // Ajuster la quantité selon l'action
    if (action === "reduire" && currentQuantity > 1) {
        currentQuantity--;
    } else if (action === "augmenter") {
        currentQuantity++;
    }

    // Envoie le PATCH request pour update seulement la quantité
    const response = await fetch(
        `${App.instance.baseURL}/api/achat/${id}/quantite`,
        {
            method: "PATCH",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken, // Ajoute CSRF token
                Authorization: "Bearer " + localStorage.getItem("token"), // Ajoute le token
            },
            body: JSON.stringify({
                quantity: currentQuantity,
            }),
        }
    );

    if (response.ok) {
        // Mettre à jour le UI
        quantityElement.textContent = currentQuantity;

        // Desactiver le bouton "-" si la quantité est == 1
        const btnReduire = purchaseItem.querySelector(
            "[data-js-action='reduire']"
        );
        if (currentQuantity === 1) {
            btnReduire.setAttribute("inert", "true");
            btnReduire.classList.add("card_purchase_deactivated");
        } else {
            btnReduire.removeAttribute("inert");
            btnReduire.classList.remove("card_purchase_deactivated");
        }
    } else {
        // console.log("Échec.");
    }
}

function afficherModaleSupressionAchat(event) {
    const declencheur = event.target;
    const elToChange = declencheur.closest("article");
    const purchaseID = elToChange.dataset.jsId;
    const purchaseNom = elToChange.dataset.jsName;

    const modale = new ModaleAction(
        purchaseID,
        purchaseNom,
        "supprimerAchat",
        "supprimer",
        "achat",
        elToChange
    );
}
