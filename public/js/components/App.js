import Alerte from "./Alerte.js";
import ModaleAction from "./ModaleAction.js";

export default class App {
    static #instance;
    #alerte;
    #btnsSupprimerCellier;

    //Permet d'accéder à l'instance de la classe de n'importe où dans le code en utilisant App.instance
    static get instance() {
        return App.#instance;
    }

    constructor() {
        //singleton
        if (App.#instance) {
            return App.#instance;
        } else {
            App.#instance = this;
        }

        this.baseURL = "http://localhost:8000";
        this.#alerte = document.querySelector(".alerte");

        if (this.#alerte) {
            new Alerte(this.#alerte);
        }

        this.#btnsSupprimerCellier = document.querySelectorAll(
            "[data-js-action='supprimerCellier']"
        );

		console.log(this.#btnsSupprimerCellier);

		if (this.#btnsSupprimerCellier){
            for (const btn of this.#btnsSupprimerCellier) {
                btn.addEventListener("click", this.#afficherModale.bind(this));
            }
		}

    }

	#afficherModale(event){

		const declencheur = event.target;
		const cellierID = declencheur.dataset.jsCellier;
		const cellierNom = declencheur.dataset.jsName;

		console.log(cellierNom);

		const modale = new ModaleAction(cellierID, cellierNom);

	}
}
