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
        const gabarit = document.querySelector("template#alerte");
        let alert = gabarit.content.cloneNode(true);
        alert.querySelector("p").textContent =
            "Erreur au retrait de la bouteille";

        document.querySelector("[data-js='header']").after(alert);


        purchaseItem.remove();

        const alertHTML = document.querySelector(".alerte");
        alertHTML.classList.add("alerte_succes");
    } else {
        const gabarit = document.querySelector("template#alerte");
        let alert = gabarit.content.cloneNode(true);
        alert.querySelector("p").textContent =
            "Erreur au retrait de la bouteille";

        document.querySelector("[data-js='header']").after(alert);

        const alertHTML = document.querySelector(".alerte");
        alertHTML.classList.add("alerte_erreur");
    }
}
