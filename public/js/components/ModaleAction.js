import App from "./App.js";

class ModaleAction {
    #id;
    #displayName;
    #conteneurHTML;
    #btnAnnuler;
	#btnAction;
    #gabarit;
    #elementHTML;
	#action;
	#model;

    constructor(id, name, template, action, model) {
        this.#id = id;
        this.#displayName = name;
        this.#action = action;
        this.#model = model;
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

		this.#btnAction = this.#elementHTML.querySelector("form");

		this.#btnAction.action =
            App.instance.baseURL + "/" + this.#action + "/" + this.#model + "/" +  this.#id; 
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
}

export default ModaleAction;
