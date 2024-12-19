import App from "./App.js";
import Alerte from "./Alerte.js";

class ModaleAction {
    #id;
    #displayName;
    #conteneurHTML;
    #btnAnnuler;
    #btnAction;
    #elToChange;
    #gabarit;
    #elementHTML;
    #action;
    #model;

    constructor(id, name, template, action, model, elToChange = null) {
        this.#id = id;
        this.#displayName = name;
        this.#action = action;
        this.#model = model;
        this.#elToChange = elToChange;
        this.#conteneurHTML = document.querySelector("main");
        this.#gabarit = document.querySelector(`[id='${template}']`);
        this.#elementHTML;
        this.#afficher();
    }

    /**
     * Méthode privée pour afficher la modale
     */
    #afficher() {
        let modale = this.#gabarit.content.cloneNode(true);
        modale.querySelector("[data-js-replace='nom']").textContent =
            this.#displayName;

        this.#conteneurHTML.prepend(modale);
        this.#elementHTML = this.#conteneurHTML.firstElementChild;

        this.#btnAnnuler = this.#elementHTML.querySelector(
            "[data-js-action='annuler']"
        );

        this.#btnAnnuler.addEventListener(
            "click",
            this.#fermerModale.bind(this)
        );

        this.#btnAction = this.#btnAnnuler = this.#elementHTML.querySelector(
            "[data-js-action='" + this.#action + "']"
        );

        console.log(this.#btnAction);

        if (this.#action == "supprimer") {
            this.#btnAction.addEventListener(
                "click",
                this.#supprimer.bind(this)
            );
        }
    }

    /**
     * Méthode privée pour afermer modale
     */
    #fermerModale() {
        this.#elementHTML.classList.add("remove");

        setTimeout(() => {
            this.#elementHTML.remove();
        }, 2650);
    }

    async #supprimer() {
        const response = await fetch(
            "/api/" + this.#action + "/" + this.#model + "/" + this.#id,
            {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + localStorage.getItem("token"), // ajouter token
                },
            }
        );

        const top = document.querySelector("[data-js='header']");
        console.log(top);

        if (response.ok) {
            const message = "Cellier supprimé avec succès";
            this.#elToChange.remove();

            this.#elementHTML.remove();
            top.scrollIntoView();
            new Alerte(null, message, "succes");
        } else {
            const message = "Erreur à la suppression du cellier";
            this.#elementHTML.remove();
            top.scrollIntoView();
            new Alerte(null, message, "erreur");
        }
    }
}

export default ModaleAction;
