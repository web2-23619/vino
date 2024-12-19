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
})();

async function deletePurchaseItem(event) {
    const trigger = event.target;

    const purchaseItem = trigger.closest("article");
    const id = purchaseItem.dataset.jsId;

    const response = await fetch(`/api/supprimer/achat/${id}`, {
        method: "DELETE",
        headers: {
            "Content-Type": "application/json",
            Authorization: "Bearer " + localStorage.getItem("token"), // ajouter token
        },
    });

    //FIXME: optimser pour eviter repetition. modifier classe Alerte

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
