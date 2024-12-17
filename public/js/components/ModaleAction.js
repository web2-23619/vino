import App from "./App.js";

class ModaleAction {
    #cellierID;
    #cellierNom;
    #conteneurHTML;
    #btnAnnuler;
	#btnSupprimer;
    #gabarit;
    #elementHTML;

    constructor(id, cellierNom) {
        this.#cellierID = id;
        this.#cellierNom = cellierNom;
        this.#conteneurHTML = document.querySelector("main");
        this.#gabarit = document.querySelector("template#supprimerCellier");
        this.#elementHTML;
        this.#afficher();

        console.log(cellierNom);
    }

    /**
     * Méthode privée pour afficher la modale
     */
    #afficher() {
        let modale = this.#gabarit.content.cloneNode(true);
        modale.querySelector("[data-js-replace='nom']").textContent =
            this.#cellierNom;

        this.#conteneurHTML.prepend(modale);
        this.#elementHTML = this.#conteneurHTML.firstElementChild;

        this.#btnAnnuler = this.#elementHTML.querySelector(
            "[data-js-action='annuler']"
        );

        this.#btnAnnuler.addEventListener(
            "click",
            this.#fermerModale.bind(this)
        );

		this.#btnSupprimer = this.#elementHTML.querySelector("form");

		console.log(this.#btnSupprimer);
		this.#btnSupprimer.action = App.instance.baseURL + "/supprimer/cellier/" + this.#cellierID; 
    }

    /**
     * Méthode privée pour afermer modale
     */
    #fermerModale() {

		//FIXME: faire remonter modale avant de la retirer

		this.#elementHTML.remove();
	}
}

export default ModaleAction;
