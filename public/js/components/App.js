import Alerte from "./Alerte.js";

export default class App {
    static #instance;

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
		this.token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    }

    async removeBottleFromCellar(event) {
        new App();

        const trigger = event.target;

        const bouteille = trigger.closest("article");
        const key = bouteille.dataset.jsKey;
        const ids = key.split("|");

        const response = await fetch(
            `${App.instance.baseURL}/api/retirer/${ids[0]}/${ids[1]}`,
            {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + localStorage.getItem("token"), // ajouter token
                    "X-CSRF-TOKEN": App.instance.token,
                },
            }
        );

        if (response.ok) {
            const message = "Bouteille retirée avec succès";

            bouteille.classList.add("fade");
            setTimeout(() => {
                bouteille.remove();
            }, 500);

            new Alerte(null, message, "succes");
        } else {
            const message = "Erreur au retrait de la bouteille";
            new Alerte(null, message, "erreur");
        }
    }
}
