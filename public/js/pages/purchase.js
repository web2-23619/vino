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
        btn.addEventListener("click", deletePurchaseItem);
    }

    const btnsReduire = document.querySelectorAll("[data-js-action='reduire']");
    const btnsAugmenter = document.querySelectorAll("[data-js-action='augmenter']");

    for (const btn of btnsReduire) {
        btn.addEventListener("click", (event) => changeQuantity(event, "reduire"));
    }

    for (const btn of btnsAugmenter) {
        btn.addEventListener("click", (event) => changeQuantity(event, "augmenter"));
    }

})();

async function deletePurchaseItem(event) {
    const trigger = event.target;

    const purchaseItem = trigger.closest("article");
    const id = purchaseItem.dataset.jsId;

    const response = await fetch(
        `${App.instance.baseURL}/api/supprimer/achat/${id}`,
        {
            method: "DELETE",
            headers: {
                "Content-Type": "application/json",
                Authorization: "Bearer " + localStorage.getItem("token"), // ajouter token
            },
        }
    );

    if (response.ok) {
        const message = "Bouteille retirée avec succès";

        purchaseItem.classList.add("fade");
        setTimeout(() => {
            purchaseItem.remove();
        }, 500);

        new Alerte(null, message, "succes");
    } else {
        const message = "Erreur au retrait de la bouteille";
        new Alerte(null, message, "erreur");
    }
}


async function changeQuantity(event, action) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const trigger = event.target;
    const purchaseItem = trigger.closest("article");
    const id = purchaseItem.dataset.jsId;
    const quantityElement = purchaseItem.querySelector("[data-js-quantite='quantite']");
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
        const btnReduire = purchaseItem.querySelector("[data-js-action='reduire']");
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