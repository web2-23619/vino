import App from "../components/App.js";
import Alerte from "../components/Alerte.js";
import ModaleAction from "../components/ModaleAction.js";

(function () {
    new App();

    const alerte = document.querySelector(".alerte");

    if (alerte) {
        new Alerte(alerte);
    }

    const btnsSupprimer = document.querySelectorAll(
        "[data-js-action='afficherModaleConfirmation']"
    );

    for (const btn of btnsSupprimer) {
        btn.addEventListener("click", afficherModaleSupressionBouteille);
    }
    const btnsReduire = document.querySelectorAll("[data-js-action='reduire']");
    const btnsAugmenter = document.querySelectorAll("[data-js-action='augmenter']");

    for (const btn of btnsReduire) {
        btn.addEventListener("click", (event) => changeQuantity(event, "reduire"));
    }

    for (const btn of btnsAugmenter) {
        btn.addEventListener("click", (event) => changeQuantity(event, "augmenter"));
    }

	 document.addEventListener("fermerModale", function (event) {
         const bouteilles = document.querySelectorAll(".card_bottle");
         const nbBouteilles = bouteilles.length;

         if (nbBouteilles === 0) {
             const template = document.querySelector("template#noBottle");
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

async function afficherModaleSupressionBouteille(event) {
    const declencheur = event.target;
    const elToChange = declencheur.closest("article");
    const ids = elToChange.dataset.jsKey;
    const nom = elToChange.dataset.jsName;

    new ModaleAction(
        ids,
        nom,
        "retirerBouteille",
        "supprimer",
        "cellier_has_bouteille",
        elToChange
    );
}


async function changeQuantity(event, action) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const trigger = event.target;
    const purchaseItem = trigger.closest("article");
    const [cellarId, bottleId] = purchaseItem.dataset.jsKey.split('|');
    const quantityElement = purchaseItem.querySelector("[data-js-quantite='quantite']");
    let currentQuantity = parseInt(quantityElement.textContent);

    // Ajuste la quantité selon l'action
    if (action === "reduire" && currentQuantity > 0) {
        currentQuantity--;
    } else if (action === "augmenter") {
        currentQuantity++;
    }

    // Envoie la requête PATCH pour mettre à jour la quantité
    const response = await fetch(
        `${App.instance.baseURL}/api/mesBouteilles`,   
        {
            method: "PATCH",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
                Authorization: "Bearer " + localStorage.getItem("token"),
            },
            body: JSON.stringify({
                bottles: [{
                    cellarId: cellarId,
                    bottleId: bottleId,
                    quantity: currentQuantity
                }]
            }),
        }
    );

    if (response.ok) {
        // Met à jour la quantité affichée
        quantityElement.textContent = currentQuantity;

        // Gère l'activation/désactivation des boutons selon la quantité
        const btnReduire = purchaseItem.querySelector("[data-js-action='reduire']");
        if (currentQuantity === 0) {
            btnReduire.setAttribute("inert", "true");
            btnReduire.classList.add("card_purchase_deactivated");
        } else {
            btnReduire.removeAttribute("inert");
            btnReduire.classList.remove("card_purchase_deactivated");
        }
    } else {
        // Gère les erreurs si la requête échoue
        const errorData = await response.json();
        console.error(errorData.error || "Une erreur est survenue");
    }
}
